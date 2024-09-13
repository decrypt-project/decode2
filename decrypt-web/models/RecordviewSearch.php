<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class RecordviewSearch extends Recordview
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "RecordviewSearch";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "RecordviewSearch";

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'recordview';
        $this->TableName = 'recordview';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-search-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (recordview)
        if (!isset($GLOBALS["recordview"]) || get_class($GLOBALS["recordview"]) == PROJECT_NAMESPACE . "recordview") {
            $GLOBALS["recordview"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'recordview');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents(): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response (Assume return to modal for simplicity)
            if ($this->IsModal) { // Show as modal
                $result = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page => View page
                    $result["caption"] = $this->getModalCaption($pageName);
                    $result["view"] = $pageName == "RecordviewView"; // If View page, no primary button
                } else { // List page
                    // $result["list"] = $this->PageID == "search"; // Refresh List page if current page is Search page
                    $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                    $this->clearFailureMessage();
                }
                WriteJson($result);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['id'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->id->Visible = false;
        }
    }

    // Lookup data
    public function lookup($ar = null)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $ar["field"] ?? Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;
        $name = $ar["name"] ?? Post("name");
        $isQuery = ContainsString($name, "query_builder_rule");
        if ($isQuery) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }

        // Get lookup parameters
        $lookupType = $ar["ajax"] ?? Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $ar["q"] ?? Param("q") ?? $ar["sv"] ?? Post("sv", "");
            $pageSize = $ar["n"] ?? Param("n") ?? $ar["recperpage"] ?? Post("recperpage", 10);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $ar["q"] ?? Param("q", "");
            $pageSize = $ar["n"] ?? Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $ar["start"] ?? Param("start", -1);
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $ar["page"] ?? Param("page", -1);
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($ar["s"] ?? Post("s", ""));
        $userFilter = Decrypt($ar["f"] ?? Post("f", ""));
        $userOrderBy = Decrypt($ar["o"] ?? Post("o", ""));
        $keys = $ar["keys"] ?? Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $ar["v0"] ?? $ar["lookupValue"] ?? Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $ar["v" . $i] ?? Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, !is_array($ar)); // Use settings from current page
    }
    public $FormClassName = "ew-form ew-search-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $UserProfile, $Language, $Security, $CurrentForm, $SkipHeaderFooter;

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->id->setVisibility();
        $this->name->setVisibility();
        $this->record_group_id->Visible = false;
        $this->start_year->setVisibility();
        $this->start_month->setVisibility();
        $this->start_day->setVisibility();
        $this->end_year->setVisibility();
        $this->end_month->setVisibility();
        $this->end_day->setVisibility();
        $this->current_country->setVisibility();
        $this->current_city->setVisibility();
        $this->current_holder->setVisibility();
        $this->author->setVisibility();
        $this->sender->setVisibility();
        $this->receiver->setVisibility();
        $this->origin_region->setVisibility();
        $this->origin_city->setVisibility();
        $this->record_type->setVisibility();
        $this->status->setVisibility();
        $this->cipher_type_other->Visible = false;
        $this->symbol_set_other->Visible = false;
        $this->number_of_pages->setVisibility();
        $this->inline_plaintext->setVisibility();
        $this->inline_cleartext->setVisibility();
        $this->cleartext_lang->setVisibility();
        $this->plaintext_lang->setVisibility();
        $this->cipher_types->setVisibility();
        $this->symbol_sets->setVisibility();
        $this->transc_files->setVisibility();
        $this->private_ciphertext->Visible = false;
        $this->paper->setVisibility();
        $this->additional_information->setVisibility();
        $this->creation_date->setVisibility();
        $this->owner->setVisibility();
        $this->owner_id->Visible = false;
        $this->creator_id->setVisibility();
        $this->access_mode->setVisibility();
        $this->link->setVisibility();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->record_type);
        $this->setupLookupOptions($this->status);
        $this->setupLookupOptions($this->inline_plaintext);
        $this->setupLookupOptions($this->inline_cleartext);
        $this->setupLookupOptions($this->cleartext_lang);
        $this->setupLookupOptions($this->plaintext_lang);
        $this->setupLookupOptions($this->cipher_types);
        $this->setupLookupOptions($this->symbol_sets);
        $this->setupLookupOptions($this->private_ciphertext);
        $this->setupLookupOptions($this->owner_id);
        $this->setupLookupOptions($this->creator_id);
        $this->setupLookupOptions($this->access_mode);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;

        // Get action
        $this->CurrentAction = Post("action");
        if ($this->isSearch()) {
            // Build search string for advanced search, remove blank field
            $this->loadSearchValues(); // Get search values
            $srchStr = $this->validateSearch() ? $this->buildAdvancedSearch() : "";
            if ($srchStr != "") {
                $srchStr = "RecordviewList" . "?" . $srchStr;
                // Do not return Json for UseAjaxActions
                if ($this->IsModal && $this->UseAjaxActions) {
                    $this->IsModal = false;
                }
                $this->terminate($srchStr); // Go to list page
                return;
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Render row for search
        $this->RowType = ROWTYPE_SEARCH;
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Build advanced search
    protected function buildAdvancedSearch()
    {
        $srchUrl = "";
        $this->buildSearchUrl($srchUrl, $this->id); // id
        $this->buildSearchUrl($srchUrl, $this->name); // name
        $this->buildSearchUrl($srchUrl, $this->start_year); // start_year
        $this->buildSearchUrl($srchUrl, $this->start_month); // start_month
        $this->buildSearchUrl($srchUrl, $this->start_day); // start_day
        $this->buildSearchUrl($srchUrl, $this->end_year); // end_year
        $this->buildSearchUrl($srchUrl, $this->end_month); // end_month
        $this->buildSearchUrl($srchUrl, $this->end_day); // end_day
        $this->buildSearchUrl($srchUrl, $this->current_country); // current_country
        $this->buildSearchUrl($srchUrl, $this->current_city); // current_city
        $this->buildSearchUrl($srchUrl, $this->current_holder); // current_holder
        $this->buildSearchUrl($srchUrl, $this->author); // author
        $this->buildSearchUrl($srchUrl, $this->sender); // sender
        $this->buildSearchUrl($srchUrl, $this->receiver); // receiver
        $this->buildSearchUrl($srchUrl, $this->origin_region); // origin_region
        $this->buildSearchUrl($srchUrl, $this->origin_city); // origin_city
        $this->buildSearchUrl($srchUrl, $this->record_type); // record_type
        $this->buildSearchUrl($srchUrl, $this->status); // status
        $this->buildSearchUrl($srchUrl, $this->number_of_pages); // number_of_pages
        $this->buildSearchUrl($srchUrl, $this->inline_plaintext); // inline_plaintext
        $this->buildSearchUrl($srchUrl, $this->inline_cleartext); // inline_cleartext
        $this->buildSearchUrl($srchUrl, $this->cleartext_lang); // cleartext_lang
        $this->buildSearchUrl($srchUrl, $this->plaintext_lang); // plaintext_lang
        $this->buildSearchUrl($srchUrl, $this->cipher_types); // cipher_types
        $this->buildSearchUrl($srchUrl, $this->symbol_sets); // symbol_sets
        $this->buildSearchUrl($srchUrl, $this->transc_files); // transc_files
        $this->buildSearchUrl($srchUrl, $this->paper); // paper
        $this->buildSearchUrl($srchUrl, $this->additional_information); // additional_information
        $this->buildSearchUrl($srchUrl, $this->creation_date); // creation_date
        $this->buildSearchUrl($srchUrl, $this->owner); // owner
        $this->buildSearchUrl($srchUrl, $this->creator_id); // creator_id
        $this->buildSearchUrl($srchUrl, $this->access_mode); // access_mode
        $this->buildSearchUrl($srchUrl, $this->link); // link
        if ($srchUrl != "") {
            $srchUrl .= "&";
        }
        $srchUrl .= "cmd=search";
        return $srchUrl;
    }

    // Build search URL
    protected function buildSearchUrl(&$url, $fld, $oprOnly = false)
    {
        global $CurrentForm;
        $wrk = "";
        $fldParm = $fld->Param;
        [
            "value" => $fldVal,
            "operator" => $fldOpr,
            "condition" => $fldCond,
            "value2" => $fldVal2,
            "operator2" => $fldOpr2
        ] = $CurrentForm->getSearchValues($fldParm);
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        $fldDataType = $fld->DataType;
        $value = ConvertSearchValue($fldVal, $fldOpr, $fld); // For testing if numeric only
        $value2 = ConvertSearchValue($fldVal2, $fldOpr2, $fld); // For testing if numeric only
        $fldOpr = ConvertSearchOperator($fldOpr, $fld, $value);
        $fldOpr2 = ConvertSearchOperator($fldOpr2, $fld, $value2);
        if (in_array($fldOpr, ["BETWEEN", "NOT BETWEEN"])) {
            $isValidValue = $fldDataType != DATATYPE_NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value, $fldOpr, $fld) && IsNumericSearchValue($value2, $fldOpr2, $fld);
            if ($fldVal != "" && $fldVal2 != "" && $isValidValue) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) . "&y_" . $fldParm . "=" . urlencode($fldVal2) . "&z_" . $fldParm . "=" . urlencode($fldOpr);
            }
        } else {
            $isValidValue = $fldDataType != DATATYPE_NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value, $fldOpr, $fld);
            if ($fldVal != "" && $isValidValue && IsValidOperator($fldOpr)) {
                $wrk = "x_" . $fldParm . "=" . urlencode($fldVal) . "&z_" . $fldParm . "=" . urlencode($fldOpr);
            } elseif (in_array($fldOpr, ["IS NULL", "IS NOT NULL", "IS EMPTY", "IS NOT EMPTY"]) || ($fldOpr != "" && $oprOnly && IsValidOperator($fldOpr))) {
                $wrk = "z_" . $fldParm . "=" . urlencode($fldOpr);
            }
            $isValidValue = $fldDataType != DATATYPE_NUMBER || $fld->VirtualSearch || IsNumericSearchValue($value2, $fldOpr2, $fld);
            if ($fldVal2 != "" && $isValidValue && IsValidOperator($fldOpr2)) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "y_" . $fldParm . "=" . urlencode($fldVal2) . "&w_" . $fldParm . "=" . urlencode($fldOpr2);
            } elseif (in_array($fldOpr2, ["IS NULL", "IS NOT NULL", "IS EMPTY", "IS NOT EMPTY"]) || ($fldOpr2 != "" && $oprOnly && IsValidOperator($fldOpr2))) {
                if ($wrk != "") {
                    $wrk .= "&v_" . $fldParm . "=" . urlencode($fldCond) . "&";
                }
                $wrk .= "w_" . $fldParm . "=" . urlencode($fldOpr2);
            }
        }
        if ($wrk != "") {
            if ($url != "") {
                $url .= "&";
            }
            $url .= $wrk;
        }
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;

        // id
        if ($this->id->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // name
        if ($this->name->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // start_year
        if ($this->start_year->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // start_month
        if ($this->start_month->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // start_day
        if ($this->start_day->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // end_year
        if ($this->end_year->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // end_month
        if ($this->end_month->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // end_day
        if ($this->end_day->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // current_country
        if ($this->current_country->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // current_city
        if ($this->current_city->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // current_holder
        if ($this->current_holder->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // author
        if ($this->author->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // sender
        if ($this->sender->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // receiver
        if ($this->receiver->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // origin_region
        if ($this->origin_region->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // origin_city
        if ($this->origin_city->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // record_type
        if ($this->record_type->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // status
        if ($this->status->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->status->AdvancedSearch->SearchValue)) {
            $this->status->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->status->AdvancedSearch->SearchValue);
        }
        if (is_array($this->status->AdvancedSearch->SearchValue2)) {
            $this->status->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->status->AdvancedSearch->SearchValue2);
        }

        // number_of_pages
        if ($this->number_of_pages->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // inline_plaintext
        if ($this->inline_plaintext->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // inline_cleartext
        if ($this->inline_cleartext->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // cleartext_lang
        if ($this->cleartext_lang->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // plaintext_lang
        if ($this->plaintext_lang->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // cipher_types
        if ($this->cipher_types->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->cipher_types->AdvancedSearch->SearchValue)) {
            $this->cipher_types->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->cipher_types->AdvancedSearch->SearchValue);
        }
        if (is_array($this->cipher_types->AdvancedSearch->SearchValue2)) {
            $this->cipher_types->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->cipher_types->AdvancedSearch->SearchValue2);
        }

        // symbol_sets
        if ($this->symbol_sets->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->symbol_sets->AdvancedSearch->SearchValue)) {
            $this->symbol_sets->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->symbol_sets->AdvancedSearch->SearchValue);
        }
        if (is_array($this->symbol_sets->AdvancedSearch->SearchValue2)) {
            $this->symbol_sets->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->symbol_sets->AdvancedSearch->SearchValue2);
        }

        // transc_files
        if ($this->transc_files->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // paper
        if ($this->paper->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // additional_information
        if ($this->additional_information->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // creation_date
        if ($this->creation_date->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // owner
        if ($this->owner->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // creator_id
        if ($this->creator_id->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // access_mode
        if ($this->access_mode->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // link
        if ($this->link->AdvancedSearch->get()) {
            $hasValue = true;
        }
        return $hasValue;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id
        $this->id->RowCssClass = "row";

        // name
        $this->name->RowCssClass = "row";

        // record_group_id
        $this->record_group_id->RowCssClass = "row";

        // start_year
        $this->start_year->RowCssClass = "row";

        // start_month
        $this->start_month->RowCssClass = "row";

        // start_day
        $this->start_day->RowCssClass = "row";

        // end_year
        $this->end_year->RowCssClass = "row";

        // end_month
        $this->end_month->RowCssClass = "row";

        // end_day
        $this->end_day->RowCssClass = "row";

        // current_country
        $this->current_country->RowCssClass = "row";

        // current_city
        $this->current_city->RowCssClass = "row";

        // current_holder
        $this->current_holder->RowCssClass = "row";

        // author
        $this->author->RowCssClass = "row";

        // sender
        $this->sender->RowCssClass = "row";

        // receiver
        $this->receiver->RowCssClass = "row";

        // origin_region
        $this->origin_region->RowCssClass = "row";

        // origin_city
        $this->origin_city->RowCssClass = "row";

        // record_type
        $this->record_type->RowCssClass = "row";

        // status
        $this->status->RowCssClass = "row";

        // cipher_type_other
        $this->cipher_type_other->RowCssClass = "row";

        // symbol_set_other
        $this->symbol_set_other->RowCssClass = "row";

        // number_of_pages
        $this->number_of_pages->RowCssClass = "row";

        // inline_plaintext
        $this->inline_plaintext->RowCssClass = "row";

        // inline_cleartext
        $this->inline_cleartext->RowCssClass = "row";

        // cleartext_lang
        $this->cleartext_lang->RowCssClass = "row";

        // plaintext_lang
        $this->plaintext_lang->RowCssClass = "row";

        // cipher_types
        $this->cipher_types->RowCssClass = "row";

        // symbol_sets
        $this->symbol_sets->RowCssClass = "row";

        // transc_files
        $this->transc_files->RowCssClass = "row";

        // private_ciphertext
        $this->private_ciphertext->RowCssClass = "row";

        // paper
        $this->paper->RowCssClass = "row";

        // additional_information
        $this->additional_information->RowCssClass = "row";

        // creation_date
        $this->creation_date->RowCssClass = "row";

        // owner
        $this->owner->RowCssClass = "row";

        // owner_id
        $this->owner_id->RowCssClass = "row";

        // creator_id
        $this->creator_id->RowCssClass = "row";

        // access_mode
        $this->access_mode->RowCssClass = "row";

        // link
        $this->link->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // name
            $this->name->ViewValue = $this->name->CurrentValue;

            // record_group_id
            $this->record_group_id->ViewValue = $this->record_group_id->CurrentValue;
            $this->record_group_id->ViewValue = FormatNumber($this->record_group_id->ViewValue, $this->record_group_id->formatPattern());

            // start_year
            $this->start_year->ViewValue = $this->start_year->CurrentValue;

            // start_month
            $this->start_month->ViewValue = $this->start_month->CurrentValue;
            $this->start_month->ViewValue = FormatNumber($this->start_month->ViewValue, $this->start_month->formatPattern());

            // start_day
            $this->start_day->ViewValue = $this->start_day->CurrentValue;
            $this->start_day->ViewValue = FormatNumber($this->start_day->ViewValue, $this->start_day->formatPattern());

            // end_year
            $this->end_year->ViewValue = $this->end_year->CurrentValue;

            // end_month
            $this->end_month->ViewValue = $this->end_month->CurrentValue;
            $this->end_month->ViewValue = FormatNumber($this->end_month->ViewValue, $this->end_month->formatPattern());

            // end_day
            $this->end_day->ViewValue = $this->end_day->CurrentValue;
            $this->end_day->ViewValue = FormatNumber($this->end_day->ViewValue, $this->end_day->formatPattern());

            // current_country
            $this->current_country->ViewValue = $this->current_country->CurrentValue;

            // current_city
            $this->current_city->ViewValue = $this->current_city->CurrentValue;

            // current_holder
            $this->current_holder->ViewValue = $this->current_holder->CurrentValue;

            // author
            $this->author->ViewValue = $this->author->CurrentValue;

            // sender
            $this->sender->ViewValue = $this->sender->CurrentValue;

            // receiver
            $this->receiver->ViewValue = $this->receiver->CurrentValue;

            // origin_region
            $this->origin_region->ViewValue = $this->origin_region->CurrentValue;

            // origin_city
            $this->origin_city->ViewValue = $this->origin_city->CurrentValue;

            // record_type
            if (strval($this->record_type->CurrentValue) != "") {
                $this->record_type->ViewValue = $this->record_type->optionCaption($this->record_type->CurrentValue);
            } else {
                $this->record_type->ViewValue = null;
            }

            // status
            if (strval($this->status->CurrentValue) != "") {
                $this->status->ViewValue = new OptionValues();
                $arwrk = explode(",", strval($this->status->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->status->ViewValue->add($this->status->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->status->ViewValue = null;
            }

            // cipher_type_other
            $this->cipher_type_other->ViewValue = $this->cipher_type_other->CurrentValue;

            // symbol_set_other
            $this->symbol_set_other->ViewValue = $this->symbol_set_other->CurrentValue;

            // number_of_pages
            $this->number_of_pages->ViewValue = $this->number_of_pages->CurrentValue;
            $this->number_of_pages->ViewValue = FormatNumber($this->number_of_pages->ViewValue, $this->number_of_pages->formatPattern());

            // inline_plaintext
            if (strval($this->inline_plaintext->CurrentValue) != "") {
                $this->inline_plaintext->ViewValue = $this->inline_plaintext->optionCaption($this->inline_plaintext->CurrentValue);
            } else {
                $this->inline_plaintext->ViewValue = null;
            }

            // inline_cleartext
            if (strval($this->inline_cleartext->CurrentValue) != "") {
                $this->inline_cleartext->ViewValue = $this->inline_cleartext->optionCaption($this->inline_cleartext->CurrentValue);
            } else {
                $this->inline_cleartext->ViewValue = null;
            }

            // cleartext_lang
            $this->cleartext_lang->ViewValue = $this->cleartext_lang->CurrentValue;
            $curVal = strval($this->cleartext_lang->CurrentValue);
            if ($curVal != "") {
                $this->cleartext_lang->ViewValue = $this->cleartext_lang->lookupCacheOption($curVal);
                if ($this->cleartext_lang->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`id`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->cleartext_lang->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cleartext_lang->Lookup->renderViewRow($rswrk[0]);
                        $this->cleartext_lang->ViewValue = $this->cleartext_lang->displayValue($arwrk);
                    } else {
                        $this->cleartext_lang->ViewValue = $this->cleartext_lang->CurrentValue;
                    }
                }
            } else {
                $this->cleartext_lang->ViewValue = null;
            }

            // plaintext_lang
            $this->plaintext_lang->ViewValue = $this->plaintext_lang->CurrentValue;
            $curVal = strval($this->plaintext_lang->CurrentValue);
            if ($curVal != "") {
                $this->plaintext_lang->ViewValue = $this->plaintext_lang->lookupCacheOption($curVal);
                if ($this->plaintext_lang->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`id`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->plaintext_lang->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->plaintext_lang->Lookup->renderViewRow($rswrk[0]);
                        $this->plaintext_lang->ViewValue = $this->plaintext_lang->displayValue($arwrk);
                    } else {
                        $this->plaintext_lang->ViewValue = $this->plaintext_lang->CurrentValue;
                    }
                }
            } else {
                $this->plaintext_lang->ViewValue = null;
            }

            // cipher_types
            if (strval($this->cipher_types->CurrentValue) != "") {
                $this->cipher_types->ViewValue = new OptionValues();
                $arwrk = explode(",", strval($this->cipher_types->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->cipher_types->ViewValue->add($this->cipher_types->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->cipher_types->ViewValue = null;
            }

            // symbol_sets
            if (strval($this->symbol_sets->CurrentValue) != "") {
                $this->symbol_sets->ViewValue = new OptionValues();
                $arwrk = explode(",", strval($this->symbol_sets->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->symbol_sets->ViewValue->add($this->symbol_sets->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->symbol_sets->ViewValue = null;
            }

            // transc_files
            $this->transc_files->ViewValue = $this->transc_files->CurrentValue;

            // private_ciphertext
            if (strval($this->private_ciphertext->CurrentValue) != "") {
                $this->private_ciphertext->ViewValue = $this->private_ciphertext->optionCaption($this->private_ciphertext->CurrentValue);
            } else {
                $this->private_ciphertext->ViewValue = null;
            }

            // paper
            $this->paper->ViewValue = $this->paper->CurrentValue;

            // additional_information
            $this->additional_information->ViewValue = $this->additional_information->CurrentValue;

            // creation_date
            $this->creation_date->ViewValue = $this->creation_date->CurrentValue;
            $this->creation_date->ViewValue = FormatDateTime($this->creation_date->ViewValue, $this->creation_date->formatPattern());

            // owner
            $this->owner->ViewValue = $this->owner->CurrentValue;

            // creator_id
            $this->creator_id->ViewValue = $this->creator_id->CurrentValue;
            $curVal = strval($this->creator_id->CurrentValue);
            if ($curVal != "") {
                $this->creator_id->ViewValue = $this->creator_id->lookupCacheOption($curVal);
                if ($this->creator_id->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`id`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->creator_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->creator_id->Lookup->renderViewRow($rswrk[0]);
                        $this->creator_id->ViewValue = $this->creator_id->displayValue($arwrk);
                    } else {
                        $this->creator_id->ViewValue = FormatNumber($this->creator_id->CurrentValue, $this->creator_id->formatPattern());
                    }
                }
            } else {
                $this->creator_id->ViewValue = null;
            }

            // access_mode
            if (strval($this->access_mode->CurrentValue) != "") {
                $this->access_mode->ViewValue = $this->access_mode->optionCaption($this->access_mode->CurrentValue);
            } else {
                $this->access_mode->ViewValue = null;
            }

            // link
            $this->link->ViewValue = $this->link->CurrentValue;

            // id
            $this->id->HrefValue = "";
            $this->id->TooltipValue = "";

            // name
            $this->name->HrefValue = "";
            $this->name->TooltipValue = "";

            // start_year
            $this->start_year->HrefValue = "";
            $this->start_year->TooltipValue = "";

            // start_month
            $this->start_month->HrefValue = "";
            $this->start_month->TooltipValue = "";

            // start_day
            $this->start_day->HrefValue = "";
            $this->start_day->TooltipValue = "";

            // end_year
            $this->end_year->HrefValue = "";
            $this->end_year->TooltipValue = "";

            // end_month
            $this->end_month->HrefValue = "";
            $this->end_month->TooltipValue = "";

            // end_day
            $this->end_day->HrefValue = "";
            $this->end_day->TooltipValue = "";

            // current_country
            $this->current_country->HrefValue = "";
            $this->current_country->TooltipValue = "";

            // current_city
            $this->current_city->HrefValue = "";
            $this->current_city->TooltipValue = "";

            // current_holder
            $this->current_holder->HrefValue = "";
            $this->current_holder->TooltipValue = "";

            // author
            $this->author->HrefValue = "";
            $this->author->TooltipValue = "";

            // sender
            $this->sender->HrefValue = "";
            $this->sender->TooltipValue = "";

            // receiver
            $this->receiver->HrefValue = "";
            $this->receiver->TooltipValue = "";

            // origin_region
            $this->origin_region->HrefValue = "";
            $this->origin_region->TooltipValue = "";

            // origin_city
            $this->origin_city->HrefValue = "";
            $this->origin_city->TooltipValue = "";

            // record_type
            $this->record_type->HrefValue = "";
            $this->record_type->TooltipValue = "";

            // status
            $this->status->HrefValue = "";
            $this->status->TooltipValue = "";

            // number_of_pages
            $this->number_of_pages->HrefValue = "";
            $this->number_of_pages->TooltipValue = "";

            // inline_plaintext
            $this->inline_plaintext->HrefValue = "";
            $this->inline_plaintext->TooltipValue = "";

            // inline_cleartext
            $this->inline_cleartext->HrefValue = "";
            $this->inline_cleartext->TooltipValue = "";

            // cleartext_lang
            $this->cleartext_lang->HrefValue = "";
            $this->cleartext_lang->TooltipValue = "";

            // plaintext_lang
            $this->plaintext_lang->HrefValue = "";
            $this->plaintext_lang->TooltipValue = "";

            // cipher_types
            $this->cipher_types->HrefValue = "";
            $this->cipher_types->TooltipValue = "";

            // symbol_sets
            $this->symbol_sets->HrefValue = "";
            $this->symbol_sets->TooltipValue = "";

            // transc_files
            $this->transc_files->HrefValue = "";
            $this->transc_files->TooltipValue = "";

            // paper
            $this->paper->HrefValue = "";
            $this->paper->TooltipValue = "";

            // additional_information
            $this->additional_information->HrefValue = "";
            $this->additional_information->TooltipValue = "";

            // creation_date
            $this->creation_date->HrefValue = "";
            $this->creation_date->TooltipValue = "";

            // owner
            $this->owner->HrefValue = "";
            $this->owner->TooltipValue = "";

            // creator_id
            $this->creator_id->HrefValue = "";
            $this->creator_id->TooltipValue = "";

            // access_mode
            $this->access_mode->HrefValue = "";
            $this->access_mode->TooltipValue = "";

            // link
            $this->link->HrefValue = "";
            $this->link->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // id
            $this->id->setupEditAttributes();
            $this->id->EditValue = HtmlEncode($this->id->AdvancedSearch->SearchValue);
            $this->id->PlaceHolder = RemoveHtml($this->id->caption());

            // name
            $this->name->setupEditAttributes();
            if (!$this->name->Raw) {
                $this->name->AdvancedSearch->SearchValue = HtmlDecode($this->name->AdvancedSearch->SearchValue);
            }
            $this->name->EditValue = HtmlEncode($this->name->AdvancedSearch->SearchValue);
            $this->name->PlaceHolder = RemoveHtml($this->name->caption());

            // start_year
            $this->start_year->setupEditAttributes();
            $this->start_year->EditValue = HtmlEncode($this->start_year->AdvancedSearch->SearchValue);
            $this->start_year->PlaceHolder = RemoveHtml($this->start_year->caption());
            $this->start_year->setupEditAttributes();
            $this->start_year->EditValue2 = HtmlEncode($this->start_year->AdvancedSearch->SearchValue2);
            $this->start_year->PlaceHolder = RemoveHtml($this->start_year->caption());

            // start_month
            $this->start_month->setupEditAttributes();
            $this->start_month->EditValue = HtmlEncode($this->start_month->AdvancedSearch->SearchValue);
            $this->start_month->PlaceHolder = RemoveHtml($this->start_month->caption());
            $this->start_month->setupEditAttributes();
            $this->start_month->EditValue2 = HtmlEncode($this->start_month->AdvancedSearch->SearchValue2);
            $this->start_month->PlaceHolder = RemoveHtml($this->start_month->caption());

            // start_day
            $this->start_day->setupEditAttributes();
            $this->start_day->EditValue = HtmlEncode($this->start_day->AdvancedSearch->SearchValue);
            $this->start_day->PlaceHolder = RemoveHtml($this->start_day->caption());
            $this->start_day->setupEditAttributes();
            $this->start_day->EditValue2 = HtmlEncode($this->start_day->AdvancedSearch->SearchValue2);
            $this->start_day->PlaceHolder = RemoveHtml($this->start_day->caption());

            // end_year
            $this->end_year->setupEditAttributes();
            $this->end_year->EditValue = HtmlEncode($this->end_year->AdvancedSearch->SearchValue);
            $this->end_year->PlaceHolder = RemoveHtml($this->end_year->caption());
            $this->end_year->setupEditAttributes();
            $this->end_year->EditValue2 = HtmlEncode($this->end_year->AdvancedSearch->SearchValue2);
            $this->end_year->PlaceHolder = RemoveHtml($this->end_year->caption());

            // end_month
            $this->end_month->setupEditAttributes();
            $this->end_month->EditValue = HtmlEncode($this->end_month->AdvancedSearch->SearchValue);
            $this->end_month->PlaceHolder = RemoveHtml($this->end_month->caption());
            $this->end_month->setupEditAttributes();
            $this->end_month->EditValue2 = HtmlEncode($this->end_month->AdvancedSearch->SearchValue2);
            $this->end_month->PlaceHolder = RemoveHtml($this->end_month->caption());

            // end_day
            $this->end_day->setupEditAttributes();
            $this->end_day->EditValue = HtmlEncode($this->end_day->AdvancedSearch->SearchValue);
            $this->end_day->PlaceHolder = RemoveHtml($this->end_day->caption());
            $this->end_day->setupEditAttributes();
            $this->end_day->EditValue2 = HtmlEncode($this->end_day->AdvancedSearch->SearchValue2);
            $this->end_day->PlaceHolder = RemoveHtml($this->end_day->caption());

            // current_country
            $this->current_country->setupEditAttributes();
            if (!$this->current_country->Raw) {
                $this->current_country->AdvancedSearch->SearchValue = HtmlDecode($this->current_country->AdvancedSearch->SearchValue);
            }
            $this->current_country->EditValue = HtmlEncode($this->current_country->AdvancedSearch->SearchValue);
            $this->current_country->PlaceHolder = RemoveHtml($this->current_country->caption());

            // current_city
            $this->current_city->setupEditAttributes();
            if (!$this->current_city->Raw) {
                $this->current_city->AdvancedSearch->SearchValue = HtmlDecode($this->current_city->AdvancedSearch->SearchValue);
            }
            $this->current_city->EditValue = HtmlEncode($this->current_city->AdvancedSearch->SearchValue);
            $this->current_city->PlaceHolder = RemoveHtml($this->current_city->caption());

            // current_holder
            $this->current_holder->setupEditAttributes();
            if (!$this->current_holder->Raw) {
                $this->current_holder->AdvancedSearch->SearchValue = HtmlDecode($this->current_holder->AdvancedSearch->SearchValue);
            }
            $this->current_holder->EditValue = HtmlEncode($this->current_holder->AdvancedSearch->SearchValue);
            $this->current_holder->PlaceHolder = RemoveHtml($this->current_holder->caption());

            // author
            $this->author->setupEditAttributes();
            if (!$this->author->Raw) {
                $this->author->AdvancedSearch->SearchValue = HtmlDecode($this->author->AdvancedSearch->SearchValue);
            }
            $this->author->EditValue = HtmlEncode($this->author->AdvancedSearch->SearchValue);
            $this->author->PlaceHolder = RemoveHtml($this->author->caption());

            // sender
            $this->sender->setupEditAttributes();
            if (!$this->sender->Raw) {
                $this->sender->AdvancedSearch->SearchValue = HtmlDecode($this->sender->AdvancedSearch->SearchValue);
            }
            $this->sender->EditValue = HtmlEncode($this->sender->AdvancedSearch->SearchValue);
            $this->sender->PlaceHolder = RemoveHtml($this->sender->caption());

            // receiver
            $this->receiver->setupEditAttributes();
            if (!$this->receiver->Raw) {
                $this->receiver->AdvancedSearch->SearchValue = HtmlDecode($this->receiver->AdvancedSearch->SearchValue);
            }
            $this->receiver->EditValue = HtmlEncode($this->receiver->AdvancedSearch->SearchValue);
            $this->receiver->PlaceHolder = RemoveHtml($this->receiver->caption());

            // origin_region
            $this->origin_region->setupEditAttributes();
            if (!$this->origin_region->Raw) {
                $this->origin_region->AdvancedSearch->SearchValue = HtmlDecode($this->origin_region->AdvancedSearch->SearchValue);
            }
            $this->origin_region->EditValue = HtmlEncode($this->origin_region->AdvancedSearch->SearchValue);
            $this->origin_region->PlaceHolder = RemoveHtml($this->origin_region->caption());

            // origin_city
            $this->origin_city->setupEditAttributes();
            if (!$this->origin_city->Raw) {
                $this->origin_city->AdvancedSearch->SearchValue = HtmlDecode($this->origin_city->AdvancedSearch->SearchValue);
            }
            $this->origin_city->EditValue = HtmlEncode($this->origin_city->AdvancedSearch->SearchValue);
            $this->origin_city->PlaceHolder = RemoveHtml($this->origin_city->caption());

            // record_type
            $this->record_type->setupEditAttributes();
            $this->record_type->EditValue = $this->record_type->options(true);
            $this->record_type->PlaceHolder = RemoveHtml($this->record_type->caption());

            // status
            $this->status->EditValue = $this->status->options(false);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // number_of_pages
            $this->number_of_pages->setupEditAttributes();
            $this->number_of_pages->EditValue = HtmlEncode($this->number_of_pages->AdvancedSearch->SearchValue);
            $this->number_of_pages->PlaceHolder = RemoveHtml($this->number_of_pages->caption());
            $this->number_of_pages->setupEditAttributes();
            $this->number_of_pages->EditValue2 = HtmlEncode($this->number_of_pages->AdvancedSearch->SearchValue2);
            $this->number_of_pages->PlaceHolder = RemoveHtml($this->number_of_pages->caption());

            // inline_plaintext
            $this->inline_plaintext->EditValue = $this->inline_plaintext->options(false);
            $this->inline_plaintext->PlaceHolder = RemoveHtml($this->inline_plaintext->caption());

            // inline_cleartext
            $this->inline_cleartext->EditValue = $this->inline_cleartext->options(false);
            $this->inline_cleartext->PlaceHolder = RemoveHtml($this->inline_cleartext->caption());

            // cleartext_lang
            $this->cleartext_lang->setupEditAttributes();
            if (!$this->cleartext_lang->Raw) {
                $this->cleartext_lang->AdvancedSearch->SearchValue = HtmlDecode($this->cleartext_lang->AdvancedSearch->SearchValue);
            }
            $this->cleartext_lang->EditValue = HtmlEncode($this->cleartext_lang->AdvancedSearch->SearchValue);
            $curVal = strval($this->cleartext_lang->AdvancedSearch->SearchValue);
            if ($curVal != "") {
                $this->cleartext_lang->EditValue = $this->cleartext_lang->lookupCacheOption($curVal);
                if ($this->cleartext_lang->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`id`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->cleartext_lang->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->cleartext_lang->Lookup->renderViewRow($rswrk[0]);
                        $this->cleartext_lang->EditValue = $this->cleartext_lang->displayValue($arwrk);
                    } else {
                        $this->cleartext_lang->EditValue = HtmlEncode($this->cleartext_lang->AdvancedSearch->SearchValue);
                    }
                }
            } else {
                $this->cleartext_lang->EditValue = null;
            }
            $this->cleartext_lang->PlaceHolder = RemoveHtml($this->cleartext_lang->caption());

            // plaintext_lang
            $this->plaintext_lang->setupEditAttributes();
            if (!$this->plaintext_lang->Raw) {
                $this->plaintext_lang->AdvancedSearch->SearchValue = HtmlDecode($this->plaintext_lang->AdvancedSearch->SearchValue);
            }
            $this->plaintext_lang->EditValue = HtmlEncode($this->plaintext_lang->AdvancedSearch->SearchValue);
            $curVal = strval($this->plaintext_lang->AdvancedSearch->SearchValue);
            if ($curVal != "") {
                $this->plaintext_lang->EditValue = $this->plaintext_lang->lookupCacheOption($curVal);
                if ($this->plaintext_lang->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`id`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->plaintext_lang->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->plaintext_lang->Lookup->renderViewRow($rswrk[0]);
                        $this->plaintext_lang->EditValue = $this->plaintext_lang->displayValue($arwrk);
                    } else {
                        $this->plaintext_lang->EditValue = HtmlEncode($this->plaintext_lang->AdvancedSearch->SearchValue);
                    }
                }
            } else {
                $this->plaintext_lang->EditValue = null;
            }
            $this->plaintext_lang->PlaceHolder = RemoveHtml($this->plaintext_lang->caption());

            // cipher_types
            $this->cipher_types->EditValue = $this->cipher_types->options(false);
            $this->cipher_types->PlaceHolder = RemoveHtml($this->cipher_types->caption());

            // symbol_sets
            $this->symbol_sets->EditValue = $this->symbol_sets->options(false);
            $this->symbol_sets->PlaceHolder = RemoveHtml($this->symbol_sets->caption());

            // transc_files
            $this->transc_files->setupEditAttributes();
            $this->transc_files->EditValue = HtmlEncode($this->transc_files->AdvancedSearch->SearchValue);
            $this->transc_files->PlaceHolder = RemoveHtml($this->transc_files->caption());

            // paper
            $this->paper->setupEditAttributes();
            if (!$this->paper->Raw) {
                $this->paper->AdvancedSearch->SearchValue = HtmlDecode($this->paper->AdvancedSearch->SearchValue);
            }
            $this->paper->EditValue = HtmlEncode($this->paper->AdvancedSearch->SearchValue);
            $this->paper->PlaceHolder = RemoveHtml($this->paper->caption());

            // additional_information
            $this->additional_information->setupEditAttributes();
            $this->additional_information->EditValue = HtmlEncode($this->additional_information->AdvancedSearch->SearchValue);
            $this->additional_information->PlaceHolder = RemoveHtml($this->additional_information->caption());

            // creation_date
            $this->creation_date->setupEditAttributes();
            $this->creation_date->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->creation_date->AdvancedSearch->SearchValue, $this->creation_date->formatPattern()), $this->creation_date->formatPattern()));
            $this->creation_date->PlaceHolder = RemoveHtml($this->creation_date->caption());
            $this->creation_date->setupEditAttributes();
            $this->creation_date->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->creation_date->AdvancedSearch->SearchValue2, $this->creation_date->formatPattern()), $this->creation_date->formatPattern()));
            $this->creation_date->PlaceHolder = RemoveHtml($this->creation_date->caption());

            // owner
            $this->owner->setupEditAttributes();
            if (!$this->owner->Raw) {
                $this->owner->AdvancedSearch->SearchValue = HtmlDecode($this->owner->AdvancedSearch->SearchValue);
            }
            $this->owner->EditValue = HtmlEncode($this->owner->AdvancedSearch->SearchValue);
            $this->owner->PlaceHolder = RemoveHtml($this->owner->caption());

            // creator_id
            $this->creator_id->setupEditAttributes();
            $this->creator_id->EditValue = HtmlEncode($this->creator_id->AdvancedSearch->SearchValue);
            $curVal = strval($this->creator_id->AdvancedSearch->SearchValue);
            if ($curVal != "") {
                $this->creator_id->EditValue = $this->creator_id->lookupCacheOption($curVal);
                if ($this->creator_id->EditValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`id`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->creator_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->creator_id->Lookup->renderViewRow($rswrk[0]);
                        $this->creator_id->EditValue = $this->creator_id->displayValue($arwrk);
                    } else {
                        $this->creator_id->EditValue = HtmlEncode(FormatNumber($this->creator_id->AdvancedSearch->SearchValue, $this->creator_id->formatPattern()));
                    }
                }
            } else {
                $this->creator_id->EditValue = null;
            }
            $this->creator_id->PlaceHolder = RemoveHtml($this->creator_id->caption());
            $this->creator_id->setupEditAttributes();
            $this->creator_id->EditValue2 = HtmlEncode($this->creator_id->AdvancedSearch->SearchValue2);
            $curVal = strval($this->creator_id->AdvancedSearch->SearchValue2);
            if ($curVal != "") {
                $this->creator_id->EditValue2 = $this->creator_id->lookupCacheOption($curVal);
                if ($this->creator_id->EditValue2 === null) { // Lookup from database
                    $filterWrk = SearchFilter("`id`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->creator_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->creator_id->Lookup->renderViewRow($rswrk[0]);
                        $this->creator_id->EditValue2 = $this->creator_id->displayValue($arwrk);
                    } else {
                        $this->creator_id->EditValue2 = HtmlEncode(FormatNumber($this->creator_id->AdvancedSearch->SearchValue2, $this->creator_id->formatPattern()));
                    }
                }
            } else {
                $this->creator_id->EditValue2 = null;
            }
            $this->creator_id->PlaceHolder = RemoveHtml($this->creator_id->caption());

            // access_mode
            $this->access_mode->EditValue = $this->access_mode->options(false);
            $this->access_mode->PlaceHolder = RemoveHtml($this->access_mode->caption());

            // link
            $this->link->setupEditAttributes();
            if (!$this->link->Raw) {
                $this->link->AdvancedSearch->SearchValue = HtmlDecode($this->link->AdvancedSearch->SearchValue);
            }
            $this->link->EditValue = HtmlEncode($this->link->AdvancedSearch->SearchValue);
            $this->link->PlaceHolder = RemoveHtml($this->link->caption());
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate search
    protected function validateSearch()
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if (!CheckInteger($this->id->AdvancedSearch->SearchValue)) {
            $this->id->addErrorMessage($this->id->getErrorMessage(false));
        }
        if (!CheckInteger($this->start_year->AdvancedSearch->SearchValue)) {
            $this->start_year->addErrorMessage($this->start_year->getErrorMessage(false));
        }
        if (!CheckInteger($this->start_year->AdvancedSearch->SearchValue2)) {
            $this->start_year->addErrorMessage($this->start_year->getErrorMessage(false));
        }
        if (!CheckInteger($this->start_month->AdvancedSearch->SearchValue)) {
            $this->start_month->addErrorMessage($this->start_month->getErrorMessage(false));
        }
        if (!CheckInteger($this->start_month->AdvancedSearch->SearchValue2)) {
            $this->start_month->addErrorMessage($this->start_month->getErrorMessage(false));
        }
        if (!CheckInteger($this->start_day->AdvancedSearch->SearchValue)) {
            $this->start_day->addErrorMessage($this->start_day->getErrorMessage(false));
        }
        if (!CheckInteger($this->start_day->AdvancedSearch->SearchValue2)) {
            $this->start_day->addErrorMessage($this->start_day->getErrorMessage(false));
        }
        if (!CheckInteger($this->end_year->AdvancedSearch->SearchValue)) {
            $this->end_year->addErrorMessage($this->end_year->getErrorMessage(false));
        }
        if (!CheckInteger($this->end_year->AdvancedSearch->SearchValue2)) {
            $this->end_year->addErrorMessage($this->end_year->getErrorMessage(false));
        }
        if (!CheckInteger($this->end_month->AdvancedSearch->SearchValue)) {
            $this->end_month->addErrorMessage($this->end_month->getErrorMessage(false));
        }
        if (!CheckInteger($this->end_month->AdvancedSearch->SearchValue2)) {
            $this->end_month->addErrorMessage($this->end_month->getErrorMessage(false));
        }
        if (!CheckInteger($this->end_day->AdvancedSearch->SearchValue)) {
            $this->end_day->addErrorMessage($this->end_day->getErrorMessage(false));
        }
        if (!CheckInteger($this->end_day->AdvancedSearch->SearchValue2)) {
            $this->end_day->addErrorMessage($this->end_day->getErrorMessage(false));
        }
        if (!CheckInteger($this->number_of_pages->AdvancedSearch->SearchValue)) {
            $this->number_of_pages->addErrorMessage($this->number_of_pages->getErrorMessage(false));
        }
        if (!CheckInteger($this->number_of_pages->AdvancedSearch->SearchValue2)) {
            $this->number_of_pages->addErrorMessage($this->number_of_pages->getErrorMessage(false));
        }
        if (!CheckDate($this->creation_date->AdvancedSearch->SearchValue, $this->creation_date->formatPattern())) {
            $this->creation_date->addErrorMessage($this->creation_date->getErrorMessage(false));
        }
        if (!CheckDate($this->creation_date->AdvancedSearch->SearchValue2, $this->creation_date->formatPattern())) {
            $this->creation_date->addErrorMessage($this->creation_date->getErrorMessage(false));
        }
        if (!CheckInteger($this->creator_id->AdvancedSearch->SearchValue)) {
            $this->creator_id->addErrorMessage($this->creator_id->getErrorMessage(false));
        }
        if (!CheckInteger($this->creator_id->AdvancedSearch->SearchValue2)) {
            $this->creator_id->addErrorMessage($this->creator_id->getErrorMessage(false));
        }

        // Return validate result
        $validateSearch = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateSearch = $validateSearch && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateSearch;
    }

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->id->AdvancedSearch->load();
        $this->name->AdvancedSearch->load();
        $this->start_year->AdvancedSearch->load();
        $this->start_month->AdvancedSearch->load();
        $this->start_day->AdvancedSearch->load();
        $this->end_year->AdvancedSearch->load();
        $this->end_month->AdvancedSearch->load();
        $this->end_day->AdvancedSearch->load();
        $this->current_country->AdvancedSearch->load();
        $this->current_city->AdvancedSearch->load();
        $this->current_holder->AdvancedSearch->load();
        $this->author->AdvancedSearch->load();
        $this->sender->AdvancedSearch->load();
        $this->receiver->AdvancedSearch->load();
        $this->origin_region->AdvancedSearch->load();
        $this->origin_city->AdvancedSearch->load();
        $this->record_type->AdvancedSearch->load();
        $this->status->AdvancedSearch->load();
        $this->number_of_pages->AdvancedSearch->load();
        $this->inline_plaintext->AdvancedSearch->load();
        $this->inline_cleartext->AdvancedSearch->load();
        $this->cleartext_lang->AdvancedSearch->load();
        $this->plaintext_lang->AdvancedSearch->load();
        $this->cipher_types->AdvancedSearch->load();
        $this->symbol_sets->AdvancedSearch->load();
        $this->transc_files->AdvancedSearch->load();
        $this->paper->AdvancedSearch->load();
        $this->additional_information->AdvancedSearch->load();
        $this->creation_date->AdvancedSearch->load();
        $this->owner->AdvancedSearch->load();
        $this->creator_id->AdvancedSearch->load();
        $this->access_mode->AdvancedSearch->load();
        $this->link->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("RecordsList");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("RecordviewList"), "", $this->TableVar, true);
        $pageId = "search";
        $Breadcrumb->add("search", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_record_type":
                    break;
                case "x_status":
                    break;
                case "x_inline_plaintext":
                    break;
                case "x_inline_cleartext":
                    break;
                case "x_cleartext_lang":
                    break;
                case "x_plaintext_lang":
                    break;
                case "x_cipher_types":
                    break;
                case "x_symbol_sets":
                    break;
                case "x_private_ciphertext":
                    break;
                case "x_owner_id":
                    break;
                case "x_creator_id":
                    break;
                case "x_access_mode":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
