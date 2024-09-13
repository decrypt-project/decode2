<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class RecordsList extends Records
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "RecordsList";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "frecordslist";
    public $FormActionName = "";
    public $FormBlankRowName = "";
    public $FormKeyCountName = "";

    // CSS class/style
    public $CurrentPageName = "RecordsList";

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
        $this->name->Visible = false;
        $this->owner_id->Visible = false;
        $this->owner->Visible = false;
        $this->record_group_id->Visible = false;
        $this->c_holder->setVisibility();
        $this->c_cates->setVisibility();
        $this->c_author->setVisibility();
        $this->c_lang->setVisibility();
        $this->current_country->Visible = false;
        $this->current_city->Visible = false;
        $this->current_holder->Visible = false;
        $this->author->Visible = false;
        $this->sender->Visible = false;
        $this->receiver->Visible = false;
        $this->origin_region->Visible = false;
        $this->origin_city->Visible = false;
        $this->start_year->Visible = false;
        $this->start_month->Visible = false;
        $this->start_day->Visible = false;
        $this->end_year->Visible = false;
        $this->end_month->Visible = false;
        $this->end_day->Visible = false;
        $this->record_type->setVisibility();
        $this->status->setVisibility();
        $this->symbol_sets->Visible = false;
        $this->cipher_types->Visible = false;
        $this->cipher_type_other->Visible = false;
        $this->symbol_set_other->Visible = false;
        $this->number_of_pages->setVisibility();
        $this->inline_cleartext->Visible = false;
        $this->inline_plaintext->Visible = false;
        $this->cleartext_lang->Visible = false;
        $this->plaintext_lang->Visible = false;
        $this->private_ciphertext->Visible = false;
        $this->document_types->Visible = false;
        $this->paper->Visible = false;
        $this->additional_information->Visible = false;
        $this->creator_id->Visible = false;
        $this->access_mode->Visible = false;
        $this->creation_date->Visible = false;
        $this->km_encoded_plaintext_type->Visible = false;
        $this->km_numbers->Visible = false;
        $this->km_content_words->Visible = false;
        $this->km_function_words->Visible = false;
        $this->km_syllables->Visible = false;
        $this->km_morphological_endings->Visible = false;
        $this->km_phrases->Visible = false;
        $this->km_sentences->Visible = false;
        $this->km_punctuation->Visible = false;
        $this->km_nomenclature_size->Visible = false;
        $this->km_sections->Visible = false;
        $this->km_headings->Visible = false;
        $this->km_plaintext_arrangement->Visible = false;
        $this->km_ciphertext_arrangement->Visible = false;
        $this->km_memorability->Visible = false;
        $this->km_symbol_set->Visible = false;
        $this->km_diacritics->Visible = false;
        $this->km_code_length->Visible = false;
        $this->km_code_type->Visible = false;
        $this->km_metaphors->Visible = false;
        $this->km_material_properties->Visible = false;
        $this->km_instructions->Visible = false;
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->FormActionName = Config("FORM_ROW_ACTION_NAME");
        $this->FormBlankRowName = Config("FORM_BLANK_ROW_NAME");
        $this->FormKeyCountName = Config("FORM_KEY_COUNT_NAME");
        $this->TableVar = 'records';
        $this->TableName = 'records';

        // Table CSS class
        $this->TableClass = "table table-bordered table-hover table-sm ew-table";

        // CSS class name as context
        $this->ContextClass = CheckClassName($this->TableVar);
        AppendClass($this->TableGridClass, $this->ContextClass);

        // Fixed header table
        if (!$this->UseCustomTemplate) {
            $this->setFixedHeaderTable(Config("USE_FIXED_HEADER_TABLE"), Config("FIXED_HEADER_TABLE_HEIGHT"));
        }

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (records)
        if (!isset($GLOBALS["records"]) || get_class($GLOBALS["records"]) == PROJECT_NAMESPACE . "records") {
            $GLOBALS["records"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs
        $this->AddUrl = "RecordsAdd?" . Config("TABLE_SHOW_DETAIL") . "=";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiEditUrl = $pageUrl . "action=multiedit";
        $this->MultiDeleteUrl = "RecordsDelete";
        $this->MultiUpdateUrl = "RecordsUpdate";

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

        // List options
        $this->ListOptions = new ListOptions(["Tag" => "td", "TableVar" => $this->TableVar]);

        // Export options
        $this->ExportOptions = new ListOptions(["TagClassName" => "ew-export-option"]);

        // Import options
        $this->ImportOptions = new ListOptions(["TagClassName" => "ew-import-option"]);

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }

        // Grid-Add/Edit
        $this->OtherOptions["addedit"] = new ListOptions([
            "TagClassName" => "ew-add-edit-option",
            "UseDropDownButton" => false,
            "DropDownButtonPhrase" => $Language->phrase("ButtonAddEdit"),
            "UseButtonGroup" => true
        ]);

        // Detail tables
        $this->OtherOptions["detail"] = new ListOptions(["TagClassName" => "ew-detail-option"]);
        // Actions
        $this->OtherOptions["action"] = new ListOptions(["TagClassName" => "ew-action-option"]);

        // Column visibility
        $this->OtherOptions["column"] = new ListOptions([
            "TableVar" => $this->TableVar,
            "TagClassName" => "ew-column-option",
            "ButtonGroupClass" => "ew-column-dropdown",
            "UseDropDownButton" => true,
            "DropDownButtonPhrase" => $Language->phrase("Columns"),
            "DropDownAutoClose" => "outside",
            "UseButtonGroup" => false
        ]);

        // Filter options
        $this->FilterOptions = new ListOptions(["TagClassName" => "ew-filter-option"]);

        // List actions
        $this->ListActions = new ListActions();
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
                        if ($fld->DataType == DATATYPE_MEMO && $fld->MemoMaxLength > 0) {
                            $val = TruncateMemo($val, $fld->MemoMaxLength, $fld->TruncateMemoRemoveHtml);
                        }
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

    // Class variables
    public $ListOptions; // List options
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $OtherOptions; // Other options
    public $FilterOptions; // Filter options
    public $ImportOptions; // Import options
    public $ListActions; // List actions
    public $SelectedCount = 0;
    public $SelectedIndex = 0;
    public $DisplayRecords = 20;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = "10,20,50,-1"; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = ""; // Search WHERE clause
    public $SearchPanelClass = "ew-search-panel collapse show"; // Search Panel class
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $RecordCount = 0; // Record count
    public $InlineRowCount = 0;
    public $StartRowCount = 1;
    public $RowCount = 0;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $MultiColumnGridClass = "row-cols-md";
    public $MultiColumnEditClass = "col-12 w-100";
    public $MultiColumnCardClass = "card h-100 ew-card";
    public $MultiColumnListOptionsPosition = "bottom-start";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $MasterRecordExists;
    public $MultiSelectKey;
    public $Command;
    public $UserAction; // User action
    public $RestoreSearch = false;
    public $HashValue; // Hash value
    public $DetailPages;
    public $TopContentClass = "ew-top";
    public $MiddleContentClass = "ew-middle";
    public $BottomContentClass = "ew-bottom";
    public $PageAction;
    public $RecKeys = [];
    public $IsModal = false;
    protected $FilterForModalActions = "";
    private $UseInfiniteScroll = false;

    /**
     * Load recordset from filter
     *
     * @return void
     */
    public function loadRecordsetFromFilter($filter)
    {
        // Set up list options
        $this->setupListOptions();

        // Search options
        $this->setupSearchOptions();

        // Other options
        $this->setupOtherOptions();

        // Set visibility
        $this->setVisibility();

        // Load recordset
        $this->TotalRecords = $this->loadRecordCount($filter);
        $this->StartRecord = 1;
        $this->StopRecord = $this->DisplayRecords;
        $this->CurrentFilter = $filter;
        $this->Recordset = $this->loadRecordset();

        // Set up pager
        $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);
    }

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $UserProfile, $Language, $Security, $CurrentForm, $DashboardReport;

        // Multi column button position
        $this->MultiColumnListOptionsPosition = Config("MULTI_COLUMN_LIST_OPTIONS_POSITION");
        $DashboardReport = $DashboardReport || ConvertToBool(Param(Config("PAGE_DASHBOARD"), false));

        // Is modal
        $this->IsModal = ConvertToBool(Param("modal"));

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));

        // Get export parameters
        $custom = "";
        if (Param("export") !== null) {
            $this->Export = Param("export");
            $custom = Param("custom", "");
        } else {
            $this->setExportReturnUrl(CurrentUrl());
        }
        $ExportType = $this->Export; // Get export parameter, used in header
        if ($ExportType != "") {
            global $SkipHeaderFooter;
            $SkipHeaderFooter = true;
        }
        $this->CurrentAction = Param("action"); // Set up current action

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();

        // Setup import options
        $this->setupImportOptions();
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

        // Setup other options
        $this->setupOtherOptions();

        // Set up custom action (compatible with old version)
        foreach ($this->CustomActions as $name => $action) {
            $this->ListActions->add($name, $action);
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

        // Update form name to avoid conflict
        if ($this->IsModal) {
            $this->FormName = "frecordsgrid";
        }

        // Set up page action
        $this->PageAction = CurrentPageUrl(false);

        // Set up infinite scroll
        $this->UseInfiniteScroll = ConvertToBool(Param("infinitescroll"));

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $filter = ""; // Filter
        $query = ""; // Query builder

        // Get command
        $this->Command = strtolower(Get("cmd", ""));

        // Process list action first
        if ($this->processListAction()) { // Ajax request
            $this->terminate();
            return;
        }

        // Set up records per page
        $this->setupDisplayRecords();

        // Handle reset command
        $this->resetCmd();

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

        // Process import
        if ($this->isImport()) {
            $this->import(Param(Config("API_FILE_TOKEN_NAME")), ConvertToBool(Param("rollback")));
            $this->terminate();
            return;
        }

        // Hide list options
        if ($this->isExport()) {
            $this->ListOptions->hideAllOptions(["sequence"]);
            $this->ListOptions->UseDropDownButton = false; // Disable drop down button
            $this->ListOptions->UseButtonGroup = false; // Disable button group
        } elseif ($this->isGridAdd() || $this->isGridEdit() || $this->isMultiEdit() || $this->isConfirm()) {
            $this->ListOptions->hideAllOptions();
            $this->ListOptions->UseDropDownButton = false; // Disable drop down button
            $this->ListOptions->UseButtonGroup = false; // Disable button group
        }

        // Hide options
        if ($this->isExport() || !(EmptyValue($this->CurrentAction) || $this->isSearch())) {
            $this->ExportOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
            $this->ImportOptions->hideAllOptions();
        }

        // Hide other options
        if ($this->isExport()) {
            $this->OtherOptions->hideAllOptions();
        }

        // Get default search criteria
        AddFilter($this->DefaultSearchWhere, $this->basicSearchWhere(true));
        AddFilter($this->DefaultSearchWhere, $this->advancedSearchWhere(true));

        // Get basic search values
        $this->loadBasicSearchValues();

        // Get and validate search values for advanced search
        if (EmptyValue($this->UserAction)) { // Skip if user action
            $this->loadSearchValues();
        }

        // Process filter list
        if ($this->processFilterList()) {
            $this->terminate();
            return;
        }
        if ($CurrentForm) {
            $CurrentForm->resetIndex();
        }
        if (!$this->validateSearch()) {
            // Nothing to do
        }

        // Restore search parms from Session if not searching / reset / export
        if (($this->isExport() || $this->Command != "search" && $this->Command != "reset" && $this->Command != "resetall") && $this->Command != "json" && $this->checkSearchParms()) {
            $this->restoreSearchParms();
        }

        // Call Recordset SearchValidated event
        $this->recordsetSearchValidated();

        // Set up sorting order
        $this->setupSortOrder();

        // Get basic search criteria
        if (!$this->hasInvalidFields()) {
            $srchBasic = $this->basicSearchWhere();
        }

        // Get advanced search criteria
        if (!$this->hasInvalidFields()) {
            $srchAdvanced = $this->advancedSearchWhere();
        }

        // Get query builder criteria
        $query = $this->queryBuilderWhere();

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 20; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Load search default if no existing search criteria
        if (!$this->checkSearchParms() && !$query) {
            // Load basic search from default
            $this->BasicSearch->loadDefault();
            if ($this->BasicSearch->Keyword != "") {
                $srchBasic = $this->basicSearchWhere(); // Save to session
            }

            // Load advanced search from default
            if ($this->loadAdvancedSearchDefault()) {
                $srchAdvanced = $this->advancedSearchWhere(); // Save to session
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Build search criteria
        if ($query) {
            AddFilter($this->SearchWhere, $query);
        } else {
            AddFilter($this->SearchWhere, $srchAdvanced);
            AddFilter($this->SearchWhere, $srchBasic);
        }

        // Call Recordset_Searching event
        $this->recordsetSearching($this->SearchWhere);

        // Save search criteria
        if ($this->Command == "search" && !$this->RestoreSearch) {
            $this->setSearchWhere($this->SearchWhere); // Save to Session
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->Command != "json" && !$query) {
            $this->SearchWhere = $this->getSearchWhere();
        }

        // Build filter
        $filter = "";
        if (!$Security->canList()) {
            $filter = "(0=1)"; // Filter all records
        }
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $filter;
        } else {
            $this->setSessionWhere($filter);
            $this->CurrentFilter = "";
        }
        $this->Filter = $this->applyUserIDFilters($filter);
        if ($this->isGridAdd()) {
            $this->CurrentFilter = "0=1";
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->GridAddRowCount;
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } elseif (($this->isEdit() || $this->isCopy() || $this->isInlineInserted() || $this->isInlineUpdated()) && $this->UseInfiniteScroll) { // Get current record only
            $this->CurrentFilter = $this->isInlineUpdated() ? $this->getRecordFilter() : $this->getFilterFromRecordKeys();
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->StopRecord = $this->DisplayRecords;
            $this->Recordset = $this->loadRecordset();
        } elseif (
            $this->UseInfiniteScroll && $this->isGridInserted() ||
            $this->UseInfiniteScroll && ($this->isGridEdit() || $this->isGridUpdated()) ||
            $this->isMultiEdit() ||
            $this->UseInfiniteScroll && $this->isMultiUpdated()
        ) { // Get current records only
            $this->CurrentFilter = $this->FilterForModalActions; // Restore filter
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->StopRecord = $this->DisplayRecords;
            $this->Recordset = $this->loadRecordset();
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            if ($this->DisplayRecords <= 0 || ($this->isExport() && $this->ExportAll)) { // Display all records
                $this->DisplayRecords = $this->TotalRecords;
            }
            if (!($this->isExport() && $this->ExportAll)) { // Set up start record position
                $this->setupStartRecord();
            }
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);

            // Set no record found message
            if ((EmptyValue($this->CurrentAction) || $this->isSearch()) && $this->TotalRecords == 0) {
                if (!$Security->canList()) {
                    $this->setWarningMessage(DeniedMessage());
                }
                if ($this->SearchWhere == "0=101") {
                    $this->setWarningMessage($Language->phrase("EnterSearchCriteria"));
                } else {
                    $this->setWarningMessage($Language->phrase("NoRecord"));
                }
            }
        }

        // Set up list action columns
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Allow) {
                if ($listaction->Select == ACTION_MULTIPLE) { // Show checkbox column if multiple action
                    $this->ListOptions["checkbox"]->Visible = true;
                } elseif ($listaction->Select == ACTION_SINGLE) { // Show list action column
                        $this->ListOptions["listactions"]->Visible = true; // Set visible if any list action is allowed
                }
            }
        }

        // Search options
        $this->setupSearchOptions();

        // Set up search panel class
        if ($this->SearchWhere != "") {
            if ($query) { // Hide search panel if using QueryBuilder
                RemoveClass($this->SearchPanelClass, "show");
            } else {
                AppendClass($this->SearchPanelClass, "show");
            }
        }

        // API list action
        if (IsApi()) {
            if (Route(0) == Config("API_LIST_ACTION")) {
                if (!$this->isExport()) {
                    $rows = $this->getRecordsFromRecordset($this->Recordset);
                    $this->Recordset->close();
                    WriteJson([
                        "success" => true,
                        "action" => Config("API_LIST_ACTION"),
                        $this->TableVar => $rows,
                        "totalRecordCount" => $this->TotalRecords
                    ]);
                    $this->terminate(true);
                }
                return;
            } elseif ($this->getFailureMessage() != "") {
                WriteJson(["error" => $this->getFailureMessage()]);
                $this->clearFailureMessage();
                $this->terminate(true);
                return;
            }
        }

        // Render other options
        $this->renderOtherOptions();

        // Set up pager
        $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

        // Set ReturnUrl in header if necessary
        if ($returnUrl = Container("flash")->getFirstMessage("Return-Url")) {
            AddHeader("Return-Url", GetUrl($returnUrl));
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

    // Get page number
    public function getPageNumber()
    {
        return ($this->DisplayRecords > 0 && $this->StartRecord > 0) ? ceil($this->StartRecord / $this->DisplayRecords) : 1;
    }

    // Set up number of records displayed per page
    protected function setupDisplayRecords()
    {
        $wrk = Get(Config("TABLE_REC_PER_PAGE"), "");
        if ($wrk != "") {
            if (is_numeric($wrk)) {
                $this->DisplayRecords = (int)$wrk;
            } else {
                if (SameText($wrk, "all")) { // Display all records
                    $this->DisplayRecords = -1;
                } else {
                    $this->DisplayRecords = 20; // Non-numeric, load default
                }
            }
            $this->setRecordsPerPage($this->DisplayRecords); // Save to Session
            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Build filter for all keys
    protected function buildKeyFilter()
    {
        global $CurrentForm;
        $wrkFilter = "";

        // Update row index and get row key
        $rowindex = 1;
        $CurrentForm->Index = $rowindex;
        $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        while ($thisKey != "") {
            $this->setKey($thisKey);
            if ($this->OldKey != "") {
                $filter = $this->getRecordFilter();
                if ($wrkFilter != "") {
                    $wrkFilter .= " OR ";
                }
                $wrkFilter .= $filter;
            } else {
                $wrkFilter = "0=1";
                break;
            }

            // Update row index and get row key
            $rowindex++; // Next row
            $CurrentForm->Index = $rowindex;
            $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        }
        return $wrkFilter;
    }

    // Get list of filters
    public function getFilterList()
    {
        global $UserProfile;

        // Initialize
        $filterList = "";
        $savedFilterList = "";
        $filterList = Concat($filterList, $this->id->AdvancedSearch->toJson(), ","); // Field id
        $filterList = Concat($filterList, $this->owner->AdvancedSearch->toJson(), ","); // Field owner
        $filterList = Concat($filterList, $this->c_holder->AdvancedSearch->toJson(), ","); // Field c_holder
        $filterList = Concat($filterList, $this->current_country->AdvancedSearch->toJson(), ","); // Field current_country
        $filterList = Concat($filterList, $this->author->AdvancedSearch->toJson(), ","); // Field author
        $filterList = Concat($filterList, $this->sender->AdvancedSearch->toJson(), ","); // Field sender
        $filterList = Concat($filterList, $this->receiver->AdvancedSearch->toJson(), ","); // Field receiver
        $filterList = Concat($filterList, $this->origin_region->AdvancedSearch->toJson(), ","); // Field origin_region
        $filterList = Concat($filterList, $this->origin_city->AdvancedSearch->toJson(), ","); // Field origin_city
        $filterList = Concat($filterList, $this->start_year->AdvancedSearch->toJson(), ","); // Field start_year
        $filterList = Concat($filterList, $this->start_month->AdvancedSearch->toJson(), ","); // Field start_month
        $filterList = Concat($filterList, $this->start_day->AdvancedSearch->toJson(), ","); // Field start_day
        $filterList = Concat($filterList, $this->end_year->AdvancedSearch->toJson(), ","); // Field end_year
        $filterList = Concat($filterList, $this->end_month->AdvancedSearch->toJson(), ","); // Field end_month
        $filterList = Concat($filterList, $this->end_day->AdvancedSearch->toJson(), ","); // Field end_day
        $filterList = Concat($filterList, $this->record_type->AdvancedSearch->toJson(), ","); // Field record_type
        $filterList = Concat($filterList, $this->status->AdvancedSearch->toJson(), ","); // Field status
        $filterList = Concat($filterList, $this->cipher_type_other->AdvancedSearch->toJson(), ","); // Field cipher_type_other
        $filterList = Concat($filterList, $this->symbol_set_other->AdvancedSearch->toJson(), ","); // Field symbol_set_other
        $filterList = Concat($filterList, $this->inline_cleartext->AdvancedSearch->toJson(), ","); // Field inline_cleartext
        $filterList = Concat($filterList, $this->inline_plaintext->AdvancedSearch->toJson(), ","); // Field inline_plaintext
        $filterList = Concat($filterList, $this->cleartext_lang->AdvancedSearch->toJson(), ","); // Field cleartext_lang
        $filterList = Concat($filterList, $this->plaintext_lang->AdvancedSearch->toJson(), ","); // Field plaintext_lang
        $filterList = Concat($filterList, $this->document_types->AdvancedSearch->toJson(), ","); // Field document_types
        $filterList = Concat($filterList, $this->paper->AdvancedSearch->toJson(), ","); // Field paper
        $filterList = Concat($filterList, $this->additional_information->AdvancedSearch->toJson(), ","); // Field additional_information
        $filterList = Concat($filterList, $this->creator_id->AdvancedSearch->toJson(), ","); // Field creator_id
        $filterList = Concat($filterList, $this->creation_date->AdvancedSearch->toJson(), ","); // Field creation_date
        $filterList = Concat($filterList, $this->km_encoded_plaintext_type->AdvancedSearch->toJson(), ","); // Field km_encoded_plaintext_type
        $filterList = Concat($filterList, $this->km_numbers->AdvancedSearch->toJson(), ","); // Field km_numbers
        $filterList = Concat($filterList, $this->km_content_words->AdvancedSearch->toJson(), ","); // Field km_content_words
        $filterList = Concat($filterList, $this->km_function_words->AdvancedSearch->toJson(), ","); // Field km_function_words
        $filterList = Concat($filterList, $this->km_syllables->AdvancedSearch->toJson(), ","); // Field km_syllables
        $filterList = Concat($filterList, $this->km_morphological_endings->AdvancedSearch->toJson(), ","); // Field km_morphological_endings
        $filterList = Concat($filterList, $this->km_phrases->AdvancedSearch->toJson(), ","); // Field km_phrases
        $filterList = Concat($filterList, $this->km_sentences->AdvancedSearch->toJson(), ","); // Field km_sentences
        $filterList = Concat($filterList, $this->km_punctuation->AdvancedSearch->toJson(), ","); // Field km_punctuation
        $filterList = Concat($filterList, $this->km_nomenclature_size->AdvancedSearch->toJson(), ","); // Field km_nomenclature_size
        $filterList = Concat($filterList, $this->km_sections->AdvancedSearch->toJson(), ","); // Field km_sections
        $filterList = Concat($filterList, $this->km_headings->AdvancedSearch->toJson(), ","); // Field km_headings
        $filterList = Concat($filterList, $this->km_plaintext_arrangement->AdvancedSearch->toJson(), ","); // Field km_plaintext_arrangement
        $filterList = Concat($filterList, $this->km_ciphertext_arrangement->AdvancedSearch->toJson(), ","); // Field km_ciphertext_arrangement
        $filterList = Concat($filterList, $this->km_memorability->AdvancedSearch->toJson(), ","); // Field km_memorability
        $filterList = Concat($filterList, $this->km_symbol_set->AdvancedSearch->toJson(), ","); // Field km_symbol_set
        $filterList = Concat($filterList, $this->km_diacritics->AdvancedSearch->toJson(), ","); // Field km_diacritics
        $filterList = Concat($filterList, $this->km_code_length->AdvancedSearch->toJson(), ","); // Field km_code_length
        $filterList = Concat($filterList, $this->km_code_type->AdvancedSearch->toJson(), ","); // Field km_code_type
        $filterList = Concat($filterList, $this->km_metaphors->AdvancedSearch->toJson(), ","); // Field km_metaphors
        $filterList = Concat($filterList, $this->km_material_properties->AdvancedSearch->toJson(), ","); // Field km_material_properties
        $filterList = Concat($filterList, $this->km_instructions->AdvancedSearch->toJson(), ","); // Field km_instructions
        if ($this->BasicSearch->Keyword != "") {
            $wrk = "\"" . Config("TABLE_BASIC_SEARCH") . "\":\"" . JsEncode($this->BasicSearch->Keyword) . "\",\"" . Config("TABLE_BASIC_SEARCH_TYPE") . "\":\"" . JsEncode($this->BasicSearch->Type) . "\"";
            $filterList = Concat($filterList, $wrk, ",");
        }

        // Return filter list in JSON
        if ($filterList != "") {
            $filterList = "\"data\":{" . $filterList . "}";
        }
        if ($savedFilterList != "") {
            $filterList = Concat($filterList, "\"filters\":" . $savedFilterList, ",");
        }
        return ($filterList != "") ? "{" . $filterList . "}" : "null";
    }

    // Process filter list
    protected function processFilterList()
    {
        global $UserProfile;
        if (Post("ajax") == "savefilters") { // Save filter request (Ajax)
            $filters = Post("filters");
            $UserProfile->setSearchFilters(CurrentUserName(), "frecordssrch", $filters);
            WriteJson([["success" => true]]); // Success
            return true;
        } elseif (Post("cmd") == "resetfilter") {
            $this->restoreFilterList();
        }
        return false;
    }

    // Restore list of filters
    protected function restoreFilterList()
    {
        // Return if not reset filter
        if (Post("cmd") !== "resetfilter") {
            return false;
        }
        $filter = json_decode(Post("filter"), true);
        $this->Command = "search";

        // Field id
        $this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
        $this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
        $this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
        $this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
        $this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
        $this->id->AdvancedSearch->save();

        // Field owner
        $this->owner->AdvancedSearch->SearchValue = @$filter["x_owner"];
        $this->owner->AdvancedSearch->SearchOperator = @$filter["z_owner"];
        $this->owner->AdvancedSearch->SearchCondition = @$filter["v_owner"];
        $this->owner->AdvancedSearch->SearchValue2 = @$filter["y_owner"];
        $this->owner->AdvancedSearch->SearchOperator2 = @$filter["w_owner"];
        $this->owner->AdvancedSearch->save();

        // Field c_holder
        $this->c_holder->AdvancedSearch->SearchValue = @$filter["x_c_holder"];
        $this->c_holder->AdvancedSearch->SearchOperator = @$filter["z_c_holder"];
        $this->c_holder->AdvancedSearch->SearchCondition = @$filter["v_c_holder"];
        $this->c_holder->AdvancedSearch->SearchValue2 = @$filter["y_c_holder"];
        $this->c_holder->AdvancedSearch->SearchOperator2 = @$filter["w_c_holder"];
        $this->c_holder->AdvancedSearch->save();

        // Field current_country
        $this->current_country->AdvancedSearch->SearchValue = @$filter["x_current_country"];
        $this->current_country->AdvancedSearch->SearchOperator = @$filter["z_current_country"];
        $this->current_country->AdvancedSearch->SearchCondition = @$filter["v_current_country"];
        $this->current_country->AdvancedSearch->SearchValue2 = @$filter["y_current_country"];
        $this->current_country->AdvancedSearch->SearchOperator2 = @$filter["w_current_country"];
        $this->current_country->AdvancedSearch->save();

        // Field author
        $this->author->AdvancedSearch->SearchValue = @$filter["x_author"];
        $this->author->AdvancedSearch->SearchOperator = @$filter["z_author"];
        $this->author->AdvancedSearch->SearchCondition = @$filter["v_author"];
        $this->author->AdvancedSearch->SearchValue2 = @$filter["y_author"];
        $this->author->AdvancedSearch->SearchOperator2 = @$filter["w_author"];
        $this->author->AdvancedSearch->save();

        // Field sender
        $this->sender->AdvancedSearch->SearchValue = @$filter["x_sender"];
        $this->sender->AdvancedSearch->SearchOperator = @$filter["z_sender"];
        $this->sender->AdvancedSearch->SearchCondition = @$filter["v_sender"];
        $this->sender->AdvancedSearch->SearchValue2 = @$filter["y_sender"];
        $this->sender->AdvancedSearch->SearchOperator2 = @$filter["w_sender"];
        $this->sender->AdvancedSearch->save();

        // Field receiver
        $this->receiver->AdvancedSearch->SearchValue = @$filter["x_receiver"];
        $this->receiver->AdvancedSearch->SearchOperator = @$filter["z_receiver"];
        $this->receiver->AdvancedSearch->SearchCondition = @$filter["v_receiver"];
        $this->receiver->AdvancedSearch->SearchValue2 = @$filter["y_receiver"];
        $this->receiver->AdvancedSearch->SearchOperator2 = @$filter["w_receiver"];
        $this->receiver->AdvancedSearch->save();

        // Field origin_region
        $this->origin_region->AdvancedSearch->SearchValue = @$filter["x_origin_region"];
        $this->origin_region->AdvancedSearch->SearchOperator = @$filter["z_origin_region"];
        $this->origin_region->AdvancedSearch->SearchCondition = @$filter["v_origin_region"];
        $this->origin_region->AdvancedSearch->SearchValue2 = @$filter["y_origin_region"];
        $this->origin_region->AdvancedSearch->SearchOperator2 = @$filter["w_origin_region"];
        $this->origin_region->AdvancedSearch->save();

        // Field origin_city
        $this->origin_city->AdvancedSearch->SearchValue = @$filter["x_origin_city"];
        $this->origin_city->AdvancedSearch->SearchOperator = @$filter["z_origin_city"];
        $this->origin_city->AdvancedSearch->SearchCondition = @$filter["v_origin_city"];
        $this->origin_city->AdvancedSearch->SearchValue2 = @$filter["y_origin_city"];
        $this->origin_city->AdvancedSearch->SearchOperator2 = @$filter["w_origin_city"];
        $this->origin_city->AdvancedSearch->save();

        // Field start_year
        $this->start_year->AdvancedSearch->SearchValue = @$filter["x_start_year"];
        $this->start_year->AdvancedSearch->SearchOperator = @$filter["z_start_year"];
        $this->start_year->AdvancedSearch->SearchCondition = @$filter["v_start_year"];
        $this->start_year->AdvancedSearch->SearchValue2 = @$filter["y_start_year"];
        $this->start_year->AdvancedSearch->SearchOperator2 = @$filter["w_start_year"];
        $this->start_year->AdvancedSearch->save();

        // Field start_month
        $this->start_month->AdvancedSearch->SearchValue = @$filter["x_start_month"];
        $this->start_month->AdvancedSearch->SearchOperator = @$filter["z_start_month"];
        $this->start_month->AdvancedSearch->SearchCondition = @$filter["v_start_month"];
        $this->start_month->AdvancedSearch->SearchValue2 = @$filter["y_start_month"];
        $this->start_month->AdvancedSearch->SearchOperator2 = @$filter["w_start_month"];
        $this->start_month->AdvancedSearch->save();

        // Field start_day
        $this->start_day->AdvancedSearch->SearchValue = @$filter["x_start_day"];
        $this->start_day->AdvancedSearch->SearchOperator = @$filter["z_start_day"];
        $this->start_day->AdvancedSearch->SearchCondition = @$filter["v_start_day"];
        $this->start_day->AdvancedSearch->SearchValue2 = @$filter["y_start_day"];
        $this->start_day->AdvancedSearch->SearchOperator2 = @$filter["w_start_day"];
        $this->start_day->AdvancedSearch->save();

        // Field end_year
        $this->end_year->AdvancedSearch->SearchValue = @$filter["x_end_year"];
        $this->end_year->AdvancedSearch->SearchOperator = @$filter["z_end_year"];
        $this->end_year->AdvancedSearch->SearchCondition = @$filter["v_end_year"];
        $this->end_year->AdvancedSearch->SearchValue2 = @$filter["y_end_year"];
        $this->end_year->AdvancedSearch->SearchOperator2 = @$filter["w_end_year"];
        $this->end_year->AdvancedSearch->save();

        // Field end_month
        $this->end_month->AdvancedSearch->SearchValue = @$filter["x_end_month"];
        $this->end_month->AdvancedSearch->SearchOperator = @$filter["z_end_month"];
        $this->end_month->AdvancedSearch->SearchCondition = @$filter["v_end_month"];
        $this->end_month->AdvancedSearch->SearchValue2 = @$filter["y_end_month"];
        $this->end_month->AdvancedSearch->SearchOperator2 = @$filter["w_end_month"];
        $this->end_month->AdvancedSearch->save();

        // Field end_day
        $this->end_day->AdvancedSearch->SearchValue = @$filter["x_end_day"];
        $this->end_day->AdvancedSearch->SearchOperator = @$filter["z_end_day"];
        $this->end_day->AdvancedSearch->SearchCondition = @$filter["v_end_day"];
        $this->end_day->AdvancedSearch->SearchValue2 = @$filter["y_end_day"];
        $this->end_day->AdvancedSearch->SearchOperator2 = @$filter["w_end_day"];
        $this->end_day->AdvancedSearch->save();

        // Field record_type
        $this->record_type->AdvancedSearch->SearchValue = @$filter["x_record_type"];
        $this->record_type->AdvancedSearch->SearchOperator = @$filter["z_record_type"];
        $this->record_type->AdvancedSearch->SearchCondition = @$filter["v_record_type"];
        $this->record_type->AdvancedSearch->SearchValue2 = @$filter["y_record_type"];
        $this->record_type->AdvancedSearch->SearchOperator2 = @$filter["w_record_type"];
        $this->record_type->AdvancedSearch->save();

        // Field status
        $this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
        $this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
        $this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
        $this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
        $this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
        $this->status->AdvancedSearch->save();

        // Field cipher_type_other
        $this->cipher_type_other->AdvancedSearch->SearchValue = @$filter["x_cipher_type_other"];
        $this->cipher_type_other->AdvancedSearch->SearchOperator = @$filter["z_cipher_type_other"];
        $this->cipher_type_other->AdvancedSearch->SearchCondition = @$filter["v_cipher_type_other"];
        $this->cipher_type_other->AdvancedSearch->SearchValue2 = @$filter["y_cipher_type_other"];
        $this->cipher_type_other->AdvancedSearch->SearchOperator2 = @$filter["w_cipher_type_other"];
        $this->cipher_type_other->AdvancedSearch->save();

        // Field symbol_set_other
        $this->symbol_set_other->AdvancedSearch->SearchValue = @$filter["x_symbol_set_other"];
        $this->symbol_set_other->AdvancedSearch->SearchOperator = @$filter["z_symbol_set_other"];
        $this->symbol_set_other->AdvancedSearch->SearchCondition = @$filter["v_symbol_set_other"];
        $this->symbol_set_other->AdvancedSearch->SearchValue2 = @$filter["y_symbol_set_other"];
        $this->symbol_set_other->AdvancedSearch->SearchOperator2 = @$filter["w_symbol_set_other"];
        $this->symbol_set_other->AdvancedSearch->save();

        // Field inline_cleartext
        $this->inline_cleartext->AdvancedSearch->SearchValue = @$filter["x_inline_cleartext"];
        $this->inline_cleartext->AdvancedSearch->SearchOperator = @$filter["z_inline_cleartext"];
        $this->inline_cleartext->AdvancedSearch->SearchCondition = @$filter["v_inline_cleartext"];
        $this->inline_cleartext->AdvancedSearch->SearchValue2 = @$filter["y_inline_cleartext"];
        $this->inline_cleartext->AdvancedSearch->SearchOperator2 = @$filter["w_inline_cleartext"];
        $this->inline_cleartext->AdvancedSearch->save();

        // Field inline_plaintext
        $this->inline_plaintext->AdvancedSearch->SearchValue = @$filter["x_inline_plaintext"];
        $this->inline_plaintext->AdvancedSearch->SearchOperator = @$filter["z_inline_plaintext"];
        $this->inline_plaintext->AdvancedSearch->SearchCondition = @$filter["v_inline_plaintext"];
        $this->inline_plaintext->AdvancedSearch->SearchValue2 = @$filter["y_inline_plaintext"];
        $this->inline_plaintext->AdvancedSearch->SearchOperator2 = @$filter["w_inline_plaintext"];
        $this->inline_plaintext->AdvancedSearch->save();

        // Field cleartext_lang
        $this->cleartext_lang->AdvancedSearch->SearchValue = @$filter["x_cleartext_lang"];
        $this->cleartext_lang->AdvancedSearch->SearchOperator = @$filter["z_cleartext_lang"];
        $this->cleartext_lang->AdvancedSearch->SearchCondition = @$filter["v_cleartext_lang"];
        $this->cleartext_lang->AdvancedSearch->SearchValue2 = @$filter["y_cleartext_lang"];
        $this->cleartext_lang->AdvancedSearch->SearchOperator2 = @$filter["w_cleartext_lang"];
        $this->cleartext_lang->AdvancedSearch->save();

        // Field plaintext_lang
        $this->plaintext_lang->AdvancedSearch->SearchValue = @$filter["x_plaintext_lang"];
        $this->plaintext_lang->AdvancedSearch->SearchOperator = @$filter["z_plaintext_lang"];
        $this->plaintext_lang->AdvancedSearch->SearchCondition = @$filter["v_plaintext_lang"];
        $this->plaintext_lang->AdvancedSearch->SearchValue2 = @$filter["y_plaintext_lang"];
        $this->plaintext_lang->AdvancedSearch->SearchOperator2 = @$filter["w_plaintext_lang"];
        $this->plaintext_lang->AdvancedSearch->save();

        // Field document_types
        $this->document_types->AdvancedSearch->SearchValue = @$filter["x_document_types"];
        $this->document_types->AdvancedSearch->SearchOperator = @$filter["z_document_types"];
        $this->document_types->AdvancedSearch->SearchCondition = @$filter["v_document_types"];
        $this->document_types->AdvancedSearch->SearchValue2 = @$filter["y_document_types"];
        $this->document_types->AdvancedSearch->SearchOperator2 = @$filter["w_document_types"];
        $this->document_types->AdvancedSearch->save();

        // Field paper
        $this->paper->AdvancedSearch->SearchValue = @$filter["x_paper"];
        $this->paper->AdvancedSearch->SearchOperator = @$filter["z_paper"];
        $this->paper->AdvancedSearch->SearchCondition = @$filter["v_paper"];
        $this->paper->AdvancedSearch->SearchValue2 = @$filter["y_paper"];
        $this->paper->AdvancedSearch->SearchOperator2 = @$filter["w_paper"];
        $this->paper->AdvancedSearch->save();

        // Field additional_information
        $this->additional_information->AdvancedSearch->SearchValue = @$filter["x_additional_information"];
        $this->additional_information->AdvancedSearch->SearchOperator = @$filter["z_additional_information"];
        $this->additional_information->AdvancedSearch->SearchCondition = @$filter["v_additional_information"];
        $this->additional_information->AdvancedSearch->SearchValue2 = @$filter["y_additional_information"];
        $this->additional_information->AdvancedSearch->SearchOperator2 = @$filter["w_additional_information"];
        $this->additional_information->AdvancedSearch->save();

        // Field creator_id
        $this->creator_id->AdvancedSearch->SearchValue = @$filter["x_creator_id"];
        $this->creator_id->AdvancedSearch->SearchOperator = @$filter["z_creator_id"];
        $this->creator_id->AdvancedSearch->SearchCondition = @$filter["v_creator_id"];
        $this->creator_id->AdvancedSearch->SearchValue2 = @$filter["y_creator_id"];
        $this->creator_id->AdvancedSearch->SearchOperator2 = @$filter["w_creator_id"];
        $this->creator_id->AdvancedSearch->save();

        // Field creation_date
        $this->creation_date->AdvancedSearch->SearchValue = @$filter["x_creation_date"];
        $this->creation_date->AdvancedSearch->SearchOperator = @$filter["z_creation_date"];
        $this->creation_date->AdvancedSearch->SearchCondition = @$filter["v_creation_date"];
        $this->creation_date->AdvancedSearch->SearchValue2 = @$filter["y_creation_date"];
        $this->creation_date->AdvancedSearch->SearchOperator2 = @$filter["w_creation_date"];
        $this->creation_date->AdvancedSearch->save();

        // Field km_encoded_plaintext_type
        $this->km_encoded_plaintext_type->AdvancedSearch->SearchValue = @$filter["x_km_encoded_plaintext_type"];
        $this->km_encoded_plaintext_type->AdvancedSearch->SearchOperator = @$filter["z_km_encoded_plaintext_type"];
        $this->km_encoded_plaintext_type->AdvancedSearch->SearchCondition = @$filter["v_km_encoded_plaintext_type"];
        $this->km_encoded_plaintext_type->AdvancedSearch->SearchValue2 = @$filter["y_km_encoded_plaintext_type"];
        $this->km_encoded_plaintext_type->AdvancedSearch->SearchOperator2 = @$filter["w_km_encoded_plaintext_type"];
        $this->km_encoded_plaintext_type->AdvancedSearch->save();

        // Field km_numbers
        $this->km_numbers->AdvancedSearch->SearchValue = @$filter["x_km_numbers"];
        $this->km_numbers->AdvancedSearch->SearchOperator = @$filter["z_km_numbers"];
        $this->km_numbers->AdvancedSearch->SearchCondition = @$filter["v_km_numbers"];
        $this->km_numbers->AdvancedSearch->SearchValue2 = @$filter["y_km_numbers"];
        $this->km_numbers->AdvancedSearch->SearchOperator2 = @$filter["w_km_numbers"];
        $this->km_numbers->AdvancedSearch->save();

        // Field km_content_words
        $this->km_content_words->AdvancedSearch->SearchValue = @$filter["x_km_content_words"];
        $this->km_content_words->AdvancedSearch->SearchOperator = @$filter["z_km_content_words"];
        $this->km_content_words->AdvancedSearch->SearchCondition = @$filter["v_km_content_words"];
        $this->km_content_words->AdvancedSearch->SearchValue2 = @$filter["y_km_content_words"];
        $this->km_content_words->AdvancedSearch->SearchOperator2 = @$filter["w_km_content_words"];
        $this->km_content_words->AdvancedSearch->save();

        // Field km_function_words
        $this->km_function_words->AdvancedSearch->SearchValue = @$filter["x_km_function_words"];
        $this->km_function_words->AdvancedSearch->SearchOperator = @$filter["z_km_function_words"];
        $this->km_function_words->AdvancedSearch->SearchCondition = @$filter["v_km_function_words"];
        $this->km_function_words->AdvancedSearch->SearchValue2 = @$filter["y_km_function_words"];
        $this->km_function_words->AdvancedSearch->SearchOperator2 = @$filter["w_km_function_words"];
        $this->km_function_words->AdvancedSearch->save();

        // Field km_syllables
        $this->km_syllables->AdvancedSearch->SearchValue = @$filter["x_km_syllables"];
        $this->km_syllables->AdvancedSearch->SearchOperator = @$filter["z_km_syllables"];
        $this->km_syllables->AdvancedSearch->SearchCondition = @$filter["v_km_syllables"];
        $this->km_syllables->AdvancedSearch->SearchValue2 = @$filter["y_km_syllables"];
        $this->km_syllables->AdvancedSearch->SearchOperator2 = @$filter["w_km_syllables"];
        $this->km_syllables->AdvancedSearch->save();

        // Field km_morphological_endings
        $this->km_morphological_endings->AdvancedSearch->SearchValue = @$filter["x_km_morphological_endings"];
        $this->km_morphological_endings->AdvancedSearch->SearchOperator = @$filter["z_km_morphological_endings"];
        $this->km_morphological_endings->AdvancedSearch->SearchCondition = @$filter["v_km_morphological_endings"];
        $this->km_morphological_endings->AdvancedSearch->SearchValue2 = @$filter["y_km_morphological_endings"];
        $this->km_morphological_endings->AdvancedSearch->SearchOperator2 = @$filter["w_km_morphological_endings"];
        $this->km_morphological_endings->AdvancedSearch->save();

        // Field km_phrases
        $this->km_phrases->AdvancedSearch->SearchValue = @$filter["x_km_phrases"];
        $this->km_phrases->AdvancedSearch->SearchOperator = @$filter["z_km_phrases"];
        $this->km_phrases->AdvancedSearch->SearchCondition = @$filter["v_km_phrases"];
        $this->km_phrases->AdvancedSearch->SearchValue2 = @$filter["y_km_phrases"];
        $this->km_phrases->AdvancedSearch->SearchOperator2 = @$filter["w_km_phrases"];
        $this->km_phrases->AdvancedSearch->save();

        // Field km_sentences
        $this->km_sentences->AdvancedSearch->SearchValue = @$filter["x_km_sentences"];
        $this->km_sentences->AdvancedSearch->SearchOperator = @$filter["z_km_sentences"];
        $this->km_sentences->AdvancedSearch->SearchCondition = @$filter["v_km_sentences"];
        $this->km_sentences->AdvancedSearch->SearchValue2 = @$filter["y_km_sentences"];
        $this->km_sentences->AdvancedSearch->SearchOperator2 = @$filter["w_km_sentences"];
        $this->km_sentences->AdvancedSearch->save();

        // Field km_punctuation
        $this->km_punctuation->AdvancedSearch->SearchValue = @$filter["x_km_punctuation"];
        $this->km_punctuation->AdvancedSearch->SearchOperator = @$filter["z_km_punctuation"];
        $this->km_punctuation->AdvancedSearch->SearchCondition = @$filter["v_km_punctuation"];
        $this->km_punctuation->AdvancedSearch->SearchValue2 = @$filter["y_km_punctuation"];
        $this->km_punctuation->AdvancedSearch->SearchOperator2 = @$filter["w_km_punctuation"];
        $this->km_punctuation->AdvancedSearch->save();

        // Field km_nomenclature_size
        $this->km_nomenclature_size->AdvancedSearch->SearchValue = @$filter["x_km_nomenclature_size"];
        $this->km_nomenclature_size->AdvancedSearch->SearchOperator = @$filter["z_km_nomenclature_size"];
        $this->km_nomenclature_size->AdvancedSearch->SearchCondition = @$filter["v_km_nomenclature_size"];
        $this->km_nomenclature_size->AdvancedSearch->SearchValue2 = @$filter["y_km_nomenclature_size"];
        $this->km_nomenclature_size->AdvancedSearch->SearchOperator2 = @$filter["w_km_nomenclature_size"];
        $this->km_nomenclature_size->AdvancedSearch->save();

        // Field km_sections
        $this->km_sections->AdvancedSearch->SearchValue = @$filter["x_km_sections"];
        $this->km_sections->AdvancedSearch->SearchOperator = @$filter["z_km_sections"];
        $this->km_sections->AdvancedSearch->SearchCondition = @$filter["v_km_sections"];
        $this->km_sections->AdvancedSearch->SearchValue2 = @$filter["y_km_sections"];
        $this->km_sections->AdvancedSearch->SearchOperator2 = @$filter["w_km_sections"];
        $this->km_sections->AdvancedSearch->save();

        // Field km_headings
        $this->km_headings->AdvancedSearch->SearchValue = @$filter["x_km_headings"];
        $this->km_headings->AdvancedSearch->SearchOperator = @$filter["z_km_headings"];
        $this->km_headings->AdvancedSearch->SearchCondition = @$filter["v_km_headings"];
        $this->km_headings->AdvancedSearch->SearchValue2 = @$filter["y_km_headings"];
        $this->km_headings->AdvancedSearch->SearchOperator2 = @$filter["w_km_headings"];
        $this->km_headings->AdvancedSearch->save();

        // Field km_plaintext_arrangement
        $this->km_plaintext_arrangement->AdvancedSearch->SearchValue = @$filter["x_km_plaintext_arrangement"];
        $this->km_plaintext_arrangement->AdvancedSearch->SearchOperator = @$filter["z_km_plaintext_arrangement"];
        $this->km_plaintext_arrangement->AdvancedSearch->SearchCondition = @$filter["v_km_plaintext_arrangement"];
        $this->km_plaintext_arrangement->AdvancedSearch->SearchValue2 = @$filter["y_km_plaintext_arrangement"];
        $this->km_plaintext_arrangement->AdvancedSearch->SearchOperator2 = @$filter["w_km_plaintext_arrangement"];
        $this->km_plaintext_arrangement->AdvancedSearch->save();

        // Field km_ciphertext_arrangement
        $this->km_ciphertext_arrangement->AdvancedSearch->SearchValue = @$filter["x_km_ciphertext_arrangement"];
        $this->km_ciphertext_arrangement->AdvancedSearch->SearchOperator = @$filter["z_km_ciphertext_arrangement"];
        $this->km_ciphertext_arrangement->AdvancedSearch->SearchCondition = @$filter["v_km_ciphertext_arrangement"];
        $this->km_ciphertext_arrangement->AdvancedSearch->SearchValue2 = @$filter["y_km_ciphertext_arrangement"];
        $this->km_ciphertext_arrangement->AdvancedSearch->SearchOperator2 = @$filter["w_km_ciphertext_arrangement"];
        $this->km_ciphertext_arrangement->AdvancedSearch->save();

        // Field km_memorability
        $this->km_memorability->AdvancedSearch->SearchValue = @$filter["x_km_memorability"];
        $this->km_memorability->AdvancedSearch->SearchOperator = @$filter["z_km_memorability"];
        $this->km_memorability->AdvancedSearch->SearchCondition = @$filter["v_km_memorability"];
        $this->km_memorability->AdvancedSearch->SearchValue2 = @$filter["y_km_memorability"];
        $this->km_memorability->AdvancedSearch->SearchOperator2 = @$filter["w_km_memorability"];
        $this->km_memorability->AdvancedSearch->save();

        // Field km_symbol_set
        $this->km_symbol_set->AdvancedSearch->SearchValue = @$filter["x_km_symbol_set"];
        $this->km_symbol_set->AdvancedSearch->SearchOperator = @$filter["z_km_symbol_set"];
        $this->km_symbol_set->AdvancedSearch->SearchCondition = @$filter["v_km_symbol_set"];
        $this->km_symbol_set->AdvancedSearch->SearchValue2 = @$filter["y_km_symbol_set"];
        $this->km_symbol_set->AdvancedSearch->SearchOperator2 = @$filter["w_km_symbol_set"];
        $this->km_symbol_set->AdvancedSearch->save();

        // Field km_diacritics
        $this->km_diacritics->AdvancedSearch->SearchValue = @$filter["x_km_diacritics"];
        $this->km_diacritics->AdvancedSearch->SearchOperator = @$filter["z_km_diacritics"];
        $this->km_diacritics->AdvancedSearch->SearchCondition = @$filter["v_km_diacritics"];
        $this->km_diacritics->AdvancedSearch->SearchValue2 = @$filter["y_km_diacritics"];
        $this->km_diacritics->AdvancedSearch->SearchOperator2 = @$filter["w_km_diacritics"];
        $this->km_diacritics->AdvancedSearch->save();

        // Field km_code_length
        $this->km_code_length->AdvancedSearch->SearchValue = @$filter["x_km_code_length"];
        $this->km_code_length->AdvancedSearch->SearchOperator = @$filter["z_km_code_length"];
        $this->km_code_length->AdvancedSearch->SearchCondition = @$filter["v_km_code_length"];
        $this->km_code_length->AdvancedSearch->SearchValue2 = @$filter["y_km_code_length"];
        $this->km_code_length->AdvancedSearch->SearchOperator2 = @$filter["w_km_code_length"];
        $this->km_code_length->AdvancedSearch->save();

        // Field km_code_type
        $this->km_code_type->AdvancedSearch->SearchValue = @$filter["x_km_code_type"];
        $this->km_code_type->AdvancedSearch->SearchOperator = @$filter["z_km_code_type"];
        $this->km_code_type->AdvancedSearch->SearchCondition = @$filter["v_km_code_type"];
        $this->km_code_type->AdvancedSearch->SearchValue2 = @$filter["y_km_code_type"];
        $this->km_code_type->AdvancedSearch->SearchOperator2 = @$filter["w_km_code_type"];
        $this->km_code_type->AdvancedSearch->save();

        // Field km_metaphors
        $this->km_metaphors->AdvancedSearch->SearchValue = @$filter["x_km_metaphors"];
        $this->km_metaphors->AdvancedSearch->SearchOperator = @$filter["z_km_metaphors"];
        $this->km_metaphors->AdvancedSearch->SearchCondition = @$filter["v_km_metaphors"];
        $this->km_metaphors->AdvancedSearch->SearchValue2 = @$filter["y_km_metaphors"];
        $this->km_metaphors->AdvancedSearch->SearchOperator2 = @$filter["w_km_metaphors"];
        $this->km_metaphors->AdvancedSearch->save();

        // Field km_material_properties
        $this->km_material_properties->AdvancedSearch->SearchValue = @$filter["x_km_material_properties"];
        $this->km_material_properties->AdvancedSearch->SearchOperator = @$filter["z_km_material_properties"];
        $this->km_material_properties->AdvancedSearch->SearchCondition = @$filter["v_km_material_properties"];
        $this->km_material_properties->AdvancedSearch->SearchValue2 = @$filter["y_km_material_properties"];
        $this->km_material_properties->AdvancedSearch->SearchOperator2 = @$filter["w_km_material_properties"];
        $this->km_material_properties->AdvancedSearch->save();

        // Field km_instructions
        $this->km_instructions->AdvancedSearch->SearchValue = @$filter["x_km_instructions"];
        $this->km_instructions->AdvancedSearch->SearchOperator = @$filter["z_km_instructions"];
        $this->km_instructions->AdvancedSearch->SearchCondition = @$filter["v_km_instructions"];
        $this->km_instructions->AdvancedSearch->SearchValue2 = @$filter["y_km_instructions"];
        $this->km_instructions->AdvancedSearch->SearchOperator2 = @$filter["w_km_instructions"];
        $this->km_instructions->AdvancedSearch->save();
        $this->BasicSearch->setKeyword(@$filter[Config("TABLE_BASIC_SEARCH")]);
        $this->BasicSearch->setType(@$filter[Config("TABLE_BASIC_SEARCH_TYPE")]);
    }

    // Advanced search WHERE clause based on QueryString
    public function advancedSearchWhere($default = false)
    {
        global $Security;
        $where = "";
        if (!$Security->canSearch()) {
            return "";
        }
        $this->buildSearchSql($where, $this->id, $default, false); // id
        $this->buildSearchSql($where, $this->owner, $default, false); // owner
        $this->buildSearchSql($where, $this->c_holder, $default, false); // c_holder
        $this->buildSearchSql($where, $this->current_country, $default, false); // current_country
        $this->buildSearchSql($where, $this->author, $default, false); // author
        $this->buildSearchSql($where, $this->sender, $default, false); // sender
        $this->buildSearchSql($where, $this->receiver, $default, false); // receiver
        $this->buildSearchSql($where, $this->origin_region, $default, false); // origin_region
        $this->buildSearchSql($where, $this->origin_city, $default, false); // origin_city
        $this->buildSearchSql($where, $this->start_year, $default, false); // start_year
        $this->buildSearchSql($where, $this->start_month, $default, false); // start_month
        $this->buildSearchSql($where, $this->start_day, $default, false); // start_day
        $this->buildSearchSql($where, $this->end_year, $default, false); // end_year
        $this->buildSearchSql($where, $this->end_month, $default, false); // end_month
        $this->buildSearchSql($where, $this->end_day, $default, false); // end_day
        $this->buildSearchSql($where, $this->record_type, $default, false); // record_type
        $this->buildSearchSql($where, $this->status, $default, false); // status
        $this->buildSearchSql($where, $this->cipher_type_other, $default, false); // cipher_type_other
        $this->buildSearchSql($where, $this->symbol_set_other, $default, false); // symbol_set_other
        $this->buildSearchSql($where, $this->inline_cleartext, $default, false); // inline_cleartext
        $this->buildSearchSql($where, $this->inline_plaintext, $default, false); // inline_plaintext
        $this->buildSearchSql($where, $this->cleartext_lang, $default, false); // cleartext_lang
        $this->buildSearchSql($where, $this->plaintext_lang, $default, false); // plaintext_lang
        $this->buildSearchSql($where, $this->document_types, $default, true); // document_types
        $this->buildSearchSql($where, $this->paper, $default, false); // paper
        $this->buildSearchSql($where, $this->additional_information, $default, false); // additional_information
        $this->buildSearchSql($where, $this->creator_id, $default, false); // creator_id
        $this->buildSearchSql($where, $this->creation_date, $default, false); // creation_date
        $this->buildSearchSql($where, $this->km_encoded_plaintext_type, $default, true); // km_encoded_plaintext_type
        $this->buildSearchSql($where, $this->km_numbers, $default, false); // km_numbers
        $this->buildSearchSql($where, $this->km_content_words, $default, false); // km_content_words
        $this->buildSearchSql($where, $this->km_function_words, $default, false); // km_function_words
        $this->buildSearchSql($where, $this->km_syllables, $default, false); // km_syllables
        $this->buildSearchSql($where, $this->km_morphological_endings, $default, false); // km_morphological_endings
        $this->buildSearchSql($where, $this->km_phrases, $default, false); // km_phrases
        $this->buildSearchSql($where, $this->km_sentences, $default, false); // km_sentences
        $this->buildSearchSql($where, $this->km_punctuation, $default, false); // km_punctuation
        $this->buildSearchSql($where, $this->km_nomenclature_size, $default, false); // km_nomenclature_size
        $this->buildSearchSql($where, $this->km_sections, $default, false); // km_sections
        $this->buildSearchSql($where, $this->km_headings, $default, false); // km_headings
        $this->buildSearchSql($where, $this->km_plaintext_arrangement, $default, true); // km_plaintext_arrangement
        $this->buildSearchSql($where, $this->km_ciphertext_arrangement, $default, true); // km_ciphertext_arrangement
        $this->buildSearchSql($where, $this->km_memorability, $default, false); // km_memorability
        $this->buildSearchSql($where, $this->km_symbol_set, $default, true); // km_symbol_set
        $this->buildSearchSql($where, $this->km_diacritics, $default, false); // km_diacritics
        $this->buildSearchSql($where, $this->km_code_length, $default, true); // km_code_length
        $this->buildSearchSql($where, $this->km_code_type, $default, true); // km_code_type
        $this->buildSearchSql($where, $this->km_metaphors, $default, false); // km_metaphors
        $this->buildSearchSql($where, $this->km_material_properties, $default, true); // km_material_properties
        $this->buildSearchSql($where, $this->km_instructions, $default, false); // km_instructions

        // Set up search command
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->id->AdvancedSearch->save(); // id
            $this->owner->AdvancedSearch->save(); // owner
            $this->c_holder->AdvancedSearch->save(); // c_holder
            $this->current_country->AdvancedSearch->save(); // current_country
            $this->author->AdvancedSearch->save(); // author
            $this->sender->AdvancedSearch->save(); // sender
            $this->receiver->AdvancedSearch->save(); // receiver
            $this->origin_region->AdvancedSearch->save(); // origin_region
            $this->origin_city->AdvancedSearch->save(); // origin_city
            $this->start_year->AdvancedSearch->save(); // start_year
            $this->start_month->AdvancedSearch->save(); // start_month
            $this->start_day->AdvancedSearch->save(); // start_day
            $this->end_year->AdvancedSearch->save(); // end_year
            $this->end_month->AdvancedSearch->save(); // end_month
            $this->end_day->AdvancedSearch->save(); // end_day
            $this->record_type->AdvancedSearch->save(); // record_type
            $this->status->AdvancedSearch->save(); // status
            $this->cipher_type_other->AdvancedSearch->save(); // cipher_type_other
            $this->symbol_set_other->AdvancedSearch->save(); // symbol_set_other
            $this->inline_cleartext->AdvancedSearch->save(); // inline_cleartext
            $this->inline_plaintext->AdvancedSearch->save(); // inline_plaintext
            $this->cleartext_lang->AdvancedSearch->save(); // cleartext_lang
            $this->plaintext_lang->AdvancedSearch->save(); // plaintext_lang
            $this->document_types->AdvancedSearch->save(); // document_types
            $this->paper->AdvancedSearch->save(); // paper
            $this->additional_information->AdvancedSearch->save(); // additional_information
            $this->creator_id->AdvancedSearch->save(); // creator_id
            $this->creation_date->AdvancedSearch->save(); // creation_date
            $this->km_encoded_plaintext_type->AdvancedSearch->save(); // km_encoded_plaintext_type
            $this->km_numbers->AdvancedSearch->save(); // km_numbers
            $this->km_content_words->AdvancedSearch->save(); // km_content_words
            $this->km_function_words->AdvancedSearch->save(); // km_function_words
            $this->km_syllables->AdvancedSearch->save(); // km_syllables
            $this->km_morphological_endings->AdvancedSearch->save(); // km_morphological_endings
            $this->km_phrases->AdvancedSearch->save(); // km_phrases
            $this->km_sentences->AdvancedSearch->save(); // km_sentences
            $this->km_punctuation->AdvancedSearch->save(); // km_punctuation
            $this->km_nomenclature_size->AdvancedSearch->save(); // km_nomenclature_size
            $this->km_sections->AdvancedSearch->save(); // km_sections
            $this->km_headings->AdvancedSearch->save(); // km_headings
            $this->km_plaintext_arrangement->AdvancedSearch->save(); // km_plaintext_arrangement
            $this->km_ciphertext_arrangement->AdvancedSearch->save(); // km_ciphertext_arrangement
            $this->km_memorability->AdvancedSearch->save(); // km_memorability
            $this->km_symbol_set->AdvancedSearch->save(); // km_symbol_set
            $this->km_diacritics->AdvancedSearch->save(); // km_diacritics
            $this->km_code_length->AdvancedSearch->save(); // km_code_length
            $this->km_code_type->AdvancedSearch->save(); // km_code_type
            $this->km_metaphors->AdvancedSearch->save(); // km_metaphors
            $this->km_material_properties->AdvancedSearch->save(); // km_material_properties
            $this->km_instructions->AdvancedSearch->save(); // km_instructions

            // Clear rules for QueryBuilder
            $this->setSessionRules("");
        }
        return $where;
    }

    // Parse query builder rule function
    protected function parseRules($group, $fieldName = "") {
        $group["condition"] ??= "AND";
        if (!in_array($group["condition"], ["AND", "OR"])) {
            throw new \Exception("Unable to build SQL query with condition '" . $group["condition"] . "'");
        }
        if (!is_array($group["rules"] ?? null)) {
            return "";
        }
        $parts = [];
        foreach ($group["rules"] as $rule) {
            if (is_array($rule["rules"] ?? null) && count($rule["rules"]) > 0) {
                $parts[] = "(" . " " . $this->parseRules($rule, $fieldName) . " " . ")" . " ";
            } else {
                $field = $rule["field"];
                $fld = $this->fieldByParam($field);
                if (!$fld) {
                    throw new \Exception("Failed to find field '" . $field . "'");
                }
                if ($fieldName == "" || $fld->Name == $fieldName) { // Field name not specified or matched field name
                    $fldOpr = array_search($rule["operator"], Config("CLIENT_SEARCH_OPERATORS"));
                    $ope = Config("QUERY_BUILDER_OPERATORS")[$rule["operator"]] ?? null;
                    if (!$ope || !$fldOpr) {
                        throw new \Exception("Unknown SQL operation for operator '" . $rule["operator"] . "'");
                    }
                    if ($ope["nb_inputs"] > 0 && ($rule["value"] ?? false) || IsNullOrEmptyOperator($fldOpr)) {
                        $rule["value"] = !is_array($rule["value"]) ? [$rule["value"]] : $rule["value"];
                        $fldVal = $rule["value"][0];
                        if (is_array($fldVal)) {
                            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
                        }
                        $useFilter = $fld->UseFilter; // Query builder does not use filter
                        try {
                            if ($fld->isMultiSelect()) {
                                $parts[] = $fldVal != "" ? GetMultiSearchSql($fld, $fldOpr, ConvertSearchValue($fldVal, $fldOpr, $fld), $this->Dbid) : "";
                            } else {
                                $fldVal2 = ContainsString($fldOpr, "BETWEEN") ? $rule["value"][1] : ""; // BETWEEN
                                if (is_array($fldVal2)) {
                                    $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
                                }
                                $parts[] = GetSearchSql(
                                    $fld,
                                    ConvertSearchValue($fldVal, $fldOpr, $fld), // $fldVal
                                    $fldOpr,
                                    "", // $fldCond not used
                                    ConvertSearchValue($fldVal2, $fldOpr, $fld), // $fldVal2
                                    "", // $fldOpr2 not used
                                    $this->Dbid
                                );
                            }
                        } finally {
                            $fld->UseFilter = $useFilter;
                        }
                    }
                }
            }
        }
        $where = implode(" " . $group["condition"] . " ", array_filter($parts));
        if ($group["not"] ?? false) {
            $where = "NOT (" . $where . ")";
        }
        return $where;
    }

    // Quey builder WHERE clause
    public function queryBuilderWhere($fieldName = "")
    {
        global $Security;
        if (!$Security->canSearch()) {
            return "";
        }

        // Get rules by query builder
        $rules = Post("rules") ?? $this->getSessionRules();

        // Decode and parse rules
        $where = $rules ? $this->parseRules(json_decode($rules, true), $fieldName) : "";

        // Clear other search and save rules to session
        if ($where && $fieldName == "") { // Skip if get query for specific field
            $this->resetSearchParms();
            $this->setSessionRules($rules);
        }

        // Return query
        return $where;
    }

    // Build search SQL
    protected function buildSearchSql(&$where, $fld, $default, $multiValue)
    {
        $fldParm = $fld->Param;
        $fldVal = $default ? $fld->AdvancedSearch->SearchValueDefault : $fld->AdvancedSearch->SearchValue;
        $fldOpr = $default ? $fld->AdvancedSearch->SearchOperatorDefault : $fld->AdvancedSearch->SearchOperator;
        $fldCond = $default ? $fld->AdvancedSearch->SearchConditionDefault : $fld->AdvancedSearch->SearchCondition;
        $fldVal2 = $default ? $fld->AdvancedSearch->SearchValue2Default : $fld->AdvancedSearch->SearchValue2;
        $fldOpr2 = $default ? $fld->AdvancedSearch->SearchOperator2Default : $fld->AdvancedSearch->SearchOperator2;
        $fldVal = ConvertSearchValue($fldVal, $fldOpr, $fld);
        $fldVal2 = ConvertSearchValue($fldVal2, $fldOpr2, $fld);
        $fldOpr = ConvertSearchOperator($fldOpr, $fld, $fldVal);
        $fldOpr2 = ConvertSearchOperator($fldOpr2, $fld, $fldVal2);
        $wrk = "";
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        if (Config("SEARCH_MULTI_VALUE_OPTION") == 1 && !$fld->UseFilter || !IsMultiSearchOperator($fldOpr)) {
            $multiValue = false;
        }
        if ($multiValue) {
            $wrk = $fldVal != "" ? GetMultiSearchSql($fld, $fldOpr, $fldVal, $this->Dbid) : ""; // Field value 1
            $wrk2 = $fldVal2 != "" ? GetMultiSearchSql($fld, $fldOpr2, $fldVal2, $this->Dbid) : ""; // Field value 2
            AddFilter($wrk, $wrk2, $fldCond);
        } else {
            $wrk = GetSearchSql($fld, $fldVal, $fldOpr, $fldCond, $fldVal2, $fldOpr2, $this->Dbid);
        }
        if ($this->SearchOption == "AUTO" && in_array($this->BasicSearch->getType(), ["AND", "OR"])) {
            $cond = $this->BasicSearch->getType();
        } else {
            $cond = SameText($this->SearchOption, "OR") ? "OR" : "AND";
        }
        AddFilter($where, $wrk, $cond);
    }

    // Show list of filters
    public function showFilterList()
    {
        global $Language;

        // Initialize
        $filterList = "";
        $captionClass = $this->isExport("email") ? "ew-filter-caption-email" : "ew-filter-caption";
        $captionSuffix = $this->isExport("email") ? ": " : "";

        // Field id
        $filter = $this->queryBuilderWhere("id");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->id, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->id->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field c_holder
        $filter = $this->queryBuilderWhere("c_holder");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->c_holder, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->c_holder->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field record_type
        $filter = $this->queryBuilderWhere("record_type");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->record_type, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->record_type->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }

        // Field status
        $filter = $this->queryBuilderWhere("status");
        if (!$filter) {
            $this->buildSearchSql($filter, $this->status, false, false);
        }
        if ($filter != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $this->status->caption() . "</span>" . $captionSuffix . $filter . "</div>";
        }
        if ($this->BasicSearch->Keyword != "") {
            $filterList .= "<div><span class=\"" . $captionClass . "\">" . $Language->phrase("BasicSearchKeyword") . "</span>" . $captionSuffix . $this->BasicSearch->Keyword . "</div>";
        }

        // Show Filters
        if ($filterList != "") {
            $message = "<div id=\"ew-filter-list\" class=\"callout callout-info d-table\"><div id=\"ew-current-filters\">" .
                $Language->phrase("CurrentFilters") . "</div>" . $filterList . "</div>";
            $this->messageShowing($message, "");
            Write($message);
        } else { // Output empty tag
            Write("<div id=\"ew-filter-list\"></div>");
        }
    }

    // Return basic search WHERE clause based on search keyword and type
    public function basicSearchWhere($default = false)
    {
        global $Security;
        $searchStr = "";
        if (!$Security->canSearch()) {
            return "";
        }

        // Fields to search
        $searchFlds = [];
        $searchFlds[] = &$this->id;
        $searchFlds[] = &$this->name;
        $searchFlds[] = &$this->owner;
        $searchFlds[] = &$this->c_holder;
        $searchFlds[] = &$this->c_cates;
        $searchFlds[] = &$this->c_author;
        $searchFlds[] = &$this->c_lang;
        $searchFlds[] = &$this->current_country;
        $searchFlds[] = &$this->current_city;
        $searchFlds[] = &$this->current_holder;
        $searchFlds[] = &$this->author;
        $searchFlds[] = &$this->sender;
        $searchFlds[] = &$this->receiver;
        $searchFlds[] = &$this->origin_region;
        $searchFlds[] = &$this->origin_city;
        $searchFlds[] = &$this->status;
        $searchFlds[] = &$this->symbol_sets;
        $searchFlds[] = &$this->cipher_types;
        $searchFlds[] = &$this->cipher_type_other;
        $searchFlds[] = &$this->symbol_set_other;
        $searchFlds[] = &$this->cleartext_lang;
        $searchFlds[] = &$this->plaintext_lang;
        $searchFlds[] = &$this->document_types;
        $searchFlds[] = &$this->paper;
        $searchFlds[] = &$this->additional_information;
        $searchFlds[] = &$this->km_encoded_plaintext_type;
        $searchFlds[] = &$this->km_nomenclature_size;
        $searchFlds[] = &$this->km_plaintext_arrangement;
        $searchFlds[] = &$this->km_ciphertext_arrangement;
        $searchFlds[] = &$this->km_memorability;
        $searchFlds[] = &$this->km_symbol_set;
        $searchFlds[] = &$this->km_code_length;
        $searchFlds[] = &$this->km_code_type;
        $searchFlds[] = &$this->km_material_properties;
        $searchKeyword = $default ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
        $searchType = $default ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

        // Get search SQL
        if ($searchKeyword != "") {
            $ar = $this->BasicSearch->keywordList($default);
            $searchStr = GetQuickSearchFilter($searchFlds, $ar, $searchType, Config("BASIC_SEARCH_ANY_FIELDS"), $this->Dbid);
            if (!$default && in_array($this->Command, ["", "reset", "resetall"])) {
                $this->Command = "search";
            }
        }
        if (!$default && $this->Command == "search") {
            $this->BasicSearch->setKeyword($searchKeyword);
            $this->BasicSearch->setType($searchType);

            // Clear rules for QueryBuilder
            $this->setSessionRules("");
        }
        return $searchStr;
    }

    // Check if search parm exists
    protected function checkSearchParms()
    {
        // Check basic search
        if ($this->BasicSearch->issetSession()) {
            return true;
        }
        if ($this->id->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->owner->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->c_holder->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->current_country->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->author->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->sender->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->receiver->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->origin_region->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->origin_city->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->start_year->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->start_month->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->start_day->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->end_year->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->end_month->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->end_day->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->record_type->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->status->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cipher_type_other->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->symbol_set_other->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->inline_cleartext->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->inline_plaintext->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->cleartext_lang->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->plaintext_lang->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->document_types->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->paper->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->additional_information->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->creator_id->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->creation_date->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_encoded_plaintext_type->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_numbers->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_content_words->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_function_words->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_syllables->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_morphological_endings->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_phrases->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_sentences->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_punctuation->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_nomenclature_size->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_sections->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_headings->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_plaintext_arrangement->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_ciphertext_arrangement->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_memorability->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_symbol_set->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_diacritics->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_code_length->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_code_type->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_metaphors->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_material_properties->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->km_instructions->AdvancedSearch->issetSession()) {
            return true;
        }
        return false;
    }

    // Clear all search parameters
    protected function resetSearchParms()
    {
        // Clear search WHERE clause
        $this->SearchWhere = "";
        $this->setSearchWhere($this->SearchWhere);

        // Clear basic search parameters
        $this->resetBasicSearchParms();

        // Clear advanced search parameters
        $this->resetAdvancedSearchParms();

        // Clear queryBuilder
        $this->setSessionRules("");
    }

    // Load advanced search default values
    protected function loadAdvancedSearchDefault()
    {
        return false;
    }

    // Clear all basic search parameters
    protected function resetBasicSearchParms()
    {
        $this->BasicSearch->unsetSession();
    }

    // Clear all advanced search parameters
    protected function resetAdvancedSearchParms()
    {
        $this->id->AdvancedSearch->unsetSession();
        $this->owner->AdvancedSearch->unsetSession();
        $this->c_holder->AdvancedSearch->unsetSession();
        $this->current_country->AdvancedSearch->unsetSession();
        $this->author->AdvancedSearch->unsetSession();
        $this->sender->AdvancedSearch->unsetSession();
        $this->receiver->AdvancedSearch->unsetSession();
        $this->origin_region->AdvancedSearch->unsetSession();
        $this->origin_city->AdvancedSearch->unsetSession();
        $this->start_year->AdvancedSearch->unsetSession();
        $this->start_month->AdvancedSearch->unsetSession();
        $this->start_day->AdvancedSearch->unsetSession();
        $this->end_year->AdvancedSearch->unsetSession();
        $this->end_month->AdvancedSearch->unsetSession();
        $this->end_day->AdvancedSearch->unsetSession();
        $this->record_type->AdvancedSearch->unsetSession();
        $this->status->AdvancedSearch->unsetSession();
        $this->cipher_type_other->AdvancedSearch->unsetSession();
        $this->symbol_set_other->AdvancedSearch->unsetSession();
        $this->inline_cleartext->AdvancedSearch->unsetSession();
        $this->inline_plaintext->AdvancedSearch->unsetSession();
        $this->cleartext_lang->AdvancedSearch->unsetSession();
        $this->plaintext_lang->AdvancedSearch->unsetSession();
        $this->document_types->AdvancedSearch->unsetSession();
        $this->paper->AdvancedSearch->unsetSession();
        $this->additional_information->AdvancedSearch->unsetSession();
        $this->creator_id->AdvancedSearch->unsetSession();
        $this->creation_date->AdvancedSearch->unsetSession();
        $this->km_encoded_plaintext_type->AdvancedSearch->unsetSession();
        $this->km_numbers->AdvancedSearch->unsetSession();
        $this->km_content_words->AdvancedSearch->unsetSession();
        $this->km_function_words->AdvancedSearch->unsetSession();
        $this->km_syllables->AdvancedSearch->unsetSession();
        $this->km_morphological_endings->AdvancedSearch->unsetSession();
        $this->km_phrases->AdvancedSearch->unsetSession();
        $this->km_sentences->AdvancedSearch->unsetSession();
        $this->km_punctuation->AdvancedSearch->unsetSession();
        $this->km_nomenclature_size->AdvancedSearch->unsetSession();
        $this->km_sections->AdvancedSearch->unsetSession();
        $this->km_headings->AdvancedSearch->unsetSession();
        $this->km_plaintext_arrangement->AdvancedSearch->unsetSession();
        $this->km_ciphertext_arrangement->AdvancedSearch->unsetSession();
        $this->km_memorability->AdvancedSearch->unsetSession();
        $this->km_symbol_set->AdvancedSearch->unsetSession();
        $this->km_diacritics->AdvancedSearch->unsetSession();
        $this->km_code_length->AdvancedSearch->unsetSession();
        $this->km_code_type->AdvancedSearch->unsetSession();
        $this->km_metaphors->AdvancedSearch->unsetSession();
        $this->km_material_properties->AdvancedSearch->unsetSession();
        $this->km_instructions->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
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

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Load default Sorting Order
        if ($this->Command != "json") {
            $defaultSort = $this->id->Expression . " DESC"; // Set up default sort
            if ($this->getSessionOrderBy() == "" && $defaultSort != "") {
                $this->setSessionOrderBy($defaultSort);
            }
        }

        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->id); // id
            $this->updateSort($this->c_holder); // c_holder
            $this->updateSort($this->c_cates); // c_cates
            $this->updateSort($this->c_author); // c_author
            $this->updateSort($this->c_lang); // c_lang
            $this->updateSort($this->record_type); // record_type
            $this->updateSort($this->status); // status
            $this->updateSort($this->number_of_pages); // number_of_pages
            $this->setStartRecordNumber(1); // Reset start position
        }

        // Update field sort
        $this->updateFieldSort();
    }

    // Reset command
    // - cmd=reset (Reset search parameters)
    // - cmd=resetall (Reset search and master/detail parameters)
    // - cmd=resetsort (Reset sort parameters)
    protected function resetCmd()
    {
        // Check if reset command
        if (StartsString("reset", $this->Command)) {
            // Reset search criteria
            if ($this->Command == "reset" || $this->Command == "resetall") {
                $this->resetSearchParms();
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
                $this->id->setSort("");
                $this->name->setSort("");
                $this->owner_id->setSort("");
                $this->owner->setSort("");
                $this->record_group_id->setSort("");
                $this->c_holder->setSort("");
                $this->c_cates->setSort("");
                $this->c_author->setSort("");
                $this->c_lang->setSort("");
                $this->current_country->setSort("");
                $this->current_city->setSort("");
                $this->current_holder->setSort("");
                $this->author->setSort("");
                $this->sender->setSort("");
                $this->receiver->setSort("");
                $this->origin_region->setSort("");
                $this->origin_city->setSort("");
                $this->start_year->setSort("");
                $this->start_month->setSort("");
                $this->start_day->setSort("");
                $this->end_year->setSort("");
                $this->end_month->setSort("");
                $this->end_day->setSort("");
                $this->record_type->setSort("");
                $this->status->setSort("");
                $this->symbol_sets->setSort("");
                $this->cipher_types->setSort("");
                $this->cipher_type_other->setSort("");
                $this->symbol_set_other->setSort("");
                $this->number_of_pages->setSort("");
                $this->inline_cleartext->setSort("");
                $this->inline_plaintext->setSort("");
                $this->cleartext_lang->setSort("");
                $this->plaintext_lang->setSort("");
                $this->private_ciphertext->setSort("");
                $this->document_types->setSort("");
                $this->paper->setSort("");
                $this->additional_information->setSort("");
                $this->creator_id->setSort("");
                $this->access_mode->setSort("");
                $this->creation_date->setSort("");
                $this->km_encoded_plaintext_type->setSort("");
                $this->km_numbers->setSort("");
                $this->km_content_words->setSort("");
                $this->km_function_words->setSort("");
                $this->km_syllables->setSort("");
                $this->km_morphological_endings->setSort("");
                $this->km_phrases->setSort("");
                $this->km_sentences->setSort("");
                $this->km_punctuation->setSort("");
                $this->km_nomenclature_size->setSort("");
                $this->km_sections->setSort("");
                $this->km_headings->setSort("");
                $this->km_plaintext_arrangement->setSort("");
                $this->km_ciphertext_arrangement->setSort("");
                $this->km_memorability->setSort("");
                $this->km_symbol_set->setSort("");
                $this->km_diacritics->setSort("");
                $this->km_code_length->setSort("");
                $this->km_code_type->setSort("");
                $this->km_metaphors->setSort("");
                $this->km_material_properties->setSort("");
                $this->km_instructions->setSort("");
            }

            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // Add group option item ("button")
        $item = &$this->ListOptions->addGroupOption();
        $item->Body = "";
        $item->OnLeft = false;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = false;

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = false;

        // "copy"
        $item = &$this->ListOptions->add("copy");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canAdd();
        $item->OnLeft = false;

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = false;

        // "detail_images"
        $item = &$this->ListOptions->add("detail_images");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'images');
        $item->OnLeft = false;
        $item->ShowInButtonGroup = false;

        // "detail_documents"
        $item = &$this->ListOptions->add("detail_documents");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'documents');
        $item->OnLeft = false;
        $item->ShowInButtonGroup = false;

        // "detail_associated_records"
        $item = &$this->ListOptions->add("detail_associated_records");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'associated_records');
        $item->OnLeft = false;
        $item->ShowInButtonGroup = false;

        // Multiple details
        if ($this->ShowMultipleDetails) {
            $item = &$this->ListOptions->add("details");
            $item->CssClass = "text-nowrap";
            $item->Visible = $this->ShowMultipleDetails && $this->ListOptions->detailVisible();
            $item->OnLeft = false;
            $item->ShowInButtonGroup = false;
            $this->ListOptions->hideDetailItems();
        }

        // Set up detail pages
        $pages = new SubPages();
        $pages->add("images");
        $pages->add("documents");
        $pages->add("associated_records");
        $this->DetailPages = $pages;

        // List actions
        $item = &$this->ListOptions->add("listactions");
        $item->CssClass = "text-nowrap";
        $item->OnLeft = false;
        $item->Visible = false;
        $item->ShowInButtonGroup = false;
        $item->ShowInDropDown = false;

        // "checkbox"
        $item = &$this->ListOptions->add("checkbox");
        $item->Visible = false;
        $item->OnLeft = false;
        $item->Header = "<div class=\"form-check\"><input type=\"checkbox\" name=\"key\" id=\"key\" class=\"form-check-input\" data-ew-action=\"select-all-keys\"></div>";
        if ($item->OnLeft) {
            $item->moveTo(0);
        }
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = false;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = false;
        if ($this->ListOptions->UseButtonGroup && IsMobile()) {
            $this->ListOptions->UseDropDownButton = true;
        }

        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $this->setupListOptionsExt();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Set up list options (extensions)
    protected function setupListOptionsExt()
    {
            // Set up list options (to be implemented by extensions)
    }

    // Add "hash" parameter to URL
    public function urlAddHash($url, $hash)
    {
        return $this->UseAjaxActions ? $url : UrlAddQuery($url, "hash=" . $hash);
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm, $UserProfile;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();
        $pageUrl = $this->pageUrl(false);
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                if ($this->ModalView && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-table=\"records\" data-caption=\"" . $viewcaption . "\" data-ew-action=\"modal\" data-action=\"view\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\" data-btn=\"null\">" . $Language->phrase("ViewLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit()) {
                if ($this->ModalEdit && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-table=\"records\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-action=\"edit\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\" data-btn=\"SaveBtn\">" . $Language->phrase("EditLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd()) {
                if ($this->ModalAdd && !IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-table=\"records\" data-caption=\"" . $copycaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("CopyLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
                }
            } else {
                $opt->Body = "";
            }

            // "delete"
            $opt = $this->ListOptions["delete"];
            if ($Security->canDelete()) {
                $deleteCaption = $Language->phrase("DeleteLink");
                $deleteTitle = HtmlTitle($deleteCaption);
                if ($this->UseAjaxActions) {
                    $opt->Body = "<a class=\"ew-row-link ew-delete\" data-ew-action=\"inline\" data-action=\"delete\" title=\"" . $deleteTitle . "\" data-caption=\"" . $deleteTitle . "\" data-key= \"" . HtmlEncode($this->getKey(true)) . "\" data-url=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $deleteCaption . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-delete\"" .
                        ($this->InlineDelete ? " data-ew-action=\"inline-delete\"" : "") .
                        " title=\"" . $deleteTitle . "\" data-caption=\"" . $deleteTitle . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $deleteCaption . "</a>";
                }
            } else {
                $opt->Body = "";
            }
        } // End View mode

        // Set up list action buttons
        $opt = $this->ListOptions["listactions"];
        if ($opt && !$this->isExport() && !$this->CurrentAction) {
            $body = "";
            $links = [];
            foreach ($this->ListActions->Items as $listaction) {
                $action = $listaction->Action;
                $allowed = $listaction->Allow;
                if ($listaction->Select == ACTION_SINGLE && $allowed) {
                    $caption = $listaction->Caption;
                    $icon = ($listaction->Icon != "") ? "<i class=\"" . HtmlEncode(str_replace(" ew-icon", "", $listaction->Icon)) . "\" data-caption=\"" . HtmlTitle($caption) . "\"></i> " : "";
                    $link = "<li><button type=\"button\" class=\"dropdown-item ew-action ew-list-action\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"frecordslist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . " " . $listaction->Caption . "</button></li>";
                    if ($link != "") {
                        $links[] = $link;
                        if ($body == "") { // Setup first button
                            $body = "<button type=\"button\" class=\"btn btn-default ew-action ew-list-action\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"frecordslist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . " " . $listaction->Caption . "</button>";
                        }
                    }
                }
            }
            if (count($links) > 1) { // More than one buttons, use dropdown
                $body = "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-actions\" title=\"" . HtmlTitle($Language->phrase("ListActionButton")) . "\" data-bs-toggle=\"dropdown\">" . $Language->phrase("ListActionButton") . "</button>";
                $content = "";
                foreach ($links as $link) {
                    $content .= "<li>" . $link . "</li>";
                }
                $body .= "<ul class=\"dropdown-menu" . ($opt->OnLeft ? "" : " dropdown-menu-right") . "\">" . $content . "</ul>";
                $body = "<div class=\"btn-group btn-group-sm\">" . $body . "</div>";
            }
            if (count($links) > 0) {
                $opt->Body = $body;
            }
        }
        $detailViewTblVar = "";
        $detailCopyTblVar = "";
        $detailEditTblVar = "";

        // "detail_images"
        $opt = $this->ListOptions["detail_images"];
        if ($Security->allowList(CurrentProjectID() . 'images')) {
            $body = $Language->phrase("DetailLink") . $Language->TablePhrase("images", "TblCaption");
            if (!$this->ShowMultipleDetails) { // Skip loading record count if show multiple details
                $detailTbl = Container("images");
                $detailFilter = $detailTbl->getDetailFilter($this);
                $detailTbl->setCurrentMasterTable($this->TableVar);
                $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
                $detailTbl->Count = $detailTbl->loadRecordCount($detailFilter);
                $body .= "&nbsp;" . str_replace("%c", Container("images")->Count, $Language->phrase("DetailCount"));
            }
            $body = "<a class=\"btn btn-default ew-row-link ew-detail" . ($this->ListOptions->UseDropDownButton ? " dropdown-toggle" : "") . "\" data-action=\"list\" href=\"" . HtmlEncode("ImagesList?" . Config("TABLE_SHOW_MASTER") . "=records&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("ImagesGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'records')) {
                $caption = $Language->phrase("MasterDetailViewLink", null);
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=images");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "images";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'records')) {
                $caption = $Language->phrase("MasterDetailEditLink", null);
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=images");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailEditTblVar != "") {
                    $detailEditTblVar .= ",";
                }
                $detailEditTblVar .= "images";
            }
            if ($detailPage->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'records')) {
                $caption = $Language->phrase("MasterDetailCopyLink", null);
                $url = $this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=images");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
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
            $opt->Body = $body;
            if ($this->ShowMultipleDetails) {
                $opt->Visible = false;
            }
        }

        // "detail_documents"
        $opt = $this->ListOptions["detail_documents"];
        if ($Security->allowList(CurrentProjectID() . 'documents')) {
            $body = $Language->phrase("DetailLink") . $Language->TablePhrase("documents", "TblCaption");
            if (!$this->ShowMultipleDetails) { // Skip loading record count if show multiple details
                $detailTbl = Container("documents");
                $detailFilter = $detailTbl->getDetailFilter($this);
                $detailTbl->setCurrentMasterTable($this->TableVar);
                $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
                $detailTbl->Count = $detailTbl->loadRecordCount($detailFilter);
                $body .= "&nbsp;" . str_replace("%c", Container("documents")->Count, $Language->phrase("DetailCount"));
            }
            $body = "<a class=\"btn btn-default ew-row-link ew-detail" . ($this->ListOptions->UseDropDownButton ? " dropdown-toggle" : "") . "\" data-action=\"list\" href=\"" . HtmlEncode("DocumentsList?" . Config("TABLE_SHOW_MASTER") . "=records&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("DocumentsGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'records')) {
                $caption = $Language->phrase("MasterDetailViewLink", null);
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=documents");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "documents";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'records')) {
                $caption = $Language->phrase("MasterDetailEditLink", null);
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=documents");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailEditTblVar != "") {
                    $detailEditTblVar .= ",";
                }
                $detailEditTblVar .= "documents";
            }
            if ($detailPage->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'records')) {
                $caption = $Language->phrase("MasterDetailCopyLink", null);
                $url = $this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=documents");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
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
            $opt->Body = $body;
            if ($this->ShowMultipleDetails) {
                $opt->Visible = false;
            }
        }

        // "detail_associated_records"
        $opt = $this->ListOptions["detail_associated_records"];
        if ($Security->allowList(CurrentProjectID() . 'associated_records')) {
            $body = $Language->phrase("DetailLink") . $Language->TablePhrase("associated_records", "TblCaption");
            if (!$this->ShowMultipleDetails) { // Skip loading record count if show multiple details
                $detailTbl = Container("associated_records");
                $detailFilter = $detailTbl->getDetailFilter($this);
                $detailTbl->setCurrentMasterTable($this->TableVar);
                $detailFilter = $detailTbl->applyUserIDFilters($detailFilter);
                $detailTbl->Count = $detailTbl->loadRecordCount($detailFilter);
                $body .= "&nbsp;" . str_replace("%c", Container("associated_records")->Count, $Language->phrase("DetailCount"));
            }
            $body = "<a class=\"btn btn-default ew-row-link ew-detail" . ($this->ListOptions->UseDropDownButton ? " dropdown-toggle" : "") . "\" data-action=\"list\" href=\"" . HtmlEncode("AssociatedRecordsList?" . Config("TABLE_SHOW_MASTER") . "=records&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("AssociatedRecordsGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'records')) {
                $caption = $Language->phrase("MasterDetailViewLink", null);
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=associated_records");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "associated_records";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'records')) {
                $caption = $Language->phrase("MasterDetailEditLink", null);
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=associated_records");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailEditTblVar != "") {
                    $detailEditTblVar .= ",";
                }
                $detailEditTblVar .= "associated_records";
            }
            if ($detailPage->DetailAdd && $Security->canAdd() && $Security->allowAdd(CurrentProjectID() . 'records')) {
                $caption = $Language->phrase("MasterDetailCopyLink", null);
                $url = $this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=associated_records");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
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
            $opt->Body = $body;
            if ($this->ShowMultipleDetails) {
                $opt->Visible = false;
            }
        }
        if ($this->ShowMultipleDetails) {
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">";
            $links = "";
            if ($detailViewTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailViewLink", true)) . "\" href=\"" . HtmlEncode($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailViewTblVar)) . "\">" . $Language->phrase("MasterDetailViewLink", null) . "</a></li>";
            }
            if ($detailEditTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailEditLink", true)) . "\" href=\"" . HtmlEncode($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailEditTblVar)) . "\">" . $Language->phrase("MasterDetailEditLink", null) . "</a></li>";
            }
            if ($detailCopyTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailCopyLink", true)) . "\" href=\"" . HtmlEncode($this->GetCopyUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailCopyTblVar)) . "\">" . $Language->phrase("MasterDetailCopyLink", null) . "</a></li>";
            }
            if ($links != "") {
                $body .= "<button type=\"button\" class=\"dropdown-toggle btn btn-default ew-master-detail\" title=\"" . HtmlEncode($Language->phrase("MultipleMasterDetails", true)) . "\" data-bs-toggle=\"dropdown\">" . $Language->phrase("MultipleMasterDetails") . "</button>";
                $body .= "<ul class=\"dropdown-menu ew-dropdown-menu\">" . $links . "</ul>";
            }
            $body .= "</div>";
            // Multiple details
            $opt = $this->ListOptions["details"];
            $opt->Body = $body;
        }

        // "checkbox"
        $opt = $this->ListOptions["checkbox"];
        $opt->Body = "<div class=\"form-check\"><input type=\"checkbox\" id=\"key_m_" . $this->RowCount . "\" name=\"key_m[]\" class=\"form-check-input ew-multi-select\" value=\"" . HtmlEncode($this->id->CurrentValue) . "\" data-ew-action=\"select-key\"></div>";
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Render list options (extensions)
    protected function renderListOptionsExt()
    {
        // Render list options (to be implemented by extensions)
        global $Security, $Language;
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["addedit"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("AddLink"));
        if ($this->ModalAdd && !IsMobile()) {
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-table=\"records\" data-caption=\"" . $addcaption . "\" data-ew-action=\"modal\" data-action=\"add\" data-ajax=\"" . ($this->UseAjaxActions ? "true" : "false") . "\" data-url=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("AddLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
        }
        $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        $option = $options["detail"];
        $detailTableLink = "";
                $item = &$option->add("detailadd_images");
                $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=images");
                $detailPage = Container("ImagesGrid");
                $caption = $Language->phrase("Add") . "&nbsp;" . $this->tableCaption() . "/" . $detailPage->tableCaption();
                $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
                $item->Visible = ($detailPage->DetailAdd && $Security->allowAdd(CurrentProjectID() . 'records') && $Security->canAdd());
                if ($item->Visible) {
                    if ($detailTableLink != "") {
                        $detailTableLink .= ",";
                    }
                    $detailTableLink .= "images";
                }
                $item = &$option->add("detailadd_documents");
                $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=documents");
                $detailPage = Container("DocumentsGrid");
                $caption = $Language->phrase("Add") . "&nbsp;" . $this->tableCaption() . "/" . $detailPage->tableCaption();
                $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
                $item->Visible = ($detailPage->DetailAdd && $Security->allowAdd(CurrentProjectID() . 'records') && $Security->canAdd());
                if ($item->Visible) {
                    if ($detailTableLink != "") {
                        $detailTableLink .= ",";
                    }
                    $detailTableLink .= "documents";
                }
                $item = &$option->add("detailadd_associated_records");
                $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=associated_records");
                $detailPage = Container("AssociatedRecordsGrid");
                $caption = $Language->phrase("Add") . "&nbsp;" . $this->tableCaption() . "/" . $detailPage->tableCaption();
                $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
                $item->Visible = ($detailPage->DetailAdd && $Security->allowAdd(CurrentProjectID() . 'records') && $Security->canAdd());
                if ($item->Visible) {
                    if ($detailTableLink != "") {
                        $detailTableLink .= ",";
                    }
                    $detailTableLink .= "associated_records";
                }

        // Add multiple details
        if ($this->ShowMultipleDetails) {
            $item = &$option->add("detailsadd");
            $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailTableLink);
            $caption = $Language->phrase("AddMasterDetailLink");
            $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
            $item->Visible = $detailTableLink != "" && $Security->canAdd();
            // Hide single master/detail items
            $ar = explode(",", $detailTableLink);
            $cnt = count($ar);
            for ($i = 0; $i < $cnt; $i++) {
                if ($item = $option["detailadd_" . $ar[$i]]) {
                    $item->Visible = false;
                }
            }
        }
        $option = $options["action"];

        // Show column list for column visibility
        if ($this->UseColumnVisibility) {
            $option = $this->OtherOptions["column"];
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = $this->UseColumnVisibility;
            $option->add("id", $this->createColumnOption("id"));
            $option->add("c_holder", $this->createColumnOption("c_holder"));
            $option->add("c_cates", $this->createColumnOption("c_cates"));
            $option->add("c_author", $this->createColumnOption("c_author"));
            $option->add("c_lang", $this->createColumnOption("c_lang"));
            $option->add("record_type", $this->createColumnOption("record_type"));
            $option->add("status", $this->createColumnOption("status"));
            $option->add("number_of_pages", $this->createColumnOption("number_of_pages"));
        }

        // Set up options default
        foreach ($options as $name => $option) {
            if ($name != "column") { // Always use dropdown for column
                $option->UseDropDownButton = false;
                $option->UseButtonGroup = true;
            }
            //$option->ButtonClass = ""; // Class for button group
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = false;
        }
        $options["addedit"]->DropDownButtonPhrase = $Language->phrase("ButtonAddEdit");
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $options["action"]->DropDownButtonPhrase = $Language->phrase("ButtonActions");

        // Filter button
        $item = &$this->FilterOptions->add("savecurrentfilter");
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"frecordssrch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"frecordssrch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
        $item->Visible = true;
        $this->FilterOptions->UseDropDownButton = true;
        $this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
        $this->FilterOptions->DropDownButtonPhrase = $Language->phrase("Filters");

        // Add group option item
        $item = &$this->FilterOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    // Create new column option
    public function createColumnOption($name)
    {
        $field = $this->Fields[$name] ?? false;
        if ($field && $field->Visible) {
            $item = new ListOption($field->Name);
            $item->Body = '<button class="dropdown-item">' .
                '<div class="form-check ew-dropdown-checkbox">' .
                '<div class="form-check-input ew-dropdown-check-input" data-field="' . $field->Param . '"></div>' .
                '<label class="form-check-label ew-dropdown-check-label">' . $field->caption() . '</label></div></button>';
            return $item;
        }
        return null;
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["action"];
        // Set up list action buttons
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Select == ACTION_MULTIPLE) {
                $item = &$option->add("custom_" . $listaction->Action);
                $caption = $listaction->Caption;
                $icon = ($listaction->Icon != "") ? '<i class="' . HtmlEncode($listaction->Icon) . '" data-caption="' . HtmlEncode($caption) . '"></i>' . $caption : $caption;
                $item->Body = '<button type="button" class="btn btn-default ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" data-ew-action="submit" form="frecordslist"' . $listaction->toDataAttrs() . '>' . $icon . '</button>';
                $item->Visible = $listaction->Allow;
            }
        }

        // Hide multi edit, grid edit and other options
        if ($this->TotalRecords <= 0) {
            $option = $options["addedit"];
            $item = $option["gridedit"];
            if ($item) {
                $item->Visible = false;
            }
            $option = $options["action"];
            $option->hideAllOptions();
        }
    }

    // Process list action
    protected function processListAction()
    {
        global $Language, $Security, $Response;
        $userlist = "";
        $user = "";
        $filter = $this->getFilterFromRecordKeys();
        $userAction = Post("action", "");
        if ($filter != "" && $userAction != "") {
            // Clear current action
            $this->CurrentAction = "";
            $this->UserAction = $userAction;
            // Check permission first
            $actionCaption = $userAction;
            if (array_key_exists($userAction, $this->ListActions->Items)) {
                $actionCaption = $this->ListActions[$userAction]->Caption;
                if (!$this->ListActions[$userAction]->Allow) {
                    $errmsg = str_replace('%s', $actionCaption, $Language->phrase("CustomActionNotAllowed"));
                    if (Post("ajax") == $userAction) { // Ajax
                        echo "<p class=\"text-danger\">" . $errmsg . "</p>";
                        return true;
                    } else {
                        $this->setFailureMessage($errmsg);
                        return false;
                    }
                }
            }
            $this->CurrentFilter = $filter;
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rows = ExecuteRows($sql, $conn);
            $count = count($rows);
            $this->ActionValue = Post("actionvalue");

            // Call row action event
            if ($count > 0) {
                if ($this->UseTransaction) {
                    $conn->beginTransaction();
                }
                $this->SelectedCount = $count;
                $this->SelectedIndex = 0;
                foreach ($rows as $row) {
                    $this->SelectedIndex++;
                    $processed = $this->rowCustomAction($userAction, $row);
                    if (!$processed) {
                        break;
                    }
                }
                if ($processed) {
                    if ($this->UseTransaction) { // Commit transaction
                        $conn->commit();
                    }
                    if ($this->getSuccessMessage() == "" && !ob_get_length() && !$Response->getBody()->getSize()) { // No output
                        $this->setSuccessMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionCompleted"))); // Set up success message
                    }
                } else {
                    if ($this->UseTransaction) { // Rollback transaction
                        $conn->rollback();
                    }

                    // Set up error message
                    if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                        // Use the message, do nothing
                    } elseif ($this->CancelMessage != "") {
                        $this->setFailureMessage($this->CancelMessage);
                        $this->CancelMessage = "";
                    } else {
                        $this->setFailureMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionFailed")));
                    }
                }
            }
            if (Post("ajax") == $userAction) { // Ajax
                if ($this->getSuccessMessage() != "") {
                    echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
                    $this->clearSuccessMessage(); // Clear message
                }
                if ($this->getFailureMessage() != "") {
                    echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
                    $this->clearFailureMessage(); // Clear message
                }
                return true;
            }
        }
        return false; // Not ajax request
    }

    // Set up Grid
    public function setupGrid()
    {
        global $CurrentForm;
        if ($this->ExportAll && $this->isExport()) {
            $this->StopRecord = $this->TotalRecords;
        } else {
            // Set the last record to display
            if ($this->TotalRecords > $this->StartRecord + $this->DisplayRecords - 1) {
                $this->StopRecord = $this->StartRecord + $this->DisplayRecords - 1;
            } else {
                $this->StopRecord = $this->TotalRecords;
            }
        }
        $this->RecordCount = $this->StartRecord - 1;
        if ($this->Recordset && !$this->Recordset->EOF) {
            // Nothing to do
        } elseif ($this->isGridAdd() && !$this->AllowAddDeleteRow && $this->StopRecord == 0) { // Grid-Add with no records
            $this->StopRecord = $this->GridAddRowCount;
        } elseif ($this->isAdd() && $this->TotalRecords == 0) { // Inline-Add with no records
            $this->StopRecord = 1;
        }

        // Initialize aggregate
        $this->RowType = ROWTYPE_AGGREGATEINIT;
        $this->resetAttributes();
        $this->renderRow();
        if (($this->isGridAdd() || $this->isGridEdit())) { // Render template row first
            $this->RowIndex = '$rowindex$';
        }
    }

    // Set up Row
    public function setupRow()
    {
        global $CurrentForm;
        if ($this->isGridAdd() || $this->isGridEdit()) {
            if ($this->RowIndex === '$rowindex$') { // Render template row first
                $this->loadRowValues();

                // Set row properties
                $this->resetAttributes();
                $this->RowAttrs->merge(["data-rowindex" => $this->RowIndex, "id" => "r0_records", "data-rowtype" => ROWTYPE_ADD]);
                $this->RowAttrs->appendClass("ew-template");
                // Render row
                $this->RowType = ROWTYPE_ADD;
                $this->renderRow();

                // Render list options
                $this->renderListOptions();

                // Reset record count for template row
                $this->RecordCount--;
                return;
            }
        }

        // Set up key count
        $this->KeyCount = $this->RowIndex;

        // Init row class and style
        $this->resetAttributes();
        $this->CssClass = "";
        if ($this->isCopy() && $this->InlineRowCount == 0 && !$this->loadRow()) { // Inline copy
            $this->CurrentAction = "add";
        }
        if ($this->isAdd() && $this->InlineRowCount == 0 || $this->isGridAdd()) {
            $this->loadRowValues(); // Load default values
            $this->OldKey = "";
            $this->setKey($this->OldKey);
        } elseif ($this->isInlineInserted() && $this->UseInfiniteScroll) {
            // Nothing to do, just use current values
        } elseif (!($this->isCopy() && $this->InlineRowCount == 0)) {
            $this->loadRowValues($this->Recordset); // Load row values
            if ($this->isGridEdit() || $this->isMultiEdit()) {
                $this->OldKey = $this->getKey(true); // Get from CurrentValue
                $this->setKey($this->OldKey);
            }
        }
        $this->RowType = ROWTYPE_VIEW; // Render view
        if (($this->isAdd() || $this->isCopy()) && $this->InlineRowCount == 0 || $this->isGridAdd()) { // Add
            $this->RowType = ROWTYPE_ADD; // Render add
        }

        // Inline Add/Copy row (row 0)
        if ($this->RowType == ROWTYPE_ADD && ($this->isAdd() || $this->isCopy())) {
            $this->InlineRowCount++;
            $this->RecordCount--; // Reset record count for inline add/copy row
            if ($this->TotalRecords == 0) { // Reset stop record if no records
                $this->StopRecord = 0;
            }
        } else {
            // Inline Edit row
            if ($this->RowType == ROWTYPE_EDIT && $this->isEdit()) {
                $this->InlineRowCount++;
            }
            $this->RowCount++; // Increment row count
        }

        // Set up row attributes
        $this->RowAttrs->merge([
            "data-rowindex" => $this->RowCount,
            "data-key" => $this->getKey(true),
            "id" => "r" . $this->RowCount . "_records",
            "data-rowtype" => $this->RowType,
            "data-inline" => ($this->isAdd() || $this->isCopy() || $this->isEdit()) ? "true" : "false", // Inline-Add/Copy/Edit
            "class" => ($this->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($this->isAdd() && $this->RowType == ROWTYPE_ADD || $this->isEdit() && $this->RowType == ROWTYPE_EDIT) { // Inline-Add/Edit row
            $this->RowAttrs->appendClass("table-active");
        }

        // Render row
        $this->renderRow();

        // Render list options
        $this->renderListOptions();
    }

    // Load basic search values
    protected function loadBasicSearchValues()
    {
        $this->BasicSearch->setKeyword(Get(Config("TABLE_BASIC_SEARCH"), ""), false);
        if ($this->BasicSearch->Keyword != "" && $this->Command == "") {
            $this->Command = "search";
        }
        $this->BasicSearch->setType(Get(Config("TABLE_BASIC_SEARCH_TYPE"), ""), false);
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;

        // Load query builder rules
        $rules = Post("rules");
        if ($rules && $this->Command == "") {
            $this->QueryRules = $rules;
            $this->Command = "search";
        }

        // id
        if ($this->id->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->id->AdvancedSearch->SearchValue != "" || $this->id->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // owner
        if ($this->owner->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->owner->AdvancedSearch->SearchValue != "" || $this->owner->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // c_holder
        if ($this->c_holder->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->c_holder->AdvancedSearch->SearchValue != "" || $this->c_holder->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // current_country
        if ($this->current_country->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->current_country->AdvancedSearch->SearchValue != "" || $this->current_country->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // author
        if ($this->author->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->author->AdvancedSearch->SearchValue != "" || $this->author->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // sender
        if ($this->sender->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->sender->AdvancedSearch->SearchValue != "" || $this->sender->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // receiver
        if ($this->receiver->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->receiver->AdvancedSearch->SearchValue != "" || $this->receiver->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // origin_region
        if ($this->origin_region->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->origin_region->AdvancedSearch->SearchValue != "" || $this->origin_region->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // origin_city
        if ($this->origin_city->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->origin_city->AdvancedSearch->SearchValue != "" || $this->origin_city->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // start_year
        if ($this->start_year->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->start_year->AdvancedSearch->SearchValue != "" || $this->start_year->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // start_month
        if ($this->start_month->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->start_month->AdvancedSearch->SearchValue != "" || $this->start_month->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // start_day
        if ($this->start_day->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->start_day->AdvancedSearch->SearchValue != "" || $this->start_day->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // end_year
        if ($this->end_year->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->end_year->AdvancedSearch->SearchValue != "" || $this->end_year->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // end_month
        if ($this->end_month->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->end_month->AdvancedSearch->SearchValue != "" || $this->end_month->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // end_day
        if ($this->end_day->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->end_day->AdvancedSearch->SearchValue != "" || $this->end_day->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // record_type
        if ($this->record_type->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->record_type->AdvancedSearch->SearchValue != "" || $this->record_type->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // status
        if ($this->status->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->status->AdvancedSearch->SearchValue != "" || $this->status->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cipher_type_other
        if ($this->cipher_type_other->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cipher_type_other->AdvancedSearch->SearchValue != "" || $this->cipher_type_other->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // symbol_set_other
        if ($this->symbol_set_other->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->symbol_set_other->AdvancedSearch->SearchValue != "" || $this->symbol_set_other->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // inline_cleartext
        if ($this->inline_cleartext->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->inline_cleartext->AdvancedSearch->SearchValue != "" || $this->inline_cleartext->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // inline_plaintext
        if ($this->inline_plaintext->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->inline_plaintext->AdvancedSearch->SearchValue != "" || $this->inline_plaintext->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // cleartext_lang
        if ($this->cleartext_lang->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->cleartext_lang->AdvancedSearch->SearchValue != "" || $this->cleartext_lang->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // plaintext_lang
        if ($this->plaintext_lang->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->plaintext_lang->AdvancedSearch->SearchValue != "" || $this->plaintext_lang->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // document_types
        if ($this->document_types->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->document_types->AdvancedSearch->SearchValue != "" || $this->document_types->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
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
            if (($this->paper->AdvancedSearch->SearchValue != "" || $this->paper->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // additional_information
        if ($this->additional_information->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->additional_information->AdvancedSearch->SearchValue != "" || $this->additional_information->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // creator_id
        if ($this->creator_id->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->creator_id->AdvancedSearch->SearchValue != "" || $this->creator_id->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // creation_date
        if ($this->creation_date->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->creation_date->AdvancedSearch->SearchValue != "" || $this->creation_date->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_encoded_plaintext_type
        if ($this->km_encoded_plaintext_type->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_encoded_plaintext_type->AdvancedSearch->SearchValue != "" || $this->km_encoded_plaintext_type->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
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
            if (($this->km_numbers->AdvancedSearch->SearchValue != "" || $this->km_numbers->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_content_words
        if ($this->km_content_words->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_content_words->AdvancedSearch->SearchValue != "" || $this->km_content_words->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_function_words
        if ($this->km_function_words->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_function_words->AdvancedSearch->SearchValue != "" || $this->km_function_words->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_syllables
        if ($this->km_syllables->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_syllables->AdvancedSearch->SearchValue != "" || $this->km_syllables->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_morphological_endings
        if ($this->km_morphological_endings->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_morphological_endings->AdvancedSearch->SearchValue != "" || $this->km_morphological_endings->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_phrases
        if ($this->km_phrases->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_phrases->AdvancedSearch->SearchValue != "" || $this->km_phrases->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_sentences
        if ($this->km_sentences->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_sentences->AdvancedSearch->SearchValue != "" || $this->km_sentences->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_punctuation
        if ($this->km_punctuation->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_punctuation->AdvancedSearch->SearchValue != "" || $this->km_punctuation->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_nomenclature_size
        if ($this->km_nomenclature_size->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_nomenclature_size->AdvancedSearch->SearchValue != "" || $this->km_nomenclature_size->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_sections
        if ($this->km_sections->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_sections->AdvancedSearch->SearchValue != "" || $this->km_sections->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_headings
        if ($this->km_headings->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_headings->AdvancedSearch->SearchValue != "" || $this->km_headings->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_plaintext_arrangement
        if ($this->km_plaintext_arrangement->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_plaintext_arrangement->AdvancedSearch->SearchValue != "" || $this->km_plaintext_arrangement->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
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
            if (($this->km_ciphertext_arrangement->AdvancedSearch->SearchValue != "" || $this->km_ciphertext_arrangement->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
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
            if (($this->km_memorability->AdvancedSearch->SearchValue != "" || $this->km_memorability->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_symbol_set
        if ($this->km_symbol_set->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_symbol_set->AdvancedSearch->SearchValue != "" || $this->km_symbol_set->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
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
            if (($this->km_diacritics->AdvancedSearch->SearchValue != "" || $this->km_diacritics->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_code_length
        if ($this->km_code_length->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_code_length->AdvancedSearch->SearchValue != "" || $this->km_code_length->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
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
            if (($this->km_code_type->AdvancedSearch->SearchValue != "" || $this->km_code_type->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
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
            if (($this->km_metaphors->AdvancedSearch->SearchValue != "" || $this->km_metaphors->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // km_material_properties
        if ($this->km_material_properties->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->km_material_properties->AdvancedSearch->SearchValue != "" || $this->km_material_properties->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
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
            if (($this->km_instructions->AdvancedSearch->SearchValue != "" || $this->km_instructions->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }
        return $hasValue;
    }

    // Load recordset
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->execute();
        $rs = new Recordset($result, $sql);

        // Call Recordset Selected event
        $this->recordsetSelected($rs);
        return $rs;
    }

    // Load records as associative array
    public function loadRows($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->execute();
        return $result->fetchAllAssociative();
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

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        if ($this->OldKey != "") {
            $this->setKey($this->OldKey);
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = LoadRecordset($sql, $conn);
            if ($rs && ($row = $rs->fields)) {
                $this->loadRowValues($row); // Load row values
                return $row;
            }
        }
        $this->loadRowValues(); // Load default row values
        return null;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->InlineEditUrl = $this->getInlineEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->InlineCopyUrl = $this->getInlineCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

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

            // record_type
            $this->record_type->HrefValue = "";
            $this->record_type->TooltipValue = "";

            // status
            $this->status->HrefValue = "";
            $this->status->TooltipValue = "";

            // number_of_pages
            $this->number_of_pages->HrefValue = "";
            $this->number_of_pages->TooltipValue = "";
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

    /**
     * Import file
     *
     * @param string $filetoken File token to locate the uploaded import file
     * @param bool $rollback Try import and then rollback
     * @return bool
     */
    public function import($filetoken, $rollback = false)
    {
        global $Security, $Language;
        if (!$Security->canImport()) {
            return false; // Import not allowed
        }

        // Check if valid token
        if (EmptyValue($filetoken)) {
            return false;
        }

        // Get uploaded files by token
        $files = GetUploadedFileNames($filetoken);
        $exts = explode(",", Config("IMPORT_FILE_ALLOWED_EXTENSIONS"));
        $result = [Config("API_FILE_TOKEN_NAME") => $filetoken, "files" => []];

        // Set header
        if (ob_get_length()) {
            ob_clean();
        }
        header("Cache-Control: no-store");
        header("Content-Type: text/event-stream");

        // Import records
        try {
            foreach ($files as $file) {
                $res = ["file" => basename($file)];
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                // Ignore log file
                if ($ext == "txt") {
                    continue;
                }

                // Check file extension
                if (!in_array($ext, $exts)) {
                    $res = array_merge($res, ["error" => str_replace("%e", $ext, $Language->phrase("ImportInvalidFileExtension"))]);
                    SendEvent($res, "error");
                    return false;
                }

                // Set up options
                $options = [
                    "file" => $file,
                    "inputEncoding" => "", // For CSV only
                    "delimiter" => ",", // For CSV only
                    "enclosure" => "\"", // For CSV only
                    "escape" => "\\", // For CSV only
                    "activeSheet" => null, // For PhpSpreadsheet only
                    "readOnly" => true, // For PhpSpreadsheet only
                    "maxRows" => null, // For PhpSpreadsheet only
                    "headerRowNumber" => 0,
                    "headers" => [],
                    "offset" => 0,
                    "limit" => null,
                ];
                foreach ($_GET as $key => $value) {
                    if (!in_array($key, [Config("API_ACTION_NAME"), Config("API_FILE_TOKEN_NAME")])) {
                        $options[$key] = $value;
                    }
                }

                // Workflow builder
                $builder = fn($workflow) => $workflow;

                // Call Page Importing server event
                if (!$this->pageImporting($builder, $options)) {
                    SendEvent($res, "error");
                    return false;
                }

                // Set max execution time
                if (Config("IMPORT_MAX_EXECUTION_TIME") > 0) {
                    ini_set("max_execution_time", Config("IMPORT_MAX_EXECUTION_TIME"));
                }

                // Reader
                try {
                    if ($ext == "csv") {
                        $csv = file_get_contents($file);
                        if ($csv !== false) {
                            if (StartsString("\xEF\xBB\xBF", $csv)) { // UTF-8 BOM
                                $csv = substr($csv, 3);
                            } elseif ($options["inputEncoding"] != "" && !SameText($options["inputEncoding"], "UTF-8")) {
                                $csv = Convert($options["inputEncoding"], "UTF-8", $csv);
                            }
                            file_put_contents($file, $csv);
                        }
                        $reader = new \Port\Csv\CsvReader(new \SplFileObject($file), $options["delimiter"], $options["enclosure"], $options["escape"]);
                    } else {
                        $reader = new \Port\Spreadsheet\SpreadsheetReader(new \SplFileObject($file), $options["headerRowNumber"], $options["activeSheet"], $options["readOnly"], $options["maxRows"]);
                    }
                    if (is_array($options["headers"]) && count($options["headers"]) > 0) {
                        $reader->setColumnHeaders($options["headers"]);
                    } elseif (is_int($options["headerRowNumber"])) {
                        $reader->setHeaderRowNumber($options["headerRowNumber"]);
                    }
                } catch (\Exception $e) {
                    $res = array_merge($res, ["error" => $e->getMessage()]);
                    SendEvent($res, "error");
                    return false;
                }

                // Column headers
                $headers = $reader->getColumnHeaders();
                if (count($headers) == 0) { // Missing headers
                    $res["error"] = $Language->phrase("ImportNoHeaderRow");
                    SendEvent($res, "error");
                    return false;
                }

                // Counts
                $recordCnt = $reader->count();
                if ($options["offset"] > 0) {
                    $recordCnt -= $options["offset"];
                    if ($options["limit"] > 0) {
                        $recordCnt = min($recordCnt, $options["limit"]);
                    }
                    if ($recordCnt < 0) {
                        $recordCnt = 0;
                    }
                }
                $cnt = 0;
                $successCnt = 0;
                $failCnt = 0;
                $res = array_merge($res, ["totalCount" => $recordCnt, "count" => $cnt, "successCount" => 0, "failCount" => 0]);

                // Writer
                $writer = new \Port\Writer\CallbackWriter(function ($row) use (&$res, &$cnt, &$successCnt, &$failCnt) {
                    try {
                        $success = $this->importRow($row, ++$cnt); // Import row
                        $err = "";
                        if ($success) {
                            $successCnt++;
                        } else {
                            if (!EmptyValue($this->DbErrorMessage)) {
                                $err = $this->DbErrorMessage;
                                Log("import error for record " . $cnt . ": " . $err); // Log error to log file
                            }
                            $failCnt++;
                        }
                    } catch (\Port\Exception $e) { // Catch exception so the workflow continues
                        $failCnt++;
                        $err = $e->getMessage();
                        Log("import error for record " . $cnt . ": " . $err); // Log error to log file
                        if ($failCnt > $this->ImportMaxFailures) {
                            throw $e; // Throw \Port\Exception to terminate the workflow
                        }
                    } finally {
                        $res = array_merge($res, [
                            "row" => $row, // Current row
                            "success" => $success, // For current row
                            "error" => $err, // For current row
                            "count" => $cnt,
                            "successCount" => $successCnt,
                            "failCount" => $failCnt
                        ]);
                        $this->clearMessages();
                        SendEvent($res);
                    }
                });

                // Connection
                $conn = $this->getConnection();

                // Begin transaction
                if ($this->ImportUseTransaction) {
                    $conn->beginTransaction();
                }

                // Workflow
                $workflow = new \Port\Steps\StepAggregator($reader);
                $workflow->setLogger(Logger());
                $workflow->setSkipItemOnFailure(false); // Stop on exception
                $workflow = $builder($workflow);

                // Filter step
                $step = new \Port\Steps\Step\FilterStep();
                $step->add(new \Port\Filter\OffsetFilter($options["offset"], $options["limit"]));
                try {
                    $info = @$workflow->addWriter($writer)->addStep($step)->process();
                } finally {
                    // Rollback transaction
                    if ($this->ImportUseTransaction) {
                        if ($rollback || $failCnt > $this->ImportMaxFailures) {
                            $res["rollbacked"] = $conn->rollback();
                        } else {
                            $conn->commit();
                        }
                    }
                    unset($res["row"], $res["error"]); // Remove current row info
                    $res["success"] = $cnt > 0 && $failCnt <= $this->ImportMaxFailures; // Set success status of current file
                    SendEvent($res); // Current file imported
                    $result["files"][] = $res;

                    // Call Page Imported server event
                    $this->pageImported($info, $res);
                }
            }
        } finally {
            $result["failCount"] = array_reduce($result["files"], fn($carry, $item) => $carry + $item["failCount"], 0); // For client side
            $result["success"] = array_reduce($result["files"], fn($carry, $item) => $carry && $item["success"], true); // All files successful
            $result["rollbacked"] = array_reduce($result["files"], fn($carry, $item) => $carry && $item["success"] && ($item["rollbacked"] ?? false), true); // All file rollbacked successfully
            if ($result["success"] && !$result["rollbacked"]) {
                CleanUploadTempPaths($filetoken);
            }
            SendEvent($result, "complete"); // All files imported
            return $result["success"];
        }
    }

    /**
     * Import a row
     *
     * @param array $row Row to be imported
     * @param int $cnt Index of the row (1-based)
     * @return bool
     */
    protected function importRow(&$row, $cnt)
    {
        global $Language;

        // Call Row Import server event
        if (!$this->rowImport($row, $cnt)) {
            return false;
        }

        // Check field names and values
        foreach ($row as $name => $value) {
            $fld = $this->Fields[$name];
            if (!$fld) {
                throw new \Port\Exception\UnexpectedValueException(str_replace("%f", $name, $Language->phrase("ImportInvalidFieldName")));
            }
            if (!$this->checkValue($fld, $value)) {
                throw new \Port\Exception\UnexpectedValueException(str_replace(["%f", "%v"], [$name, $value], $Language->phrase("ImportInvalidFieldValue")));
            }
        }

        // Insert/Update to database
        $res = false;
        if (!$this->ImportInsertOnly && $oldrow = $this->load($row)) {
            if (!method_exists($this, "rowUpdating") || $this->rowUpdating($oldrow, $row)) {
                if ($res = $this->update($row, "", $oldrow)) {
                    if (method_exists($this, "rowUpdated")) {
                        $this->rowUpdated($oldrow, $row);
                    }
                }
            }
        } else {
            if (!method_exists($this, "rowInserting") || $this->rowInserting(null, $row)) {
                if ($res = $this->insert($row)) {
                    if (method_exists($this, "rowInserted")) {
                        $this->rowInserted(null, $row);
                    }
                }
            }
        }
        return $res;
    }

    /**
     * Check field value
     *
     * @param object $fld Field object
     * @param object $value
     * @return bool
     */
    protected function checkValue($fld, $value)
    {
        if ($fld->DataType == DATATYPE_NUMBER && !is_numeric($value)) {
            return false;
        } elseif ($fld->DataType == DATATYPE_DATE && !CheckDate($value, $fld->formatPattern())) {
            return false;
        }
        return true;
    }

    // Load row
    protected function load($row)
    {
        $filter = $this->getRecordFilter($row);
        if (!$filter) {
            return null;
        }
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        return $conn->fetchAssociative($sql);
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

    // Set up search options
    protected function setupSearchOptions()
    {
        global $Language, $Security;
        $pageUrl = $this->pageUrl(false);
        $this->SearchOptions = new ListOptions(["TagClassName" => "ew-search-option"]);

        // Search button
        $item = &$this->SearchOptions->add("searchtoggle");
        $searchToggleClass = ($this->SearchWhere != "") ? " active" : " active";
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"frecordssrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        if ($this->UseCustomTemplate || !$this->UseAjaxActions) {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        } else {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" data-ew-action=\"refresh\" data-url=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        }
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

        // Advanced search button
        $item = &$this->SearchOptions->add("advancedsearch");
        if ($this->ModalSearch && !IsMobile()) {
            $item->Body = "<a class=\"btn btn-default ew-advanced-search\" title=\"" . $Language->phrase("AdvancedSearch", true) . "\" data-table=\"records\" data-caption=\"" . $Language->phrase("AdvancedSearch", true) . "\" data-ew-action=\"modal\" data-url=\"RecordsSearch\" data-btn=\"SearchBtn\">" . $Language->phrase("AdvancedSearch", false) . "</a>";
        } else {
            $item->Body = "<a class=\"btn btn-default ew-advanced-search\" title=\"" . $Language->phrase("AdvancedSearch", true) . "\" data-caption=\"" . $Language->phrase("AdvancedSearch", true) . "\" href=\"RecordsSearch\">" . $Language->phrase("AdvancedSearch", false) . "</a>";
        }
        $item->Visible = true;

        // Button group for search
        $this->SearchOptions->UseDropDownButton = false;
        $this->SearchOptions->UseButtonGroup = true;
        $this->SearchOptions->DropDownButtonPhrase = $Language->phrase("ButtonSearch");

        // Add group option item
        $item = &$this->SearchOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Hide search options
        if ($this->isExport() || $this->CurrentAction && $this->CurrentAction != "search") {
            $this->SearchOptions->hideAllOptions();
        }
        if (!$Security->canSearch()) {
            $this->SearchOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
        }
    }

    // Check if any search fields
    public function hasSearchFields()
    {
        return true;
    }

    // Render search options
    protected function renderSearchOptions()
    {
        if (!$this->hasSearchFields() && $this->SearchOptions["searchtoggle"]) {
            $this->SearchOptions["searchtoggle"]->Visible = false;
        }
    }

    // Set up import options
    protected function setupImportOptions()
    {
        global $Security, $Language;

        // Import
        $item = &$this->ImportOptions->add("import");
        $item->Body = "<a class=\"ew-import-link ew-import\" role=\"button\" title=\"" . $Language->phrase("Import", true) . "\" data-caption=\"" . $Language->phrase("Import", true) . "\" data-ew-action=\"import\" data-hdr=\"" . $Language->phrase("Import", true) . "\">" . $Language->phrase("Import") . "</a>";
        $item->Visible = $Security->canImport();
        $this->ImportOptions->UseButtonGroup = true;
        $this->ImportOptions->UseDropDownButton = false;
        $this->ImportOptions->DropDownButtonPhrase = $Language->phrase("Import");

        // Add group option item
        $item = &$this->ImportOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("RecordsList");
        $url = CurrentUrl();
        $url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset(all)
        $Breadcrumb->add("list", $this->TableVar, $url, "", $this->TableVar, true);
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
        $infiniteScroll = ConvertToBool(Param("infinitescroll"));
        if ($pageNo !== null) { // Check for "pageno" parameter first
            $pageNo = ParseInteger($pageNo);
            if (is_numeric($pageNo)) {
                $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                if ($this->StartRecord <= 0) {
                    $this->StartRecord = 1;
                } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                    $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                }
            }
        } elseif ($startRec !== null && is_numeric($startRec)) { // Check for "start" parameter
            $this->StartRecord = $startRec;
        } elseif (!$infiniteScroll) {
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
        // Example:
        $header = "<span style='widht:100%;text-align:center;'>If you are using the DECODE database in your research, please cite the papers in the footer!</span>";
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

    // ListOptions Load event
    public function listOptionsLoad()
    {
        $this->ListOptions->Items["edit"]->Visible = false;
        $this->ListOptions->Items["delete"]->Visible = false;
    //    $this->ListOptions->Items["detail_images"]->Visible = false;
    //    $this->ListOptions->Items["detail_documents"]->Visible = false;
        $this->ListOptions->Items["detail_associated_records"]->Visible = false;
        $this->ListOptions->Items["view"]->moveTo(0);
        $this->ListOptions->Items["view"]->OnLeft = true;

     //   $item = &$this->ListOptions->add("Details");
     //   $item->Header = "Details"; 
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered()
    {
        $this->ListOptions["details"]->Body = $this->ListOptions["detail_images"]->Body;
    }

    // Row Custom Action event
    public function rowCustomAction($action, $row)
    {
        // Return false to abort
        return true;
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

    // Page Importing event
    public function pageImporting(&$builder, &$options)
    {
        //var_dump($options); // Show all options for importing
        //$builder = fn($workflow) => $workflow->addStep($myStep);
        //return false; // Return false to skip import
        return true;
    }

    // Row Import event
    public function rowImport(&$row, $cnt)
    {
        //Log($cnt); // Import record count
        //var_dump($row); // Import row
        //return false; // Return false to skip import
        return true;
    }

    // Page Imported event
    public function pageImported($obj, $results)
    {
        //var_dump($obj); // Workflow result object
        //var_dump($results); // Import results
    }
}
