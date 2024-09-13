<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class RecordviewList extends Recordview
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "RecordviewList";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "frecordviewlist";
    public $FormActionName = "";
    public $FormBlankRowName = "";
    public $FormKeyCountName = "";

    // CSS class/style
    public $CurrentPageName = "RecordviewList";

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
        $this->owner_id->Visible = false;
        $this->owner->setVisibility();
        $this->record_group_id->Visible = false;
        $this->start_year->setVisibility();
        $this->start_month->setVisibility();
        $this->start_day->setVisibility();
        $this->end_year->setVisibility();
        $this->end_month->setVisibility();
        $this->end_day->setVisibility();
        $this->creator_id->setVisibility();
        $this->inline_cleartext->setVisibility();
        $this->inline_plaintext->setVisibility();
        $this->current_country->setVisibility();
        $this->current_city->setVisibility();
        $this->current_holder->setVisibility();
        $this->additional_information->Visible = false;
        $this->number_of_pages->setVisibility();
        $this->cleartext_lang->setVisibility();
        $this->plaintext_lang->setVisibility();
        $this->author->Visible = false;
        $this->sender->Visible = false;
        $this->receiver->Visible = false;
        $this->origin_region->setVisibility();
        $this->origin_city->setVisibility();
        $this->paper->setVisibility();
        $this->creation_date->setVisibility();
        $this->private_ciphertext->setVisibility();
        $this->cipher_types->setVisibility();
        $this->symbol_sets->setVisibility();
        $this->cipher_type_other->setVisibility();
        $this->symbol_set_other->setVisibility();
        $this->status->setVisibility();
        $this->record_type->setVisibility();
        $this->access_mode->setVisibility();
        $this->transc_files->Visible = false;
        $this->link->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->FormActionName = Config("FORM_ROW_ACTION_NAME");
        $this->FormBlankRowName = Config("FORM_BLANK_ROW_NAME");
        $this->FormKeyCountName = Config("FORM_KEY_COUNT_NAME");
        $this->TableVar = 'recordview';
        $this->TableName = 'recordview';

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

        // Table object (recordview)
        if (!isset($GLOBALS["recordview"]) || get_class($GLOBALS["recordview"]) == PROJECT_NAMESPACE . "recordview") {
            $GLOBALS["recordview"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs
        $this->AddUrl = "RecordviewAdd";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiEditUrl = $pageUrl . "action=multiedit";
        $this->MultiDeleteUrl = "RecordviewDelete";
        $this->MultiUpdateUrl = "RecordviewUpdate";

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
        $this->setupLookupOptions($this->owner_id);
        $this->setupLookupOptions($this->creator_id);
        $this->setupLookupOptions($this->inline_cleartext);
        $this->setupLookupOptions($this->inline_plaintext);
        $this->setupLookupOptions($this->private_ciphertext);
        $this->setupLookupOptions($this->record_type);

        // Update form name to avoid conflict
        if ($this->IsModal) {
            $this->FormName = "frecordviewgrid";
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

        // Get basic search values
        $this->loadBasicSearchValues();

        // Process filter list
        if ($this->processFilterList()) {
            $this->terminate();
            return;
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
        $filterList = Concat($filterList, $this->name->AdvancedSearch->toJson(), ","); // Field name
        $filterList = Concat($filterList, $this->owner_id->AdvancedSearch->toJson(), ","); // Field owner_id
        $filterList = Concat($filterList, $this->owner->AdvancedSearch->toJson(), ","); // Field owner
        $filterList = Concat($filterList, $this->record_group_id->AdvancedSearch->toJson(), ","); // Field record_group_id
        $filterList = Concat($filterList, $this->start_year->AdvancedSearch->toJson(), ","); // Field start_year
        $filterList = Concat($filterList, $this->start_month->AdvancedSearch->toJson(), ","); // Field start_month
        $filterList = Concat($filterList, $this->start_day->AdvancedSearch->toJson(), ","); // Field start_day
        $filterList = Concat($filterList, $this->end_year->AdvancedSearch->toJson(), ","); // Field end_year
        $filterList = Concat($filterList, $this->end_month->AdvancedSearch->toJson(), ","); // Field end_month
        $filterList = Concat($filterList, $this->end_day->AdvancedSearch->toJson(), ","); // Field end_day
        $filterList = Concat($filterList, $this->creator_id->AdvancedSearch->toJson(), ","); // Field creator_id
        $filterList = Concat($filterList, $this->inline_cleartext->AdvancedSearch->toJson(), ","); // Field inline_cleartext
        $filterList = Concat($filterList, $this->inline_plaintext->AdvancedSearch->toJson(), ","); // Field inline_plaintext
        $filterList = Concat($filterList, $this->current_country->AdvancedSearch->toJson(), ","); // Field current_country
        $filterList = Concat($filterList, $this->current_city->AdvancedSearch->toJson(), ","); // Field current_city
        $filterList = Concat($filterList, $this->current_holder->AdvancedSearch->toJson(), ","); // Field current_holder
        $filterList = Concat($filterList, $this->additional_information->AdvancedSearch->toJson(), ","); // Field additional_information
        $filterList = Concat($filterList, $this->number_of_pages->AdvancedSearch->toJson(), ","); // Field number_of_pages
        $filterList = Concat($filterList, $this->cleartext_lang->AdvancedSearch->toJson(), ","); // Field cleartext_lang
        $filterList = Concat($filterList, $this->plaintext_lang->AdvancedSearch->toJson(), ","); // Field plaintext_lang
        $filterList = Concat($filterList, $this->author->AdvancedSearch->toJson(), ","); // Field author
        $filterList = Concat($filterList, $this->sender->AdvancedSearch->toJson(), ","); // Field sender
        $filterList = Concat($filterList, $this->receiver->AdvancedSearch->toJson(), ","); // Field receiver
        $filterList = Concat($filterList, $this->origin_region->AdvancedSearch->toJson(), ","); // Field origin_region
        $filterList = Concat($filterList, $this->origin_city->AdvancedSearch->toJson(), ","); // Field origin_city
        $filterList = Concat($filterList, $this->paper->AdvancedSearch->toJson(), ","); // Field paper
        $filterList = Concat($filterList, $this->creation_date->AdvancedSearch->toJson(), ","); // Field creation_date
        $filterList = Concat($filterList, $this->private_ciphertext->AdvancedSearch->toJson(), ","); // Field private_ciphertext
        $filterList = Concat($filterList, $this->cipher_types->AdvancedSearch->toJson(), ","); // Field cipher_types
        $filterList = Concat($filterList, $this->symbol_sets->AdvancedSearch->toJson(), ","); // Field symbol_sets
        $filterList = Concat($filterList, $this->cipher_type_other->AdvancedSearch->toJson(), ","); // Field cipher_type_other
        $filterList = Concat($filterList, $this->symbol_set_other->AdvancedSearch->toJson(), ","); // Field symbol_set_other
        $filterList = Concat($filterList, $this->status->AdvancedSearch->toJson(), ","); // Field status
        $filterList = Concat($filterList, $this->record_type->AdvancedSearch->toJson(), ","); // Field record_type
        $filterList = Concat($filterList, $this->access_mode->AdvancedSearch->toJson(), ","); // Field access_mode
        $filterList = Concat($filterList, $this->transc_files->AdvancedSearch->toJson(), ","); // Field transc_files
        $filterList = Concat($filterList, $this->link->AdvancedSearch->toJson(), ","); // Field link
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
            $UserProfile->setSearchFilters(CurrentUserName(), "frecordviewsrch", $filters);
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

        // Field name
        $this->name->AdvancedSearch->SearchValue = @$filter["x_name"];
        $this->name->AdvancedSearch->SearchOperator = @$filter["z_name"];
        $this->name->AdvancedSearch->SearchCondition = @$filter["v_name"];
        $this->name->AdvancedSearch->SearchValue2 = @$filter["y_name"];
        $this->name->AdvancedSearch->SearchOperator2 = @$filter["w_name"];
        $this->name->AdvancedSearch->save();

        // Field owner_id
        $this->owner_id->AdvancedSearch->SearchValue = @$filter["x_owner_id"];
        $this->owner_id->AdvancedSearch->SearchOperator = @$filter["z_owner_id"];
        $this->owner_id->AdvancedSearch->SearchCondition = @$filter["v_owner_id"];
        $this->owner_id->AdvancedSearch->SearchValue2 = @$filter["y_owner_id"];
        $this->owner_id->AdvancedSearch->SearchOperator2 = @$filter["w_owner_id"];
        $this->owner_id->AdvancedSearch->save();

        // Field owner
        $this->owner->AdvancedSearch->SearchValue = @$filter["x_owner"];
        $this->owner->AdvancedSearch->SearchOperator = @$filter["z_owner"];
        $this->owner->AdvancedSearch->SearchCondition = @$filter["v_owner"];
        $this->owner->AdvancedSearch->SearchValue2 = @$filter["y_owner"];
        $this->owner->AdvancedSearch->SearchOperator2 = @$filter["w_owner"];
        $this->owner->AdvancedSearch->save();

        // Field record_group_id
        $this->record_group_id->AdvancedSearch->SearchValue = @$filter["x_record_group_id"];
        $this->record_group_id->AdvancedSearch->SearchOperator = @$filter["z_record_group_id"];
        $this->record_group_id->AdvancedSearch->SearchCondition = @$filter["v_record_group_id"];
        $this->record_group_id->AdvancedSearch->SearchValue2 = @$filter["y_record_group_id"];
        $this->record_group_id->AdvancedSearch->SearchOperator2 = @$filter["w_record_group_id"];
        $this->record_group_id->AdvancedSearch->save();

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

        // Field creator_id
        $this->creator_id->AdvancedSearch->SearchValue = @$filter["x_creator_id"];
        $this->creator_id->AdvancedSearch->SearchOperator = @$filter["z_creator_id"];
        $this->creator_id->AdvancedSearch->SearchCondition = @$filter["v_creator_id"];
        $this->creator_id->AdvancedSearch->SearchValue2 = @$filter["y_creator_id"];
        $this->creator_id->AdvancedSearch->SearchOperator2 = @$filter["w_creator_id"];
        $this->creator_id->AdvancedSearch->save();

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

        // Field current_country
        $this->current_country->AdvancedSearch->SearchValue = @$filter["x_current_country"];
        $this->current_country->AdvancedSearch->SearchOperator = @$filter["z_current_country"];
        $this->current_country->AdvancedSearch->SearchCondition = @$filter["v_current_country"];
        $this->current_country->AdvancedSearch->SearchValue2 = @$filter["y_current_country"];
        $this->current_country->AdvancedSearch->SearchOperator2 = @$filter["w_current_country"];
        $this->current_country->AdvancedSearch->save();

        // Field current_city
        $this->current_city->AdvancedSearch->SearchValue = @$filter["x_current_city"];
        $this->current_city->AdvancedSearch->SearchOperator = @$filter["z_current_city"];
        $this->current_city->AdvancedSearch->SearchCondition = @$filter["v_current_city"];
        $this->current_city->AdvancedSearch->SearchValue2 = @$filter["y_current_city"];
        $this->current_city->AdvancedSearch->SearchOperator2 = @$filter["w_current_city"];
        $this->current_city->AdvancedSearch->save();

        // Field current_holder
        $this->current_holder->AdvancedSearch->SearchValue = @$filter["x_current_holder"];
        $this->current_holder->AdvancedSearch->SearchOperator = @$filter["z_current_holder"];
        $this->current_holder->AdvancedSearch->SearchCondition = @$filter["v_current_holder"];
        $this->current_holder->AdvancedSearch->SearchValue2 = @$filter["y_current_holder"];
        $this->current_holder->AdvancedSearch->SearchOperator2 = @$filter["w_current_holder"];
        $this->current_holder->AdvancedSearch->save();

        // Field additional_information
        $this->additional_information->AdvancedSearch->SearchValue = @$filter["x_additional_information"];
        $this->additional_information->AdvancedSearch->SearchOperator = @$filter["z_additional_information"];
        $this->additional_information->AdvancedSearch->SearchCondition = @$filter["v_additional_information"];
        $this->additional_information->AdvancedSearch->SearchValue2 = @$filter["y_additional_information"];
        $this->additional_information->AdvancedSearch->SearchOperator2 = @$filter["w_additional_information"];
        $this->additional_information->AdvancedSearch->save();

        // Field number_of_pages
        $this->number_of_pages->AdvancedSearch->SearchValue = @$filter["x_number_of_pages"];
        $this->number_of_pages->AdvancedSearch->SearchOperator = @$filter["z_number_of_pages"];
        $this->number_of_pages->AdvancedSearch->SearchCondition = @$filter["v_number_of_pages"];
        $this->number_of_pages->AdvancedSearch->SearchValue2 = @$filter["y_number_of_pages"];
        $this->number_of_pages->AdvancedSearch->SearchOperator2 = @$filter["w_number_of_pages"];
        $this->number_of_pages->AdvancedSearch->save();

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

        // Field paper
        $this->paper->AdvancedSearch->SearchValue = @$filter["x_paper"];
        $this->paper->AdvancedSearch->SearchOperator = @$filter["z_paper"];
        $this->paper->AdvancedSearch->SearchCondition = @$filter["v_paper"];
        $this->paper->AdvancedSearch->SearchValue2 = @$filter["y_paper"];
        $this->paper->AdvancedSearch->SearchOperator2 = @$filter["w_paper"];
        $this->paper->AdvancedSearch->save();

        // Field creation_date
        $this->creation_date->AdvancedSearch->SearchValue = @$filter["x_creation_date"];
        $this->creation_date->AdvancedSearch->SearchOperator = @$filter["z_creation_date"];
        $this->creation_date->AdvancedSearch->SearchCondition = @$filter["v_creation_date"];
        $this->creation_date->AdvancedSearch->SearchValue2 = @$filter["y_creation_date"];
        $this->creation_date->AdvancedSearch->SearchOperator2 = @$filter["w_creation_date"];
        $this->creation_date->AdvancedSearch->save();

        // Field private_ciphertext
        $this->private_ciphertext->AdvancedSearch->SearchValue = @$filter["x_private_ciphertext"];
        $this->private_ciphertext->AdvancedSearch->SearchOperator = @$filter["z_private_ciphertext"];
        $this->private_ciphertext->AdvancedSearch->SearchCondition = @$filter["v_private_ciphertext"];
        $this->private_ciphertext->AdvancedSearch->SearchValue2 = @$filter["y_private_ciphertext"];
        $this->private_ciphertext->AdvancedSearch->SearchOperator2 = @$filter["w_private_ciphertext"];
        $this->private_ciphertext->AdvancedSearch->save();

        // Field cipher_types
        $this->cipher_types->AdvancedSearch->SearchValue = @$filter["x_cipher_types"];
        $this->cipher_types->AdvancedSearch->SearchOperator = @$filter["z_cipher_types"];
        $this->cipher_types->AdvancedSearch->SearchCondition = @$filter["v_cipher_types"];
        $this->cipher_types->AdvancedSearch->SearchValue2 = @$filter["y_cipher_types"];
        $this->cipher_types->AdvancedSearch->SearchOperator2 = @$filter["w_cipher_types"];
        $this->cipher_types->AdvancedSearch->save();

        // Field symbol_sets
        $this->symbol_sets->AdvancedSearch->SearchValue = @$filter["x_symbol_sets"];
        $this->symbol_sets->AdvancedSearch->SearchOperator = @$filter["z_symbol_sets"];
        $this->symbol_sets->AdvancedSearch->SearchCondition = @$filter["v_symbol_sets"];
        $this->symbol_sets->AdvancedSearch->SearchValue2 = @$filter["y_symbol_sets"];
        $this->symbol_sets->AdvancedSearch->SearchOperator2 = @$filter["w_symbol_sets"];
        $this->symbol_sets->AdvancedSearch->save();

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

        // Field status
        $this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
        $this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
        $this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
        $this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
        $this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
        $this->status->AdvancedSearch->save();

        // Field record_type
        $this->record_type->AdvancedSearch->SearchValue = @$filter["x_record_type"];
        $this->record_type->AdvancedSearch->SearchOperator = @$filter["z_record_type"];
        $this->record_type->AdvancedSearch->SearchCondition = @$filter["v_record_type"];
        $this->record_type->AdvancedSearch->SearchValue2 = @$filter["y_record_type"];
        $this->record_type->AdvancedSearch->SearchOperator2 = @$filter["w_record_type"];
        $this->record_type->AdvancedSearch->save();

        // Field access_mode
        $this->access_mode->AdvancedSearch->SearchValue = @$filter["x_access_mode"];
        $this->access_mode->AdvancedSearch->SearchOperator = @$filter["z_access_mode"];
        $this->access_mode->AdvancedSearch->SearchCondition = @$filter["v_access_mode"];
        $this->access_mode->AdvancedSearch->SearchValue2 = @$filter["y_access_mode"];
        $this->access_mode->AdvancedSearch->SearchOperator2 = @$filter["w_access_mode"];
        $this->access_mode->AdvancedSearch->save();

        // Field transc_files
        $this->transc_files->AdvancedSearch->SearchValue = @$filter["x_transc_files"];
        $this->transc_files->AdvancedSearch->SearchOperator = @$filter["z_transc_files"];
        $this->transc_files->AdvancedSearch->SearchCondition = @$filter["v_transc_files"];
        $this->transc_files->AdvancedSearch->SearchValue2 = @$filter["y_transc_files"];
        $this->transc_files->AdvancedSearch->SearchOperator2 = @$filter["w_transc_files"];
        $this->transc_files->AdvancedSearch->save();

        // Field link
        $this->link->AdvancedSearch->SearchValue = @$filter["x_link"];
        $this->link->AdvancedSearch->SearchOperator = @$filter["z_link"];
        $this->link->AdvancedSearch->SearchCondition = @$filter["v_link"];
        $this->link->AdvancedSearch->SearchValue2 = @$filter["y_link"];
        $this->link->AdvancedSearch->SearchOperator2 = @$filter["w_link"];
        $this->link->AdvancedSearch->save();
        $this->BasicSearch->setKeyword(@$filter[Config("TABLE_BASIC_SEARCH")]);
        $this->BasicSearch->setType(@$filter[Config("TABLE_BASIC_SEARCH_TYPE")]);
    }

    // Show list of filters
    public function showFilterList()
    {
        global $Language;

        // Initialize
        $filterList = "";
        $captionClass = $this->isExport("email") ? "ew-filter-caption-email" : "ew-filter-caption";
        $captionSuffix = $this->isExport("email") ? ": " : "";
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
        $searchFlds[] = &$this->name;
        $searchFlds[] = &$this->owner;
        $searchFlds[] = &$this->current_country;
        $searchFlds[] = &$this->current_city;
        $searchFlds[] = &$this->current_holder;
        $searchFlds[] = &$this->additional_information;
        $searchFlds[] = &$this->cleartext_lang;
        $searchFlds[] = &$this->plaintext_lang;
        $searchFlds[] = &$this->author;
        $searchFlds[] = &$this->sender;
        $searchFlds[] = &$this->receiver;
        $searchFlds[] = &$this->origin_region;
        $searchFlds[] = &$this->origin_city;
        $searchFlds[] = &$this->paper;
        $searchFlds[] = &$this->cipher_types;
        $searchFlds[] = &$this->symbol_sets;
        $searchFlds[] = &$this->cipher_type_other;
        $searchFlds[] = &$this->symbol_set_other;
        $searchFlds[] = &$this->transc_files;
        $searchFlds[] = &$this->link;
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

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Load default Sorting Order
        if ($this->Command != "json") {
            $defaultSort = ""; // Set up default sort
            if ($this->getSessionOrderBy() == "" && $defaultSort != "") {
                $this->setSessionOrderBy($defaultSort);
            }
        }

        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->id); // id
            $this->updateSort($this->name); // name
            $this->updateSort($this->owner); // owner
            $this->updateSort($this->start_year); // start_year
            $this->updateSort($this->start_month); // start_month
            $this->updateSort($this->start_day); // start_day
            $this->updateSort($this->end_year); // end_year
            $this->updateSort($this->end_month); // end_month
            $this->updateSort($this->end_day); // end_day
            $this->updateSort($this->creator_id); // creator_id
            $this->updateSort($this->inline_cleartext); // inline_cleartext
            $this->updateSort($this->inline_plaintext); // inline_plaintext
            $this->updateSort($this->current_country); // current_country
            $this->updateSort($this->current_city); // current_city
            $this->updateSort($this->current_holder); // current_holder
            $this->updateSort($this->number_of_pages); // number_of_pages
            $this->updateSort($this->cleartext_lang); // cleartext_lang
            $this->updateSort($this->plaintext_lang); // plaintext_lang
            $this->updateSort($this->origin_region); // origin_region
            $this->updateSort($this->origin_city); // origin_city
            $this->updateSort($this->paper); // paper
            $this->updateSort($this->creation_date); // creation_date
            $this->updateSort($this->private_ciphertext); // private_ciphertext
            $this->updateSort($this->cipher_types); // cipher_types
            $this->updateSort($this->symbol_sets); // symbol_sets
            $this->updateSort($this->cipher_type_other); // cipher_type_other
            $this->updateSort($this->symbol_set_other); // symbol_set_other
            $this->updateSort($this->status); // status
            $this->updateSort($this->record_type); // record_type
            $this->updateSort($this->access_mode); // access_mode
            $this->updateSort($this->link); // link
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
                $this->start_year->setSort("");
                $this->start_month->setSort("");
                $this->start_day->setSort("");
                $this->end_year->setSort("");
                $this->end_month->setSort("");
                $this->end_day->setSort("");
                $this->creator_id->setSort("");
                $this->inline_cleartext->setSort("");
                $this->inline_plaintext->setSort("");
                $this->current_country->setSort("");
                $this->current_city->setSort("");
                $this->current_holder->setSort("");
                $this->additional_information->setSort("");
                $this->number_of_pages->setSort("");
                $this->cleartext_lang->setSort("");
                $this->plaintext_lang->setSort("");
                $this->author->setSort("");
                $this->sender->setSort("");
                $this->receiver->setSort("");
                $this->origin_region->setSort("");
                $this->origin_city->setSort("");
                $this->paper->setSort("");
                $this->creation_date->setSort("");
                $this->private_ciphertext->setSort("");
                $this->cipher_types->setSort("");
                $this->symbol_sets->setSort("");
                $this->cipher_type_other->setSort("");
                $this->symbol_set_other->setSort("");
                $this->status->setSort("");
                $this->record_type->setSort("");
                $this->access_mode->setSort("");
                $this->transc_files->setSort("");
                $this->link->setSort("");
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
        if ($this->CurrentMode == "view") { // Check view mode
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
                    $link = "<li><button type=\"button\" class=\"dropdown-item ew-action ew-list-action\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"frecordviewlist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . " " . $listaction->Caption . "</button></li>";
                    if ($link != "") {
                        $links[] = $link;
                        if ($body == "") { // Setup first button
                            $body = "<button type=\"button\" class=\"btn btn-default ew-action ew-list-action\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"frecordviewlist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . " " . $listaction->Caption . "</button>";
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
        $option = $options["action"];

        // Show column list for column visibility
        if ($this->UseColumnVisibility) {
            $option = $this->OtherOptions["column"];
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = $this->UseColumnVisibility;
            $option->add("id", $this->createColumnOption("id"));
            $option->add("name", $this->createColumnOption("name"));
            $option->add("owner", $this->createColumnOption("owner"));
            $option->add("start_year", $this->createColumnOption("start_year"));
            $option->add("start_month", $this->createColumnOption("start_month"));
            $option->add("start_day", $this->createColumnOption("start_day"));
            $option->add("end_year", $this->createColumnOption("end_year"));
            $option->add("end_month", $this->createColumnOption("end_month"));
            $option->add("end_day", $this->createColumnOption("end_day"));
            $option->add("creator_id", $this->createColumnOption("creator_id"));
            $option->add("inline_cleartext", $this->createColumnOption("inline_cleartext"));
            $option->add("inline_plaintext", $this->createColumnOption("inline_plaintext"));
            $option->add("current_country", $this->createColumnOption("current_country"));
            $option->add("current_city", $this->createColumnOption("current_city"));
            $option->add("current_holder", $this->createColumnOption("current_holder"));
            $option->add("number_of_pages", $this->createColumnOption("number_of_pages"));
            $option->add("cleartext_lang", $this->createColumnOption("cleartext_lang"));
            $option->add("plaintext_lang", $this->createColumnOption("plaintext_lang"));
            $option->add("origin_region", $this->createColumnOption("origin_region"));
            $option->add("origin_city", $this->createColumnOption("origin_city"));
            $option->add("paper", $this->createColumnOption("paper"));
            $option->add("creation_date", $this->createColumnOption("creation_date"));
            $option->add("private_ciphertext", $this->createColumnOption("private_ciphertext"));
            $option->add("cipher_types", $this->createColumnOption("cipher_types"));
            $option->add("symbol_sets", $this->createColumnOption("symbol_sets"));
            $option->add("cipher_type_other", $this->createColumnOption("cipher_type_other"));
            $option->add("symbol_set_other", $this->createColumnOption("symbol_set_other"));
            $option->add("status", $this->createColumnOption("status"));
            $option->add("record_type", $this->createColumnOption("record_type"));
            $option->add("access_mode", $this->createColumnOption("access_mode"));
            $option->add("link", $this->createColumnOption("link"));
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
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"frecordviewsrch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"frecordviewsrch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
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
                $item->Body = '<button type="button" class="btn btn-default ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" data-ew-action="submit" form="frecordviewlist"' . $listaction->toDataAttrs() . '>' . $icon . '</button>';
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
                $this->RowAttrs->merge(["data-rowindex" => $this->RowIndex, "id" => "r0_recordview", "data-rowtype" => ROWTYPE_ADD]);
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
            "id" => "r" . $this->RowCount . "_recordview",
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
        $this->start_year->setDbValue($row['start_year']);
        $this->start_month->setDbValue($row['start_month']);
        $this->start_day->setDbValue($row['start_day']);
        $this->end_year->setDbValue($row['end_year']);
        $this->end_month->setDbValue($row['end_month']);
        $this->end_day->setDbValue($row['end_day']);
        $this->creator_id->setDbValue($row['creator_id']);
        $this->inline_cleartext->setDbValue($row['inline_cleartext']);
        $this->inline_plaintext->setDbValue($row['inline_plaintext']);
        $this->current_country->setDbValue($row['current_country']);
        $this->current_city->setDbValue($row['current_city']);
        $this->current_holder->setDbValue($row['current_holder']);
        $this->additional_information->setDbValue($row['additional_information']);
        $this->number_of_pages->setDbValue($row['number_of_pages']);
        $this->cleartext_lang->setDbValue($row['cleartext_lang']);
        $this->plaintext_lang->setDbValue($row['plaintext_lang']);
        $this->author->setDbValue($row['author']);
        $this->sender->setDbValue($row['sender']);
        $this->receiver->setDbValue($row['receiver']);
        $this->origin_region->setDbValue($row['origin_region']);
        $this->origin_city->setDbValue($row['origin_city']);
        $this->paper->setDbValue($row['paper']);
        $this->creation_date->setDbValue($row['creation_date']);
        $this->private_ciphertext->setDbValue($row['private_ciphertext']);
        $this->cipher_types->setDbValue($row['cipher_types']);
        $this->symbol_sets->setDbValue($row['symbol_sets']);
        $this->cipher_type_other->setDbValue($row['cipher_type_other']);
        $this->symbol_set_other->setDbValue($row['symbol_set_other']);
        $this->status->setDbValue($row['status']);
        $this->record_type->setDbValue($row['record_type']);
        $this->access_mode->setDbValue($row['access_mode']);
        $this->transc_files->setDbValue($row['transc_files']);
        $this->link->setDbValue($row['link']);
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
        $row['start_year'] = $this->start_year->DefaultValue;
        $row['start_month'] = $this->start_month->DefaultValue;
        $row['start_day'] = $this->start_day->DefaultValue;
        $row['end_year'] = $this->end_year->DefaultValue;
        $row['end_month'] = $this->end_month->DefaultValue;
        $row['end_day'] = $this->end_day->DefaultValue;
        $row['creator_id'] = $this->creator_id->DefaultValue;
        $row['inline_cleartext'] = $this->inline_cleartext->DefaultValue;
        $row['inline_plaintext'] = $this->inline_plaintext->DefaultValue;
        $row['current_country'] = $this->current_country->DefaultValue;
        $row['current_city'] = $this->current_city->DefaultValue;
        $row['current_holder'] = $this->current_holder->DefaultValue;
        $row['additional_information'] = $this->additional_information->DefaultValue;
        $row['number_of_pages'] = $this->number_of_pages->DefaultValue;
        $row['cleartext_lang'] = $this->cleartext_lang->DefaultValue;
        $row['plaintext_lang'] = $this->plaintext_lang->DefaultValue;
        $row['author'] = $this->author->DefaultValue;
        $row['sender'] = $this->sender->DefaultValue;
        $row['receiver'] = $this->receiver->DefaultValue;
        $row['origin_region'] = $this->origin_region->DefaultValue;
        $row['origin_city'] = $this->origin_city->DefaultValue;
        $row['paper'] = $this->paper->DefaultValue;
        $row['creation_date'] = $this->creation_date->DefaultValue;
        $row['private_ciphertext'] = $this->private_ciphertext->DefaultValue;
        $row['cipher_types'] = $this->cipher_types->DefaultValue;
        $row['symbol_sets'] = $this->symbol_sets->DefaultValue;
        $row['cipher_type_other'] = $this->cipher_type_other->DefaultValue;
        $row['symbol_set_other'] = $this->symbol_set_other->DefaultValue;
        $row['status'] = $this->status->DefaultValue;
        $row['record_type'] = $this->record_type->DefaultValue;
        $row['access_mode'] = $this->access_mode->DefaultValue;
        $row['transc_files'] = $this->transc_files->DefaultValue;
        $row['link'] = $this->link->DefaultValue;
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

        // start_year

        // start_month

        // start_day

        // end_year

        // end_month

        // end_day

        // creator_id

        // inline_cleartext

        // inline_plaintext

        // current_country

        // current_city

        // current_holder

        // additional_information

        // number_of_pages

        // cleartext_lang

        // plaintext_lang

        // author

        // sender

        // receiver

        // origin_region

        // origin_city

        // paper

        // creation_date

        // private_ciphertext

        // cipher_types

        // symbol_sets

        // cipher_type_other

        // symbol_set_other

        // status

        // record_type

        // access_mode

        // transc_files

        // link

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // name
            $this->name->ViewValue = $this->name->CurrentValue;

            // owner_id
            $this->owner_id->ViewValue = $this->owner_id->CurrentValue;
            $curVal = strval($this->owner_id->CurrentValue);
            if ($curVal != "") {
                $this->owner_id->ViewValue = $this->owner_id->lookupCacheOption($curVal);
                if ($this->owner_id->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`id`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->owner_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->owner_id->Lookup->renderViewRow($rswrk[0]);
                        $this->owner_id->ViewValue = $this->owner_id->displayValue($arwrk);
                    } else {
                        $this->owner_id->ViewValue = $this->owner_id->CurrentValue;
                    }
                }
            } else {
                $this->owner_id->ViewValue = null;
            }

            // owner
            $this->owner->ViewValue = $this->owner->CurrentValue;

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

            // current_country
            $this->current_country->ViewValue = $this->current_country->CurrentValue;

            // current_city
            $this->current_city->ViewValue = $this->current_city->CurrentValue;

            // current_holder
            $this->current_holder->ViewValue = $this->current_holder->CurrentValue;

            // number_of_pages
            $this->number_of_pages->ViewValue = $this->number_of_pages->CurrentValue;
            $this->number_of_pages->ViewValue = FormatNumber($this->number_of_pages->ViewValue, $this->number_of_pages->formatPattern());

            // cleartext_lang
            $this->cleartext_lang->ViewValue = $this->cleartext_lang->CurrentValue;

            // plaintext_lang
            $this->plaintext_lang->ViewValue = $this->plaintext_lang->CurrentValue;

            // origin_region
            $this->origin_region->ViewValue = $this->origin_region->CurrentValue;

            // origin_city
            $this->origin_city->ViewValue = $this->origin_city->CurrentValue;

            // paper
            $this->paper->ViewValue = $this->paper->CurrentValue;

            // creation_date
            $this->creation_date->ViewValue = $this->creation_date->CurrentValue;
            $this->creation_date->ViewValue = FormatDateTime($this->creation_date->ViewValue, $this->creation_date->formatPattern());

            // private_ciphertext
            if (strval($this->private_ciphertext->CurrentValue) != "") {
                $this->private_ciphertext->ViewValue = $this->private_ciphertext->optionCaption($this->private_ciphertext->CurrentValue);
            } else {
                $this->private_ciphertext->ViewValue = null;
            }

            // cipher_types
            $this->cipher_types->ViewValue = $this->cipher_types->CurrentValue;

            // symbol_sets
            $this->symbol_sets->ViewValue = $this->symbol_sets->CurrentValue;

            // cipher_type_other
            $this->cipher_type_other->ViewValue = $this->cipher_type_other->CurrentValue;

            // symbol_set_other
            $this->symbol_set_other->ViewValue = $this->symbol_set_other->CurrentValue;

            // status
            $this->status->ViewValue = $this->status->CurrentValue;
            $this->status->ViewValue = FormatNumber($this->status->ViewValue, $this->status->formatPattern());

            // record_type
            if (strval($this->record_type->CurrentValue) != "") {
                $this->record_type->ViewValue = $this->record_type->optionCaption($this->record_type->CurrentValue);
            } else {
                $this->record_type->ViewValue = null;
            }

            // access_mode
            $this->access_mode->ViewValue = $this->access_mode->CurrentValue;
            $this->access_mode->ViewValue = FormatNumber($this->access_mode->ViewValue, $this->access_mode->formatPattern());

            // link
            $this->link->ViewValue = $this->link->CurrentValue;

            // id
            if (!EmptyValue($this->id->CurrentValue)) {
                $this->id->HrefValue = $this->id->getLinkPrefix() . (!empty($this->id->ViewValue) && !is_array($this->id->ViewValue) ? RemoveHtml($this->id->ViewValue) : $this->id->CurrentValue); // Add prefix/suffix
                $this->id->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->id->HrefValue = FullUrl($this->id->HrefValue, "href");
                }
            } else {
                $this->id->HrefValue = "";
            }
            $this->id->TooltipValue = "";

            // name
            $this->name->HrefValue = "";
            $this->name->TooltipValue = "";

            // owner
            $this->owner->HrefValue = "";
            $this->owner->TooltipValue = "";

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

            // creator_id
            $this->creator_id->HrefValue = "";
            $this->creator_id->TooltipValue = "";

            // inline_cleartext
            $this->inline_cleartext->HrefValue = "";
            $this->inline_cleartext->TooltipValue = "";

            // inline_plaintext
            $this->inline_plaintext->HrefValue = "";
            $this->inline_plaintext->TooltipValue = "";

            // current_country
            $this->current_country->HrefValue = "";
            $this->current_country->TooltipValue = "";

            // current_city
            $this->current_city->HrefValue = "";
            $this->current_city->TooltipValue = "";

            // current_holder
            $this->current_holder->HrefValue = "";
            $this->current_holder->TooltipValue = "";

            // number_of_pages
            $this->number_of_pages->HrefValue = "";
            $this->number_of_pages->TooltipValue = "";

            // cleartext_lang
            $this->cleartext_lang->HrefValue = "";
            $this->cleartext_lang->TooltipValue = "";

            // plaintext_lang
            $this->plaintext_lang->HrefValue = "";
            $this->plaintext_lang->TooltipValue = "";

            // origin_region
            $this->origin_region->HrefValue = "";
            $this->origin_region->TooltipValue = "";

            // origin_city
            $this->origin_city->HrefValue = "";
            $this->origin_city->TooltipValue = "";

            // paper
            $this->paper->HrefValue = "";
            $this->paper->TooltipValue = "";

            // creation_date
            $this->creation_date->HrefValue = "";
            $this->creation_date->TooltipValue = "";

            // private_ciphertext
            $this->private_ciphertext->HrefValue = "";
            $this->private_ciphertext->TooltipValue = "";

            // cipher_types
            $this->cipher_types->HrefValue = "";
            $this->cipher_types->TooltipValue = "";

            // symbol_sets
            $this->symbol_sets->HrefValue = "";
            $this->symbol_sets->TooltipValue = "";

            // cipher_type_other
            $this->cipher_type_other->HrefValue = "";
            $this->cipher_type_other->TooltipValue = "";

            // symbol_set_other
            $this->symbol_set_other->HrefValue = "";
            $this->symbol_set_other->TooltipValue = "";

            // status
            $this->status->HrefValue = "";
            $this->status->TooltipValue = "";

            // record_type
            $this->record_type->HrefValue = "";
            $this->record_type->TooltipValue = "";

            // access_mode
            $this->access_mode->HrefValue = "";
            $this->access_mode->TooltipValue = "";

            // link
            if (!EmptyValue($this->link->CurrentValue)) {
                $this->link->HrefValue = (!empty($this->link->ViewValue) && !is_array($this->link->ViewValue) ? RemoveHtml($this->link->ViewValue) : $this->link->CurrentValue); // Add prefix/suffix
                $this->link->LinkAttrs["target"] = ""; // Add target
                if ($this->isExport()) {
                    $this->link->HrefValue = FullUrl($this->link->HrefValue, "href");
                }
            } else {
                $this->link->HrefValue = "";
            }
            $this->link->TooltipValue = "";
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"frecordviewsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        if ($this->UseCustomTemplate || !$this->UseAjaxActions) {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        } else {
            $item->Body = "<a class=\"btn btn-default ew-show-all\" role=\"button\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" data-ew-action=\"refresh\" data-url=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        }
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

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
                case "x_owner_id":
                    break;
                case "x_creator_id":
                    break;
                case "x_inline_cleartext":
                    break;
                case "x_inline_plaintext":
                    break;
                case "x_private_ciphertext":
                    break;
                case "x_record_type":
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

    // ListOptions Load event
    public function listOptionsLoad()
    {
        // Example:
        //$opt = &$this->ListOptions->Add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->MoveTo(0); // Move to first column
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
        // Example:
        //$this->ListOptions["new"]->Body = "xxx";
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
