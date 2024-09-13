<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class UsersAdd extends Users
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "UsersAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "UsersAdd";

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
        $this->id->Visible = false;
        $this->_username->setVisibility();
        $this->_password->setVisibility();
        $this->_userlevel->setVisibility();
        $this->parent->setVisibility();
        $this->_email->setVisibility();
        $this->activated->setVisibility();
        $this->memo->setVisibility();
        $this->terms_of_use->setVisibility();
        $this->updated_at->setVisibility();
        $this->superuser->Visible = false;
        $this->all_access->setVisibility();
        $this->name->setVisibility();
        $this->affiliation->setVisibility();
        $this->background->setVisibility();
    }

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $DashboardReport, $DebugTimer, $UserTable;
        $this->TableVar = 'users';
        $this->TableName = 'users';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Table object (users)
        if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == PROJECT_NAMESPACE . "users") {
            $GLOBALS["users"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'users');
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
                    $result["view"] = $pageName == "UsersView"; // If View page, no primary button
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
    public $FormClassName = "ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $CopyRecord;

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
        $this->setupLookupOptions($this->_userlevel);
        $this->setupLookupOptions($this->terms_of_use);
        $this->setupLookupOptions($this->superuser);
        $this->setupLookupOptions($this->all_access);

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action", "") !== "") {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("id") ?? Route("id")) !== null) {
                $this->id->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
                $this->setKey($this->OldKey); // Set up record key
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record or default values
        $rsold = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$rsold) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("UsersList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($rsold)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "UsersList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "UsersView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "UsersList") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "UsersList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
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
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = ROWTYPE_ADD; // Render add type

        // Render row
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
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->superuser->DefaultValue = $this->superuser->getDefault(); // PHP
        $this->superuser->OldValue = $this->superuser->DefaultValue;
        $this->all_access->DefaultValue = $this->all_access->getDefault(); // PHP
        $this->all_access->OldValue = $this->all_access->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'username' first before field var 'x__username'
        $val = $CurrentForm->hasValue("username") ? $CurrentForm->getValue("username") : $CurrentForm->getValue("x__username");
        if (!$this->_username->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_username->Visible = false; // Disable update for API request
            } else {
                $this->_username->setFormValue($val);
            }
        }

        // Check field name 'password' first before field var 'x__password'
        $val = $CurrentForm->hasValue("password") ? $CurrentForm->getValue("password") : $CurrentForm->getValue("x__password");
        if (!$this->_password->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_password->Visible = false; // Disable update for API request
            } else {
                $this->_password->setFormValue($val);
            }
        }

        // Check field name 'userlevel' first before field var 'x__userlevel'
        $val = $CurrentForm->hasValue("userlevel") ? $CurrentForm->getValue("userlevel") : $CurrentForm->getValue("x__userlevel");
        if (!$this->_userlevel->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_userlevel->Visible = false; // Disable update for API request
            } else {
                $this->_userlevel->setFormValue($val);
            }
        }

        // Check field name 'parent' first before field var 'x_parent'
        $val = $CurrentForm->hasValue("parent") ? $CurrentForm->getValue("parent") : $CurrentForm->getValue("x_parent");
        if (!$this->parent->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->parent->Visible = false; // Disable update for API request
            } else {
                $this->parent->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'email' first before field var 'x__email'
        $val = $CurrentForm->hasValue("email") ? $CurrentForm->getValue("email") : $CurrentForm->getValue("x__email");
        if (!$this->_email->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_email->Visible = false; // Disable update for API request
            } else {
                $this->_email->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'activated' first before field var 'x_activated'
        $val = $CurrentForm->hasValue("activated") ? $CurrentForm->getValue("activated") : $CurrentForm->getValue("x_activated");
        if (!$this->activated->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->activated->Visible = false; // Disable update for API request
            } else {
                $this->activated->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'memo' first before field var 'x_memo'
        $val = $CurrentForm->hasValue("memo") ? $CurrentForm->getValue("memo") : $CurrentForm->getValue("x_memo");
        if (!$this->memo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->memo->Visible = false; // Disable update for API request
            } else {
                $this->memo->setFormValue($val);
            }
        }

        // Check field name 'terms_of_use' first before field var 'x_terms_of_use'
        $val = $CurrentForm->hasValue("terms_of_use") ? $CurrentForm->getValue("terms_of_use") : $CurrentForm->getValue("x_terms_of_use");
        if (!$this->terms_of_use->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->terms_of_use->Visible = false; // Disable update for API request
            } else {
                $this->terms_of_use->setFormValue($val);
            }
        }

        // Check field name 'updated_at' first before field var 'x_updated_at'
        $val = $CurrentForm->hasValue("updated_at") ? $CurrentForm->getValue("updated_at") : $CurrentForm->getValue("x_updated_at");
        if (!$this->updated_at->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->updated_at->Visible = false; // Disable update for API request
            } else {
                $this->updated_at->setFormValue($val, true, $validate);
            }
            $this->updated_at->CurrentValue = UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        }

        // Check field name 'all_access' first before field var 'x_all_access'
        $val = $CurrentForm->hasValue("all_access") ? $CurrentForm->getValue("all_access") : $CurrentForm->getValue("x_all_access");
        if (!$this->all_access->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->all_access->Visible = false; // Disable update for API request
            } else {
                $this->all_access->setFormValue($val);
            }
        }

        // Check field name 'name' first before field var 'x_name'
        $val = $CurrentForm->hasValue("name") ? $CurrentForm->getValue("name") : $CurrentForm->getValue("x_name");
        if (!$this->name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->name->Visible = false; // Disable update for API request
            } else {
                $this->name->setFormValue($val);
            }
        }

        // Check field name 'affiliation' first before field var 'x_affiliation'
        $val = $CurrentForm->hasValue("affiliation") ? $CurrentForm->getValue("affiliation") : $CurrentForm->getValue("x_affiliation");
        if (!$this->affiliation->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->affiliation->Visible = false; // Disable update for API request
            } else {
                $this->affiliation->setFormValue($val);
            }
        }

        // Check field name 'background' first before field var 'x_background'
        $val = $CurrentForm->hasValue("background") ? $CurrentForm->getValue("background") : $CurrentForm->getValue("x_background");
        if (!$this->background->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->background->Visible = false; // Disable update for API request
            } else {
                $this->background->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->_username->CurrentValue = $this->_username->FormValue;
        $this->_password->CurrentValue = $this->_password->FormValue;
        $this->_userlevel->CurrentValue = $this->_userlevel->FormValue;
        $this->parent->CurrentValue = $this->parent->FormValue;
        $this->_email->CurrentValue = $this->_email->FormValue;
        $this->activated->CurrentValue = $this->activated->FormValue;
        $this->memo->CurrentValue = $this->memo->FormValue;
        $this->terms_of_use->CurrentValue = $this->terms_of_use->FormValue;
        $this->updated_at->CurrentValue = $this->updated_at->FormValue;
        $this->updated_at->CurrentValue = UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        $this->all_access->CurrentValue = $this->all_access->FormValue;
        $this->name->CurrentValue = $this->name->FormValue;
        $this->affiliation->CurrentValue = $this->affiliation->FormValue;
        $this->background->CurrentValue = $this->background->FormValue;
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
        $this->_username->setDbValue($row['username']);
        $this->_password->setDbValue($row['password']);
        $this->_userlevel->setDbValue($row['userlevel']);
        $this->parent->setDbValue($row['parent']);
        $this->_email->setDbValue($row['email']);
        $this->activated->setDbValue($row['activated']);
        $this->memo->setDbValue($row['memo']);
        $this->terms_of_use->setDbValue($row['terms_of_use']);
        $this->updated_at->setDbValue($row['updated_at']);
        $this->superuser->setDbValue($row['superuser']);
        $this->all_access->setDbValue($row['all_access']);
        $this->name->setDbValue($row['name']);
        $this->affiliation->setDbValue($row['affiliation']);
        $this->background->setDbValue($row['background']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['username'] = $this->_username->DefaultValue;
        $row['password'] = $this->_password->DefaultValue;
        $row['userlevel'] = $this->_userlevel->DefaultValue;
        $row['parent'] = $this->parent->DefaultValue;
        $row['email'] = $this->_email->DefaultValue;
        $row['activated'] = $this->activated->DefaultValue;
        $row['memo'] = $this->memo->DefaultValue;
        $row['terms_of_use'] = $this->terms_of_use->DefaultValue;
        $row['updated_at'] = $this->updated_at->DefaultValue;
        $row['superuser'] = $this->superuser->DefaultValue;
        $row['all_access'] = $this->all_access->DefaultValue;
        $row['name'] = $this->name->DefaultValue;
        $row['affiliation'] = $this->affiliation->DefaultValue;
        $row['background'] = $this->background->DefaultValue;
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

        // username
        $this->_username->RowCssClass = "row";

        // password
        $this->_password->RowCssClass = "row";

        // userlevel
        $this->_userlevel->RowCssClass = "row";

        // parent
        $this->parent->RowCssClass = "row";

        // email
        $this->_email->RowCssClass = "row";

        // activated
        $this->activated->RowCssClass = "row";

        // memo
        $this->memo->RowCssClass = "row";

        // terms_of_use
        $this->terms_of_use->RowCssClass = "row";

        // updated_at
        $this->updated_at->RowCssClass = "row";

        // superuser
        $this->superuser->RowCssClass = "row";

        // all_access
        $this->all_access->RowCssClass = "row";

        // name
        $this->name->RowCssClass = "row";

        // affiliation
        $this->affiliation->RowCssClass = "row";

        // background
        $this->background->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;

            // username
            $this->_username->ViewValue = $this->_username->CurrentValue;

            // password
            $this->_password->ViewValue = $Language->phrase("PasswordMask");

            // userlevel
            if ($Security->canAdmin()) { // System admin
                if (strval($this->_userlevel->CurrentValue) != "") {
                    $this->_userlevel->ViewValue = $this->_userlevel->optionCaption($this->_userlevel->CurrentValue);
                } else {
                    $this->_userlevel->ViewValue = null;
                }
            } else {
                $this->_userlevel->ViewValue = $Language->phrase("PasswordMask");
            }

            // parent
            $this->parent->ViewValue = $this->parent->CurrentValue;
            $this->parent->ViewValue = FormatNumber($this->parent->ViewValue, $this->parent->formatPattern());

            // email
            $this->_email->ViewValue = $this->_email->CurrentValue;

            // activated
            $this->activated->ViewValue = $this->activated->CurrentValue;
            $this->activated->ViewValue = FormatNumber($this->activated->ViewValue, $this->activated->formatPattern());

            // memo
            $this->memo->ViewValue = $this->memo->CurrentValue;

            // terms_of_use
            if (ConvertToBool($this->terms_of_use->CurrentValue)) {
                $this->terms_of_use->ViewValue = $this->terms_of_use->tagCaption(1) != "" ? $this->terms_of_use->tagCaption(1) : "Y";
            } else {
                $this->terms_of_use->ViewValue = $this->terms_of_use->tagCaption(2) != "" ? $this->terms_of_use->tagCaption(2) : "N";
            }

            // updated_at
            $this->updated_at->ViewValue = $this->updated_at->CurrentValue;
            $this->updated_at->ViewValue = FormatDateTime($this->updated_at->ViewValue, $this->updated_at->formatPattern());

            // all_access
            if (strval($this->all_access->CurrentValue) != "") {
                $this->all_access->ViewValue = $this->all_access->optionCaption($this->all_access->CurrentValue);
            } else {
                $this->all_access->ViewValue = null;
            }

            // name
            $this->name->ViewValue = $this->name->CurrentValue;

            // affiliation
            $this->affiliation->ViewValue = $this->affiliation->CurrentValue;

            // background
            $this->background->ViewValue = $this->background->CurrentValue;

            // username
            $this->_username->HrefValue = "";

            // password
            $this->_password->HrefValue = "";

            // userlevel
            $this->_userlevel->HrefValue = "";

            // parent
            $this->parent->HrefValue = "";

            // email
            $this->_email->HrefValue = "";

            // activated
            $this->activated->HrefValue = "";

            // memo
            $this->memo->HrefValue = "";

            // terms_of_use
            $this->terms_of_use->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // all_access
            $this->all_access->HrefValue = "";

            // name
            $this->name->HrefValue = "";

            // affiliation
            $this->affiliation->HrefValue = "";

            // background
            $this->background->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // username
            $this->_username->setupEditAttributes();
            if (!$this->_username->Raw) {
                $this->_username->CurrentValue = HtmlDecode($this->_username->CurrentValue);
            }
            $this->_username->EditValue = HtmlEncode($this->_username->CurrentValue);
            $this->_username->PlaceHolder = RemoveHtml($this->_username->caption());

            // password
            $this->_password->setupEditAttributes();
            $this->_password->PlaceHolder = RemoveHtml($this->_password->caption());

            // userlevel
            $this->_userlevel->setupEditAttributes();
            if (!$Security->canAdmin()) { // System admin
                $this->_userlevel->EditValue = $Language->phrase("PasswordMask");
            } else {
                $this->_userlevel->EditValue = $this->_userlevel->options(true);
                $this->_userlevel->PlaceHolder = RemoveHtml($this->_userlevel->caption());
            }

            // parent
            $this->parent->setupEditAttributes();
            $this->parent->EditValue = $this->parent->CurrentValue;
            $this->parent->PlaceHolder = RemoveHtml($this->parent->caption());
            if (strval($this->parent->EditValue) != "" && is_numeric($this->parent->EditValue)) {
                $this->parent->EditValue = FormatNumber($this->parent->EditValue, null);
            }

            // email
            $this->_email->setupEditAttributes();
            if (!$this->_email->Raw) {
                $this->_email->CurrentValue = HtmlDecode($this->_email->CurrentValue);
            }
            $this->_email->EditValue = HtmlEncode($this->_email->CurrentValue);
            $this->_email->PlaceHolder = RemoveHtml($this->_email->caption());

            // activated
            $this->activated->setupEditAttributes();
            $this->activated->EditValue = $this->activated->CurrentValue;
            $this->activated->PlaceHolder = RemoveHtml($this->activated->caption());
            if (strval($this->activated->EditValue) != "" && is_numeric($this->activated->EditValue)) {
                $this->activated->EditValue = FormatNumber($this->activated->EditValue, null);
            }

            // memo
            $this->memo->setupEditAttributes();
            $this->memo->EditValue = HtmlEncode($this->memo->CurrentValue);
            $this->memo->PlaceHolder = RemoveHtml($this->memo->caption());

            // terms_of_use
            $this->terms_of_use->EditValue = $this->terms_of_use->options(false);
            $this->terms_of_use->PlaceHolder = RemoveHtml($this->terms_of_use->caption());

            // updated_at
            $this->updated_at->setupEditAttributes();
            $this->updated_at->EditValue = HtmlEncode(FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()));
            $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

            // all_access
            $this->all_access->EditValue = $this->all_access->options(false);
            $this->all_access->PlaceHolder = RemoveHtml($this->all_access->caption());

            // name
            $this->name->setupEditAttributes();
            if (!$this->name->Raw) {
                $this->name->CurrentValue = HtmlDecode($this->name->CurrentValue);
            }
            $this->name->EditValue = HtmlEncode($this->name->CurrentValue);
            $this->name->PlaceHolder = RemoveHtml($this->name->caption());

            // affiliation
            $this->affiliation->setupEditAttributes();
            if (!$this->affiliation->Raw) {
                $this->affiliation->CurrentValue = HtmlDecode($this->affiliation->CurrentValue);
            }
            $this->affiliation->EditValue = HtmlEncode($this->affiliation->CurrentValue);
            $this->affiliation->PlaceHolder = RemoveHtml($this->affiliation->caption());

            // background
            $this->background->setupEditAttributes();
            if (!$this->background->Raw) {
                $this->background->CurrentValue = HtmlDecode($this->background->CurrentValue);
            }
            $this->background->EditValue = HtmlEncode($this->background->CurrentValue);
            $this->background->PlaceHolder = RemoveHtml($this->background->caption());

            // Add refer script

            // username
            $this->_username->HrefValue = "";

            // password
            $this->_password->HrefValue = "";

            // userlevel
            $this->_userlevel->HrefValue = "";

            // parent
            $this->parent->HrefValue = "";

            // email
            $this->_email->HrefValue = "";

            // activated
            $this->activated->HrefValue = "";

            // memo
            $this->memo->HrefValue = "";

            // terms_of_use
            $this->terms_of_use->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";

            // all_access
            $this->all_access->HrefValue = "";

            // name
            $this->name->HrefValue = "";

            // affiliation
            $this->affiliation->HrefValue = "";

            // background
            $this->background->HrefValue = "";
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
        if ($this->_username->Visible && $this->_username->Required) {
            if (!$this->_username->IsDetailKey && EmptyValue($this->_username->FormValue)) {
                $this->_username->addErrorMessage(str_replace("%s", $this->_username->caption(), $this->_username->RequiredErrorMessage));
            }
        }
        if (!$this->_username->Raw && Config("REMOVE_XSS") && CheckUsername($this->_username->FormValue)) {
            $this->_username->addErrorMessage($Language->phrase("InvalidUsernameChars"));
        }
        if ($this->_password->Visible && $this->_password->Required) {
            if (!$this->_password->IsDetailKey && EmptyValue($this->_password->FormValue)) {
                $this->_password->addErrorMessage(str_replace("%s", $this->_password->caption(), $this->_password->RequiredErrorMessage));
            }
        }
        if (!$this->_password->Raw && Config("REMOVE_XSS") && CheckPassword($this->_password->FormValue)) {
            $this->_password->addErrorMessage($Language->phrase("InvalidPasswordChars"));
        }
        if ($this->_userlevel->Visible && $this->_userlevel->Required) {
            if ($Security->canAdmin() && !$this->_userlevel->IsDetailKey && EmptyValue($this->_userlevel->FormValue)) {
                $this->_userlevel->addErrorMessage(str_replace("%s", $this->_userlevel->caption(), $this->_userlevel->RequiredErrorMessage));
            }
        }
        if ($this->parent->Visible && $this->parent->Required) {
            if (!$this->parent->IsDetailKey && EmptyValue($this->parent->FormValue)) {
                $this->parent->addErrorMessage(str_replace("%s", $this->parent->caption(), $this->parent->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->parent->FormValue)) {
            $this->parent->addErrorMessage($this->parent->getErrorMessage(false));
        }
        if ($this->_email->Visible && $this->_email->Required) {
            if (!$this->_email->IsDetailKey && EmptyValue($this->_email->FormValue)) {
                $this->_email->addErrorMessage(str_replace("%s", $this->_email->caption(), $this->_email->RequiredErrorMessage));
            }
        }
        if (!CheckEmail($this->_email->FormValue)) {
            $this->_email->addErrorMessage($this->_email->getErrorMessage(false));
        }
        if ($this->activated->Visible && $this->activated->Required) {
            if (!$this->activated->IsDetailKey && EmptyValue($this->activated->FormValue)) {
                $this->activated->addErrorMessage(str_replace("%s", $this->activated->caption(), $this->activated->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->activated->FormValue)) {
            $this->activated->addErrorMessage($this->activated->getErrorMessage(false));
        }
        if ($this->memo->Visible && $this->memo->Required) {
            if (!$this->memo->IsDetailKey && EmptyValue($this->memo->FormValue)) {
                $this->memo->addErrorMessage(str_replace("%s", $this->memo->caption(), $this->memo->RequiredErrorMessage));
            }
        }
        if ($this->terms_of_use->Visible && $this->terms_of_use->Required) {
            if ($this->terms_of_use->FormValue == "") {
                $this->terms_of_use->addErrorMessage(str_replace("%s", $this->terms_of_use->caption(), $this->terms_of_use->RequiredErrorMessage));
            }
        }
        if ($this->updated_at->Visible && $this->updated_at->Required) {
            if (!$this->updated_at->IsDetailKey && EmptyValue($this->updated_at->FormValue)) {
                $this->updated_at->addErrorMessage(str_replace("%s", $this->updated_at->caption(), $this->updated_at->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->updated_at->FormValue, $this->updated_at->formatPattern())) {
            $this->updated_at->addErrorMessage($this->updated_at->getErrorMessage(false));
        }
        if ($this->all_access->Visible && $this->all_access->Required) {
            if ($this->all_access->FormValue == "") {
                $this->all_access->addErrorMessage(str_replace("%s", $this->all_access->caption(), $this->all_access->RequiredErrorMessage));
            }
        }
        if ($this->name->Visible && $this->name->Required) {
            if (!$this->name->IsDetailKey && EmptyValue($this->name->FormValue)) {
                $this->name->addErrorMessage(str_replace("%s", $this->name->caption(), $this->name->RequiredErrorMessage));
            }
        }
        if ($this->affiliation->Visible && $this->affiliation->Required) {
            if (!$this->affiliation->IsDetailKey && EmptyValue($this->affiliation->FormValue)) {
                $this->affiliation->addErrorMessage(str_replace("%s", $this->affiliation->caption(), $this->affiliation->RequiredErrorMessage));
            }
        }
        if ($this->background->Visible && $this->background->Required) {
            if (!$this->background->IsDetailKey && EmptyValue($this->background->FormValue)) {
                $this->background->addErrorMessage(str_replace("%s", $this->background->caption(), $this->background->RequiredErrorMessage));
            }
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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set new row
        $rsnew = [];

        // username
        $this->_username->setDbValueDef($rsnew, $this->_username->CurrentValue, false);

        // password
        if (!IsMaskedPassword($this->_password->CurrentValue)) {
            $this->_password->setDbValueDef($rsnew, $this->_password->CurrentValue, false);
        }

        // userlevel
        if ($Security->canAdmin()) { // System admin
            $this->_userlevel->setDbValueDef($rsnew, $this->_userlevel->CurrentValue, false);
        }

        // parent
        $this->parent->setDbValueDef($rsnew, $this->parent->CurrentValue, false);

        // email
        $this->_email->setDbValueDef($rsnew, $this->_email->CurrentValue, false);

        // activated
        $this->activated->setDbValueDef($rsnew, $this->activated->CurrentValue, false);

        // memo
        $this->memo->setDbValueDef($rsnew, $this->memo->CurrentValue, false);

        // terms_of_use
        $tmpBool = $this->terms_of_use->CurrentValue;
        if ($tmpBool != "Y" && $tmpBool != "N") {
            $tmpBool = !empty($tmpBool) ? "Y" : "N";
        }
        $this->terms_of_use->setDbValueDef($rsnew, $tmpBool, false);

        // updated_at
        $this->updated_at->setDbValueDef($rsnew, UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern()), false);

        // all_access
        $this->all_access->setDbValueDef($rsnew, $this->all_access->CurrentValue, strval($this->all_access->CurrentValue) == "");

        // name
        $this->name->setDbValueDef($rsnew, $this->name->CurrentValue, false);

        // affiliation
        $this->affiliation->setDbValueDef($rsnew, $this->affiliation->CurrentValue, false);

        // background
        $this->background->setDbValueDef($rsnew, $this->background->CurrentValue, false);

        // Update current values
        $this->setCurrentValues($rsnew);
        if ($this->_username->CurrentValue != "") { // Check field with unique index
            $filter = "(`username` = '" . AdjustSql($this->_username->CurrentValue, $this->Dbid) . "')";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->_username->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->_username->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            } elseif (!EmptyValue($this->DbErrorMessage)) { // Show database error
                $this->setFailureMessage($this->DbErrorMessage);
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Write JSON response
        if (IsJsonResponse() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_ADD_ACTION"), $table => $row]);
        }
        return $addRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("RecordsList");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("UsersList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
                case "x__userlevel":
                    break;
                case "x_terms_of_use":
                    break;
                case "x_superuser":
                    break;
                case "x_all_access":
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
