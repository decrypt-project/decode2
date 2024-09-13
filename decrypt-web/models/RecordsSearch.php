<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class RecordsSearch extends Records
{
    use MessagesTrait;

    // Page ID
    public $PageID = "search";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "RecordsSearch";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "RecordsSearch";

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

    // Set field visibility
    public function setVisibility()
    {
        $this->id->setVisibility();
        $this->name->Visible = false;
        $this->owner_id->Visible = false;
        $this->owner->setVisibility();
        $this->record_group_id->Visible = false;
        $this->c_holder->setVisibility();
        $this->c_cates->Visible = false;
        $this->c_author->Visible = false;
        $this->c_lang->Visible = false;
        $this->current_country->setVisibility();
        $this->current_city->Visible = false;
        $this->current_holder->Visible = false;
        $this->author->setVisibility();
        $this->sender->setVisibility();
        $this->receiver->setVisibility();
        $this->origin_region->setVisibility();
        $this->origin_city->setVisibility();
        $this->start_year->setVisibility();
        $this->start_month->setVisibility();
        $this->start_day->setVisibility();
        $this->end_year->setVisibility();
        $this->end_month->setVisibility();
        $this->end_day->setVisibility();
        $this->record_type->setVisibility();
        $this->status->setVisibility();
        $this->symbol_sets->Visible = false;
        $this->cipher_types->Visible = false;
        $this->cipher_type_other->setVisibility();
        $this->symbol_set_other->setVisibility();
        $this->number_of_pages->Visible = false;
        $this->inline_cleartext->setVisibility();
        $this->inline_plaintext->setVisibility();
        $this->cleartext_lang->setVisibility();
        $this->plaintext_lang->setVisibility();
        $this->private_ciphertext->Visible = false;
        $this->document_types->setVisibility();
        $this->paper->setVisibility();
        $this->additional_information->setVisibility();
        $this->creator_id->setVisibility();
        $this->access_mode->Visible = false;
        $this->creation_date->setVisibility();
        $this->km_encoded_plaintext_type->setVisibility();
        $this->km_numbers->setVisibility();
        $this->km_content_words->setVisibility();
        $this->km_function_words->setVisibility();
        $this->km_syllables->setVisibility();
        $this->km_morphological_endings->setVisibility();
        $this->km_phrases->setVisibility();
        $this->km_sentences->setVisibility();
        $this->km_punctuation->setVisibility();
        $this->km_nomenclature_size->setVisibility();
        $this->km_sections->setVisibility();
        $this->km_headings->setVisibility();
        $this->km_plaintext_arrangement->setVisibility();
        $this->km_ciphertext_arrangement->setVisibility();
        $this->km_memorability->setVisibility();
        $this->km_symbol_set->setVisibility();
        $this->km_diacritics->setVisibility();
        $this->km_code_length->setVisibility();
        $this->km_code_type->setVisibility();
        $this->km_metaphors->setVisibility();
        $this->km_material_properties->setVisibility();
        $this->km_instructions->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'records';
        $this->TableName = 'records';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-search-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (records)
        if (!isset($GLOBALS["records"]) || get_class($GLOBALS["records"]) == PROJECT_NAMESPACE . "records") {
            $GLOBALS["records"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'records');
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
                    $result["view"] = $pageName == "RecordsView"; // If View page, no primary button
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
        $this->setVisibility();

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
        $this->setupLookupOptions($this->record_group_id);
        $this->setupLookupOptions($this->record_type);
        $this->setupLookupOptions($this->status);
        $this->setupLookupOptions($this->symbol_sets);
        $this->setupLookupOptions($this->cipher_types);
        $this->setupLookupOptions($this->inline_cleartext);
        $this->setupLookupOptions($this->inline_plaintext);
        $this->setupLookupOptions($this->private_ciphertext);
        $this->setupLookupOptions($this->document_types);
        $this->setupLookupOptions($this->access_mode);
        $this->setupLookupOptions($this->km_encoded_plaintext_type);
        $this->setupLookupOptions($this->km_numbers);
        $this->setupLookupOptions($this->km_content_words);
        $this->setupLookupOptions($this->km_function_words);
        $this->setupLookupOptions($this->km_syllables);
        $this->setupLookupOptions($this->km_morphological_endings);
        $this->setupLookupOptions($this->km_phrases);
        $this->setupLookupOptions($this->km_sentences);
        $this->setupLookupOptions($this->km_punctuation);
        $this->setupLookupOptions($this->km_nomenclature_size);
        $this->setupLookupOptions($this->km_sections);
        $this->setupLookupOptions($this->km_headings);
        $this->setupLookupOptions($this->km_plaintext_arrangement);
        $this->setupLookupOptions($this->km_ciphertext_arrangement);
        $this->setupLookupOptions($this->km_memorability);
        $this->setupLookupOptions($this->km_symbol_set);
        $this->setupLookupOptions($this->km_diacritics);
        $this->setupLookupOptions($this->km_code_length);
        $this->setupLookupOptions($this->km_code_type);
        $this->setupLookupOptions($this->km_metaphors);
        $this->setupLookupOptions($this->km_material_properties);
        $this->setupLookupOptions($this->km_instructions);

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
                $srchStr = "RecordsList" . "?" . $srchStr;
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
        $this->buildSearchUrl($srchUrl, $this->owner); // owner
        $this->buildSearchUrl($srchUrl, $this->c_holder); // c_holder
        $this->buildSearchUrl($srchUrl, $this->current_country); // current_country
        $this->buildSearchUrl($srchUrl, $this->author); // author
        $this->buildSearchUrl($srchUrl, $this->sender); // sender
        $this->buildSearchUrl($srchUrl, $this->receiver); // receiver
        $this->buildSearchUrl($srchUrl, $this->origin_region); // origin_region
        $this->buildSearchUrl($srchUrl, $this->origin_city); // origin_city
        $this->buildSearchUrl($srchUrl, $this->start_year); // start_year
        $this->buildSearchUrl($srchUrl, $this->start_month); // start_month
        $this->buildSearchUrl($srchUrl, $this->start_day); // start_day
        $this->buildSearchUrl($srchUrl, $this->end_year); // end_year
        $this->buildSearchUrl($srchUrl, $this->end_month); // end_month
        $this->buildSearchUrl($srchUrl, $this->end_day); // end_day
        $this->buildSearchUrl($srchUrl, $this->record_type); // record_type
        $this->buildSearchUrl($srchUrl, $this->status); // status
        $this->buildSearchUrl($srchUrl, $this->cipher_type_other); // cipher_type_other
        $this->buildSearchUrl($srchUrl, $this->symbol_set_other); // symbol_set_other
        $this->buildSearchUrl($srchUrl, $this->inline_cleartext); // inline_cleartext
        $this->buildSearchUrl($srchUrl, $this->inline_plaintext); // inline_plaintext
        $this->buildSearchUrl($srchUrl, $this->cleartext_lang); // cleartext_lang
        $this->buildSearchUrl($srchUrl, $this->plaintext_lang); // plaintext_lang
        $this->buildSearchUrl($srchUrl, $this->document_types); // document_types
        $this->buildSearchUrl($srchUrl, $this->paper); // paper
        $this->buildSearchUrl($srchUrl, $this->additional_information); // additional_information
        $this->buildSearchUrl($srchUrl, $this->creator_id); // creator_id
        $this->buildSearchUrl($srchUrl, $this->creation_date); // creation_date
        $this->buildSearchUrl($srchUrl, $this->km_encoded_plaintext_type); // km_encoded_plaintext_type
        $this->buildSearchUrl($srchUrl, $this->km_numbers); // km_numbers
        $this->buildSearchUrl($srchUrl, $this->km_content_words); // km_content_words
        $this->buildSearchUrl($srchUrl, $this->km_function_words); // km_function_words
        $this->buildSearchUrl($srchUrl, $this->km_syllables); // km_syllables
        $this->buildSearchUrl($srchUrl, $this->km_morphological_endings); // km_morphological_endings
        $this->buildSearchUrl($srchUrl, $this->km_phrases); // km_phrases
        $this->buildSearchUrl($srchUrl, $this->km_sentences); // km_sentences
        $this->buildSearchUrl($srchUrl, $this->km_punctuation); // km_punctuation
        $this->buildSearchUrl($srchUrl, $this->km_nomenclature_size); // km_nomenclature_size
        $this->buildSearchUrl($srchUrl, $this->km_sections); // km_sections
        $this->buildSearchUrl($srchUrl, $this->km_headings); // km_headings
        $this->buildSearchUrl($srchUrl, $this->km_plaintext_arrangement); // km_plaintext_arrangement
        $this->buildSearchUrl($srchUrl, $this->km_ciphertext_arrangement); // km_ciphertext_arrangement
        $this->buildSearchUrl($srchUrl, $this->km_memorability); // km_memorability
        $this->buildSearchUrl($srchUrl, $this->km_symbol_set); // km_symbol_set
        $this->buildSearchUrl($srchUrl, $this->km_diacritics); // km_diacritics
        $this->buildSearchUrl($srchUrl, $this->km_code_length); // km_code_length
        $this->buildSearchUrl($srchUrl, $this->km_code_type); // km_code_type
        $this->buildSearchUrl($srchUrl, $this->km_metaphors); // km_metaphors
        $this->buildSearchUrl($srchUrl, $this->km_material_properties); // km_material_properties
        $this->buildSearchUrl($srchUrl, $this->km_instructions); // km_instructions
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

        // owner
        if ($this->owner->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // c_holder
        if ($this->c_holder->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // current_country
        if ($this->current_country->AdvancedSearch->get()) {
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

        // record_type
        if ($this->record_type->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // status
        if ($this->status->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // cipher_type_other
        if ($this->cipher_type_other->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // symbol_set_other
        if ($this->symbol_set_other->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // inline_cleartext
        if ($this->inline_cleartext->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // inline_plaintext
        if ($this->inline_plaintext->AdvancedSearch->get()) {
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

        // document_types
        if ($this->document_types->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->document_types->AdvancedSearch->SearchValue)) {
            $this->document_types->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->document_types->AdvancedSearch->SearchValue);
        }
        if (is_array($this->document_types->AdvancedSearch->SearchValue2)) {
            $this->document_types->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->document_types->AdvancedSearch->SearchValue2);
        }

        // paper
        if ($this->paper->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // additional_information
        if ($this->additional_information->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // creator_id
        if ($this->creator_id->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // creation_date
        if ($this->creation_date->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_encoded_plaintext_type
        if ($this->km_encoded_plaintext_type->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->km_encoded_plaintext_type->AdvancedSearch->SearchValue)) {
            $this->km_encoded_plaintext_type->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_encoded_plaintext_type->AdvancedSearch->SearchValue);
        }
        if (is_array($this->km_encoded_plaintext_type->AdvancedSearch->SearchValue2)) {
            $this->km_encoded_plaintext_type->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_encoded_plaintext_type->AdvancedSearch->SearchValue2);
        }

        // km_numbers
        if ($this->km_numbers->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_content_words
        if ($this->km_content_words->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_function_words
        if ($this->km_function_words->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_syllables
        if ($this->km_syllables->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_morphological_endings
        if ($this->km_morphological_endings->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_phrases
        if ($this->km_phrases->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_sentences
        if ($this->km_sentences->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_punctuation
        if ($this->km_punctuation->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_nomenclature_size
        if ($this->km_nomenclature_size->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_sections
        if ($this->km_sections->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_headings
        if ($this->km_headings->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_plaintext_arrangement
        if ($this->km_plaintext_arrangement->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->km_plaintext_arrangement->AdvancedSearch->SearchValue)) {
            $this->km_plaintext_arrangement->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_plaintext_arrangement->AdvancedSearch->SearchValue);
        }
        if (is_array($this->km_plaintext_arrangement->AdvancedSearch->SearchValue2)) {
            $this->km_plaintext_arrangement->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_plaintext_arrangement->AdvancedSearch->SearchValue2);
        }

        // km_ciphertext_arrangement
        if ($this->km_ciphertext_arrangement->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->km_ciphertext_arrangement->AdvancedSearch->SearchValue)) {
            $this->km_ciphertext_arrangement->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_ciphertext_arrangement->AdvancedSearch->SearchValue);
        }
        if (is_array($this->km_ciphertext_arrangement->AdvancedSearch->SearchValue2)) {
            $this->km_ciphertext_arrangement->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_ciphertext_arrangement->AdvancedSearch->SearchValue2);
        }

        // km_memorability
        if ($this->km_memorability->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_symbol_set
        if ($this->km_symbol_set->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->km_symbol_set->AdvancedSearch->SearchValue)) {
            $this->km_symbol_set->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_symbol_set->AdvancedSearch->SearchValue);
        }
        if (is_array($this->km_symbol_set->AdvancedSearch->SearchValue2)) {
            $this->km_symbol_set->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_symbol_set->AdvancedSearch->SearchValue2);
        }

        // km_diacritics
        if ($this->km_diacritics->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_code_length
        if ($this->km_code_length->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->km_code_length->AdvancedSearch->SearchValue)) {
            $this->km_code_length->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_code_length->AdvancedSearch->SearchValue);
        }
        if (is_array($this->km_code_length->AdvancedSearch->SearchValue2)) {
            $this->km_code_length->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_code_length->AdvancedSearch->SearchValue2);
        }

        // km_code_type
        if ($this->km_code_type->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->km_code_type->AdvancedSearch->SearchValue)) {
            $this->km_code_type->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_code_type->AdvancedSearch->SearchValue);
        }
        if (is_array($this->km_code_type->AdvancedSearch->SearchValue2)) {
            $this->km_code_type->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_code_type->AdvancedSearch->SearchValue2);
        }

        // km_metaphors
        if ($this->km_metaphors->AdvancedSearch->get()) {
            $hasValue = true;
        }

        // km_material_properties
        if ($this->km_material_properties->AdvancedSearch->get()) {
            $hasValue = true;
        }
        if (is_array($this->km_material_properties->AdvancedSearch->SearchValue)) {
            $this->km_material_properties->AdvancedSearch->SearchValue = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_material_properties->AdvancedSearch->SearchValue);
        }
        if (is_array($this->km_material_properties->AdvancedSearch->SearchValue2)) {
            $this->km_material_properties->AdvancedSearch->SearchValue2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $this->km_material_properties->AdvancedSearch->SearchValue2);
        }

        // km_instructions
        if ($this->km_instructions->AdvancedSearch->get()) {
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

        // owner_id
        $this->owner_id->RowCssClass = "row";

        // owner
        $this->owner->RowCssClass = "row";

        // record_group_id
        $this->record_group_id->RowCssClass = "row";

        // c_holder
        $this->c_holder->RowCssClass = "row";

        // c_cates
        $this->c_cates->RowCssClass = "row";

        // c_author
        $this->c_author->RowCssClass = "row";

        // c_lang
        $this->c_lang->RowCssClass = "row";

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

        // record_type
        $this->record_type->RowCssClass = "row";

        // status
        $this->status->RowCssClass = "row";

        // symbol_sets
        $this->symbol_sets->RowCssClass = "row";

        // cipher_types
        $this->cipher_types->RowCssClass = "row";

        // cipher_type_other
        $this->cipher_type_other->RowCssClass = "row";

        // symbol_set_other
        $this->symbol_set_other->RowCssClass = "row";

        // number_of_pages
        $this->number_of_pages->RowCssClass = "row";

        // inline_cleartext
        $this->inline_cleartext->RowCssClass = "row";

        // inline_plaintext
        $this->inline_plaintext->RowCssClass = "row";

        // cleartext_lang
        $this->cleartext_lang->RowCssClass = "row";

        // plaintext_lang
        $this->plaintext_lang->RowCssClass = "row";

        // private_ciphertext
        $this->private_ciphertext->RowCssClass = "row";

        // document_types
        $this->document_types->RowCssClass = "row";

        // paper
        $this->paper->RowCssClass = "row";

        // additional_information
        $this->additional_information->RowCssClass = "row";

        // creator_id
        $this->creator_id->RowCssClass = "row";

        // access_mode
        $this->access_mode->RowCssClass = "row";

        // creation_date
        $this->creation_date->RowCssClass = "row";

        // km_encoded_plaintext_type
        $this->km_encoded_plaintext_type->RowCssClass = "row";

        // km_numbers
        $this->km_numbers->RowCssClass = "row";

        // km_content_words
        $this->km_content_words->RowCssClass = "row";

        // km_function_words
        $this->km_function_words->RowCssClass = "row";

        // km_syllables
        $this->km_syllables->RowCssClass = "row";

        // km_morphological_endings
        $this->km_morphological_endings->RowCssClass = "row";

        // km_phrases
        $this->km_phrases->RowCssClass = "row";

        // km_sentences
        $this->km_sentences->RowCssClass = "row";

        // km_punctuation
        $this->km_punctuation->RowCssClass = "row";

        // km_nomenclature_size
        $this->km_nomenclature_size->RowCssClass = "row";

        // km_sections
        $this->km_sections->RowCssClass = "row";

        // km_headings
        $this->km_headings->RowCssClass = "row";

        // km_plaintext_arrangement
        $this->km_plaintext_arrangement->RowCssClass = "row";

        // km_ciphertext_arrangement
        $this->km_ciphertext_arrangement->RowCssClass = "row";

        // km_memorability
        $this->km_memorability->RowCssClass = "row";

        // km_symbol_set
        $this->km_symbol_set->RowCssClass = "row";

        // km_diacritics
        $this->km_diacritics->RowCssClass = "row";

        // km_code_length
        $this->km_code_length->RowCssClass = "row";

        // km_code_type
        $this->km_code_type->RowCssClass = "row";

        // km_metaphors
        $this->km_metaphors->RowCssClass = "row";

        // km_material_properties
        $this->km_material_properties->RowCssClass = "row";

        // km_instructions
        $this->km_instructions->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // name
            $this->name->ViewValue = $this->name->CurrentValue;

            // owner_id
            $this->owner_id->ViewValue = $this->owner_id->CurrentValue;
            $this->owner_id->ViewValue = FormatNumber($this->owner_id->ViewValue, $this->owner_id->formatPattern());

            // owner
            $this->owner->ViewValue = $this->owner->CurrentValue;

            // c_holder
            $this->c_holder->ViewValue = $this->c_holder->CurrentValue;

            // c_cates
            $this->c_cates->ViewValue = $this->c_cates->CurrentValue;
            $this->c_cates->CssClass = "fst-italic";

            // c_lang
            $this->c_lang->ViewValue = $this->c_lang->CurrentValue;
            $this->c_lang->ViewCustomAttributes = $this->c_lang->getViewCustomAttributes(); // PHP

            // current_country
            $this->current_country->ViewValue = $this->current_country->CurrentValue;

            // current_city
            $this->current_city->ViewValue = $this->current_city->CurrentValue;

            // current_holder
            $this->current_holder->ViewValue = $this->current_holder->CurrentValue;

            // author
            $this->author->ViewValue = $this->author->CurrentValue;
            $this->author->CssClass = "fw-bold";

            // sender
            $this->sender->ViewValue = $this->sender->CurrentValue;

            // receiver
            $this->receiver->ViewValue = $this->receiver->CurrentValue;

            // origin_region
            $this->origin_region->ViewValue = $this->origin_region->CurrentValue;
            $this->origin_region->CssClass = "fw-bold";

            // origin_city
            $this->origin_city->ViewValue = $this->origin_city->CurrentValue;

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

            // record_type
            if (strval($this->record_type->CurrentValue) != "") {
                $this->record_type->ViewValue = $this->record_type->optionCaption($this->record_type->CurrentValue);
            } else {
                $this->record_type->ViewValue = null;
            }
            $this->record_type->CssClass = "fw-bold";
            $this->record_type->ViewCustomAttributes = $this->record_type->getViewCustomAttributes(); // PHP

            // status
            if (strval($this->status->CurrentValue) != "") {
                $this->status->ViewValue = $this->status->optionCaption($this->status->CurrentValue);
            } else {
                $this->status->ViewValue = null;
            }
            $this->status->CssClass = "fw-bold";
            $this->status->ViewCustomAttributes = $this->status->getViewCustomAttributes(); // PHP

            // symbol_sets
            if (strval($this->symbol_sets->CurrentValue) != "") {
                $this->symbol_sets->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->symbol_sets->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->symbol_sets->ViewValue->add($this->symbol_sets->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->symbol_sets->ViewValue = null;
            }

            // cipher_types
            if (strval($this->cipher_types->CurrentValue) != "") {
                $this->cipher_types->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->cipher_types->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->cipher_types->ViewValue->add($this->cipher_types->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->cipher_types->ViewValue = null;
            }

            // cipher_type_other
            $this->cipher_type_other->ViewValue = $this->cipher_type_other->CurrentValue;

            // symbol_set_other
            $this->symbol_set_other->ViewValue = $this->symbol_set_other->CurrentValue;

            // number_of_pages
            $this->number_of_pages->ViewValue = $this->number_of_pages->CurrentValue;
            $this->number_of_pages->ViewValue = FormatNumber($this->number_of_pages->ViewValue, $this->number_of_pages->formatPattern());

            // inline_cleartext
            if (strval($this->inline_cleartext->CurrentValue) != "") {
                $this->inline_cleartext->ViewValue = $this->inline_cleartext->optionCaption($this->inline_cleartext->CurrentValue);
            } else {
                $this->inline_cleartext->ViewValue = null;
            }

            // inline_plaintext
            if (strval($this->inline_plaintext->CurrentValue) != "") {
                $this->inline_plaintext->ViewValue = $this->inline_plaintext->optionCaption($this->inline_plaintext->CurrentValue);
            } else {
                $this->inline_plaintext->ViewValue = null;
            }

            // cleartext_lang
            $this->cleartext_lang->ViewValue = $this->cleartext_lang->CurrentValue;

            // plaintext_lang
            $this->plaintext_lang->ViewValue = $this->plaintext_lang->CurrentValue;

            // private_ciphertext
            if (strval($this->private_ciphertext->CurrentValue) != "") {
                $this->private_ciphertext->ViewValue = $this->private_ciphertext->optionCaption($this->private_ciphertext->CurrentValue);
            } else {
                $this->private_ciphertext->ViewValue = null;
            }

            // document_types
            if (strval($this->document_types->CurrentValue) != "") {
                $this->document_types->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->document_types->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->document_types->ViewValue->add($this->document_types->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->document_types->ViewValue = null;
            }

            // paper
            $this->paper->ViewValue = $this->paper->CurrentValue;

            // additional_information
            $this->additional_information->ViewValue = $this->additional_information->CurrentValue;

            // creator_id
            $this->creator_id->ViewValue = $this->creator_id->CurrentValue;
            $this->creator_id->ViewValue = FormatNumber($this->creator_id->ViewValue, $this->creator_id->formatPattern());

            // access_mode
            if (strval($this->access_mode->CurrentValue) != "") {
                $this->access_mode->ViewValue = $this->access_mode->optionCaption($this->access_mode->CurrentValue);
            } else {
                $this->access_mode->ViewValue = null;
            }

            // creation_date
            $this->creation_date->ViewValue = $this->creation_date->CurrentValue;
            $this->creation_date->ViewValue = FormatDateTime($this->creation_date->ViewValue, $this->creation_date->formatPattern());

            // km_encoded_plaintext_type
            if (strval($this->km_encoded_plaintext_type->CurrentValue) != "") {
                $this->km_encoded_plaintext_type->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->km_encoded_plaintext_type->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->km_encoded_plaintext_type->ViewValue->add($this->km_encoded_plaintext_type->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->km_encoded_plaintext_type->ViewValue = null;
            }

            // km_numbers
            if (strval($this->km_numbers->CurrentValue) != "") {
                $this->km_numbers->ViewValue = $this->km_numbers->optionCaption($this->km_numbers->CurrentValue);
            } else {
                $this->km_numbers->ViewValue = null;
            }

            // km_content_words
            if (strval($this->km_content_words->CurrentValue) != "") {
                $this->km_content_words->ViewValue = $this->km_content_words->optionCaption($this->km_content_words->CurrentValue);
            } else {
                $this->km_content_words->ViewValue = null;
            }

            // km_function_words
            if (strval($this->km_function_words->CurrentValue) != "") {
                $this->km_function_words->ViewValue = $this->km_function_words->optionCaption($this->km_function_words->CurrentValue);
            } else {
                $this->km_function_words->ViewValue = null;
            }

            // km_syllables
            if (strval($this->km_syllables->CurrentValue) != "") {
                $this->km_syllables->ViewValue = $this->km_syllables->optionCaption($this->km_syllables->CurrentValue);
            } else {
                $this->km_syllables->ViewValue = null;
            }

            // km_morphological_endings
            if (strval($this->km_morphological_endings->CurrentValue) != "") {
                $this->km_morphological_endings->ViewValue = $this->km_morphological_endings->optionCaption($this->km_morphological_endings->CurrentValue);
            } else {
                $this->km_morphological_endings->ViewValue = null;
            }

            // km_phrases
            if (strval($this->km_phrases->CurrentValue) != "") {
                $this->km_phrases->ViewValue = $this->km_phrases->optionCaption($this->km_phrases->CurrentValue);
            } else {
                $this->km_phrases->ViewValue = null;
            }

            // km_sentences
            if (strval($this->km_sentences->CurrentValue) != "") {
                $this->km_sentences->ViewValue = $this->km_sentences->optionCaption($this->km_sentences->CurrentValue);
            } else {
                $this->km_sentences->ViewValue = null;
            }

            // km_punctuation
            if (strval($this->km_punctuation->CurrentValue) != "") {
                $this->km_punctuation->ViewValue = $this->km_punctuation->optionCaption($this->km_punctuation->CurrentValue);
            } else {
                $this->km_punctuation->ViewValue = null;
            }

            // km_nomenclature_size
            if (strval($this->km_nomenclature_size->CurrentValue) != "") {
                $this->km_nomenclature_size->ViewValue = $this->km_nomenclature_size->optionCaption($this->km_nomenclature_size->CurrentValue);
            } else {
                $this->km_nomenclature_size->ViewValue = null;
            }

            // km_sections
            if (strval($this->km_sections->CurrentValue) != "") {
                $this->km_sections->ViewValue = $this->km_sections->optionCaption($this->km_sections->CurrentValue);
            } else {
                $this->km_sections->ViewValue = null;
            }

            // km_headings
            if (strval($this->km_headings->CurrentValue) != "") {
                $this->km_headings->ViewValue = $this->km_headings->optionCaption($this->km_headings->CurrentValue);
            } else {
                $this->km_headings->ViewValue = null;
            }

            // km_plaintext_arrangement
            if (strval($this->km_plaintext_arrangement->CurrentValue) != "") {
                $this->km_plaintext_arrangement->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->km_plaintext_arrangement->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->km_plaintext_arrangement->ViewValue->add($this->km_plaintext_arrangement->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->km_plaintext_arrangement->ViewValue = null;
            }

            // km_ciphertext_arrangement
            if (strval($this->km_ciphertext_arrangement->CurrentValue) != "") {
                $this->km_ciphertext_arrangement->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->km_ciphertext_arrangement->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->km_ciphertext_arrangement->ViewValue->add($this->km_ciphertext_arrangement->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->km_ciphertext_arrangement->ViewValue = null;
            }

            // km_memorability
            if (strval($this->km_memorability->CurrentValue) != "") {
                $this->km_memorability->ViewValue = $this->km_memorability->optionCaption($this->km_memorability->CurrentValue);
            } else {
                $this->km_memorability->ViewValue = null;
            }

            // km_symbol_set
            if (strval($this->km_symbol_set->CurrentValue) != "") {
                $this->km_symbol_set->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->km_symbol_set->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->km_symbol_set->ViewValue->add($this->km_symbol_set->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->km_symbol_set->ViewValue = null;
            }

            // km_diacritics
            if (strval($this->km_diacritics->CurrentValue) != "") {
                $this->km_diacritics->ViewValue = $this->km_diacritics->optionCaption($this->km_diacritics->CurrentValue);
            } else {
                $this->km_diacritics->ViewValue = null;
            }

            // km_code_length
            if (strval($this->km_code_length->CurrentValue) != "") {
                $this->km_code_length->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->km_code_length->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->km_code_length->ViewValue->add($this->km_code_length->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->km_code_length->ViewValue = null;
            }

            // km_code_type
            if (strval($this->km_code_type->CurrentValue) != "") {
                $this->km_code_type->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->km_code_type->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->km_code_type->ViewValue->add($this->km_code_type->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->km_code_type->ViewValue = null;
            }

            // km_metaphors
            if (strval($this->km_metaphors->CurrentValue) != "") {
                $this->km_metaphors->ViewValue = $this->km_metaphors->optionCaption($this->km_metaphors->CurrentValue);
            } else {
                $this->km_metaphors->ViewValue = null;
            }

            // km_material_properties
            if (strval($this->km_material_properties->CurrentValue) != "") {
                $this->km_material_properties->ViewValue = new OptionValues();
                $arwrk = explode(Config("MULTIPLE_OPTION_SEPARATOR"), strval($this->km_material_properties->CurrentValue));
                $cnt = count($arwrk);
                for ($ari = 0; $ari < $cnt; $ari++)
                    $this->km_material_properties->ViewValue->add($this->km_material_properties->optionCaption(trim($arwrk[$ari])));
            } else {
                $this->km_material_properties->ViewValue = null;
            }

            // km_instructions
            if (strval($this->km_instructions->CurrentValue) != "") {
                $this->km_instructions->ViewValue = $this->km_instructions->optionCaption($this->km_instructions->CurrentValue);
            } else {
                $this->km_instructions->ViewValue = null;
            }

            // id
            $this->id->HrefValue = "";
            $this->id->TooltipValue = "";

            // owner
            $this->owner->HrefValue = "";
            $this->owner->TooltipValue = "";

            // c_holder
            $this->c_holder->HrefValue = "";
            $this->c_holder->TooltipValue = "";

            // current_country
            $this->current_country->HrefValue = "";
            $this->current_country->TooltipValue = "";

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

            // record_type
            $this->record_type->HrefValue = "";
            $this->record_type->TooltipValue = "";

            // status
            $this->status->HrefValue = "";
            $this->status->TooltipValue = "";

            // cipher_type_other
            $this->cipher_type_other->HrefValue = "";
            $this->cipher_type_other->TooltipValue = "";

            // symbol_set_other
            $this->symbol_set_other->HrefValue = "";
            $this->symbol_set_other->TooltipValue = "";

            // inline_cleartext
            $this->inline_cleartext->HrefValue = "";
            $this->inline_cleartext->TooltipValue = "";

            // inline_plaintext
            $this->inline_plaintext->HrefValue = "";
            $this->inline_plaintext->TooltipValue = "";

            // cleartext_lang
            $this->cleartext_lang->HrefValue = "";
            $this->cleartext_lang->TooltipValue = "";

            // plaintext_lang
            $this->plaintext_lang->HrefValue = "";
            $this->plaintext_lang->TooltipValue = "";

            // document_types
            $this->document_types->HrefValue = "";
            $this->document_types->TooltipValue = "";

            // paper
            $this->paper->HrefValue = "";
            $this->paper->TooltipValue = "";

            // additional_information
            $this->additional_information->HrefValue = "";
            $this->additional_information->TooltipValue = "";

            // creator_id
            $this->creator_id->HrefValue = "";
            $this->creator_id->TooltipValue = "";

            // creation_date
            $this->creation_date->HrefValue = "";
            $this->creation_date->TooltipValue = "";

            // km_encoded_plaintext_type
            $this->km_encoded_plaintext_type->HrefValue = "";
            $this->km_encoded_plaintext_type->TooltipValue = "";

            // km_numbers
            $this->km_numbers->HrefValue = "";
            $this->km_numbers->TooltipValue = "";

            // km_content_words
            $this->km_content_words->HrefValue = "";
            $this->km_content_words->TooltipValue = "";

            // km_function_words
            $this->km_function_words->HrefValue = "";
            $this->km_function_words->TooltipValue = "";

            // km_syllables
            $this->km_syllables->HrefValue = "";
            $this->km_syllables->TooltipValue = "";

            // km_morphological_endings
            $this->km_morphological_endings->HrefValue = "";
            $this->km_morphological_endings->TooltipValue = "";

            // km_phrases
            $this->km_phrases->HrefValue = "";
            $this->km_phrases->TooltipValue = "";

            // km_sentences
            $this->km_sentences->HrefValue = "";
            $this->km_sentences->TooltipValue = "";

            // km_punctuation
            $this->km_punctuation->HrefValue = "";
            $this->km_punctuation->TooltipValue = "";

            // km_nomenclature_size
            $this->km_nomenclature_size->HrefValue = "";
            $this->km_nomenclature_size->TooltipValue = "";

            // km_sections
            $this->km_sections->HrefValue = "";
            $this->km_sections->TooltipValue = "";

            // km_headings
            $this->km_headings->HrefValue = "";
            $this->km_headings->TooltipValue = "";

            // km_plaintext_arrangement
            $this->km_plaintext_arrangement->HrefValue = "";
            $this->km_plaintext_arrangement->TooltipValue = "";

            // km_ciphertext_arrangement
            $this->km_ciphertext_arrangement->HrefValue = "";
            $this->km_ciphertext_arrangement->TooltipValue = "";

            // km_memorability
            $this->km_memorability->HrefValue = "";
            $this->km_memorability->TooltipValue = "";

            // km_symbol_set
            $this->km_symbol_set->HrefValue = "";
            $this->km_symbol_set->TooltipValue = "";

            // km_diacritics
            $this->km_diacritics->HrefValue = "";
            $this->km_diacritics->TooltipValue = "";

            // km_code_length
            $this->km_code_length->HrefValue = "";
            $this->km_code_length->TooltipValue = "";

            // km_code_type
            $this->km_code_type->HrefValue = "";
            $this->km_code_type->TooltipValue = "";

            // km_metaphors
            $this->km_metaphors->HrefValue = "";
            $this->km_metaphors->TooltipValue = "";

            // km_material_properties
            $this->km_material_properties->HrefValue = "";
            $this->km_material_properties->TooltipValue = "";

            // km_instructions
            $this->km_instructions->HrefValue = "";
            $this->km_instructions->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // id
            $this->id->setupEditAttributes();
            $this->id->EditValue = $this->id->AdvancedSearch->SearchValue;
            $this->id->PlaceHolder = RemoveHtml($this->id->caption());

            // owner
            $this->owner->setupEditAttributes();
            if (!$this->owner->Raw) {
                $this->owner->AdvancedSearch->SearchValue = HtmlDecode($this->owner->AdvancedSearch->SearchValue);
            }
            $this->owner->EditValue = HtmlEncode($this->owner->AdvancedSearch->SearchValue);
            $this->owner->PlaceHolder = RemoveHtml($this->owner->caption());

            // c_holder
            $this->c_holder->setupEditAttributes();
            $this->c_holder->EditValue = HtmlEncode($this->c_holder->AdvancedSearch->SearchValue);
            $this->c_holder->PlaceHolder = RemoveHtml($this->c_holder->caption());

            // current_country
            $this->current_country->setupEditAttributes();
            if (!$this->current_country->Raw) {
                $this->current_country->AdvancedSearch->SearchValue = HtmlDecode($this->current_country->AdvancedSearch->SearchValue);
            }
            $this->current_country->EditValue = HtmlEncode($this->current_country->AdvancedSearch->SearchValue);
            $arwrk = [];
            $arwrk["lf"] = $this->current_country->CurrentValue;
            $arwrk["df"] = $this->current_country->CurrentValue;
            $arwrk = $this->current_country->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->current_country->displayValue($arwrk);
            if ($dispVal != "") {
                $this->current_country->EditValue = $dispVal;
            }
            $this->current_country->PlaceHolder = RemoveHtml($this->current_country->caption());

            // author
            $this->author->setupEditAttributes();
            if (!$this->author->Raw) {
                $this->author->AdvancedSearch->SearchValue = HtmlDecode($this->author->AdvancedSearch->SearchValue);
            }
            $this->author->EditValue = HtmlEncode($this->author->AdvancedSearch->SearchValue);
            $arwrk = [];
            $arwrk["lf"] = $this->author->CurrentValue;
            $arwrk["df"] = $this->author->CurrentValue;
            $arwrk = $this->author->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->author->displayValue($arwrk);
            if ($dispVal != "") {
                $this->author->EditValue = $dispVal;
            }
            $this->author->PlaceHolder = RemoveHtml($this->author->caption());

            // sender
            $this->sender->setupEditAttributes();
            if (!$this->sender->Raw) {
                $this->sender->AdvancedSearch->SearchValue = HtmlDecode($this->sender->AdvancedSearch->SearchValue);
            }
            $this->sender->EditValue = HtmlEncode($this->sender->AdvancedSearch->SearchValue);
            $arwrk = [];
            $arwrk["lf"] = $this->sender->CurrentValue;
            $arwrk["df"] = $this->sender->CurrentValue;
            $arwrk = $this->sender->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->sender->displayValue($arwrk);
            if ($dispVal != "") {
                $this->sender->EditValue = $dispVal;
            }
            $this->sender->PlaceHolder = RemoveHtml($this->sender->caption());

            // receiver
            $this->receiver->setupEditAttributes();
            if (!$this->receiver->Raw) {
                $this->receiver->AdvancedSearch->SearchValue = HtmlDecode($this->receiver->AdvancedSearch->SearchValue);
            }
            $this->receiver->EditValue = HtmlEncode($this->receiver->AdvancedSearch->SearchValue);
            $arwrk = [];
            $arwrk["lf"] = $this->receiver->CurrentValue;
            $arwrk["df"] = $this->receiver->CurrentValue;
            $arwrk = $this->receiver->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->receiver->displayValue($arwrk);
            if ($dispVal != "") {
                $this->receiver->EditValue = $dispVal;
            }
            $this->receiver->PlaceHolder = RemoveHtml($this->receiver->caption());

            // origin_region
            $this->origin_region->setupEditAttributes();
            if (!$this->origin_region->Raw) {
                $this->origin_region->AdvancedSearch->SearchValue = HtmlDecode($this->origin_region->AdvancedSearch->SearchValue);
            }
            $this->origin_region->EditValue = HtmlEncode($this->origin_region->AdvancedSearch->SearchValue);
            $arwrk = [];
            $arwrk["lf"] = $this->origin_region->CurrentValue;
            $arwrk["df"] = $this->origin_region->CurrentValue;
            $arwrk = $this->origin_region->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->origin_region->displayValue($arwrk);
            if ($dispVal != "") {
                $this->origin_region->EditValue = $dispVal;
            }
            $this->origin_region->PlaceHolder = RemoveHtml($this->origin_region->caption());

            // origin_city
            $this->origin_city->setupEditAttributes();
            if (!$this->origin_city->Raw) {
                $this->origin_city->AdvancedSearch->SearchValue = HtmlDecode($this->origin_city->AdvancedSearch->SearchValue);
            }
            $this->origin_city->EditValue = HtmlEncode($this->origin_city->AdvancedSearch->SearchValue);
            $arwrk = [];
            $arwrk["lf"] = $this->origin_city->CurrentValue;
            $arwrk["df"] = $this->origin_city->CurrentValue;
            $arwrk = $this->origin_city->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->origin_city->displayValue($arwrk);
            if ($dispVal != "") {
                $this->origin_city->EditValue = $dispVal;
            }
            $this->origin_city->PlaceHolder = RemoveHtml($this->origin_city->caption());

            // start_year
            $this->start_year->setupEditAttributes();
            $this->start_year->EditValue = $this->start_year->AdvancedSearch->SearchValue;
            $this->start_year->PlaceHolder = RemoveHtml($this->start_year->caption());
            $this->start_year->setupEditAttributes();
            $this->start_year->EditValue2 = $this->start_year->AdvancedSearch->SearchValue2;
            $this->start_year->PlaceHolder = RemoveHtml($this->start_year->caption());

            // start_month
            $this->start_month->setupEditAttributes();
            $this->start_month->EditValue = $this->start_month->AdvancedSearch->SearchValue;
            $this->start_month->PlaceHolder = RemoveHtml($this->start_month->caption());

            // start_day
            $this->start_day->setupEditAttributes();
            $this->start_day->EditValue = $this->start_day->AdvancedSearch->SearchValue;
            $this->start_day->PlaceHolder = RemoveHtml($this->start_day->caption());

            // end_year
            $this->end_year->setupEditAttributes();
            $this->end_year->EditValue = $this->end_year->AdvancedSearch->SearchValue;
            $this->end_year->PlaceHolder = RemoveHtml($this->end_year->caption());
            $this->end_year->setupEditAttributes();
            $this->end_year->EditValue2 = $this->end_year->AdvancedSearch->SearchValue2;
            $this->end_year->PlaceHolder = RemoveHtml($this->end_year->caption());

            // end_month
            $this->end_month->setupEditAttributes();
            $this->end_month->EditValue = $this->end_month->AdvancedSearch->SearchValue;
            $this->end_month->PlaceHolder = RemoveHtml($this->end_month->caption());

            // end_day
            $this->end_day->setupEditAttributes();
            $this->end_day->EditValue = $this->end_day->AdvancedSearch->SearchValue;
            $this->end_day->PlaceHolder = RemoveHtml($this->end_day->caption());

            // record_type
            $this->record_type->setupEditAttributes();
            $this->record_type->EditValue = $this->record_type->options(true);
            $this->record_type->PlaceHolder = RemoveHtml($this->record_type->caption());

            // status
            $this->status->setupEditAttributes();
            $this->status->EditValue = $this->status->options(true);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // cipher_type_other
            $this->cipher_type_other->setupEditAttributes();
            if (!$this->cipher_type_other->Raw) {
                $this->cipher_type_other->AdvancedSearch->SearchValue = HtmlDecode($this->cipher_type_other->AdvancedSearch->SearchValue);
            }
            $this->cipher_type_other->EditValue = HtmlEncode($this->cipher_type_other->AdvancedSearch->SearchValue);
            $this->cipher_type_other->PlaceHolder = RemoveHtml($this->cipher_type_other->caption());

            // symbol_set_other
            $this->symbol_set_other->setupEditAttributes();
            if (!$this->symbol_set_other->Raw) {
                $this->symbol_set_other->AdvancedSearch->SearchValue = HtmlDecode($this->symbol_set_other->AdvancedSearch->SearchValue);
            }
            $this->symbol_set_other->EditValue = HtmlEncode($this->symbol_set_other->AdvancedSearch->SearchValue);
            $this->symbol_set_other->PlaceHolder = RemoveHtml($this->symbol_set_other->caption());

            // inline_cleartext
            $this->inline_cleartext->EditValue = $this->inline_cleartext->options(false);
            $this->inline_cleartext->PlaceHolder = RemoveHtml($this->inline_cleartext->caption());

            // inline_plaintext
            $this->inline_plaintext->EditValue = $this->inline_plaintext->options(false);
            $this->inline_plaintext->PlaceHolder = RemoveHtml($this->inline_plaintext->caption());

            // cleartext_lang
            $this->cleartext_lang->setupEditAttributes();
            if (!$this->cleartext_lang->Raw) {
                $this->cleartext_lang->AdvancedSearch->SearchValue = HtmlDecode($this->cleartext_lang->AdvancedSearch->SearchValue);
            }
            $this->cleartext_lang->EditValue = HtmlEncode($this->cleartext_lang->AdvancedSearch->SearchValue);
            $this->cleartext_lang->PlaceHolder = RemoveHtml($this->cleartext_lang->caption());

            // plaintext_lang
            $this->plaintext_lang->setupEditAttributes();
            if (!$this->plaintext_lang->Raw) {
                $this->plaintext_lang->AdvancedSearch->SearchValue = HtmlDecode($this->plaintext_lang->AdvancedSearch->SearchValue);
            }
            $this->plaintext_lang->EditValue = HtmlEncode($this->plaintext_lang->AdvancedSearch->SearchValue);
            $this->plaintext_lang->PlaceHolder = RemoveHtml($this->plaintext_lang->caption());

            // document_types
            $this->document_types->EditValue = $this->document_types->options(false);
            $this->document_types->PlaceHolder = RemoveHtml($this->document_types->caption());

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

            // creator_id
            $this->creator_id->setupEditAttributes();
            $this->creator_id->EditValue = $this->creator_id->AdvancedSearch->SearchValue;
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

            // creation_date
            $this->creation_date->setupEditAttributes();
            $this->creation_date->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->creation_date->AdvancedSearch->SearchValue, $this->creation_date->formatPattern()), $this->creation_date->formatPattern()));
            $this->creation_date->PlaceHolder = RemoveHtml($this->creation_date->caption());
            $this->creation_date->setupEditAttributes();
            $this->creation_date->EditValue2 = HtmlEncode(FormatDateTime(UnFormatDateTime($this->creation_date->AdvancedSearch->SearchValue2, $this->creation_date->formatPattern()), $this->creation_date->formatPattern()));
            $this->creation_date->PlaceHolder = RemoveHtml($this->creation_date->caption());

            // km_encoded_plaintext_type
            $this->km_encoded_plaintext_type->setupEditAttributes();
            $this->km_encoded_plaintext_type->EditValue = $this->km_encoded_plaintext_type->options(false);
            $this->km_encoded_plaintext_type->PlaceHolder = RemoveHtml($this->km_encoded_plaintext_type->caption());

            // km_numbers
            $this->km_numbers->EditValue = $this->km_numbers->options(false);
            $this->km_numbers->PlaceHolder = RemoveHtml($this->km_numbers->caption());

            // km_content_words
            $this->km_content_words->EditValue = $this->km_content_words->options(false);
            $this->km_content_words->PlaceHolder = RemoveHtml($this->km_content_words->caption());

            // km_function_words
            $this->km_function_words->EditValue = $this->km_function_words->options(false);
            $this->km_function_words->PlaceHolder = RemoveHtml($this->km_function_words->caption());

            // km_syllables
            $this->km_syllables->EditValue = $this->km_syllables->options(false);
            $this->km_syllables->PlaceHolder = RemoveHtml($this->km_syllables->caption());

            // km_morphological_endings
            $this->km_morphological_endings->EditValue = $this->km_morphological_endings->options(false);
            $this->km_morphological_endings->PlaceHolder = RemoveHtml($this->km_morphological_endings->caption());

            // km_phrases
            $this->km_phrases->EditValue = $this->km_phrases->options(false);
            $this->km_phrases->PlaceHolder = RemoveHtml($this->km_phrases->caption());

            // km_sentences
            $this->km_sentences->EditValue = $this->km_sentences->options(false);
            $this->km_sentences->PlaceHolder = RemoveHtml($this->km_sentences->caption());

            // km_punctuation
            $this->km_punctuation->EditValue = $this->km_punctuation->options(false);
            $this->km_punctuation->PlaceHolder = RemoveHtml($this->km_punctuation->caption());

            // km_nomenclature_size
            $this->km_nomenclature_size->setupEditAttributes();
            $this->km_nomenclature_size->EditValue = $this->km_nomenclature_size->options(true);
            $this->km_nomenclature_size->PlaceHolder = RemoveHtml($this->km_nomenclature_size->caption());

            // km_sections
            $this->km_sections->EditValue = $this->km_sections->options(false);
            $this->km_sections->PlaceHolder = RemoveHtml($this->km_sections->caption());

            // km_headings
            $this->km_headings->EditValue = $this->km_headings->options(false);
            $this->km_headings->PlaceHolder = RemoveHtml($this->km_headings->caption());

            // km_plaintext_arrangement
            $this->km_plaintext_arrangement->setupEditAttributes();
            $this->km_plaintext_arrangement->EditValue = $this->km_plaintext_arrangement->options(false);
            $this->km_plaintext_arrangement->PlaceHolder = RemoveHtml($this->km_plaintext_arrangement->caption());

            // km_ciphertext_arrangement
            $this->km_ciphertext_arrangement->setupEditAttributes();
            $this->km_ciphertext_arrangement->EditValue = $this->km_ciphertext_arrangement->options(false);
            $this->km_ciphertext_arrangement->PlaceHolder = RemoveHtml($this->km_ciphertext_arrangement->caption());

            // km_memorability
            $this->km_memorability->setupEditAttributes();
            $this->km_memorability->EditValue = $this->km_memorability->options(true);
            $this->km_memorability->PlaceHolder = RemoveHtml($this->km_memorability->caption());

            // km_symbol_set
            $this->km_symbol_set->EditValue = $this->km_symbol_set->options(false);
            $this->km_symbol_set->PlaceHolder = RemoveHtml($this->km_symbol_set->caption());

            // km_diacritics
            $this->km_diacritics->EditValue = $this->km_diacritics->options(false);
            $this->km_diacritics->PlaceHolder = RemoveHtml($this->km_diacritics->caption());

            // km_code_length
            $this->km_code_length->setupEditAttributes();
            $this->km_code_length->EditValue = $this->km_code_length->options(false);
            $this->km_code_length->PlaceHolder = RemoveHtml($this->km_code_length->caption());

            // km_code_type
            $this->km_code_type->setupEditAttributes();
            $this->km_code_type->EditValue = $this->km_code_type->options(false);
            $this->km_code_type->PlaceHolder = RemoveHtml($this->km_code_type->caption());

            // km_metaphors
            $this->km_metaphors->EditValue = $this->km_metaphors->options(false);
            $this->km_metaphors->PlaceHolder = RemoveHtml($this->km_metaphors->caption());

            // km_material_properties
            $this->km_material_properties->setupEditAttributes();
            $this->km_material_properties->EditValue = $this->km_material_properties->options(false);
            $this->km_material_properties->PlaceHolder = RemoveHtml($this->km_material_properties->caption());

            // km_instructions
            $this->km_instructions->EditValue = $this->km_instructions->options(false);
            $this->km_instructions->PlaceHolder = RemoveHtml($this->km_instructions->caption());
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
        if (!CheckInteger($this->start_day->AdvancedSearch->SearchValue)) {
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
        if (!CheckInteger($this->end_day->AdvancedSearch->SearchValue)) {
            $this->end_day->addErrorMessage($this->end_day->getErrorMessage(false));
        }
        if (!CheckDate($this->creation_date->AdvancedSearch->SearchValue, $this->creation_date->formatPattern())) {
            $this->creation_date->addErrorMessage($this->creation_date->getErrorMessage(false));
        }
        if (!CheckDate($this->creation_date->AdvancedSearch->SearchValue2, $this->creation_date->formatPattern())) {
            $this->creation_date->addErrorMessage($this->creation_date->getErrorMessage(false));
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
        $this->owner->AdvancedSearch->load();
        $this->c_holder->AdvancedSearch->load();
        $this->current_country->AdvancedSearch->load();
        $this->author->AdvancedSearch->load();
        $this->sender->AdvancedSearch->load();
        $this->receiver->AdvancedSearch->load();
        $this->origin_region->AdvancedSearch->load();
        $this->origin_city->AdvancedSearch->load();
        $this->start_year->AdvancedSearch->load();
        $this->start_month->AdvancedSearch->load();
        $this->start_day->AdvancedSearch->load();
        $this->end_year->AdvancedSearch->load();
        $this->end_month->AdvancedSearch->load();
        $this->end_day->AdvancedSearch->load();
        $this->record_type->AdvancedSearch->load();
        $this->status->AdvancedSearch->load();
        $this->cipher_type_other->AdvancedSearch->load();
        $this->symbol_set_other->AdvancedSearch->load();
        $this->inline_cleartext->AdvancedSearch->load();
        $this->inline_plaintext->AdvancedSearch->load();
        $this->cleartext_lang->AdvancedSearch->load();
        $this->plaintext_lang->AdvancedSearch->load();
        $this->document_types->AdvancedSearch->load();
        $this->paper->AdvancedSearch->load();
        $this->additional_information->AdvancedSearch->load();
        $this->creator_id->AdvancedSearch->load();
        $this->creation_date->AdvancedSearch->load();
        $this->km_encoded_plaintext_type->AdvancedSearch->load();
        $this->km_numbers->AdvancedSearch->load();
        $this->km_content_words->AdvancedSearch->load();
        $this->km_function_words->AdvancedSearch->load();
        $this->km_syllables->AdvancedSearch->load();
        $this->km_morphological_endings->AdvancedSearch->load();
        $this->km_phrases->AdvancedSearch->load();
        $this->km_sentences->AdvancedSearch->load();
        $this->km_punctuation->AdvancedSearch->load();
        $this->km_nomenclature_size->AdvancedSearch->load();
        $this->km_sections->AdvancedSearch->load();
        $this->km_headings->AdvancedSearch->load();
        $this->km_plaintext_arrangement->AdvancedSearch->load();
        $this->km_ciphertext_arrangement->AdvancedSearch->load();
        $this->km_memorability->AdvancedSearch->load();
        $this->km_symbol_set->AdvancedSearch->load();
        $this->km_diacritics->AdvancedSearch->load();
        $this->km_code_length->AdvancedSearch->load();
        $this->km_code_type->AdvancedSearch->load();
        $this->km_metaphors->AdvancedSearch->load();
        $this->km_material_properties->AdvancedSearch->load();
        $this->km_instructions->AdvancedSearch->load();
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("RecordsList");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("RecordsList"), "", $this->TableVar, true);
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
                case "x_name":
                    break;
                case "x_record_group_id":
                    break;
                case "x_current_country":
                    break;
                case "x_current_city":
                    break;
                case "x_current_holder":
                    break;
                case "x_author":
                    break;
                case "x_sender":
                    break;
                case "x_receiver":
                    break;
                case "x_origin_region":
                    break;
                case "x_origin_city":
                    break;
                case "x_record_type":
                    break;
                case "x_status":
                    break;
                case "x_symbol_sets":
                    break;
                case "x_cipher_types":
                    break;
                case "x_inline_cleartext":
                    break;
                case "x_inline_plaintext":
                    break;
                case "x_private_ciphertext":
                    break;
                case "x_document_types":
                    break;
                case "x_access_mode":
                    break;
                case "x_km_encoded_plaintext_type":
                    break;
                case "x_km_numbers":
                    break;
                case "x_km_content_words":
                    break;
                case "x_km_function_words":
                    break;
                case "x_km_syllables":
                    break;
                case "x_km_morphological_endings":
                    break;
                case "x_km_phrases":
                    break;
                case "x_km_sentences":
                    break;
                case "x_km_punctuation":
                    break;
                case "x_km_nomenclature_size":
                    break;
                case "x_km_sections":
                    break;
                case "x_km_headings":
                    break;
                case "x_km_plaintext_arrangement":
                    break;
                case "x_km_ciphertext_arrangement":
                    break;
                case "x_km_memorability":
                    break;
                case "x_km_symbol_set":
                    break;
                case "x_km_diacritics":
                    break;
                case "x_km_code_length":
                    break;
                case "x_km_code_type":
                    break;
                case "x_km_metaphors":
                    break;
                case "x_km_material_properties":
                    break;
                case "x_km_instructions":
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
    public function pageRender() {
    //	echo "<pre>";
    //	print_r($this);
    //	echo "</pre>";
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
