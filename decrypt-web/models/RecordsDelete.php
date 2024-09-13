<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class RecordsDelete extends Records
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "RecordsDelete";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // CSS class/style
    public $CurrentPageName = "RecordsDelete";

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
        $this->TableVar = 'records';
        $this->TableName = 'records';

        // Table CSS class
        $this->TableClass = "table table-bordered table-hover table-sm ew-table";

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
            SaveDebugMessage();
            Redirect(GetUrl($url));
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
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $TotalRecords = 0;
    public $RecordCount;
    public $RecKeys = [];
    public $StartRowCount = 1;
    public $RowCount = 0;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $UserProfile, $Language, $Security, $CurrentForm;

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

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("RecordsList"); // Prevent SQL injection, return to list
            return;
        }

        // Set up filter (WHERE Clause)
        $this->CurrentFilter = $filter;

        // Get action
        if (IsApi()) {
            $this->CurrentAction = "delete"; // Delete record directly
        } elseif (Param("action") !== null) {
            $this->CurrentAction = Param("action") == "delete" ? "delete" : "show";
        } else {
            $this->CurrentAction = $this->InlineDelete ?
                "delete" : // Delete record directly
                "show"; // Display record
        }
        if ($this->isDelete()) {
            $this->SendEmail = true; // Send email on delete success
            if ($this->deleteRows()) { // Delete rows
                if ($this->getSuccessMessage() == "") {
                    $this->setSuccessMessage($Language->phrase("DeleteSuccess")); // Set up success message
                }
                if (IsJsonResponse()) {
                    $this->terminate(true);
                    return;
                } else {
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                }
            } else { // Delete failed
                if (IsJsonResponse()) {
                    $this->terminate();
                    return;
                }
                // Return JSON error message if UseAjaxActions
                if ($this->UseAjaxActions) {
                    WriteJson([ "success" => false, "error" => $this->getFailureMessage() ]);
                    $this->clearFailureMessage();
                    $this->terminate();
                    return;                    
                }
                if ($this->InlineDelete) {
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                } else {
                    $this->CurrentAction = "show"; // Display record
                }
            }
        }
        if ($this->isShow()) { // Load records for display
            if ($this->Recordset = $this->loadRecordset()) {
                $this->TotalRecords = $this->Recordset->recordCount(); // Get record count
            }
            if ($this->TotalRecords <= 0) { // No record found, exit
                if ($this->Recordset) {
                    $this->Recordset->close();
                }
                $this->terminate("RecordsList"); // Return to list
                return;
            }
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

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

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

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        if (!$Security->canDelete()) {
            $this->setFailureMessage($Language->phrase("NoDeletePermission")); // No delete permission
            return false;
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAllAssociative($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }
        if ($this->UseTransaction) {
            $conn->beginTransaction();
        }

        // Clone old rows
        $rsold = $rows;
        $successKeys = [];
        $failKeys = [];
        foreach ($rsold as $row) {
            $thisKey = "";
            if ($thisKey != "") {
                $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
            }
            $thisKey .= $row['id'];

            // Call row deleting event
            $deleteRow = $this->rowDeleting($row);
            if ($deleteRow) { // Delete
                $deleteRow = $this->delete($row);
                if (!$deleteRow && !EmptyValue($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            }
            if ($deleteRow === false) {
                if ($this->UseTransaction) {
                    $successKeys = []; // Reset success keys
                    break;
                }
                $failKeys[] = $thisKey;
            } else {
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }

                // Call Row Deleted event
                $this->rowDeleted($row);
                $successKeys[] = $thisKey;
            }
        }

        // Any records deleted
        $deleteRows = count($successKeys) > 0;
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }
        if ($deleteRows) {
            if ($this->UseTransaction) { // Commit transaction
                $conn->commit();
            }

            // Set warning message if delete some records failed
            if (count($failKeys) > 0) {
                $this->setWarningMessage(str_replace("%k", explode(", ", $failKeys), $Language->phrase("DeleteRecordsFailed")));
            }
        } else {
            if ($this->UseTransaction) { // Rollback transaction
                $conn->rollback();
            }
        }

        // Write JSON response
        if ((IsJsonResponse() || ConvertToBool(Param("infinitescroll"))) && $deleteRows) {
            $rows = $this->getRecordsFromRecordset($rsold);
            $table = $this->TableVar;
            if (Route(2) !== null) { // Single delete
                $rows = $rows[0]; // Return object
            }
            WriteJson(["success" => true, "action" => Config("API_DELETE_ACTION"), $table => $rows]);
        }
        return $deleteRows;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("RecordsList");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("RecordsList"), "", $this->TableVar, true);
        $pageId = "delete";
        $Breadcrumb->add("delete", $pageId, $url);
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
}
