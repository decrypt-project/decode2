<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class FileInProjectEdit extends FileInProject
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "FileInProjectEdit";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "FileInProjectEdit";

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
        $this->project_id->setVisibility();
        $this->filename->setVisibility();
        $this->path->setVisibility();
        $this->type->setVisibility();
        $this->filetype->setVisibility();
        $this->filesize->setVisibility();
        $this->creation_date->setVisibility();
        $this->last_updated->setVisibility();
        $this->locked_by->setVisibility();
        $this->lock_date->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'file_in_project';
        $this->TableName = 'file_in_project';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (file_in_project)
        if (!isset($GLOBALS["file_in_project"]) || get_class($GLOBALS["file_in_project"]) == PROJECT_NAMESPACE . "file_in_project") {
            $GLOBALS["file_in_project"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'file_in_project');
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
                    $result["view"] = $pageName == "FileInProjectView"; // If View page, no primary button
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

    // Properties
    public $FormClassName = "ew-form ew-edit-form overlay-wrapper";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

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
        $this->setupLookupOptions($this->locked_by);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("id") ?? Key(0) ?? Route(2)) !== null) {
                $this->id->setQueryStringValue($keyValue);
                $this->id->setOldValue($this->id->QueryStringValue);
            } elseif (Post("id") !== null) {
                $this->id->setFormValue(Post("id"));
                $this->id->setOldValue($this->id->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action", "") !== "") {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("id") ?? Route("id")) !== null) {
                    $this->id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->id->CurrentValue = null;
                }
            }

            // Set up master detail parameters
            $this->setupMasterParms();

            // Load recordset
            if ($this->isShow()) {
                    // Load current record
                    $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("FileInProjectList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "FileInProjectList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "FileInProjectList") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "FileInProjectList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson([ "success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage() ]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
        $this->path->Upload->Index = $CurrentForm->Index;
        $this->path->Upload->uploadFile();
        $this->path->CurrentValue = $this->path->Upload->FileName;
        $this->filetype->CurrentValue = $this->path->Upload->ContentType;
        $this->filesize->CurrentValue = $this->path->Upload->FileSize;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
        if (!$this->id->IsDetailKey) {
            $this->id->setFormValue($val);
        }

        // Check field name 'project_id' first before field var 'x_project_id'
        $val = $CurrentForm->hasValue("project_id") ? $CurrentForm->getValue("project_id") : $CurrentForm->getValue("x_project_id");
        if (!$this->project_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->project_id->Visible = false; // Disable update for API request
            } else {
                $this->project_id->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'filename' first before field var 'x_filename'
        $val = $CurrentForm->hasValue("filename") ? $CurrentForm->getValue("filename") : $CurrentForm->getValue("x_filename");
        if (!$this->filename->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->filename->Visible = false; // Disable update for API request
            } else {
                $this->filename->setFormValue($val);
            }
        }

        // Check field name 'type' first before field var 'x_type'
        $val = $CurrentForm->hasValue("type") ? $CurrentForm->getValue("type") : $CurrentForm->getValue("x_type");
        if (!$this->type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->type->Visible = false; // Disable update for API request
            } else {
                $this->type->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'filetype' first before field var 'x_filetype'
        $val = $CurrentForm->hasValue("filetype") ? $CurrentForm->getValue("filetype") : $CurrentForm->getValue("x_filetype");
        if (!$this->filetype->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->filetype->Visible = false; // Disable update for API request
            } else {
                $this->filetype->setFormValue($val);
            }
        }

        // Check field name 'filesize' first before field var 'x_filesize'
        $val = $CurrentForm->hasValue("filesize") ? $CurrentForm->getValue("filesize") : $CurrentForm->getValue("x_filesize");
        if (!$this->filesize->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->filesize->Visible = false; // Disable update for API request
            } else {
                $this->filesize->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'creation_date' first before field var 'x_creation_date'
        $val = $CurrentForm->hasValue("creation_date") ? $CurrentForm->getValue("creation_date") : $CurrentForm->getValue("x_creation_date");
        if (!$this->creation_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->creation_date->Visible = false; // Disable update for API request
            } else {
                $this->creation_date->setFormValue($val, true, $validate);
            }
            $this->creation_date->CurrentValue = UnFormatDateTime($this->creation_date->CurrentValue, $this->creation_date->formatPattern());
        }

        // Check field name 'last_updated' first before field var 'x_last_updated'
        $val = $CurrentForm->hasValue("last_updated") ? $CurrentForm->getValue("last_updated") : $CurrentForm->getValue("x_last_updated");
        if (!$this->last_updated->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->last_updated->Visible = false; // Disable update for API request
            } else {
                $this->last_updated->setFormValue($val, true, $validate);
            }
            $this->last_updated->CurrentValue = UnFormatDateTime($this->last_updated->CurrentValue, $this->last_updated->formatPattern());
        }

        // Check field name 'locked_by' first before field var 'x_locked_by'
        $val = $CurrentForm->hasValue("locked_by") ? $CurrentForm->getValue("locked_by") : $CurrentForm->getValue("x_locked_by");
        if (!$this->locked_by->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->locked_by->Visible = false; // Disable update for API request
            } else {
                $this->locked_by->setFormValue($val);
            }
        }

        // Check field name 'lock_date' first before field var 'x_lock_date'
        $val = $CurrentForm->hasValue("lock_date") ? $CurrentForm->getValue("lock_date") : $CurrentForm->getValue("x_lock_date");
        if (!$this->lock_date->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->lock_date->Visible = false; // Disable update for API request
            } else {
                $this->lock_date->setFormValue($val, true, $validate);
            }
            $this->lock_date->CurrentValue = UnFormatDateTime($this->lock_date->CurrentValue, $this->lock_date->formatPattern());
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->id->CurrentValue = $this->id->FormValue;
        $this->project_id->CurrentValue = $this->project_id->FormValue;
        $this->filename->CurrentValue = $this->filename->FormValue;
        $this->type->CurrentValue = $this->type->FormValue;
        $this->creation_date->CurrentValue = $this->creation_date->FormValue;
        $this->creation_date->CurrentValue = UnFormatDateTime($this->creation_date->CurrentValue, $this->creation_date->formatPattern());
        $this->last_updated->CurrentValue = $this->last_updated->FormValue;
        $this->last_updated->CurrentValue = UnFormatDateTime($this->last_updated->CurrentValue, $this->last_updated->formatPattern());
        $this->locked_by->CurrentValue = $this->locked_by->FormValue;
        $this->lock_date->CurrentValue = $this->lock_date->FormValue;
        $this->lock_date->CurrentValue = UnFormatDateTime($this->lock_date->CurrentValue, $this->lock_date->formatPattern());
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
        $this->project_id->setDbValue($row['project_id']);
        $this->filename->setDbValue($row['filename']);
        $this->path->Upload->DbValue = $row['path'];
        $this->path->setDbValue($this->path->Upload->DbValue);
        $this->type->setDbValue($row['type']);
        $this->filetype->setDbValue($row['filetype']);
        $this->filesize->setDbValue($row['filesize']);
        $this->creation_date->setDbValue($row['creation_date']);
        $this->last_updated->setDbValue($row['last_updated']);
        $this->locked_by->setDbValue($row['locked_by']);
        $this->lock_date->setDbValue($row['lock_date']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['project_id'] = $this->project_id->DefaultValue;
        $row['filename'] = $this->filename->DefaultValue;
        $row['path'] = $this->path->DefaultValue;
        $row['type'] = $this->type->DefaultValue;
        $row['filetype'] = $this->filetype->DefaultValue;
        $row['filesize'] = $this->filesize->DefaultValue;
        $row['creation_date'] = $this->creation_date->DefaultValue;
        $row['last_updated'] = $this->last_updated->DefaultValue;
        $row['locked_by'] = $this->locked_by->DefaultValue;
        $row['lock_date'] = $this->lock_date->DefaultValue;
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

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id
        $this->id->RowCssClass = "row";

        // project_id
        $this->project_id->RowCssClass = "row";

        // filename
        $this->filename->RowCssClass = "row";

        // path
        $this->path->RowCssClass = "row";

        // type
        $this->type->RowCssClass = "row";

        // filetype
        $this->filetype->RowCssClass = "row";

        // filesize
        $this->filesize->RowCssClass = "row";

        // creation_date
        $this->creation_date->RowCssClass = "row";

        // last_updated
        $this->last_updated->RowCssClass = "row";

        // locked_by
        $this->locked_by->RowCssClass = "row";

        // lock_date
        $this->lock_date->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // project_id
            $this->project_id->ViewValue = $this->project_id->CurrentValue;
            $this->project_id->ViewValue = FormatNumber($this->project_id->ViewValue, $this->project_id->formatPattern());

            // filename
            $this->filename->ViewValue = $this->filename->CurrentValue;

            // path
            if (!EmptyValue($this->path->Upload->DbValue)) {
                $this->path->ViewValue = $this->path->Upload->DbValue;
            } else {
                $this->path->ViewValue = "";
            }

            // type
            $this->type->ViewValue = $this->type->CurrentValue;
            $this->type->ViewValue = FormatNumber($this->type->ViewValue, $this->type->formatPattern());

            // filetype
            $this->filetype->ViewValue = $this->filetype->CurrentValue;

            // filesize
            $this->filesize->ViewValue = $this->filesize->CurrentValue;
            $this->filesize->ViewValue = FormatNumber($this->filesize->ViewValue, $this->filesize->formatPattern());

            // creation_date
            $this->creation_date->ViewValue = $this->creation_date->CurrentValue;
            $this->creation_date->ViewValue = FormatDateTime($this->creation_date->ViewValue, $this->creation_date->formatPattern());

            // last_updated
            $this->last_updated->ViewValue = $this->last_updated->CurrentValue;
            $this->last_updated->ViewValue = FormatDateTime($this->last_updated->ViewValue, $this->last_updated->formatPattern());

            // locked_by
            $curVal = strval($this->locked_by->CurrentValue);
            if ($curVal != "") {
                $this->locked_by->ViewValue = $this->locked_by->lookupCacheOption($curVal);
                if ($this->locked_by->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter("`id`", "=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->locked_by->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->locked_by->Lookup->renderViewRow($rswrk[0]);
                        $this->locked_by->ViewValue = $this->locked_by->displayValue($arwrk);
                    } else {
                        $this->locked_by->ViewValue = FormatNumber($this->locked_by->CurrentValue, $this->locked_by->formatPattern());
                    }
                }
            } else {
                $this->locked_by->ViewValue = null;
            }

            // lock_date
            $this->lock_date->ViewValue = $this->lock_date->CurrentValue;
            $this->lock_date->ViewValue = FormatDateTime($this->lock_date->ViewValue, $this->lock_date->formatPattern());

            // id
            $this->id->HrefValue = "";

            // project_id
            $this->project_id->HrefValue = "";

            // filename
            $this->filename->HrefValue = "";

            // path
            $this->path->HrefValue = "";
            $this->path->ExportHrefValue = $this->path->UploadPath . $this->path->Upload->DbValue;

            // type
            $this->type->HrefValue = "";

            // filetype
            $this->filetype->HrefValue = "";

            // filesize
            $this->filesize->HrefValue = "";

            // creation_date
            $this->creation_date->HrefValue = "";

            // last_updated
            $this->last_updated->HrefValue = "";

            // locked_by
            $this->locked_by->HrefValue = "";

            // lock_date
            $this->lock_date->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // id
            $this->id->setupEditAttributes();
            $this->id->EditValue = $this->id->CurrentValue;

            // project_id
            $this->project_id->setupEditAttributes();
            if ($this->project_id->getSessionValue() != "") {
                $this->project_id->CurrentValue = GetForeignKeyValue($this->project_id->getSessionValue());
                $this->project_id->ViewValue = $this->project_id->CurrentValue;
                $this->project_id->ViewValue = FormatNumber($this->project_id->ViewValue, $this->project_id->formatPattern());
            } else {
                $this->project_id->EditValue = $this->project_id->CurrentValue;
                $this->project_id->PlaceHolder = RemoveHtml($this->project_id->caption());
                if (strval($this->project_id->EditValue) != "" && is_numeric($this->project_id->EditValue)) {
                    $this->project_id->EditValue = FormatNumber($this->project_id->EditValue, null);
                }
            }

            // filename
            $this->filename->setupEditAttributes();
            $this->filename->EditValue = HtmlEncode($this->filename->CurrentValue);
            $this->filename->PlaceHolder = RemoveHtml($this->filename->caption());

            // path
            $this->path->setupEditAttributes();
            if (!EmptyValue($this->path->Upload->DbValue)) {
                $this->path->EditValue = $this->path->Upload->DbValue;
            } else {
                $this->path->EditValue = "";
            }
            if (!EmptyValue($this->path->CurrentValue)) {
                $this->path->Upload->FileName = $this->path->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->path);
            }

            // type
            $this->type->setupEditAttributes();
            $this->type->EditValue = $this->type->CurrentValue;
            $this->type->PlaceHolder = RemoveHtml($this->type->caption());
            if (strval($this->type->EditValue) != "" && is_numeric($this->type->EditValue)) {
                $this->type->EditValue = FormatNumber($this->type->EditValue, null);
            }

            // filetype
            $this->filetype->setupEditAttributes();
            $this->filetype->EditValue = HtmlEncode($this->filetype->CurrentValue);
            $this->filetype->PlaceHolder = RemoveHtml($this->filetype->caption());

            // filesize
            $this->filesize->setupEditAttributes();
            $this->filesize->EditValue = $this->filesize->CurrentValue;
            $this->filesize->PlaceHolder = RemoveHtml($this->filesize->caption());
            if (strval($this->filesize->EditValue) != "" && is_numeric($this->filesize->EditValue)) {
                $this->filesize->EditValue = FormatNumber($this->filesize->EditValue, null);
            }

            // creation_date
            $this->creation_date->setupEditAttributes();
            $this->creation_date->EditValue = HtmlEncode(FormatDateTime($this->creation_date->CurrentValue, $this->creation_date->formatPattern()));
            $this->creation_date->PlaceHolder = RemoveHtml($this->creation_date->caption());

            // last_updated
            $this->last_updated->setupEditAttributes();
            $this->last_updated->EditValue = HtmlEncode(FormatDateTime($this->last_updated->CurrentValue, $this->last_updated->formatPattern()));
            $this->last_updated->PlaceHolder = RemoveHtml($this->last_updated->caption());

            // locked_by
            $this->locked_by->setupEditAttributes();
            $curVal = trim(strval($this->locked_by->CurrentValue));
            if ($curVal != "") {
                $this->locked_by->ViewValue = $this->locked_by->lookupCacheOption($curVal);
            } else {
                $this->locked_by->ViewValue = $this->locked_by->Lookup !== null && is_array($this->locked_by->lookupOptions()) && count($this->locked_by->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->locked_by->ViewValue !== null) { // Load from cache
                $this->locked_by->EditValue = array_values($this->locked_by->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter("`id`", "=", $this->locked_by->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->locked_by->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->locked_by->EditValue = $arwrk;
            }
            $this->locked_by->PlaceHolder = RemoveHtml($this->locked_by->caption());

            // lock_date
            $this->lock_date->setupEditAttributes();
            $this->lock_date->EditValue = HtmlEncode(FormatDateTime($this->lock_date->CurrentValue, $this->lock_date->formatPattern()));
            $this->lock_date->PlaceHolder = RemoveHtml($this->lock_date->caption());

            // Edit refer script

            // id
            $this->id->HrefValue = "";

            // project_id
            $this->project_id->HrefValue = "";

            // filename
            $this->filename->HrefValue = "";

            // path
            $this->path->HrefValue = "";
            $this->path->ExportHrefValue = $this->path->UploadPath . $this->path->Upload->DbValue;

            // type
            $this->type->HrefValue = "";

            // filetype
            $this->filetype->HrefValue = "";

            // filesize
            $this->filesize->HrefValue = "";

            // creation_date
            $this->creation_date->HrefValue = "";

            // last_updated
            $this->last_updated->HrefValue = "";

            // locked_by
            $this->locked_by->HrefValue = "";

            // lock_date
            $this->lock_date->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language, $Security;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
        if ($this->id->Visible && $this->id->Required) {
            if (!$this->id->IsDetailKey && EmptyValue($this->id->FormValue)) {
                $this->id->addErrorMessage(str_replace("%s", $this->id->caption(), $this->id->RequiredErrorMessage));
            }
        }
        if ($this->project_id->Visible && $this->project_id->Required) {
            if (!$this->project_id->IsDetailKey && EmptyValue($this->project_id->FormValue)) {
                $this->project_id->addErrorMessage(str_replace("%s", $this->project_id->caption(), $this->project_id->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->project_id->FormValue)) {
            $this->project_id->addErrorMessage($this->project_id->getErrorMessage(false));
        }
        if ($this->filename->Visible && $this->filename->Required) {
            if (!$this->filename->IsDetailKey && EmptyValue($this->filename->FormValue)) {
                $this->filename->addErrorMessage(str_replace("%s", $this->filename->caption(), $this->filename->RequiredErrorMessage));
            }
        }
        if ($this->path->Visible && $this->path->Required) {
            if ($this->path->Upload->FileName == "" && !$this->path->Upload->KeepFile) {
                $this->path->addErrorMessage(str_replace("%s", $this->path->caption(), $this->path->RequiredErrorMessage));
            }
        }
        if ($this->type->Visible && $this->type->Required) {
            if (!$this->type->IsDetailKey && EmptyValue($this->type->FormValue)) {
                $this->type->addErrorMessage(str_replace("%s", $this->type->caption(), $this->type->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->type->FormValue)) {
            $this->type->addErrorMessage($this->type->getErrorMessage(false));
        }
        if ($this->filetype->Visible && $this->filetype->Required) {
            if (!$this->filetype->IsDetailKey && EmptyValue($this->filetype->FormValue)) {
                $this->filetype->addErrorMessage(str_replace("%s", $this->filetype->caption(), $this->filetype->RequiredErrorMessage));
            }
        }
        if ($this->filesize->Visible && $this->filesize->Required) {
            if (!$this->filesize->IsDetailKey && EmptyValue($this->filesize->FormValue)) {
                $this->filesize->addErrorMessage(str_replace("%s", $this->filesize->caption(), $this->filesize->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->filesize->FormValue)) {
            $this->filesize->addErrorMessage($this->filesize->getErrorMessage(false));
        }
        if ($this->creation_date->Visible && $this->creation_date->Required) {
            if (!$this->creation_date->IsDetailKey && EmptyValue($this->creation_date->FormValue)) {
                $this->creation_date->addErrorMessage(str_replace("%s", $this->creation_date->caption(), $this->creation_date->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->creation_date->FormValue, $this->creation_date->formatPattern())) {
            $this->creation_date->addErrorMessage($this->creation_date->getErrorMessage(false));
        }
        if ($this->last_updated->Visible && $this->last_updated->Required) {
            if (!$this->last_updated->IsDetailKey && EmptyValue($this->last_updated->FormValue)) {
                $this->last_updated->addErrorMessage(str_replace("%s", $this->last_updated->caption(), $this->last_updated->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->last_updated->FormValue, $this->last_updated->formatPattern())) {
            $this->last_updated->addErrorMessage($this->last_updated->getErrorMessage(false));
        }
        if ($this->locked_by->Visible && $this->locked_by->Required) {
            if (!$this->locked_by->IsDetailKey && EmptyValue($this->locked_by->FormValue)) {
                $this->locked_by->addErrorMessage(str_replace("%s", $this->locked_by->caption(), $this->locked_by->RequiredErrorMessage));
            }
        }
        if ($this->lock_date->Visible && $this->lock_date->Required) {
            if (!$this->lock_date->IsDetailKey && EmptyValue($this->lock_date->FormValue)) {
                $this->lock_date->addErrorMessage(str_replace("%s", $this->lock_date->caption(), $this->lock_date->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->lock_date->FormValue, $this->lock_date->formatPattern())) {
            $this->lock_date->addErrorMessage($this->lock_date->getErrorMessage(false));
        }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
        }

        // Set new row
        $rsnew = [];

        // project_id
        if ($this->project_id->getSessionValue() != "") {
            $this->project_id->ReadOnly = true;
        }
        $this->project_id->setDbValueDef($rsnew, $this->project_id->CurrentValue, $this->project_id->ReadOnly);

        // filename
        $this->filename->setDbValueDef($rsnew, $this->filename->CurrentValue, $this->filename->ReadOnly);

        // path
        if ($this->path->Visible && !$this->path->ReadOnly && !$this->path->Upload->KeepFile) {
            $this->path->Upload->DbValue = $rsold['path']; // Get original value
            if ($this->path->Upload->FileName == "") {
                $rsnew['path'] = null;
            } else {
                $rsnew['path'] = $this->path->Upload->FileName;
            }
            $this->filetype->setDbValueDef($rsnew, trim($this->path->Upload->ContentType ?? ""));
            $this->filesize->setDbValueDef($rsnew, $this->path->Upload->FileSize);
        }

        // type
        $this->type->setDbValueDef($rsnew, $this->type->CurrentValue, $this->type->ReadOnly);

        // filetype

        // filesize

        // creation_date
        $this->creation_date->setDbValueDef($rsnew, UnFormatDateTime($this->creation_date->CurrentValue, $this->creation_date->formatPattern()), $this->creation_date->ReadOnly);

        // last_updated
        $this->last_updated->setDbValueDef($rsnew, UnFormatDateTime($this->last_updated->CurrentValue, $this->last_updated->formatPattern()), $this->last_updated->ReadOnly);

        // locked_by
        $this->locked_by->setDbValueDef($rsnew, $this->locked_by->CurrentValue, $this->locked_by->ReadOnly);

        // lock_date
        $this->lock_date->setDbValueDef($rsnew, UnFormatDateTime($this->lock_date->CurrentValue, $this->lock_date->formatPattern()), $this->lock_date->ReadOnly);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check referential integrity for master table 'project'
        $detailKeys = [];
        $keyValue = $rsnew['project_id'] ?? $rsold['project_id'];
        $detailKeys['project_id'] = $keyValue;
        $masterTable = Container("project");
        $masterFilter = $this->getMasterFilter($masterTable, $detailKeys);
        if (!EmptyValue($masterFilter)) {
            $rsmaster = $masterTable->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        } else { // Allow null value if not required field
            $validMasterRecord = $masterFilter === null;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "project", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
        }
        if ($this->path->Visible && !$this->path->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->path->Upload->DbValue) ? [] : [$this->path->htmlDecode($this->path->Upload->DbValue)];
            if (!EmptyValue($this->path->Upload->FileName)) {
                $newFiles = [$this->path->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->path, $this->path->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->path->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->path->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->path->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->path->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->path->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->path->setDbValueDef($rsnew, $this->path->Upload->FileName, $this->path->ReadOnly);
            }
        }

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
                if (!$editRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
                if ($this->path->Visible && !$this->path->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->path->Upload->DbValue) ? [] : [$this->path->htmlDecode($this->path->Upload->DbValue)];
                    if (!EmptyValue($this->path->Upload->FileName)) {
                        $newFiles = [$this->path->Upload->FileName];
                        $newFiles2 = [$this->path->htmlDecode($rsnew['path'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->path, $this->path->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->path->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadError7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->path->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $editRow;
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        $validMaster = false;
        $foreignKeys = [];
        // Get the keys for master table
        if (($master = Get(Config("TABLE_SHOW_MASTER"), Get(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                $validMaster = true;
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "project") {
                $validMaster = true;
                $masterTbl = Container("project");
                if (($parm = Get("fk_id", Get("project_id"))) !== null) {
                    $masterTbl->id->setQueryStringValue($parm);
                    $this->project_id->QueryStringValue = $masterTbl->id->QueryStringValue; // DO NOT change, master/detail key data type can be different
                    $this->project_id->setSessionValue($this->project_id->QueryStringValue);
                    $foreignKeys["project_id"] = $this->project_id->QueryStringValue;
                    if (!is_numeric($masterTbl->id->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        } elseif (($master = Post(Config("TABLE_SHOW_MASTER"), Post(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                    $validMaster = true;
                    $this->DbMasterFilter = "";
                    $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "project") {
                $validMaster = true;
                $masterTbl = Container("project");
                if (($parm = Post("fk_id", Post("project_id"))) !== null) {
                    $masterTbl->id->setFormValue($parm);
                    $this->project_id->FormValue = $masterTbl->id->FormValue;
                    $this->project_id->setSessionValue($this->project_id->FormValue);
                    $foreignKeys["project_id"] = $this->project_id->FormValue;
                    if (!is_numeric($masterTbl->id->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        }
        if ($validMaster) {
            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);
            $this->setSessionWhere($this->getDetailFilterFromSession());

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit() && !$this->isGridUpdate()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "project") {
                if (!array_key_exists("project_id", $foreignKeys)) { // Not current foreign key
                    $this->project_id->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Get master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Get detail filter from session
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("RecordsList");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("FileInProjectList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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
                case "x_locked_by":
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
