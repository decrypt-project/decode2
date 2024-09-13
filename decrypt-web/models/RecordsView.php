<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class RecordsView extends Records
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "RecordsView";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "RecordsView";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $CopyUrl;
    public $ListUrl;

    // Update URLs
    public $InlineAddUrl;
    public $InlineCopyUrl;
    public $InlineEditUrl;
    public $GridAddUrl;
    public $GridEditUrl;
    public $MultiEditUrl;
    public $MultiDeleteUrl;
    public $MultiUpdateUrl;

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
        $this->name->setVisibility();
        $this->owner_id->setVisibility();
        $this->owner->setVisibility();
        $this->record_group_id->setVisibility();
        $this->c_holder->setVisibility();
        $this->c_cates->setVisibility();
        $this->c_author->setVisibility();
        $this->c_lang->setVisibility();
        $this->current_country->setVisibility();
        $this->current_city->setVisibility();
        $this->current_holder->setVisibility();
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
        $this->symbol_sets->setVisibility();
        $this->cipher_types->setVisibility();
        $this->cipher_type_other->setVisibility();
        $this->symbol_set_other->setVisibility();
        $this->number_of_pages->setVisibility();
        $this->inline_cleartext->setVisibility();
        $this->inline_plaintext->setVisibility();
        $this->cleartext_lang->setVisibility();
        $this->plaintext_lang->setVisibility();
        $this->private_ciphertext->setVisibility();
        $this->document_types->setVisibility();
        $this->paper->setVisibility();
        $this->additional_information->setVisibility();
        $this->creator_id->setVisibility();
        $this->access_mode->setVisibility();
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
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-view-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (records)
        if (!isset($GLOBALS["records"]) || get_class($GLOBALS["records"]) == PROJECT_NAMESPACE . "records") {
            $GLOBALS["records"] = &$this;
        }

        // Set up record key
        if (($keyValue = Get("id") ?? Route("id")) !== null) {
            $this->RecKey["id"] = $keyValue;
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

        // Export options
        $this->ExportOptions = new ListOptions(["TagClassName" => "ew-export-option"]);

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }

        // Detail tables
        $this->OtherOptions["detail"] = new ListOptions(["TagClassName" => "ew-detail-option"]);
        // Actions
        $this->OtherOptions["action"] = new ListOptions(["TagClassName" => "ew-action-option"]);
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
    public $ExportOptions; // Export options
    public $OtherOptions; // Other options
    public $DisplayRecords = 1;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecKey = [];
    public $IsModal = false;

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

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }

        // Load current record
        $loadCurrentRecord = false;
        $returnUrl = "";
        $matchRecord = false;
        if (($keyValue = Get("id") ?? Route("id")) !== null) {
            $this->id->setQueryStringValue($keyValue);
            $this->RecKey["id"] = $this->id->QueryStringValue;
        } elseif (Post("id") !== null) {
            $this->id->setFormValue(Post("id"));
            $this->RecKey["id"] = $this->id->FormValue;
        } elseif (IsApi() && ($keyValue = Key(0) ?? Route(2)) !== null) {
            $this->id->setQueryStringValue($keyValue);
            $this->RecKey["id"] = $this->id->QueryStringValue;
        } elseif (!$loadCurrentRecord) {
            $returnUrl = "RecordsList"; // Return to list
        }

        // Get action
        $this->CurrentAction = "show"; // Display
        switch ($this->CurrentAction) {
            case "show": // Get a record to display

                    // Load record based on key
                    if (IsApi()) {
                        $filter = $this->getRecordFilter();
                        $this->CurrentFilter = $filter;
                        $sql = $this->getCurrentSql();
                        $conn = $this->getConnection();
                        $this->Recordset = LoadRecordset($sql, $conn);
                        $res = $this->Recordset && !$this->Recordset->EOF;
                    } else {
                        $res = $this->loadRow();
                    }
                    if (!$res) { // Load record based on key
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $returnUrl = "RecordsList"; // No matching record, return to list
                    }
                break;
        }
        if ($returnUrl != "") {
            $this->terminate($returnUrl);
            return;
        }

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

        // Render row
        $this->RowType = ROWTYPE_VIEW;
        $this->resetAttributes();
        $this->renderRow();

        // Set up detail parameters
        $this->setupDetailParms();

        // Normal return
        if (IsApi()) {
            if (!$this->isExport()) {
                $row = $this->getRecordsFromRecordset($this->Recordset, true); // Get current record only
                $this->Recordset->close();
                WriteJson(["success" => true, "action" => Config("API_VIEW_ACTION"), $this->TableVar => $row]);
                $this->terminate(true);
            }
            return;
        }

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

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;

        // Disable Add/Edit/Copy/Delete for Modal and UseAjaxActions
        /*
        if ($this->IsModal && $this->UseAjaxActions) {
            $this->AddUrl = "";
            $this->EditUrl = "";
            $this->CopyUrl = "";
            $this->DeleteUrl = "";
        }
        */
        $options = &$this->OtherOptions;
        $option = $options["action"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("ViewPageAddLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" data-ew-action=\"modal\" data-url=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("ViewPageAddLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("ViewPageAddLink") . "</a>";
        }
        $item->Visible = $this->AddUrl != "" && $Security->canAdd();

        // Edit
        $item = &$option->add("edit");
        $editcaption = HtmlTitle($Language->phrase("ViewPageEditLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("ViewPageEditLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("ViewPageEditLink") . "</a>";
        }
        $item->Visible = $this->EditUrl != "" && $Security->canEdit();

        // Copy
        $item = &$option->add("copy");
        $copycaption = HtmlTitle($Language->phrase("ViewPageCopyLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" data-ew-action=\"modal\" data-url=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        }
        $item->Visible = $this->CopyUrl != "" && $Security->canAdd();

        // Delete
        $item = &$option->add("delete");
        $url = GetUrl($this->DeleteUrl);
        $item->Body = "<a class=\"ew-action ew-delete\"" .
            ($this->InlineDelete || $this->IsModal ? " data-ew-action=\"inline-delete\"" : "") .
            " title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) .
            "\" href=\"" . HtmlEncode($url) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        $item->Visible = $this->DeleteUrl != "" && $Security->canDelete();
        $option = $options["detail"];
        $detailTableLink = "";
        $detailViewTblVar = "";
        $detailCopyTblVar = "";
        $detailEditTblVar = "";

        // "detail_images"
        $item = &$option->add("detail_images");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("images", "TblCaption");
        $detailTbl = Container("images");
        $detailFilter = $detailTbl->getDetailFilter($this);
        $detailTbl->setCurrentMasterTable($this->TableVar);
        $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
        $detailTbl->Count = $detailTbl->loadRecordCount($detailFilter);
        if (!$this->ShowMultipleDetails) { // Skip record count if show multiple details
            $body .= "&nbsp;" . str_replace("%c", Container("images")->Count, $Language->phrase("DetailCount"));
        }
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("ImagesList?" . Config("TABLE_SHOW_MASTER") . "=records&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("ImagesGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'records')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=images"))) . "\">" . $Language->phrase("MasterDetailViewLink", null) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "images";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'records')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=images"))) . "\">" . $Language->phrase("MasterDetailEditLink", null) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "images";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'records')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=images"))) . "\">" . $Language->phrase("MasterDetailCopyLink", null) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "images";
        }
        if ($links != "") {
            $body .= "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-detail\" data-bs-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        } else {
            $body = preg_replace('/\b\s+dropdown-toggle\b/', "", $body);
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'images');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "images";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // "detail_documents"
        $item = &$option->add("detail_documents");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("documents", "TblCaption");
        $detailTbl = Container("documents");
        $detailFilter = $detailTbl->getDetailFilter($this);
        $detailTbl->setCurrentMasterTable($this->TableVar);
        $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
        $detailTbl->Count = $detailTbl->loadRecordCount($detailFilter);
        if (!$this->ShowMultipleDetails) { // Skip record count if show multiple details
            $body .= "&nbsp;" . str_replace("%c", Container("documents")->Count, $Language->phrase("DetailCount"));
        }
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("DocumentsList?" . Config("TABLE_SHOW_MASTER") . "=records&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("DocumentsGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'records')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=documents"))) . "\">" . $Language->phrase("MasterDetailViewLink", null) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "documents";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'records')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=documents"))) . "\">" . $Language->phrase("MasterDetailEditLink", null) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "documents";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'records')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=documents"))) . "\">" . $Language->phrase("MasterDetailCopyLink", null) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "documents";
        }
        if ($links != "") {
            $body .= "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-detail\" data-bs-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        } else {
            $body = preg_replace('/\b\s+dropdown-toggle\b/', "", $body);
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'documents');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "documents";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // "detail_associated_records"
        $item = &$option->add("detail_associated_records");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("associated_records", "TblCaption");
        $detailTbl = Container("associated_records");
        $detailFilter = $detailTbl->getDetailFilter($this);
        $detailTbl->setCurrentMasterTable($this->TableVar);
        $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
        $detailTbl->Count = $detailTbl->loadRecordCount($detailFilter);
        if (!$this->ShowMultipleDetails) { // Skip record count if show multiple details
            $body .= "&nbsp;" . str_replace("%c", Container("associated_records")->Count, $Language->phrase("DetailCount"));
        }
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("AssociatedRecordsList?" . Config("TABLE_SHOW_MASTER") . "=records&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("AssociatedRecordsGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'records')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=associated_records"))) . "\">" . $Language->phrase("MasterDetailViewLink", null) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "associated_records";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'records')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=associated_records"))) . "\">" . $Language->phrase("MasterDetailEditLink", null) . "</a></li>";
            if ($detailEditTblVar != "") {
                $detailEditTblVar .= ",";
            }
            $detailEditTblVar .= "associated_records";
        }
        if ($detailPageObj->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'records')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailCopyLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=associated_records"))) . "\">" . $Language->phrase("MasterDetailCopyLink", null) . "</a></li>";
            if ($detailCopyTblVar != "") {
                $detailCopyTblVar .= ",";
            }
            $detailCopyTblVar .= "associated_records";
        }
        if ($links != "") {
            $body .= "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-detail\" data-bs-toggle=\"dropdown\"></button>";
            $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
        } else {
            $body = preg_replace('/\b\s+dropdown-toggle\b/', "", $body);
        }
        $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'associated_records');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "associated_records";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // Multiple details
        if ($this->ShowMultipleDetails) {
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">";
            $links = "";
            if ($detailViewTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailViewLink", true)) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailViewTblVar))) . "\">" . $Language->phrase("MasterDetailViewLink", null) . "</a></li>";
            }
            if ($detailEditTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailEditLink", true)) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailEditTblVar))) . "\">" . $Language->phrase("MasterDetailEditLink", null) . "</a></li>";
            }
            if ($detailCopyTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailCopyLink", true)) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailCopyTblVar))) . "\">" . $Language->phrase("MasterDetailCopyLink", null) . "</a></li>";
            }
            if ($links != "") {
                $body .= "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-master-detail\" title=\"" . HtmlEncode($Language->phrase("MultipleMasterDetails", true)) . "\" data-bs-toggle=\"dropdown\">" . $Language->phrase("MultipleMasterDetails") . "</button>";
                $body .= "<ul class=\"dropdown-menu ew-dropdown-menu\">" . $links . "</ul>";
            }
            $body .= "</div>";
            // Multiple details
            $item = &$option->add("details");
            $item->Body = $body;
        }

        // Set up detail default
        $option = $options["detail"];
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $ar = explode(",", $detailTableLink);
        $cnt = count($ar);
        $option->UseDropDownButton = ($cnt > 1);
        $option->UseButtonGroup = true;
        $item = &$option->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Set up action default
        $option = $options["action"];
        $option->DropDownButtonPhrase = $Language->phrase("ButtonActions");
        $option->UseDropDownButton = !IsJsonResponse() && false;
        $option->UseButtonGroup = true;
        $item = &$option->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }
        if (!$row) {
            return;
        }

        // Call Row Selected event
        $this->rowSelected($row);
        $this->id->setDbValue($row['id']);
        $this->name->setDbValue($row['name']);
        $this->owner_id->setDbValue($row['owner_id']);
        $this->owner->setDbValue($row['owner']);
        $this->record_group_id->setDbValue($row['record_group_id']);
        $this->c_holder->setDbValue($row['c_holder']);
        $this->c_cates->setDbValue($row['c_cates']);
        $this->c_author->setDbValue($row['c_author']);
        $this->c_lang->setDbValue($row['c_lang']);
        $this->current_country->setDbValue($row['current_country']);
        $this->current_city->setDbValue($row['current_city']);
        $this->current_holder->setDbValue($row['current_holder']);
        $this->author->setDbValue($row['author']);
        $this->sender->setDbValue($row['sender']);
        $this->receiver->setDbValue($row['receiver']);
        $this->origin_region->setDbValue($row['origin_region']);
        $this->origin_city->setDbValue($row['origin_city']);
        $this->start_year->setDbValue($row['start_year']);
        $this->start_month->setDbValue($row['start_month']);
        $this->start_day->setDbValue($row['start_day']);
        $this->end_year->setDbValue($row['end_year']);
        $this->end_month->setDbValue($row['end_month']);
        $this->end_day->setDbValue($row['end_day']);
        $this->record_type->setDbValue($row['record_type']);
        $this->status->setDbValue($row['status']);
        $this->symbol_sets->setDbValue($row['symbol_sets']);
        $this->cipher_types->setDbValue($row['cipher_types']);
        $this->cipher_type_other->setDbValue($row['cipher_type_other']);
        $this->symbol_set_other->setDbValue($row['symbol_set_other']);
        $this->number_of_pages->setDbValue($row['number_of_pages']);
        $this->inline_cleartext->setDbValue($row['inline_cleartext']);
        $this->inline_plaintext->setDbValue($row['inline_plaintext']);
        $this->cleartext_lang->setDbValue($row['cleartext_lang']);
        $this->plaintext_lang->setDbValue($row['plaintext_lang']);
        $this->private_ciphertext->setDbValue($row['private_ciphertext']);
        $this->document_types->setDbValue($row['document_types']);
        $this->paper->setDbValue($row['paper']);
        $this->additional_information->setDbValue($row['additional_information']);
        $this->creator_id->setDbValue($row['creator_id']);
        $this->access_mode->setDbValue($row['access_mode']);
        $this->creation_date->setDbValue($row['creation_date']);
        $this->km_encoded_plaintext_type->setDbValue($row['km_encoded_plaintext_type']);
        $this->km_numbers->setDbValue($row['km_numbers']);
        $this->km_content_words->setDbValue($row['km_content_words']);
        $this->km_function_words->setDbValue($row['km_function_words']);
        $this->km_syllables->setDbValue($row['km_syllables']);
        $this->km_morphological_endings->setDbValue($row['km_morphological_endings']);
        $this->km_phrases->setDbValue($row['km_phrases']);
        $this->km_sentences->setDbValue($row['km_sentences']);
        $this->km_punctuation->setDbValue($row['km_punctuation']);
        $this->km_nomenclature_size->setDbValue($row['km_nomenclature_size']);
        $this->km_sections->setDbValue($row['km_sections']);
        $this->km_headings->setDbValue($row['km_headings']);
        $this->km_plaintext_arrangement->setDbValue($row['km_plaintext_arrangement']);
        $this->km_ciphertext_arrangement->setDbValue($row['km_ciphertext_arrangement']);
        $this->km_memorability->setDbValue($row['km_memorability']);
        $this->km_symbol_set->setDbValue($row['km_symbol_set']);
        $this->km_diacritics->setDbValue($row['km_diacritics']);
        $this->km_code_length->setDbValue($row['km_code_length']);
        $this->km_code_type->setDbValue($row['km_code_type']);
        $this->km_metaphors->setDbValue($row['km_metaphors']);
        $this->km_material_properties->setDbValue($row['km_material_properties']);
        $this->km_instructions->setDbValue($row['km_instructions']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['name'] = $this->name->DefaultValue;
        $row['owner_id'] = $this->owner_id->DefaultValue;
        $row['owner'] = $this->owner->DefaultValue;
        $row['record_group_id'] = $this->record_group_id->DefaultValue;
        $row['c_holder'] = $this->c_holder->DefaultValue;
        $row['c_cates'] = $this->c_cates->DefaultValue;
        $row['c_author'] = $this->c_author->DefaultValue;
        $row['c_lang'] = $this->c_lang->DefaultValue;
        $row['current_country'] = $this->current_country->DefaultValue;
        $row['current_city'] = $this->current_city->DefaultValue;
        $row['current_holder'] = $this->current_holder->DefaultValue;
        $row['author'] = $this->author->DefaultValue;
        $row['sender'] = $this->sender->DefaultValue;
        $row['receiver'] = $this->receiver->DefaultValue;
        $row['origin_region'] = $this->origin_region->DefaultValue;
        $row['origin_city'] = $this->origin_city->DefaultValue;
        $row['start_year'] = $this->start_year->DefaultValue;
        $row['start_month'] = $this->start_month->DefaultValue;
        $row['start_day'] = $this->start_day->DefaultValue;
        $row['end_year'] = $this->end_year->DefaultValue;
        $row['end_month'] = $this->end_month->DefaultValue;
        $row['end_day'] = $this->end_day->DefaultValue;
        $row['record_type'] = $this->record_type->DefaultValue;
        $row['status'] = $this->status->DefaultValue;
        $row['symbol_sets'] = $this->symbol_sets->DefaultValue;
        $row['cipher_types'] = $this->cipher_types->DefaultValue;
        $row['cipher_type_other'] = $this->cipher_type_other->DefaultValue;
        $row['symbol_set_other'] = $this->symbol_set_other->DefaultValue;
        $row['number_of_pages'] = $this->number_of_pages->DefaultValue;
        $row['inline_cleartext'] = $this->inline_cleartext->DefaultValue;
        $row['inline_plaintext'] = $this->inline_plaintext->DefaultValue;
        $row['cleartext_lang'] = $this->cleartext_lang->DefaultValue;
        $row['plaintext_lang'] = $this->plaintext_lang->DefaultValue;
        $row['private_ciphertext'] = $this->private_ciphertext->DefaultValue;
        $row['document_types'] = $this->document_types->DefaultValue;
        $row['paper'] = $this->paper->DefaultValue;
        $row['additional_information'] = $this->additional_information->DefaultValue;
        $row['creator_id'] = $this->creator_id->DefaultValue;
        $row['access_mode'] = $this->access_mode->DefaultValue;
        $row['creation_date'] = $this->creation_date->DefaultValue;
        $row['km_encoded_plaintext_type'] = $this->km_encoded_plaintext_type->DefaultValue;
        $row['km_numbers'] = $this->km_numbers->DefaultValue;
        $row['km_content_words'] = $this->km_content_words->DefaultValue;
        $row['km_function_words'] = $this->km_function_words->DefaultValue;
        $row['km_syllables'] = $this->km_syllables->DefaultValue;
        $row['km_morphological_endings'] = $this->km_morphological_endings->DefaultValue;
        $row['km_phrases'] = $this->km_phrases->DefaultValue;
        $row['km_sentences'] = $this->km_sentences->DefaultValue;
        $row['km_punctuation'] = $this->km_punctuation->DefaultValue;
        $row['km_nomenclature_size'] = $this->km_nomenclature_size->DefaultValue;
        $row['km_sections'] = $this->km_sections->DefaultValue;
        $row['km_headings'] = $this->km_headings->DefaultValue;
        $row['km_plaintext_arrangement'] = $this->km_plaintext_arrangement->DefaultValue;
        $row['km_ciphertext_arrangement'] = $this->km_ciphertext_arrangement->DefaultValue;
        $row['km_memorability'] = $this->km_memorability->DefaultValue;
        $row['km_symbol_set'] = $this->km_symbol_set->DefaultValue;
        $row['km_diacritics'] = $this->km_diacritics->DefaultValue;
        $row['km_code_length'] = $this->km_code_length->DefaultValue;
        $row['km_code_type'] = $this->km_code_type->DefaultValue;
        $row['km_metaphors'] = $this->km_metaphors->DefaultValue;
        $row['km_material_properties'] = $this->km_material_properties->DefaultValue;
        $row['km_instructions'] = $this->km_instructions->DefaultValue;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->AddUrl = $this->getAddUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();
        $this->ListUrl = $this->getListUrl();
        $this->setupOtherOptions();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id

        // name

        // owner_id

        // owner

        // record_group_id

        // c_holder

        // c_cates

        // c_author

        // c_lang

        // current_country

        // current_city

        // current_holder

        // author

        // sender

        // receiver

        // origin_region

        // origin_city

        // start_year

        // start_month

        // start_day

        // end_year

        // end_month

        // end_day

        // record_type

        // status

        // symbol_sets

        // cipher_types

        // cipher_type_other

        // symbol_set_other

        // number_of_pages

        // inline_cleartext

        // inline_plaintext

        // cleartext_lang

        // plaintext_lang

        // private_ciphertext

        // document_types

        // paper

        // additional_information

        // creator_id

        // access_mode

        // creation_date

        // km_encoded_plaintext_type

        // km_numbers

        // km_content_words

        // km_function_words

        // km_syllables

        // km_morphological_endings

        // km_phrases

        // km_sentences

        // km_punctuation

        // km_nomenclature_size

        // km_sections

        // km_headings

        // km_plaintext_arrangement

        // km_ciphertext_arrangement

        // km_memorability

        // km_symbol_set

        // km_diacritics

        // km_code_length

        // km_code_type

        // km_metaphors

        // km_material_properties

        // km_instructions

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

            // c_author
            $this->c_author->ViewValue = $this->c_author->CurrentValue;
            $this->c_author->ViewCustomAttributes = $this->c_author->getViewCustomAttributes(); // PHP

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

            // name
            $this->name->HrefValue = "";
            $this->name->TooltipValue = "";

            // owner
            $this->owner->HrefValue = "";
            $this->owner->TooltipValue = "";

            // c_holder
            $this->c_holder->HrefValue = "";
            $this->c_holder->TooltipValue = "";

            // c_cates
            $this->c_cates->HrefValue = "";
            $this->c_cates->TooltipValue = "";

            // c_author
            $this->c_author->HrefValue = "";
            $this->c_author->TooltipValue = "";

            // c_lang
            $this->c_lang->HrefValue = "";
            $this->c_lang->TooltipValue = "";

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

            // symbol_sets
            $this->symbol_sets->HrefValue = "";
            $this->symbol_sets->TooltipValue = "";

            // cipher_types
            $this->cipher_types->HrefValue = "";
            $this->cipher_types->TooltipValue = "";

            // cipher_type_other
            $this->cipher_type_other->HrefValue = "";
            $this->cipher_type_other->TooltipValue = "";

            // symbol_set_other
            $this->symbol_set_other->HrefValue = "";
            $this->symbol_set_other->TooltipValue = "";

            // number_of_pages
            $this->number_of_pages->HrefValue = "";
            $this->number_of_pages->TooltipValue = "";

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

            // private_ciphertext
            $this->private_ciphertext->HrefValue = "";
            $this->private_ciphertext->TooltipValue = "";

            // document_types
            $this->document_types->HrefValue = "";
            $this->document_types->TooltipValue = "";

            // paper
            $this->paper->HrefValue = "";
            $this->paper->TooltipValue = "";

            // access_mode
            $this->access_mode->HrefValue = "";
            $this->access_mode->TooltipValue = "";

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
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Set up detail parms based on QueryString
    protected function setupDetailParms()
    {
        // Get the keys for master table
        $detailTblVar = Get(Config("TABLE_SHOW_DETAIL"));
        if ($detailTblVar !== null) {
            $this->setCurrentDetailTable($detailTblVar);
        } else {
            $detailTblVar = $this->getCurrentDetailTable();
        }
        if ($detailTblVar != "") {
            $detailTblVar = explode(",", $detailTblVar);
            if (in_array("images", $detailTblVar)) {
                $detailPageObj = Container("ImagesGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->record_id->IsDetailKey = true;
                    $detailPageObj->record_id->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->record_id->setSessionValue($detailPageObj->record_id->CurrentValue);
                }
            }
            if (in_array("documents", $detailTblVar)) {
                $detailPageObj = Container("DocumentsGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->record_id->IsDetailKey = true;
                    $detailPageObj->record_id->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->record_id->setSessionValue($detailPageObj->record_id->CurrentValue);
                }
            }
            if (in_array("associated_records", $detailTblVar)) {
                $detailPageObj = Container("AssociatedRecordsGrid");
                if ($detailPageObj->DetailView) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    $detailPageObj->CurrentMode = "view";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->record_id->IsDetailKey = true;
                    $detailPageObj->record_id->CurrentValue = $this->id->CurrentValue;
                    $detailPageObj->record_id->setSessionValue($detailPageObj->record_id->CurrentValue);
                }
            }
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("RecordsList");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("RecordsList"), "", $this->TableVar, true);
        $pageId = "view";
        $Breadcrumb->add("view", $pageId, $url);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = false;
        $recordNo = $pageNo ?? $startRec; // Record number = page number or start record
        if ($recordNo !== null && is_numeric($recordNo)) {
            $this->StartRecord = $recordNo;
        } else {
            $this->StartRecord = $this->getStartRecordNumber();
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount() {
        return ceil($this->TotalRecords / $this->DisplayRecords);
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
        $id = CurrentPage()->RecKey['id'];
        echo '<div class="navhead">';
        echo '<a href="/decrypt-web/RecordsEdit/' . $id . '">Edit Record</a><br/>'."\n";
        echo '<a href="/decrypt-web/ImagesList?showmaster=records&fk_id=' . $id . '">Manage Images</a>&nbsp;|'."\n";
        echo '<a href="/decrypt-web/DocumentsList?showmaster=records&fk_id=' . $id . '">Manage Documents</a>&nbsp;|'."\n";
        echo '<a href="/decrypt-web/AssociatedRecordsList?showmaster=records&fk_id=' . $id . '">Associated Records</a><br/>'."\n";
        echo '<a href="/decrypt-web/RecordsDelete/' . $id . '">Delete Record</a>'."\n";
        echo '</div>';
        echo "<div class='sumbox'>"."\n";
        echo "<div class='detbox'>"."\n";
        echo "<span class='recinf'>Record Information</span>";
        echo "<br/>";
        echo "<b>ID:</b>" . CurrentPage()->RecKey['id'];
        echo "<br/>";
        echo "<b>Name:</b>" . CurrentPage()->name->CurrentValue;
        echo "</div>"."\n";
        echo "<div class='detbox'>"."\n";
        echo "<span class='recinf'>Current Location</span>";
        echo "<br/>";
        echo "<b>Country:</b>" . CurrentPage()->current_country->CurrentValue;
        echo "<br/>";
        echo "<b>City:</b>" . CurrentPage()->current_city->CurrentValue;
        echo "<br/>";
        echo "<b>City:</b>" . CurrentPage()->current_holder->CurrentValue;
        echo "</div>"."\n";
        echo "<div class='detbox'>"."\n";
        echo "<span class='recinf'>Origin</span>";
        echo "<br/>";
        echo "<b>Dates:</b>" . CurrentPage()->start_date->ViewValue;
        echo " - " . CurrentPage()->end_date->ViewValue;
        echo "<br/>";
        echo "<b>Author:</b>" . CurrentPage()->author->CurrentValue;
        echo "<br/>";
        echo "<b>Sender:</b>" . CurrentPage()->sender->CurrentValue;
        echo "<br/>";
        echo "<b>Receiver:</b>" . CurrentPage()->receiver->CurrentValue;
        echo "<br/>";
        echo "<b>Region:</b>" . CurrentPage()->origin_region->CurrentValue;
        echo "<br/>";
        echo "<b>City:</b>" . CurrentPage()->origin_city->CurrentValue;
        echo "</div>"."\n";
        echo "<div class='detbox'>"."\n";
        echo "<span class='recinf'>Content</span>";
        echo "<br/>";
        echo "<b>Type:</b>" . CurrentPage()->record_type->ViewValue;
        echo "<br/>";
        echo "<b>Status:</b>" . CurrentPage()->status->ViewValue;
        echo "<br/>";
        echo "<b>Cipher Type:</b>" . CurrentPage()->cipher_types->ViewValue;
        echo "<br/>";
        echo "<b>Symbol Sets:</b>" . CurrentPage()->symbol_sets->ViewValue;
        echo "<br/>";
        echo "<b>Pages:</b>" . CurrentPage()->number_of_pages->CurrentValue;
        echo "</div>"."\n";
        echo "<div class='detbox'>"."\n";
        echo "<span class='recinf'>Creation</span>";
        echo "<br/>";
        echo "<b>Owner:</b>" . CurrentPage()->owner_id->ViewValue;
        echo "<br/>";
        echo "<b>Creator:</b>" . CurrentPage()->creator_id->ViewValue;
        echo "<br/>";
        echo "<b>Creation Date:</b>" . CurrentPage()->creation_date->CurrentValue;
        echo "<br/>";
        echo "<b>Access mode:</b>" . CurrentPage()->access_mode->ViewValue;
        echo "</div>"."\n";
        echo "</div>"."\n";
    ?>
    <script>
    // Open the Modal
    function openModal() {
      document.getElementById("myModal").style.display = "block";
    }
    // Close the Modal
    function closeModal() {
      document.getElementById("myModal").style.display = "none";
    }
    // Next/previous controls
    function plusSlides(n) {
      showSlides(slideIndex += n);
    }
    // Thumbnail image controls
    function currentSlide(n) {
      showSlides(slideIndex = n);
    }

    function showSlides(n) {
      var i;
      var slides = document.getElementsByClassName("mySlides");
      var dots = document.getElementsByClassName("demo");
      var captionText = document.getElementById("caption");
      if (n > slides.length) {slideIndex = 1}
      if (n < 1) {slideIndex = slides.length}
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
      }
      // here
      var oldsrc = slides[slideIndex-1].getElementsByTagName("img")[0].src;
      slides[slideIndex-1].getElementsByTagName("img")[0].src = oldsrc.replace('file=TH_','file=');
      slides[slideIndex-1].style.display = "block";
      dots[slideIndex-1].className += " active";
      captionText.innerHTML = dots[slideIndex-1].alt;
    }
    </script>
    <?php
        $imgs = ExecuteRows("SELECT id,path FROM images WHERE record_id = ". $id ." AND `path` NOT LIKE '%pdf'");
        echo "<div class='imgbox'>";
        $c = count($imgs);
        foreach($imgs as $i => $img) {
        	echo "<div class='imgth'>"."\n";
        	if($i > 10) {
        		echo "(There are <b>" . ($c - $i - 1) . "</b> more images.) </div>";
        		break;
        	}
        	echo "<img class='hover-shadow' onclick='openModal();currentSlide(";
        	echo $i+1  . ")' src='/decrypt-custom/filesrv/?file=TH_" . $img["path"] . "'/>"."\n";
        	echo "</div>"."\n";
        }
        echo "</div>"."\n";
        echo '<div id="myModal" class="modal">'."\n";
        echo '    <span class="close cursor" onclick="closeModal()">&times;</span>'."\n";
        echo '	  <div class="manage-img-box"><a class="manage-img" href="/decrypt-web/ImagesList?showmaster=records&fk_id=' .
        $id . '">Go to the <b>Image Manager</b> to zoom and view/edit metadata</a></div>' . "\n";
        echo '    <div class="modal-content">'."\n";
        foreach($imgs as $i => $img) {
        	echo '        <div class="mySlides">'."\n";
        	echo '            <div class="numbertext">' . ($i+1) .'/'. $c .'</div>'."\n";
        	echo '            <img src="/decrypt-custom/filesrv/?file=TH_' . $img["path"] . '" style="width:100%">'."\n";
        	echo '        </div>'."\n";
        }
        echo '        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>'."\n";
        echo '        <a class="next" onclick="plusSlides(1)">&#10095;</a>'."\n";
        echo '        <div class="caption-container">'."\n";
        echo '            <p id="caption"></p>'."\n";
        echo '        </div>'."\n";
        echo '		  <div class="imgnavbox">'."\n";
        foreach($imgs as $i => $img) {
            echo '          <div class="column">'."\n";
            echo '              <img class="demo" src="/decrypt-custom/filesrv/?file=TH_'. $img["path"] . '"';
            echo ' onclick="currentSlide('. ($i+1) . ')" alt="' . $img["path"] . '"/>'."\n";
            echo '          </div>'."\n";
        }
        echo '        </div>'."\n";
        echo '    </div>'."\n";
        echo '</div>'."\n";
        echo '<hr/>'."\n";
        $pdfs = ExecuteRows("SELECT id,path FROM images WHERE record_id = ". $id ." AND `path` LIKE '%pdf'");
        echo "<div class='imgbox'>";
        foreach($pdfs as $pdf) {
        	echo "<div class='imgth'>"."\n";
        	echo "<a href='/decrypt-custom/filesrv/?file=" . $pdf["path"] . "'>"."\n";
            $newname = "";
            $pos = strrpos($pdf["path"],'pdf');
            if ($pos !== false) {
                $newname = substr_replace($pdf["path"],'png',$pos,strlen('pdf'));
            }
        	echo "<img src='/decrypt-custom/filesrv/?file=TH_" . $newname ."'>"."\n";
        	echo "<p>" . $pdf['name'] . "</p>";
        	echo "</a>\n";
        	echo "</div>"."\n";
        }
        echo "</div>"."\n";
        $docs = ExecuteRows("SELECT id,title,category,path FROM documents WHERE record_id = ".$id);
        echo "<div class='imgbox'>";
        foreach($docs as $doc) {
        	echo "<div class='imgth'>"."\n";
        	echo "<a href='/decrypt-custom/filesrv/?file=" . $doc["path"] . "'>"."\n";
        	echo "<img src='/decrypt-custom/file.png'/>"."\n";
        	echo "<p>" . $doc['title'] . " [". $doc['category'] ."]</p>";
        	echo "</a>\n";
        	echo "</div>"."\n";
        }
        echo "</div>"."\n";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
    	$creator = ExecuteScalar("SELECT username FROM users WHERE id =" . CurrentPage()->creator_id->CurrentValue );
    	echo "<div id='additional_information'>\n";
    	echo "<p><b>Additional Information</b><br/>\n";
    	echo CurrentPage()->additional_information->CurrentValue . "<br/>";
    	echo "<b>Created by:</b>". $creator . "<br/>";
    	echo "</p>\n";
    	echo "</div>\n";
        ?>
        <script>
        document.getElementById('ew-page-spinner').style.display = "none";
        document.getElementsByClassName('btn-toolbar').style.display = "none";
        var slideIndex = 1;
        showSlides(slideIndex);
        </script>
        <?php
    }

    // Page Breaking event
    public function pageBreaking(&$break, &$content)
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }

    // Page Exporting event
    // $doc = export object
    public function pageExporting(&$doc)
    {
        //$doc->Text = "my header"; // Export header
        //return false; // Return false to skip default export and use Row_Export event
        return true; // Return true to use default export and skip Row_Export event
    }

    // Row Export event
    // $doc = export document object
    public function rowExport($doc, $rs)
    {
        //$doc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
    }

    // Page Exported event
    // $doc = export document object
    public function pageExported($doc)
    {
        //$doc->Text .= "my footer"; // Export footer
        //Log($doc->Text);
    }
}
