<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class RecordsAdd extends Records
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "RecordsAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "RecordsAdd";

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
        $this->name->setVisibility();
        $this->owner_id->setVisibility();
        $this->owner->setVisibility();
        $this->record_group_id->Visible = false;
        $this->c_holder->Visible = false;
        $this->c_cates->setVisibility();
        $this->c_author->Visible = false;
        $this->c_lang->Visible = false;
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
        $this->private_ciphertext->Visible = false;
        $this->document_types->setVisibility();
        $this->paper->setVisibility();
        $this->additional_information->setVisibility();
        $this->creator_id->setVisibility();
        $this->access_mode->setVisibility();
        $this->creation_date->Visible = false;
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
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

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

        // Set up detail parameters
        $this->setupDetailParms();

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
                    $this->terminate("RecordsList"); // No matching record, return to list
                    return;
                }

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($rsold)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->GetViewUrl();
                    if (GetPageName($returnUrl) == "RecordsList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "RecordsView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "RecordsList") {
                            Container("flash")->addMessage("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "RecordsList"; // Return list page content
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

                    // Set up detail parameters
                    $this->setupDetailParms();
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
        $this->owner_id->DefaultValue = $this->owner_id->getDefault(); // PHP
        $this->owner_id->OldValue = $this->owner_id->DefaultValue;
        $this->inline_cleartext->DefaultValue = $this->inline_cleartext->getDefault(); // PHP
        $this->inline_cleartext->OldValue = $this->inline_cleartext->DefaultValue;
        $this->inline_plaintext->DefaultValue = $this->inline_plaintext->getDefault(); // PHP
        $this->inline_plaintext->OldValue = $this->inline_plaintext->DefaultValue;
        $this->private_ciphertext->DefaultValue = $this->private_ciphertext->getDefault(); // PHP
        $this->private_ciphertext->OldValue = $this->private_ciphertext->DefaultValue;
        $this->creator_id->DefaultValue = $this->creator_id->getDefault(); // PHP
        $this->creator_id->OldValue = $this->creator_id->DefaultValue;
        $this->access_mode->DefaultValue = $this->access_mode->getDefault(); // PHP
        $this->access_mode->OldValue = $this->access_mode->DefaultValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'name' first before field var 'x_name'
        $val = $CurrentForm->hasValue("name") ? $CurrentForm->getValue("name") : $CurrentForm->getValue("x_name");
        if (!$this->name->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->name->Visible = false; // Disable update for API request
            } else {
                $this->name->setFormValue($val);
            }
        }

        // Check field name 'owner_id' first before field var 'x_owner_id'
        $val = $CurrentForm->hasValue("owner_id") ? $CurrentForm->getValue("owner_id") : $CurrentForm->getValue("x_owner_id");
        if (!$this->owner_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->owner_id->Visible = false; // Disable update for API request
            } else {
                $this->owner_id->setFormValue($val);
            }
        }

        // Check field name 'owner' first before field var 'x_owner'
        $val = $CurrentForm->hasValue("owner") ? $CurrentForm->getValue("owner") : $CurrentForm->getValue("x_owner");
        if (!$this->owner->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->owner->Visible = false; // Disable update for API request
            } else {
                $this->owner->setFormValue($val);
            }
        }

        // Check field name 'c_cates' first before field var 'x_c_cates'
        $val = $CurrentForm->hasValue("c_cates") ? $CurrentForm->getValue("c_cates") : $CurrentForm->getValue("x_c_cates");
        if (!$this->c_cates->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->c_cates->Visible = false; // Disable update for API request
            } else {
                $this->c_cates->setFormValue($val);
            }
        }

        // Check field name 'current_country' first before field var 'x_current_country'
        $val = $CurrentForm->hasValue("current_country") ? $CurrentForm->getValue("current_country") : $CurrentForm->getValue("x_current_country");
        if (!$this->current_country->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->current_country->Visible = false; // Disable update for API request
            } else {
                $this->current_country->setFormValue($val);
            }
        }

        // Check field name 'current_city' first before field var 'x_current_city'
        $val = $CurrentForm->hasValue("current_city") ? $CurrentForm->getValue("current_city") : $CurrentForm->getValue("x_current_city");
        if (!$this->current_city->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->current_city->Visible = false; // Disable update for API request
            } else {
                $this->current_city->setFormValue($val);
            }
        }

        // Check field name 'current_holder' first before field var 'x_current_holder'
        $val = $CurrentForm->hasValue("current_holder") ? $CurrentForm->getValue("current_holder") : $CurrentForm->getValue("x_current_holder");
        if (!$this->current_holder->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->current_holder->Visible = false; // Disable update for API request
            } else {
                $this->current_holder->setFormValue($val);
            }
        }

        // Check field name 'author' first before field var 'x_author'
        $val = $CurrentForm->hasValue("author") ? $CurrentForm->getValue("author") : $CurrentForm->getValue("x_author");
        if (!$this->author->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->author->Visible = false; // Disable update for API request
            } else {
                $this->author->setFormValue($val);
            }
        }

        // Check field name 'sender' first before field var 'x_sender'
        $val = $CurrentForm->hasValue("sender") ? $CurrentForm->getValue("sender") : $CurrentForm->getValue("x_sender");
        if (!$this->sender->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sender->Visible = false; // Disable update for API request
            } else {
                $this->sender->setFormValue($val);
            }
        }

        // Check field name 'receiver' first before field var 'x_receiver'
        $val = $CurrentForm->hasValue("receiver") ? $CurrentForm->getValue("receiver") : $CurrentForm->getValue("x_receiver");
        if (!$this->receiver->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->receiver->Visible = false; // Disable update for API request
            } else {
                $this->receiver->setFormValue($val);
            }
        }

        // Check field name 'origin_region' first before field var 'x_origin_region'
        $val = $CurrentForm->hasValue("origin_region") ? $CurrentForm->getValue("origin_region") : $CurrentForm->getValue("x_origin_region");
        if (!$this->origin_region->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->origin_region->Visible = false; // Disable update for API request
            } else {
                $this->origin_region->setFormValue($val);
            }
        }

        // Check field name 'origin_city' first before field var 'x_origin_city'
        $val = $CurrentForm->hasValue("origin_city") ? $CurrentForm->getValue("origin_city") : $CurrentForm->getValue("x_origin_city");
        if (!$this->origin_city->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->origin_city->Visible = false; // Disable update for API request
            } else {
                $this->origin_city->setFormValue($val);
            }
        }

        // Check field name 'start_year' first before field var 'x_start_year'
        $val = $CurrentForm->hasValue("start_year") ? $CurrentForm->getValue("start_year") : $CurrentForm->getValue("x_start_year");
        if (!$this->start_year->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->start_year->Visible = false; // Disable update for API request
            } else {
                $this->start_year->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'start_month' first before field var 'x_start_month'
        $val = $CurrentForm->hasValue("start_month") ? $CurrentForm->getValue("start_month") : $CurrentForm->getValue("x_start_month");
        if (!$this->start_month->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->start_month->Visible = false; // Disable update for API request
            } else {
                $this->start_month->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'start_day' first before field var 'x_start_day'
        $val = $CurrentForm->hasValue("start_day") ? $CurrentForm->getValue("start_day") : $CurrentForm->getValue("x_start_day");
        if (!$this->start_day->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->start_day->Visible = false; // Disable update for API request
            } else {
                $this->start_day->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'end_year' first before field var 'x_end_year'
        $val = $CurrentForm->hasValue("end_year") ? $CurrentForm->getValue("end_year") : $CurrentForm->getValue("x_end_year");
        if (!$this->end_year->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->end_year->Visible = false; // Disable update for API request
            } else {
                $this->end_year->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'end_month' first before field var 'x_end_month'
        $val = $CurrentForm->hasValue("end_month") ? $CurrentForm->getValue("end_month") : $CurrentForm->getValue("x_end_month");
        if (!$this->end_month->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->end_month->Visible = false; // Disable update for API request
            } else {
                $this->end_month->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'end_day' first before field var 'x_end_day'
        $val = $CurrentForm->hasValue("end_day") ? $CurrentForm->getValue("end_day") : $CurrentForm->getValue("x_end_day");
        if (!$this->end_day->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->end_day->Visible = false; // Disable update for API request
            } else {
                $this->end_day->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'record_type' first before field var 'x_record_type'
        $val = $CurrentForm->hasValue("record_type") ? $CurrentForm->getValue("record_type") : $CurrentForm->getValue("x_record_type");
        if (!$this->record_type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->record_type->Visible = false; // Disable update for API request
            } else {
                $this->record_type->setFormValue($val);
            }
        }

        // Check field name 'status' first before field var 'x_status'
        $val = $CurrentForm->hasValue("status") ? $CurrentForm->getValue("status") : $CurrentForm->getValue("x_status");
        if (!$this->status->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->status->Visible = false; // Disable update for API request
            } else {
                $this->status->setFormValue($val);
            }
        }

        // Check field name 'symbol_sets' first before field var 'x_symbol_sets'
        $val = $CurrentForm->hasValue("symbol_sets") ? $CurrentForm->getValue("symbol_sets") : $CurrentForm->getValue("x_symbol_sets");
        if (!$this->symbol_sets->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->symbol_sets->Visible = false; // Disable update for API request
            } else {
                $this->symbol_sets->setFormValue($val);
            }
        }

        // Check field name 'cipher_types' first before field var 'x_cipher_types'
        $val = $CurrentForm->hasValue("cipher_types") ? $CurrentForm->getValue("cipher_types") : $CurrentForm->getValue("x_cipher_types");
        if (!$this->cipher_types->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cipher_types->Visible = false; // Disable update for API request
            } else {
                $this->cipher_types->setFormValue($val);
            }
        }

        // Check field name 'cipher_type_other' first before field var 'x_cipher_type_other'
        $val = $CurrentForm->hasValue("cipher_type_other") ? $CurrentForm->getValue("cipher_type_other") : $CurrentForm->getValue("x_cipher_type_other");
        if (!$this->cipher_type_other->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cipher_type_other->Visible = false; // Disable update for API request
            } else {
                $this->cipher_type_other->setFormValue($val);
            }
        }

        // Check field name 'symbol_set_other' first before field var 'x_symbol_set_other'
        $val = $CurrentForm->hasValue("symbol_set_other") ? $CurrentForm->getValue("symbol_set_other") : $CurrentForm->getValue("x_symbol_set_other");
        if (!$this->symbol_set_other->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->symbol_set_other->Visible = false; // Disable update for API request
            } else {
                $this->symbol_set_other->setFormValue($val);
            }
        }

        // Check field name 'number_of_pages' first before field var 'x_number_of_pages'
        $val = $CurrentForm->hasValue("number_of_pages") ? $CurrentForm->getValue("number_of_pages") : $CurrentForm->getValue("x_number_of_pages");
        if (!$this->number_of_pages->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->number_of_pages->Visible = false; // Disable update for API request
            } else {
                $this->number_of_pages->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'inline_cleartext' first before field var 'x_inline_cleartext'
        $val = $CurrentForm->hasValue("inline_cleartext") ? $CurrentForm->getValue("inline_cleartext") : $CurrentForm->getValue("x_inline_cleartext");
        if (!$this->inline_cleartext->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->inline_cleartext->Visible = false; // Disable update for API request
            } else {
                $this->inline_cleartext->setFormValue($val);
            }
        }

        // Check field name 'inline_plaintext' first before field var 'x_inline_plaintext'
        $val = $CurrentForm->hasValue("inline_plaintext") ? $CurrentForm->getValue("inline_plaintext") : $CurrentForm->getValue("x_inline_plaintext");
        if (!$this->inline_plaintext->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->inline_plaintext->Visible = false; // Disable update for API request
            } else {
                $this->inline_plaintext->setFormValue($val);
            }
        }

        // Check field name 'cleartext_lang' first before field var 'x_cleartext_lang'
        $val = $CurrentForm->hasValue("cleartext_lang") ? $CurrentForm->getValue("cleartext_lang") : $CurrentForm->getValue("x_cleartext_lang");
        if (!$this->cleartext_lang->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->cleartext_lang->Visible = false; // Disable update for API request
            } else {
                $this->cleartext_lang->setFormValue($val);
            }
        }

        // Check field name 'plaintext_lang' first before field var 'x_plaintext_lang'
        $val = $CurrentForm->hasValue("plaintext_lang") ? $CurrentForm->getValue("plaintext_lang") : $CurrentForm->getValue("x_plaintext_lang");
        if (!$this->plaintext_lang->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->plaintext_lang->Visible = false; // Disable update for API request
            } else {
                $this->plaintext_lang->setFormValue($val);
            }
        }

        // Check field name 'document_types' first before field var 'x_document_types'
        $val = $CurrentForm->hasValue("document_types") ? $CurrentForm->getValue("document_types") : $CurrentForm->getValue("x_document_types");
        if (!$this->document_types->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->document_types->Visible = false; // Disable update for API request
            } else {
                $this->document_types->setFormValue($val);
            }
        }

        // Check field name 'paper' first before field var 'x_paper'
        $val = $CurrentForm->hasValue("paper") ? $CurrentForm->getValue("paper") : $CurrentForm->getValue("x_paper");
        if (!$this->paper->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->paper->Visible = false; // Disable update for API request
            } else {
                $this->paper->setFormValue($val);
            }
        }

        // Check field name 'additional_information' first before field var 'x_additional_information'
        $val = $CurrentForm->hasValue("additional_information") ? $CurrentForm->getValue("additional_information") : $CurrentForm->getValue("x_additional_information");
        if (!$this->additional_information->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->additional_information->Visible = false; // Disable update for API request
            } else {
                $this->additional_information->setFormValue($val);
            }
        }

        // Check field name 'creator_id' first before field var 'x_creator_id'
        $val = $CurrentForm->hasValue("creator_id") ? $CurrentForm->getValue("creator_id") : $CurrentForm->getValue("x_creator_id");
        if (!$this->creator_id->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->creator_id->Visible = false; // Disable update for API request
            } else {
                $this->creator_id->setFormValue($val);
            }
        }

        // Check field name 'access_mode' first before field var 'x_access_mode'
        $val = $CurrentForm->hasValue("access_mode") ? $CurrentForm->getValue("access_mode") : $CurrentForm->getValue("x_access_mode");
        if (!$this->access_mode->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->access_mode->Visible = false; // Disable update for API request
            } else {
                $this->access_mode->setFormValue($val);
            }
        }

        // Check field name 'km_encoded_plaintext_type' first before field var 'x_km_encoded_plaintext_type'
        $val = $CurrentForm->hasValue("km_encoded_plaintext_type") ? $CurrentForm->getValue("km_encoded_plaintext_type") : $CurrentForm->getValue("x_km_encoded_plaintext_type");
        if (!$this->km_encoded_plaintext_type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_encoded_plaintext_type->Visible = false; // Disable update for API request
            } else {
                $this->km_encoded_plaintext_type->setFormValue($val);
            }
        }

        // Check field name 'km_numbers' first before field var 'x_km_numbers'
        $val = $CurrentForm->hasValue("km_numbers") ? $CurrentForm->getValue("km_numbers") : $CurrentForm->getValue("x_km_numbers");
        if (!$this->km_numbers->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_numbers->Visible = false; // Disable update for API request
            } else {
                $this->km_numbers->setFormValue($val);
            }
        }

        // Check field name 'km_content_words' first before field var 'x_km_content_words'
        $val = $CurrentForm->hasValue("km_content_words") ? $CurrentForm->getValue("km_content_words") : $CurrentForm->getValue("x_km_content_words");
        if (!$this->km_content_words->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_content_words->Visible = false; // Disable update for API request
            } else {
                $this->km_content_words->setFormValue($val);
            }
        }

        // Check field name 'km_function_words' first before field var 'x_km_function_words'
        $val = $CurrentForm->hasValue("km_function_words") ? $CurrentForm->getValue("km_function_words") : $CurrentForm->getValue("x_km_function_words");
        if (!$this->km_function_words->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_function_words->Visible = false; // Disable update for API request
            } else {
                $this->km_function_words->setFormValue($val);
            }
        }

        // Check field name 'km_syllables' first before field var 'x_km_syllables'
        $val = $CurrentForm->hasValue("km_syllables") ? $CurrentForm->getValue("km_syllables") : $CurrentForm->getValue("x_km_syllables");
        if (!$this->km_syllables->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_syllables->Visible = false; // Disable update for API request
            } else {
                $this->km_syllables->setFormValue($val);
            }
        }

        // Check field name 'km_morphological_endings' first before field var 'x_km_morphological_endings'
        $val = $CurrentForm->hasValue("km_morphological_endings") ? $CurrentForm->getValue("km_morphological_endings") : $CurrentForm->getValue("x_km_morphological_endings");
        if (!$this->km_morphological_endings->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_morphological_endings->Visible = false; // Disable update for API request
            } else {
                $this->km_morphological_endings->setFormValue($val);
            }
        }

        // Check field name 'km_phrases' first before field var 'x_km_phrases'
        $val = $CurrentForm->hasValue("km_phrases") ? $CurrentForm->getValue("km_phrases") : $CurrentForm->getValue("x_km_phrases");
        if (!$this->km_phrases->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_phrases->Visible = false; // Disable update for API request
            } else {
                $this->km_phrases->setFormValue($val);
            }
        }

        // Check field name 'km_sentences' first before field var 'x_km_sentences'
        $val = $CurrentForm->hasValue("km_sentences") ? $CurrentForm->getValue("km_sentences") : $CurrentForm->getValue("x_km_sentences");
        if (!$this->km_sentences->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_sentences->Visible = false; // Disable update for API request
            } else {
                $this->km_sentences->setFormValue($val);
            }
        }

        // Check field name 'km_punctuation' first before field var 'x_km_punctuation'
        $val = $CurrentForm->hasValue("km_punctuation") ? $CurrentForm->getValue("km_punctuation") : $CurrentForm->getValue("x_km_punctuation");
        if (!$this->km_punctuation->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_punctuation->Visible = false; // Disable update for API request
            } else {
                $this->km_punctuation->setFormValue($val);
            }
        }

        // Check field name 'km_nomenclature_size' first before field var 'x_km_nomenclature_size'
        $val = $CurrentForm->hasValue("km_nomenclature_size") ? $CurrentForm->getValue("km_nomenclature_size") : $CurrentForm->getValue("x_km_nomenclature_size");
        if (!$this->km_nomenclature_size->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_nomenclature_size->Visible = false; // Disable update for API request
            } else {
                $this->km_nomenclature_size->setFormValue($val);
            }
        }

        // Check field name 'km_sections' first before field var 'x_km_sections'
        $val = $CurrentForm->hasValue("km_sections") ? $CurrentForm->getValue("km_sections") : $CurrentForm->getValue("x_km_sections");
        if (!$this->km_sections->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_sections->Visible = false; // Disable update for API request
            } else {
                $this->km_sections->setFormValue($val);
            }
        }

        // Check field name 'km_headings' first before field var 'x_km_headings'
        $val = $CurrentForm->hasValue("km_headings") ? $CurrentForm->getValue("km_headings") : $CurrentForm->getValue("x_km_headings");
        if (!$this->km_headings->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_headings->Visible = false; // Disable update for API request
            } else {
                $this->km_headings->setFormValue($val);
            }
        }

        // Check field name 'km_plaintext_arrangement' first before field var 'x_km_plaintext_arrangement'
        $val = $CurrentForm->hasValue("km_plaintext_arrangement") ? $CurrentForm->getValue("km_plaintext_arrangement") : $CurrentForm->getValue("x_km_plaintext_arrangement");
        if (!$this->km_plaintext_arrangement->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_plaintext_arrangement->Visible = false; // Disable update for API request
            } else {
                $this->km_plaintext_arrangement->setFormValue($val);
            }
        }

        // Check field name 'km_ciphertext_arrangement' first before field var 'x_km_ciphertext_arrangement'
        $val = $CurrentForm->hasValue("km_ciphertext_arrangement") ? $CurrentForm->getValue("km_ciphertext_arrangement") : $CurrentForm->getValue("x_km_ciphertext_arrangement");
        if (!$this->km_ciphertext_arrangement->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_ciphertext_arrangement->Visible = false; // Disable update for API request
            } else {
                $this->km_ciphertext_arrangement->setFormValue($val);
            }
        }

        // Check field name 'km_memorability' first before field var 'x_km_memorability'
        $val = $CurrentForm->hasValue("km_memorability") ? $CurrentForm->getValue("km_memorability") : $CurrentForm->getValue("x_km_memorability");
        if (!$this->km_memorability->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_memorability->Visible = false; // Disable update for API request
            } else {
                $this->km_memorability->setFormValue($val);
            }
        }

        // Check field name 'km_symbol_set' first before field var 'x_km_symbol_set'
        $val = $CurrentForm->hasValue("km_symbol_set") ? $CurrentForm->getValue("km_symbol_set") : $CurrentForm->getValue("x_km_symbol_set");
        if (!$this->km_symbol_set->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_symbol_set->Visible = false; // Disable update for API request
            } else {
                $this->km_symbol_set->setFormValue($val);
            }
        }

        // Check field name 'km_diacritics' first before field var 'x_km_diacritics'
        $val = $CurrentForm->hasValue("km_diacritics") ? $CurrentForm->getValue("km_diacritics") : $CurrentForm->getValue("x_km_diacritics");
        if (!$this->km_diacritics->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_diacritics->Visible = false; // Disable update for API request
            } else {
                $this->km_diacritics->setFormValue($val);
            }
        }

        // Check field name 'km_code_length' first before field var 'x_km_code_length'
        $val = $CurrentForm->hasValue("km_code_length") ? $CurrentForm->getValue("km_code_length") : $CurrentForm->getValue("x_km_code_length");
        if (!$this->km_code_length->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_code_length->Visible = false; // Disable update for API request
            } else {
                $this->km_code_length->setFormValue($val);
            }
        }

        // Check field name 'km_code_type' first before field var 'x_km_code_type'
        $val = $CurrentForm->hasValue("km_code_type") ? $CurrentForm->getValue("km_code_type") : $CurrentForm->getValue("x_km_code_type");
        if (!$this->km_code_type->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_code_type->Visible = false; // Disable update for API request
            } else {
                $this->km_code_type->setFormValue($val);
            }
        }

        // Check field name 'km_metaphors' first before field var 'x_km_metaphors'
        $val = $CurrentForm->hasValue("km_metaphors") ? $CurrentForm->getValue("km_metaphors") : $CurrentForm->getValue("x_km_metaphors");
        if (!$this->km_metaphors->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_metaphors->Visible = false; // Disable update for API request
            } else {
                $this->km_metaphors->setFormValue($val);
            }
        }

        // Check field name 'km_material_properties' first before field var 'x_km_material_properties'
        $val = $CurrentForm->hasValue("km_material_properties") ? $CurrentForm->getValue("km_material_properties") : $CurrentForm->getValue("x_km_material_properties");
        if (!$this->km_material_properties->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_material_properties->Visible = false; // Disable update for API request
            } else {
                $this->km_material_properties->setFormValue($val);
            }
        }

        // Check field name 'km_instructions' first before field var 'x_km_instructions'
        $val = $CurrentForm->hasValue("km_instructions") ? $CurrentForm->getValue("km_instructions") : $CurrentForm->getValue("x_km_instructions");
        if (!$this->km_instructions->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->km_instructions->Visible = false; // Disable update for API request
            } else {
                $this->km_instructions->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->name->CurrentValue = $this->name->FormValue;
        $this->owner_id->CurrentValue = $this->owner_id->FormValue;
        $this->owner->CurrentValue = $this->owner->FormValue;
        $this->c_cates->CurrentValue = $this->c_cates->FormValue;
        $this->current_country->CurrentValue = $this->current_country->FormValue;
        $this->current_city->CurrentValue = $this->current_city->FormValue;
        $this->current_holder->CurrentValue = $this->current_holder->FormValue;
        $this->author->CurrentValue = $this->author->FormValue;
        $this->sender->CurrentValue = $this->sender->FormValue;
        $this->receiver->CurrentValue = $this->receiver->FormValue;
        $this->origin_region->CurrentValue = $this->origin_region->FormValue;
        $this->origin_city->CurrentValue = $this->origin_city->FormValue;
        $this->start_year->CurrentValue = $this->start_year->FormValue;
        $this->start_month->CurrentValue = $this->start_month->FormValue;
        $this->start_day->CurrentValue = $this->start_day->FormValue;
        $this->end_year->CurrentValue = $this->end_year->FormValue;
        $this->end_month->CurrentValue = $this->end_month->FormValue;
        $this->end_day->CurrentValue = $this->end_day->FormValue;
        $this->record_type->CurrentValue = $this->record_type->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
        $this->symbol_sets->CurrentValue = $this->symbol_sets->FormValue;
        $this->cipher_types->CurrentValue = $this->cipher_types->FormValue;
        $this->cipher_type_other->CurrentValue = $this->cipher_type_other->FormValue;
        $this->symbol_set_other->CurrentValue = $this->symbol_set_other->FormValue;
        $this->number_of_pages->CurrentValue = $this->number_of_pages->FormValue;
        $this->inline_cleartext->CurrentValue = $this->inline_cleartext->FormValue;
        $this->inline_plaintext->CurrentValue = $this->inline_plaintext->FormValue;
        $this->cleartext_lang->CurrentValue = $this->cleartext_lang->FormValue;
        $this->plaintext_lang->CurrentValue = $this->plaintext_lang->FormValue;
        $this->document_types->CurrentValue = $this->document_types->FormValue;
        $this->paper->CurrentValue = $this->paper->FormValue;
        $this->additional_information->CurrentValue = $this->additional_information->FormValue;
        $this->creator_id->CurrentValue = $this->creator_id->FormValue;
        $this->access_mode->CurrentValue = $this->access_mode->FormValue;
        $this->km_encoded_plaintext_type->CurrentValue = $this->km_encoded_plaintext_type->FormValue;
        $this->km_numbers->CurrentValue = $this->km_numbers->FormValue;
        $this->km_content_words->CurrentValue = $this->km_content_words->FormValue;
        $this->km_function_words->CurrentValue = $this->km_function_words->FormValue;
        $this->km_syllables->CurrentValue = $this->km_syllables->FormValue;
        $this->km_morphological_endings->CurrentValue = $this->km_morphological_endings->FormValue;
        $this->km_phrases->CurrentValue = $this->km_phrases->FormValue;
        $this->km_sentences->CurrentValue = $this->km_sentences->FormValue;
        $this->km_punctuation->CurrentValue = $this->km_punctuation->FormValue;
        $this->km_nomenclature_size->CurrentValue = $this->km_nomenclature_size->FormValue;
        $this->km_sections->CurrentValue = $this->km_sections->FormValue;
        $this->km_headings->CurrentValue = $this->km_headings->FormValue;
        $this->km_plaintext_arrangement->CurrentValue = $this->km_plaintext_arrangement->FormValue;
        $this->km_ciphertext_arrangement->CurrentValue = $this->km_ciphertext_arrangement->FormValue;
        $this->km_memorability->CurrentValue = $this->km_memorability->FormValue;
        $this->km_symbol_set->CurrentValue = $this->km_symbol_set->FormValue;
        $this->km_diacritics->CurrentValue = $this->km_diacritics->FormValue;
        $this->km_code_length->CurrentValue = $this->km_code_length->FormValue;
        $this->km_code_type->CurrentValue = $this->km_code_type->FormValue;
        $this->km_metaphors->CurrentValue = $this->km_metaphors->FormValue;
        $this->km_material_properties->CurrentValue = $this->km_material_properties->FormValue;
        $this->km_instructions->CurrentValue = $this->km_instructions->FormValue;
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

            // name
            $this->name->HrefValue = "";

            // owner_id
            $this->owner_id->HrefValue = "";

            // owner
            $this->owner->HrefValue = "";

            // c_cates
            $this->c_cates->HrefValue = "";

            // current_country
            $this->current_country->HrefValue = "";

            // current_city
            $this->current_city->HrefValue = "";

            // current_holder
            $this->current_holder->HrefValue = "";

            // author
            $this->author->HrefValue = "";

            // sender
            $this->sender->HrefValue = "";

            // receiver
            $this->receiver->HrefValue = "";

            // origin_region
            $this->origin_region->HrefValue = "";

            // origin_city
            $this->origin_city->HrefValue = "";

            // start_year
            $this->start_year->HrefValue = "";

            // start_month
            $this->start_month->HrefValue = "";

            // start_day
            $this->start_day->HrefValue = "";

            // end_year
            $this->end_year->HrefValue = "";

            // end_month
            $this->end_month->HrefValue = "";

            // end_day
            $this->end_day->HrefValue = "";

            // record_type
            $this->record_type->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // symbol_sets
            $this->symbol_sets->HrefValue = "";

            // cipher_types
            $this->cipher_types->HrefValue = "";

            // cipher_type_other
            $this->cipher_type_other->HrefValue = "";

            // symbol_set_other
            $this->symbol_set_other->HrefValue = "";

            // number_of_pages
            $this->number_of_pages->HrefValue = "";

            // inline_cleartext
            $this->inline_cleartext->HrefValue = "";

            // inline_plaintext
            $this->inline_plaintext->HrefValue = "";

            // cleartext_lang
            $this->cleartext_lang->HrefValue = "";

            // plaintext_lang
            $this->plaintext_lang->HrefValue = "";

            // document_types
            $this->document_types->HrefValue = "";

            // paper
            $this->paper->HrefValue = "";

            // additional_information
            $this->additional_information->HrefValue = "";

            // creator_id
            $this->creator_id->HrefValue = "";
            $this->creator_id->TooltipValue = "";

            // access_mode
            $this->access_mode->HrefValue = "";

            // km_encoded_plaintext_type
            $this->km_encoded_plaintext_type->HrefValue = "";

            // km_numbers
            $this->km_numbers->HrefValue = "";

            // km_content_words
            $this->km_content_words->HrefValue = "";

            // km_function_words
            $this->km_function_words->HrefValue = "";

            // km_syllables
            $this->km_syllables->HrefValue = "";

            // km_morphological_endings
            $this->km_morphological_endings->HrefValue = "";

            // km_phrases
            $this->km_phrases->HrefValue = "";

            // km_sentences
            $this->km_sentences->HrefValue = "";

            // km_punctuation
            $this->km_punctuation->HrefValue = "";

            // km_nomenclature_size
            $this->km_nomenclature_size->HrefValue = "";

            // km_sections
            $this->km_sections->HrefValue = "";

            // km_headings
            $this->km_headings->HrefValue = "";

            // km_plaintext_arrangement
            $this->km_plaintext_arrangement->HrefValue = "";

            // km_ciphertext_arrangement
            $this->km_ciphertext_arrangement->HrefValue = "";

            // km_memorability
            $this->km_memorability->HrefValue = "";

            // km_symbol_set
            $this->km_symbol_set->HrefValue = "";

            // km_diacritics
            $this->km_diacritics->HrefValue = "";

            // km_code_length
            $this->km_code_length->HrefValue = "";

            // km_code_type
            $this->km_code_type->HrefValue = "";

            // km_metaphors
            $this->km_metaphors->HrefValue = "";

            // km_material_properties
            $this->km_material_properties->HrefValue = "";

            // km_instructions
            $this->km_instructions->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // name
            $this->name->setupEditAttributes();
            if (!$this->name->Raw) {
                $this->name->CurrentValue = HtmlDecode($this->name->CurrentValue);
            }
            $this->name->EditValue = HtmlEncode($this->name->CurrentValue);
            $arwrk = [];
            $arwrk["lf"] = $this->name->CurrentValue;
            $arwrk["df"] = $this->name->CurrentValue;
            $arwrk = $this->name->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->name->displayValue($arwrk);
            if ($dispVal != "") {
                $this->name->EditValue = $dispVal;
            }
            $this->name->PlaceHolder = RemoveHtml($this->name->caption());

            // owner_id
            $this->owner_id->setupEditAttributes();
            $this->owner_id->CurrentValue = FormatNumber($this->owner_id->getDefault(), $this->owner_id->formatPattern());
            if (strval($this->owner_id->EditValue) != "" && is_numeric($this->owner_id->EditValue)) {
                $this->owner_id->EditValue = FormatNumber($this->owner_id->EditValue, null);
            }

            // owner
            $this->owner->setupEditAttributes();
            if (!$this->owner->Raw) {
                $this->owner->CurrentValue = HtmlDecode($this->owner->CurrentValue);
            }
            $this->owner->EditValue = HtmlEncode($this->owner->CurrentValue);
            $this->owner->PlaceHolder = RemoveHtml($this->owner->caption());

            // c_cates
            $this->c_cates->setupEditAttributes();
            if (!$this->c_cates->Raw) {
                $this->c_cates->CurrentValue = HtmlDecode($this->c_cates->CurrentValue);
            }
            $this->c_cates->EditValue = HtmlEncode($this->c_cates->CurrentValue);
            $this->c_cates->PlaceHolder = RemoveHtml($this->c_cates->caption());

            // current_country
            $this->current_country->setupEditAttributes();
            if (!$this->current_country->Raw) {
                $this->current_country->CurrentValue = HtmlDecode($this->current_country->CurrentValue);
            }
            $this->current_country->EditValue = HtmlEncode($this->current_country->CurrentValue);
            $arwrk = [];
            $arwrk["lf"] = $this->current_country->CurrentValue;
            $arwrk["df"] = $this->current_country->CurrentValue;
            $arwrk = $this->current_country->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->current_country->displayValue($arwrk);
            if ($dispVal != "") {
                $this->current_country->EditValue = $dispVal;
            }
            $this->current_country->PlaceHolder = RemoveHtml($this->current_country->caption());

            // current_city
            $this->current_city->setupEditAttributes();
            if (!$this->current_city->Raw) {
                $this->current_city->CurrentValue = HtmlDecode($this->current_city->CurrentValue);
            }
            $this->current_city->EditValue = HtmlEncode($this->current_city->CurrentValue);
            $arwrk = [];
            $arwrk["lf"] = $this->current_city->CurrentValue;
            $arwrk["df"] = $this->current_city->CurrentValue;
            $arwrk = $this->current_city->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->current_city->displayValue($arwrk);
            if ($dispVal != "") {
                $this->current_city->EditValue = $dispVal;
            }
            $this->current_city->PlaceHolder = RemoveHtml($this->current_city->caption());

            // current_holder
            $this->current_holder->setupEditAttributes();
            if (!$this->current_holder->Raw) {
                $this->current_holder->CurrentValue = HtmlDecode($this->current_holder->CurrentValue);
            }
            $this->current_holder->EditValue = HtmlEncode($this->current_holder->CurrentValue);
            $arwrk = [];
            $arwrk["lf"] = $this->current_holder->CurrentValue;
            $arwrk["df"] = $this->current_holder->CurrentValue;
            $arwrk = $this->current_holder->Lookup->renderViewRow($arwrk, $this);
            $dispVal = $this->current_holder->displayValue($arwrk);
            if ($dispVal != "") {
                $this->current_holder->EditValue = $dispVal;
            }
            $this->current_holder->PlaceHolder = RemoveHtml($this->current_holder->caption());

            // author
            $this->author->setupEditAttributes();
            if (!$this->author->Raw) {
                $this->author->CurrentValue = HtmlDecode($this->author->CurrentValue);
            }
            $this->author->EditValue = HtmlEncode($this->author->CurrentValue);
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
                $this->sender->CurrentValue = HtmlDecode($this->sender->CurrentValue);
            }
            $this->sender->EditValue = HtmlEncode($this->sender->CurrentValue);
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
                $this->receiver->CurrentValue = HtmlDecode($this->receiver->CurrentValue);
            }
            $this->receiver->EditValue = HtmlEncode($this->receiver->CurrentValue);
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
                $this->origin_region->CurrentValue = HtmlDecode($this->origin_region->CurrentValue);
            }
            $this->origin_region->EditValue = HtmlEncode($this->origin_region->CurrentValue);
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
                $this->origin_city->CurrentValue = HtmlDecode($this->origin_city->CurrentValue);
            }
            $this->origin_city->EditValue = HtmlEncode($this->origin_city->CurrentValue);
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
            $this->start_year->EditValue = $this->start_year->CurrentValue;
            $this->start_year->PlaceHolder = RemoveHtml($this->start_year->caption());
            if (strval($this->start_year->EditValue) != "" && is_numeric($this->start_year->EditValue)) {
                $this->start_year->EditValue = $this->start_year->EditValue;
            }

            // start_month
            $this->start_month->setupEditAttributes();
            $this->start_month->EditValue = $this->start_month->CurrentValue;
            $this->start_month->PlaceHolder = RemoveHtml($this->start_month->caption());
            if (strval($this->start_month->EditValue) != "" && is_numeric($this->start_month->EditValue)) {
                $this->start_month->EditValue = FormatNumber($this->start_month->EditValue, null);
            }

            // start_day
            $this->start_day->setupEditAttributes();
            $this->start_day->EditValue = $this->start_day->CurrentValue;
            $this->start_day->PlaceHolder = RemoveHtml($this->start_day->caption());
            if (strval($this->start_day->EditValue) != "" && is_numeric($this->start_day->EditValue)) {
                $this->start_day->EditValue = FormatNumber($this->start_day->EditValue, null);
            }

            // end_year
            $this->end_year->setupEditAttributes();
            $this->end_year->EditValue = $this->end_year->CurrentValue;
            $this->end_year->PlaceHolder = RemoveHtml($this->end_year->caption());
            if (strval($this->end_year->EditValue) != "" && is_numeric($this->end_year->EditValue)) {
                $this->end_year->EditValue = $this->end_year->EditValue;
            }

            // end_month
            $this->end_month->setupEditAttributes();
            $this->end_month->EditValue = $this->end_month->CurrentValue;
            $this->end_month->PlaceHolder = RemoveHtml($this->end_month->caption());
            if (strval($this->end_month->EditValue) != "" && is_numeric($this->end_month->EditValue)) {
                $this->end_month->EditValue = FormatNumber($this->end_month->EditValue, null);
            }

            // end_day
            $this->end_day->setupEditAttributes();
            $this->end_day->EditValue = $this->end_day->CurrentValue;
            $this->end_day->PlaceHolder = RemoveHtml($this->end_day->caption());
            if (strval($this->end_day->EditValue) != "" && is_numeric($this->end_day->EditValue)) {
                $this->end_day->EditValue = FormatNumber($this->end_day->EditValue, null);
            }

            // record_type
            $this->record_type->setupEditAttributes();
            $this->record_type->EditValue = $this->record_type->options(true);
            $this->record_type->PlaceHolder = RemoveHtml($this->record_type->caption());

            // status
            $this->status->setupEditAttributes();
            $this->status->EditValue = $this->status->options(true);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // symbol_sets
            $this->symbol_sets->EditValue = $this->symbol_sets->options(false);
            $this->symbol_sets->PlaceHolder = RemoveHtml($this->symbol_sets->caption());

            // cipher_types
            $this->cipher_types->EditValue = $this->cipher_types->options(false);
            $this->cipher_types->PlaceHolder = RemoveHtml($this->cipher_types->caption());

            // cipher_type_other
            $this->cipher_type_other->setupEditAttributes();
            if (!$this->cipher_type_other->Raw) {
                $this->cipher_type_other->CurrentValue = HtmlDecode($this->cipher_type_other->CurrentValue);
            }
            $this->cipher_type_other->EditValue = HtmlEncode($this->cipher_type_other->CurrentValue);
            $this->cipher_type_other->PlaceHolder = RemoveHtml($this->cipher_type_other->caption());

            // symbol_set_other
            $this->symbol_set_other->setupEditAttributes();
            if (!$this->symbol_set_other->Raw) {
                $this->symbol_set_other->CurrentValue = HtmlDecode($this->symbol_set_other->CurrentValue);
            }
            $this->symbol_set_other->EditValue = HtmlEncode($this->symbol_set_other->CurrentValue);
            $this->symbol_set_other->PlaceHolder = RemoveHtml($this->symbol_set_other->caption());

            // number_of_pages
            $this->number_of_pages->setupEditAttributes();
            $this->number_of_pages->EditValue = $this->number_of_pages->CurrentValue;
            $this->number_of_pages->PlaceHolder = RemoveHtml($this->number_of_pages->caption());
            if (strval($this->number_of_pages->EditValue) != "" && is_numeric($this->number_of_pages->EditValue)) {
                $this->number_of_pages->EditValue = FormatNumber($this->number_of_pages->EditValue, null);
            }

            // inline_cleartext
            $this->inline_cleartext->EditValue = $this->inline_cleartext->options(false);
            $this->inline_cleartext->PlaceHolder = RemoveHtml($this->inline_cleartext->caption());

            // inline_plaintext
            $this->inline_plaintext->EditValue = $this->inline_plaintext->options(false);
            $this->inline_plaintext->PlaceHolder = RemoveHtml($this->inline_plaintext->caption());

            // cleartext_lang
            $this->cleartext_lang->setupEditAttributes();
            if (!$this->cleartext_lang->Raw) {
                $this->cleartext_lang->CurrentValue = HtmlDecode($this->cleartext_lang->CurrentValue);
            }
            $this->cleartext_lang->EditValue = HtmlEncode($this->cleartext_lang->CurrentValue);
            $this->cleartext_lang->PlaceHolder = RemoveHtml($this->cleartext_lang->caption());

            // plaintext_lang
            $this->plaintext_lang->setupEditAttributes();
            if (!$this->plaintext_lang->Raw) {
                $this->plaintext_lang->CurrentValue = HtmlDecode($this->plaintext_lang->CurrentValue);
            }
            $this->plaintext_lang->EditValue = HtmlEncode($this->plaintext_lang->CurrentValue);
            $this->plaintext_lang->PlaceHolder = RemoveHtml($this->plaintext_lang->caption());

            // document_types
            $this->document_types->EditValue = $this->document_types->options(false);
            $this->document_types->PlaceHolder = RemoveHtml($this->document_types->caption());

            // paper
            $this->paper->setupEditAttributes();
            if (!$this->paper->Raw) {
                $this->paper->CurrentValue = HtmlDecode($this->paper->CurrentValue);
            }
            $this->paper->EditValue = HtmlEncode($this->paper->CurrentValue);
            $this->paper->PlaceHolder = RemoveHtml($this->paper->caption());

            // additional_information
            $this->additional_information->setupEditAttributes();
            $this->additional_information->EditValue = HtmlEncode($this->additional_information->CurrentValue);
            $this->additional_information->PlaceHolder = RemoveHtml($this->additional_information->caption());

            // creator_id
            $this->creator_id->setupEditAttributes();
            $this->creator_id->CurrentValue = FormatNumber($this->creator_id->getDefault(), $this->creator_id->formatPattern());
            if (strval($this->creator_id->EditValue) != "" && is_numeric($this->creator_id->EditValue)) {
                $this->creator_id->EditValue = FormatNumber($this->creator_id->EditValue, null);
            }

            // access_mode
            $this->access_mode->EditValue = $this->access_mode->options(false);
            $this->access_mode->PlaceHolder = RemoveHtml($this->access_mode->caption());

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

            // Add refer script

            // name
            $this->name->HrefValue = "";

            // owner_id
            $this->owner_id->HrefValue = "";

            // owner
            $this->owner->HrefValue = "";

            // c_cates
            $this->c_cates->HrefValue = "";

            // current_country
            $this->current_country->HrefValue = "";

            // current_city
            $this->current_city->HrefValue = "";

            // current_holder
            $this->current_holder->HrefValue = "";

            // author
            $this->author->HrefValue = "";

            // sender
            $this->sender->HrefValue = "";

            // receiver
            $this->receiver->HrefValue = "";

            // origin_region
            $this->origin_region->HrefValue = "";

            // origin_city
            $this->origin_city->HrefValue = "";

            // start_year
            $this->start_year->HrefValue = "";

            // start_month
            $this->start_month->HrefValue = "";

            // start_day
            $this->start_day->HrefValue = "";

            // end_year
            $this->end_year->HrefValue = "";

            // end_month
            $this->end_month->HrefValue = "";

            // end_day
            $this->end_day->HrefValue = "";

            // record_type
            $this->record_type->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // symbol_sets
            $this->symbol_sets->HrefValue = "";

            // cipher_types
            $this->cipher_types->HrefValue = "";

            // cipher_type_other
            $this->cipher_type_other->HrefValue = "";

            // symbol_set_other
            $this->symbol_set_other->HrefValue = "";

            // number_of_pages
            $this->number_of_pages->HrefValue = "";

            // inline_cleartext
            $this->inline_cleartext->HrefValue = "";

            // inline_plaintext
            $this->inline_plaintext->HrefValue = "";

            // cleartext_lang
            $this->cleartext_lang->HrefValue = "";

            // plaintext_lang
            $this->plaintext_lang->HrefValue = "";

            // document_types
            $this->document_types->HrefValue = "";

            // paper
            $this->paper->HrefValue = "";

            // additional_information
            $this->additional_information->HrefValue = "";

            // creator_id
            $this->creator_id->HrefValue = "";

            // access_mode
            $this->access_mode->HrefValue = "";

            // km_encoded_plaintext_type
            $this->km_encoded_plaintext_type->HrefValue = "";

            // km_numbers
            $this->km_numbers->HrefValue = "";

            // km_content_words
            $this->km_content_words->HrefValue = "";

            // km_function_words
            $this->km_function_words->HrefValue = "";

            // km_syllables
            $this->km_syllables->HrefValue = "";

            // km_morphological_endings
            $this->km_morphological_endings->HrefValue = "";

            // km_phrases
            $this->km_phrases->HrefValue = "";

            // km_sentences
            $this->km_sentences->HrefValue = "";

            // km_punctuation
            $this->km_punctuation->HrefValue = "";

            // km_nomenclature_size
            $this->km_nomenclature_size->HrefValue = "";

            // km_sections
            $this->km_sections->HrefValue = "";

            // km_headings
            $this->km_headings->HrefValue = "";

            // km_plaintext_arrangement
            $this->km_plaintext_arrangement->HrefValue = "";

            // km_ciphertext_arrangement
            $this->km_ciphertext_arrangement->HrefValue = "";

            // km_memorability
            $this->km_memorability->HrefValue = "";

            // km_symbol_set
            $this->km_symbol_set->HrefValue = "";

            // km_diacritics
            $this->km_diacritics->HrefValue = "";

            // km_code_length
            $this->km_code_length->HrefValue = "";

            // km_code_type
            $this->km_code_type->HrefValue = "";

            // km_metaphors
            $this->km_metaphors->HrefValue = "";

            // km_material_properties
            $this->km_material_properties->HrefValue = "";

            // km_instructions
            $this->km_instructions->HrefValue = "";
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
        if ($this->name->Visible && $this->name->Required) {
            if (!$this->name->IsDetailKey && EmptyValue($this->name->FormValue)) {
                $this->name->addErrorMessage(str_replace("%s", $this->name->caption(), $this->name->RequiredErrorMessage));
            }
        }
        if ($this->owner_id->Visible && $this->owner_id->Required) {
            if (!$this->owner_id->IsDetailKey && EmptyValue($this->owner_id->FormValue)) {
                $this->owner_id->addErrorMessage(str_replace("%s", $this->owner_id->caption(), $this->owner_id->RequiredErrorMessage));
            }
        }
        if ($this->owner->Visible && $this->owner->Required) {
            if (!$this->owner->IsDetailKey && EmptyValue($this->owner->FormValue)) {
                $this->owner->addErrorMessage(str_replace("%s", $this->owner->caption(), $this->owner->RequiredErrorMessage));
            }
        }
        if ($this->c_cates->Visible && $this->c_cates->Required) {
            if (!$this->c_cates->IsDetailKey && EmptyValue($this->c_cates->FormValue)) {
                $this->c_cates->addErrorMessage(str_replace("%s", $this->c_cates->caption(), $this->c_cates->RequiredErrorMessage));
            }
        }
        if ($this->current_country->Visible && $this->current_country->Required) {
            if (!$this->current_country->IsDetailKey && EmptyValue($this->current_country->FormValue)) {
                $this->current_country->addErrorMessage(str_replace("%s", $this->current_country->caption(), $this->current_country->RequiredErrorMessage));
            }
        }
        if ($this->current_city->Visible && $this->current_city->Required) {
            if (!$this->current_city->IsDetailKey && EmptyValue($this->current_city->FormValue)) {
                $this->current_city->addErrorMessage(str_replace("%s", $this->current_city->caption(), $this->current_city->RequiredErrorMessage));
            }
        }
        if ($this->current_holder->Visible && $this->current_holder->Required) {
            if (!$this->current_holder->IsDetailKey && EmptyValue($this->current_holder->FormValue)) {
                $this->current_holder->addErrorMessage(str_replace("%s", $this->current_holder->caption(), $this->current_holder->RequiredErrorMessage));
            }
        }
        if ($this->author->Visible && $this->author->Required) {
            if (!$this->author->IsDetailKey && EmptyValue($this->author->FormValue)) {
                $this->author->addErrorMessage(str_replace("%s", $this->author->caption(), $this->author->RequiredErrorMessage));
            }
        }
        if ($this->sender->Visible && $this->sender->Required) {
            if (!$this->sender->IsDetailKey && EmptyValue($this->sender->FormValue)) {
                $this->sender->addErrorMessage(str_replace("%s", $this->sender->caption(), $this->sender->RequiredErrorMessage));
            }
        }
        if ($this->receiver->Visible && $this->receiver->Required) {
            if (!$this->receiver->IsDetailKey && EmptyValue($this->receiver->FormValue)) {
                $this->receiver->addErrorMessage(str_replace("%s", $this->receiver->caption(), $this->receiver->RequiredErrorMessage));
            }
        }
        if ($this->origin_region->Visible && $this->origin_region->Required) {
            if (!$this->origin_region->IsDetailKey && EmptyValue($this->origin_region->FormValue)) {
                $this->origin_region->addErrorMessage(str_replace("%s", $this->origin_region->caption(), $this->origin_region->RequiredErrorMessage));
            }
        }
        if ($this->origin_city->Visible && $this->origin_city->Required) {
            if (!$this->origin_city->IsDetailKey && EmptyValue($this->origin_city->FormValue)) {
                $this->origin_city->addErrorMessage(str_replace("%s", $this->origin_city->caption(), $this->origin_city->RequiredErrorMessage));
            }
        }
        if ($this->start_year->Visible && $this->start_year->Required) {
            if (!$this->start_year->IsDetailKey && EmptyValue($this->start_year->FormValue)) {
                $this->start_year->addErrorMessage(str_replace("%s", $this->start_year->caption(), $this->start_year->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->start_year->FormValue)) {
            $this->start_year->addErrorMessage($this->start_year->getErrorMessage(false));
        }
        if ($this->start_month->Visible && $this->start_month->Required) {
            if (!$this->start_month->IsDetailKey && EmptyValue($this->start_month->FormValue)) {
                $this->start_month->addErrorMessage(str_replace("%s", $this->start_month->caption(), $this->start_month->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->start_month->FormValue)) {
            $this->start_month->addErrorMessage($this->start_month->getErrorMessage(false));
        }
        if ($this->start_day->Visible && $this->start_day->Required) {
            if (!$this->start_day->IsDetailKey && EmptyValue($this->start_day->FormValue)) {
                $this->start_day->addErrorMessage(str_replace("%s", $this->start_day->caption(), $this->start_day->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->start_day->FormValue)) {
            $this->start_day->addErrorMessage($this->start_day->getErrorMessage(false));
        }
        if ($this->end_year->Visible && $this->end_year->Required) {
            if (!$this->end_year->IsDetailKey && EmptyValue($this->end_year->FormValue)) {
                $this->end_year->addErrorMessage(str_replace("%s", $this->end_year->caption(), $this->end_year->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->end_year->FormValue)) {
            $this->end_year->addErrorMessage($this->end_year->getErrorMessage(false));
        }
        if ($this->end_month->Visible && $this->end_month->Required) {
            if (!$this->end_month->IsDetailKey && EmptyValue($this->end_month->FormValue)) {
                $this->end_month->addErrorMessage(str_replace("%s", $this->end_month->caption(), $this->end_month->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->end_month->FormValue)) {
            $this->end_month->addErrorMessage($this->end_month->getErrorMessage(false));
        }
        if ($this->end_day->Visible && $this->end_day->Required) {
            if (!$this->end_day->IsDetailKey && EmptyValue($this->end_day->FormValue)) {
                $this->end_day->addErrorMessage(str_replace("%s", $this->end_day->caption(), $this->end_day->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->end_day->FormValue)) {
            $this->end_day->addErrorMessage($this->end_day->getErrorMessage(false));
        }
        if ($this->record_type->Visible && $this->record_type->Required) {
            if (!$this->record_type->IsDetailKey && EmptyValue($this->record_type->FormValue)) {
                $this->record_type->addErrorMessage(str_replace("%s", $this->record_type->caption(), $this->record_type->RequiredErrorMessage));
            }
        }
        if ($this->status->Visible && $this->status->Required) {
            if (!$this->status->IsDetailKey && EmptyValue($this->status->FormValue)) {
                $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
            }
        }
        if ($this->symbol_sets->Visible && $this->symbol_sets->Required) {
            if ($this->symbol_sets->FormValue == "") {
                $this->symbol_sets->addErrorMessage(str_replace("%s", $this->symbol_sets->caption(), $this->symbol_sets->RequiredErrorMessage));
            }
        }
        if ($this->cipher_types->Visible && $this->cipher_types->Required) {
            if ($this->cipher_types->FormValue == "") {
                $this->cipher_types->addErrorMessage(str_replace("%s", $this->cipher_types->caption(), $this->cipher_types->RequiredErrorMessage));
            }
        }
        if ($this->cipher_type_other->Visible && $this->cipher_type_other->Required) {
            if (!$this->cipher_type_other->IsDetailKey && EmptyValue($this->cipher_type_other->FormValue)) {
                $this->cipher_type_other->addErrorMessage(str_replace("%s", $this->cipher_type_other->caption(), $this->cipher_type_other->RequiredErrorMessage));
            }
        }
        if ($this->symbol_set_other->Visible && $this->symbol_set_other->Required) {
            if (!$this->symbol_set_other->IsDetailKey && EmptyValue($this->symbol_set_other->FormValue)) {
                $this->symbol_set_other->addErrorMessage(str_replace("%s", $this->symbol_set_other->caption(), $this->symbol_set_other->RequiredErrorMessage));
            }
        }
        if ($this->number_of_pages->Visible && $this->number_of_pages->Required) {
            if (!$this->number_of_pages->IsDetailKey && EmptyValue($this->number_of_pages->FormValue)) {
                $this->number_of_pages->addErrorMessage(str_replace("%s", $this->number_of_pages->caption(), $this->number_of_pages->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->number_of_pages->FormValue)) {
            $this->number_of_pages->addErrorMessage($this->number_of_pages->getErrorMessage(false));
        }
        if ($this->inline_cleartext->Visible && $this->inline_cleartext->Required) {
            if ($this->inline_cleartext->FormValue == "") {
                $this->inline_cleartext->addErrorMessage(str_replace("%s", $this->inline_cleartext->caption(), $this->inline_cleartext->RequiredErrorMessage));
            }
        }
        if ($this->inline_plaintext->Visible && $this->inline_plaintext->Required) {
            if ($this->inline_plaintext->FormValue == "") {
                $this->inline_plaintext->addErrorMessage(str_replace("%s", $this->inline_plaintext->caption(), $this->inline_plaintext->RequiredErrorMessage));
            }
        }
        if ($this->cleartext_lang->Visible && $this->cleartext_lang->Required) {
            if (!$this->cleartext_lang->IsDetailKey && EmptyValue($this->cleartext_lang->FormValue)) {
                $this->cleartext_lang->addErrorMessage(str_replace("%s", $this->cleartext_lang->caption(), $this->cleartext_lang->RequiredErrorMessage));
            }
        }
        if ($this->plaintext_lang->Visible && $this->plaintext_lang->Required) {
            if (!$this->plaintext_lang->IsDetailKey && EmptyValue($this->plaintext_lang->FormValue)) {
                $this->plaintext_lang->addErrorMessage(str_replace("%s", $this->plaintext_lang->caption(), $this->plaintext_lang->RequiredErrorMessage));
            }
        }
        if ($this->document_types->Visible && $this->document_types->Required) {
            if ($this->document_types->FormValue == "") {
                $this->document_types->addErrorMessage(str_replace("%s", $this->document_types->caption(), $this->document_types->RequiredErrorMessage));
            }
        }
        if ($this->paper->Visible && $this->paper->Required) {
            if (!$this->paper->IsDetailKey && EmptyValue($this->paper->FormValue)) {
                $this->paper->addErrorMessage(str_replace("%s", $this->paper->caption(), $this->paper->RequiredErrorMessage));
            }
        }
        if ($this->additional_information->Visible && $this->additional_information->Required) {
            if (!$this->additional_information->IsDetailKey && EmptyValue($this->additional_information->FormValue)) {
                $this->additional_information->addErrorMessage(str_replace("%s", $this->additional_information->caption(), $this->additional_information->RequiredErrorMessage));
            }
        }
        if ($this->creator_id->Visible && $this->creator_id->Required) {
            if (!$this->creator_id->IsDetailKey && EmptyValue($this->creator_id->FormValue)) {
                $this->creator_id->addErrorMessage(str_replace("%s", $this->creator_id->caption(), $this->creator_id->RequiredErrorMessage));
            }
        }
        if ($this->access_mode->Visible && $this->access_mode->Required) {
            if ($this->access_mode->FormValue == "") {
                $this->access_mode->addErrorMessage(str_replace("%s", $this->access_mode->caption(), $this->access_mode->RequiredErrorMessage));
            }
        }
        if ($this->km_encoded_plaintext_type->Visible && $this->km_encoded_plaintext_type->Required) {
            if ($this->km_encoded_plaintext_type->FormValue == "") {
                $this->km_encoded_plaintext_type->addErrorMessage(str_replace("%s", $this->km_encoded_plaintext_type->caption(), $this->km_encoded_plaintext_type->RequiredErrorMessage));
            }
        }
        if ($this->km_numbers->Visible && $this->km_numbers->Required) {
            if ($this->km_numbers->FormValue == "") {
                $this->km_numbers->addErrorMessage(str_replace("%s", $this->km_numbers->caption(), $this->km_numbers->RequiredErrorMessage));
            }
        }
        if ($this->km_content_words->Visible && $this->km_content_words->Required) {
            if ($this->km_content_words->FormValue == "") {
                $this->km_content_words->addErrorMessage(str_replace("%s", $this->km_content_words->caption(), $this->km_content_words->RequiredErrorMessage));
            }
        }
        if ($this->km_function_words->Visible && $this->km_function_words->Required) {
            if ($this->km_function_words->FormValue == "") {
                $this->km_function_words->addErrorMessage(str_replace("%s", $this->km_function_words->caption(), $this->km_function_words->RequiredErrorMessage));
            }
        }
        if ($this->km_syllables->Visible && $this->km_syllables->Required) {
            if ($this->km_syllables->FormValue == "") {
                $this->km_syllables->addErrorMessage(str_replace("%s", $this->km_syllables->caption(), $this->km_syllables->RequiredErrorMessage));
            }
        }
        if ($this->km_morphological_endings->Visible && $this->km_morphological_endings->Required) {
            if ($this->km_morphological_endings->FormValue == "") {
                $this->km_morphological_endings->addErrorMessage(str_replace("%s", $this->km_morphological_endings->caption(), $this->km_morphological_endings->RequiredErrorMessage));
            }
        }
        if ($this->km_phrases->Visible && $this->km_phrases->Required) {
            if ($this->km_phrases->FormValue == "") {
                $this->km_phrases->addErrorMessage(str_replace("%s", $this->km_phrases->caption(), $this->km_phrases->RequiredErrorMessage));
            }
        }
        if ($this->km_sentences->Visible && $this->km_sentences->Required) {
            if ($this->km_sentences->FormValue == "") {
                $this->km_sentences->addErrorMessage(str_replace("%s", $this->km_sentences->caption(), $this->km_sentences->RequiredErrorMessage));
            }
        }
        if ($this->km_punctuation->Visible && $this->km_punctuation->Required) {
            if ($this->km_punctuation->FormValue == "") {
                $this->km_punctuation->addErrorMessage(str_replace("%s", $this->km_punctuation->caption(), $this->km_punctuation->RequiredErrorMessage));
            }
        }
        if ($this->km_nomenclature_size->Visible && $this->km_nomenclature_size->Required) {
            if (!$this->km_nomenclature_size->IsDetailKey && EmptyValue($this->km_nomenclature_size->FormValue)) {
                $this->km_nomenclature_size->addErrorMessage(str_replace("%s", $this->km_nomenclature_size->caption(), $this->km_nomenclature_size->RequiredErrorMessage));
            }
        }
        if ($this->km_sections->Visible && $this->km_sections->Required) {
            if ($this->km_sections->FormValue == "") {
                $this->km_sections->addErrorMessage(str_replace("%s", $this->km_sections->caption(), $this->km_sections->RequiredErrorMessage));
            }
        }
        if ($this->km_headings->Visible && $this->km_headings->Required) {
            if ($this->km_headings->FormValue == "") {
                $this->km_headings->addErrorMessage(str_replace("%s", $this->km_headings->caption(), $this->km_headings->RequiredErrorMessage));
            }
        }
        if ($this->km_plaintext_arrangement->Visible && $this->km_plaintext_arrangement->Required) {
            if ($this->km_plaintext_arrangement->FormValue == "") {
                $this->km_plaintext_arrangement->addErrorMessage(str_replace("%s", $this->km_plaintext_arrangement->caption(), $this->km_plaintext_arrangement->RequiredErrorMessage));
            }
        }
        if ($this->km_ciphertext_arrangement->Visible && $this->km_ciphertext_arrangement->Required) {
            if ($this->km_ciphertext_arrangement->FormValue == "") {
                $this->km_ciphertext_arrangement->addErrorMessage(str_replace("%s", $this->km_ciphertext_arrangement->caption(), $this->km_ciphertext_arrangement->RequiredErrorMessage));
            }
        }
        if ($this->km_memorability->Visible && $this->km_memorability->Required) {
            if (!$this->km_memorability->IsDetailKey && EmptyValue($this->km_memorability->FormValue)) {
                $this->km_memorability->addErrorMessage(str_replace("%s", $this->km_memorability->caption(), $this->km_memorability->RequiredErrorMessage));
            }
        }
        if ($this->km_symbol_set->Visible && $this->km_symbol_set->Required) {
            if ($this->km_symbol_set->FormValue == "") {
                $this->km_symbol_set->addErrorMessage(str_replace("%s", $this->km_symbol_set->caption(), $this->km_symbol_set->RequiredErrorMessage));
            }
        }
        if ($this->km_diacritics->Visible && $this->km_diacritics->Required) {
            if ($this->km_diacritics->FormValue == "") {
                $this->km_diacritics->addErrorMessage(str_replace("%s", $this->km_diacritics->caption(), $this->km_diacritics->RequiredErrorMessage));
            }
        }
        if ($this->km_code_length->Visible && $this->km_code_length->Required) {
            if ($this->km_code_length->FormValue == "") {
                $this->km_code_length->addErrorMessage(str_replace("%s", $this->km_code_length->caption(), $this->km_code_length->RequiredErrorMessage));
            }
        }
        if ($this->km_code_type->Visible && $this->km_code_type->Required) {
            if ($this->km_code_type->FormValue == "") {
                $this->km_code_type->addErrorMessage(str_replace("%s", $this->km_code_type->caption(), $this->km_code_type->RequiredErrorMessage));
            }
        }
        if ($this->km_metaphors->Visible && $this->km_metaphors->Required) {
            if ($this->km_metaphors->FormValue == "") {
                $this->km_metaphors->addErrorMessage(str_replace("%s", $this->km_metaphors->caption(), $this->km_metaphors->RequiredErrorMessage));
            }
        }
        if ($this->km_material_properties->Visible && $this->km_material_properties->Required) {
            if ($this->km_material_properties->FormValue == "") {
                $this->km_material_properties->addErrorMessage(str_replace("%s", $this->km_material_properties->caption(), $this->km_material_properties->RequiredErrorMessage));
            }
        }
        if ($this->km_instructions->Visible && $this->km_instructions->Required) {
            if ($this->km_instructions->FormValue == "") {
                $this->km_instructions->addErrorMessage(str_replace("%s", $this->km_instructions->caption(), $this->km_instructions->RequiredErrorMessage));
            }
        }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("ImagesGrid");
        if (in_array("images", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->run();
            $validateForm = $validateForm && $detailPage->validateGridForm();
        }
        $detailPage = Container("DocumentsGrid");
        if (in_array("documents", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->run();
            $validateForm = $validateForm && $detailPage->validateGridForm();
        }
        $detailPage = Container("AssociatedRecordsGrid");
        if (in_array("associated_records", $detailTblVar) && $detailPage->DetailAdd) {
            $detailPage->run();
            $validateForm = $validateForm && $detailPage->validateGridForm();
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

        // name
        $this->name->setDbValueDef($rsnew, $this->name->CurrentValue, false);

        // owner_id
        $this->owner_id->setDbValueDef($rsnew, $this->owner_id->CurrentValue, false);

        // owner
        $this->owner->setDbValueDef($rsnew, $this->owner->CurrentValue, false);

        // c_cates
        $this->c_cates->setDbValueDef($rsnew, $this->c_cates->CurrentValue, false);

        // current_country
        $this->current_country->setDbValueDef($rsnew, $this->current_country->CurrentValue, false);

        // current_city
        $this->current_city->setDbValueDef($rsnew, $this->current_city->CurrentValue, false);

        // current_holder
        $this->current_holder->setDbValueDef($rsnew, $this->current_holder->CurrentValue, false);

        // author
        $this->author->setDbValueDef($rsnew, $this->author->CurrentValue, false);

        // sender
        $this->sender->setDbValueDef($rsnew, $this->sender->CurrentValue, false);

        // receiver
        $this->receiver->setDbValueDef($rsnew, $this->receiver->CurrentValue, false);

        // origin_region
        $this->origin_region->setDbValueDef($rsnew, $this->origin_region->CurrentValue, false);

        // origin_city
        $this->origin_city->setDbValueDef($rsnew, $this->origin_city->CurrentValue, false);

        // start_year
        $this->start_year->setDbValueDef($rsnew, $this->start_year->CurrentValue, false);

        // start_month
        $this->start_month->setDbValueDef($rsnew, $this->start_month->CurrentValue, false);

        // start_day
        $this->start_day->setDbValueDef($rsnew, $this->start_day->CurrentValue, false);

        // end_year
        $this->end_year->setDbValueDef($rsnew, $this->end_year->CurrentValue, false);

        // end_month
        $this->end_month->setDbValueDef($rsnew, $this->end_month->CurrentValue, false);

        // end_day
        $this->end_day->setDbValueDef($rsnew, $this->end_day->CurrentValue, false);

        // record_type
        $this->record_type->setDbValueDef($rsnew, $this->record_type->CurrentValue, false);

        // status
        $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, false);

        // symbol_sets
        $this->symbol_sets->setDbValueDef($rsnew, $this->symbol_sets->CurrentValue, false);

        // cipher_types
        $this->cipher_types->setDbValueDef($rsnew, $this->cipher_types->CurrentValue, false);

        // cipher_type_other
        $this->cipher_type_other->setDbValueDef($rsnew, $this->cipher_type_other->CurrentValue, false);

        // symbol_set_other
        $this->symbol_set_other->setDbValueDef($rsnew, $this->symbol_set_other->CurrentValue, false);

        // number_of_pages
        $this->number_of_pages->setDbValueDef($rsnew, $this->number_of_pages->CurrentValue, false);

        // inline_cleartext
        $this->inline_cleartext->setDbValueDef($rsnew, $this->inline_cleartext->CurrentValue, strval($this->inline_cleartext->CurrentValue) == "");

        // inline_plaintext
        $this->inline_plaintext->setDbValueDef($rsnew, $this->inline_plaintext->CurrentValue, strval($this->inline_plaintext->CurrentValue) == "");

        // cleartext_lang
        $this->cleartext_lang->setDbValueDef($rsnew, $this->cleartext_lang->CurrentValue, false);

        // plaintext_lang
        $this->plaintext_lang->setDbValueDef($rsnew, $this->plaintext_lang->CurrentValue, false);

        // document_types
        $this->document_types->setDbValueDef($rsnew, $this->document_types->CurrentValue, false);

        // paper
        $this->paper->setDbValueDef($rsnew, $this->paper->CurrentValue, false);

        // additional_information
        $this->additional_information->setDbValueDef($rsnew, $this->additional_information->CurrentValue, false);

        // creator_id
        $this->creator_id->setDbValueDef($rsnew, $this->creator_id->CurrentValue, false);

        // access_mode
        $this->access_mode->setDbValueDef($rsnew, $this->access_mode->CurrentValue, strval($this->access_mode->CurrentValue) == "");

        // km_encoded_plaintext_type
        $this->km_encoded_plaintext_type->setDbValueDef($rsnew, $this->km_encoded_plaintext_type->CurrentValue, false);

        // km_numbers
        $this->km_numbers->setDbValueDef($rsnew, $this->km_numbers->CurrentValue, false);

        // km_content_words
        $this->km_content_words->setDbValueDef($rsnew, $this->km_content_words->CurrentValue, false);

        // km_function_words
        $this->km_function_words->setDbValueDef($rsnew, $this->km_function_words->CurrentValue, false);

        // km_syllables
        $this->km_syllables->setDbValueDef($rsnew, $this->km_syllables->CurrentValue, false);

        // km_morphological_endings
        $this->km_morphological_endings->setDbValueDef($rsnew, $this->km_morphological_endings->CurrentValue, false);

        // km_phrases
        $this->km_phrases->setDbValueDef($rsnew, $this->km_phrases->CurrentValue, false);

        // km_sentences
        $this->km_sentences->setDbValueDef($rsnew, $this->km_sentences->CurrentValue, false);

        // km_punctuation
        $this->km_punctuation->setDbValueDef($rsnew, $this->km_punctuation->CurrentValue, false);

        // km_nomenclature_size
        $this->km_nomenclature_size->setDbValueDef($rsnew, $this->km_nomenclature_size->CurrentValue, false);

        // km_sections
        $this->km_sections->setDbValueDef($rsnew, $this->km_sections->CurrentValue, false);

        // km_headings
        $this->km_headings->setDbValueDef($rsnew, $this->km_headings->CurrentValue, false);

        // km_plaintext_arrangement
        $this->km_plaintext_arrangement->setDbValueDef($rsnew, $this->km_plaintext_arrangement->CurrentValue, false);

        // km_ciphertext_arrangement
        $this->km_ciphertext_arrangement->setDbValueDef($rsnew, $this->km_ciphertext_arrangement->CurrentValue, false);

        // km_memorability
        $this->km_memorability->setDbValueDef($rsnew, $this->km_memorability->CurrentValue, false);

        // km_symbol_set
        $this->km_symbol_set->setDbValueDef($rsnew, $this->km_symbol_set->CurrentValue, false);

        // km_diacritics
        $this->km_diacritics->setDbValueDef($rsnew, $this->km_diacritics->CurrentValue, false);

        // km_code_length
        $this->km_code_length->setDbValueDef($rsnew, $this->km_code_length->CurrentValue, false);

        // km_code_type
        $this->km_code_type->setDbValueDef($rsnew, $this->km_code_type->CurrentValue, false);

        // km_metaphors
        $this->km_metaphors->setDbValueDef($rsnew, $this->km_metaphors->CurrentValue, false);

        // km_material_properties
        $this->km_material_properties->setDbValueDef($rsnew, $this->km_material_properties->CurrentValue, false);

        // km_instructions
        $this->km_instructions->setDbValueDef($rsnew, $this->km_instructions->CurrentValue, false);

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Begin transaction
        if ($this->getCurrentDetailTable() != "" && $this->UseTransaction) {
            $conn->beginTransaction();
        }

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

        // Add detail records
        if ($addRow) {
            $detailTblVar = explode(",", $this->getCurrentDetailTable());
            $detailPage = Container("ImagesGrid");
            if (in_array("images", $detailTblVar) && $detailPage->DetailAdd && $addRow) {
                $detailPage->record_id->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "images"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->record_id->setSessionValue(""); // Clear master key if insert failed
                }
            }
            $detailPage = Container("DocumentsGrid");
            if (in_array("documents", $detailTblVar) && $detailPage->DetailAdd && $addRow) {
                $detailPage->record_id->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "documents"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->record_id->setSessionValue(""); // Clear master key if insert failed
                }
            }
            $detailPage = Container("AssociatedRecordsGrid");
            if (in_array("associated_records", $detailTblVar) && $detailPage->DetailAdd && $addRow) {
                $detailPage->record_id->setSessionValue($this->id->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "associated_records"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->record_id->setSessionValue(""); // Clear master key if insert failed
                }
            }
        }

        // Commit/Rollback transaction
        if ($this->getCurrentDetailTable() != "") {
            if ($addRow) {
                if ($this->UseTransaction) { // Commit transaction
                    $conn->commit();
                }
            } else {
                if ($this->UseTransaction) { // Rollback transaction
                    $conn->rollback();
                }
            }
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
                if ($detailPageObj->DetailAdd) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

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
                if ($detailPageObj->DetailAdd) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

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
                if ($detailPageObj->DetailAdd) {
                    $detailPageObj->EventCancelled = $this->EventCancelled;
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

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
    public function pageLoad() {
    	$nms = __NAMESPACE__;
    	include "../mhmkr/lang/help_fields.php";
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
