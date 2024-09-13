<?php

namespace PHPMaker2023\decryptweb23;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for records
 */
class Records extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $DbErrorMessage = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Ajax / Modal
    public $UseAjaxActions = false;
    public $ModalSearch = false;
    public $ModalView = false;
    public $ModalAdd = false;
    public $ModalEdit = false;
    public $ModalUpdate = false;
    public $InlineDelete = false;
    public $ModalGridAdd = false;
    public $ModalGridEdit = false;
    public $ModalMultiEdit = false;

    // Fields
    public $id;
    public $name;
    public $owner_id;
    public $owner;
    public $record_group_id;
    public $c_holder;
    public $c_cates;
    public $c_author;
    public $c_lang;
    public $current_country;
    public $current_city;
    public $current_holder;
    public $author;
    public $sender;
    public $receiver;
    public $origin_region;
    public $origin_city;
    public $start_year;
    public $start_month;
    public $start_day;
    public $end_year;
    public $end_month;
    public $end_day;
    public $record_type;
    public $status;
    public $symbol_sets;
    public $cipher_types;
    public $cipher_type_other;
    public $symbol_set_other;
    public $number_of_pages;
    public $inline_cleartext;
    public $inline_plaintext;
    public $cleartext_lang;
    public $plaintext_lang;
    public $private_ciphertext;
    public $document_types;
    public $paper;
    public $additional_information;
    public $creator_id;
    public $access_mode;
    public $creation_date;
    public $km_encoded_plaintext_type;
    public $km_numbers;
    public $km_content_words;
    public $km_function_words;
    public $km_syllables;
    public $km_morphological_endings;
    public $km_phrases;
    public $km_sentences;
    public $km_punctuation;
    public $km_nomenclature_size;
    public $km_sections;
    public $km_headings;
    public $km_plaintext_arrangement;
    public $km_ciphertext_arrangement;
    public $km_memorability;
    public $km_symbol_set;
    public $km_diacritics;
    public $km_code_length;
    public $km_code_type;
    public $km_metaphors;
    public $km_material_properties;
    public $km_instructions;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        parent::__construct();
        global $Language, $CurrentLanguage, $CurrentLocale;

        // Language object
        $Language = Container("language");
        $this->TableVar = "records";
        $this->TableName = 'records';
        $this->TableType = "TABLE";
        $this->ImportUseTransaction = $this->supportsTransaction() && Config("IMPORT_USE_TRANSACTION");
        $this->UseTransaction = $this->supportsTransaction() && Config("USE_TRANSACTION");

        // Update Table
        $this->UpdateTable = "records";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)

        // PDF
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)

        // PhpSpreadsheet
        $this->ExportExcelPageOrientation = null; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = null; // Page size (PhpSpreadsheet only)

        // PHPWord
        $this->ExportWordPageOrientation = ""; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = ""; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UseAjaxActions = $this->UseAjaxActions || Config("USE_AJAX_ACTIONS");
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this);

        // id
        $this->id = new DbField(
            $this, // Table
            'x_id', // Variable name
            'id', // Name
            '`id`', // Expression
            '`id`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'NO' // Edit Tag
        );
        $this->id->InputTextType = "text";
        $this->id->IsAutoIncrement = true; // Autoincrement field
        $this->id->IsPrimaryKey = true; // Primary key field
        $this->id->IsForeignKey = true; // Foreign key field
        $this->id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['id'] = &$this->id;

        // name
        $this->name = new DbField(
            $this, // Table
            'x_name', // Variable name
            'name', // Name
            '`name`', // Expression
            '`name`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`name`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->name->InputTextType = "text";
        $this->name->Nullable = false; // NOT NULL field
        $this->name->Required = true; // Required field
        $this->name->Lookup = new Lookup($this->name, 'records', true, 'name', ["name","","",""], '', '', [], [], [], [], [], [], '', '', "`name`");
        $this->name->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY"];
        $this->Fields['name'] = &$this->name;

        // owner_id
        $this->owner_id = new DbField(
            $this, // Table
            'x_owner_id', // Variable name
            'owner_id', // Name
            '`owner_id`', // Expression
            '`owner_id`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`owner_id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'HIDDEN' // Edit Tag
        );
        $this->owner_id->addMethod("getDefault", fn() => myCurrentUserID());
        $this->owner_id->InputTextType = "text";
        $this->owner_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->owner_id->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['owner_id'] = &$this->owner_id;

        // owner
        $this->owner = new DbField(
            $this, // Table
            'x_owner', // Variable name
            'owner', // Name
            '`owner`', // Expression
            '`owner`', // Basic search expression
            200, // Type
            128, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`owner`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->owner->InputTextType = "text";
        $this->owner->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['owner'] = &$this->owner;

        // record_group_id
        $this->record_group_id = new DbField(
            $this, // Table
            'x_record_group_id', // Variable name
            'record_group_id', // Name
            '`record_group_id`', // Expression
            '`record_group_id`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`record_group_id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->record_group_id->InputTextType = "text";
        $this->record_group_id->Sortable = false; // Allow sort
        $this->record_group_id->setSelectMultiple(false); // Select one
        $this->record_group_id->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->record_group_id->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->record_group_id->Lookup = new Lookup($this->record_group_id, 'record_group', false, 'id', ["name","","",""], '', '', [], [], [], [], [], [], '', '', "`name`");
        $this->record_group_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->record_group_id->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['record_group_id'] = &$this->record_group_id;

        // c_holder
        $this->c_holder = new DbField(
            $this, // Table
            'x_c_holder', // Variable name
            'c_holder', // Name
            'CONCAT(\'<b>\',IFNULL(current_city,\'\'),\'</b>\',\',\',IFNULL(current_holder,\'\'),\' <br><small>\',IFNULL(name,\'\'),\'</small>\')', // Expression
            'CONCAT(\'<b>\',IFNULL(current_city,\'\'),\'</b>\',\',\',IFNULL(current_holder,\'\'),\' <br><small>\',IFNULL(name,\'\'),\'</small>\')', // Basic search expression
            201, // Type
            602, // Size
            -1, // Date/Time format
            false, // Is upload field
            'CONCAT(\'<b>\',IFNULL(current_city,\'\'),\'</b>\',\',\',IFNULL(current_holder,\'\'),\' <br><small>\',IFNULL(name,\'\'),\'</small>\')', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->c_holder->InputTextType = "text";
        $this->c_holder->IsCustom = true; // Custom field
        $this->c_holder->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['c_holder'] = &$this->c_holder;

        // c_cates
        $this->c_cates = new DbField(
            $this, // Table
            'x_c_cates', // Variable name
            'c_cates', // Name
            'CONCAT(COALESCE(start_year,\'\'), \' - \',COALESCE(end_year,\'\'))', // Expression
            'CONCAT(COALESCE(start_year,\'\'), \' - \',COALESCE(end_year,\'\'))', // Basic search expression
            200, // Type
            15, // Size
            -1, // Date/Time format
            false, // Is upload field
            'CONCAT(COALESCE(start_year,\'\'), \' - \',COALESCE(end_year,\'\'))', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->c_cates->InputTextType = "text";
        $this->c_cates->IsCustom = true; // Custom field
        $this->c_cates->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['c_cates'] = &$this->c_cates;

        // c_author
        $this->c_author = new DbField(
            $this, // Table
            'x_c_author', // Variable name
            'c_author', // Name
            'CONCAT(\'<b>\', IFNULL(author,\'\'), \'</b><br/>\',IFNULL(origin_region,\'\'),\' \',IFNULL(origin_city,\'\'))', // Expression
            'CONCAT(\'<b>\', IFNULL(author,\'\'), \'</b><br/>\',IFNULL(origin_region,\'\'),\' \',IFNULL(origin_city,\'\'))', // Basic search expression
            201, // Type
            397, // Size
            -1, // Date/Time format
            false, // Is upload field
            'CONCAT(\'<b>\', IFNULL(author,\'\'), \'</b><br/>\',IFNULL(origin_region,\'\'),\' \',IFNULL(origin_city,\'\'))', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->c_author->addMethod("getViewCustomAttributes", fn() => "style='font-size:smaller;'");
        $this->c_author->InputTextType = "text";
        $this->c_author->IsCustom = true; // Custom field
        $this->c_author->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['c_author'] = &$this->c_author;

        // c_lang
        $this->c_lang = new DbField(
            $this, // Table
            'x_c_lang', // Variable name
            'c_lang', // Name
            'CONCAT(\'<b>Cleartext:</b>\', IFNULL(cleartext_lang,\'\'),\'<br/><b>Plaintext:</b>\',IFNULL(plaintext_lang,\'\'))', // Expression
            'CONCAT(\'<b>Cleartext:</b>\', IFNULL(cleartext_lang,\'\'),\'<br/><b>Plaintext:</b>\',IFNULL(plaintext_lang,\'\'))', // Basic search expression
            200, // Type
            167, // Size
            -1, // Date/Time format
            false, // Is upload field
            'CONCAT(\'<b>Cleartext:</b>\', IFNULL(cleartext_lang,\'\'),\'<br/><b>Plaintext:</b>\',IFNULL(plaintext_lang,\'\'))', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->c_lang->addMethod("getViewCustomAttributes", fn() => "style='font-size:smaller;'");
        $this->c_lang->InputTextType = "text";
        $this->c_lang->IsCustom = true; // Custom field
        $this->c_lang->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['c_lang'] = &$this->c_lang;

        // current_country
        $this->current_country = new DbField(
            $this, // Table
            'x_current_country', // Variable name
            'current_country', // Name
            '`current_country`', // Expression
            '`current_country`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`current_country`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->current_country->InputTextType = "text";
        $this->current_country->Lookup = new Lookup($this->current_country, 'records', true, 'current_country', ["current_country","","",""], '', '', [], [], [], [], [], [], '', '', "`current_country`");
        $this->current_country->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['current_country'] = &$this->current_country;

        // current_city
        $this->current_city = new DbField(
            $this, // Table
            'x_current_city', // Variable name
            'current_city', // Name
            '`current_city`', // Expression
            '`current_city`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`current_city`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->current_city->InputTextType = "text";
        $this->current_city->Lookup = new Lookup($this->current_city, 'records', true, 'current_city', ["current_city","","",""], '', '', [], [], [], [], [], [], '', '', "`current_city`");
        $this->current_city->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['current_city'] = &$this->current_city;

        // current_holder
        $this->current_holder = new DbField(
            $this, // Table
            'x_current_holder', // Variable name
            'current_holder', // Name
            '`current_holder`', // Expression
            '`current_holder`', // Basic search expression
            200, // Type
            255, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`current_holder`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->current_holder->InputTextType = "text";
        $this->current_holder->Lookup = new Lookup($this->current_holder, 'records', true, 'current_holder', ["current_holder","","",""], '', '', [], [], [], [], [], [], '', '', "`current_holder`");
        $this->current_holder->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['current_holder'] = &$this->current_holder;

        // author
        $this->author = new DbField(
            $this, // Table
            'x_author', // Variable name
            'author', // Name
            '`author`', // Expression
            '`author`', // Basic search expression
            201, // Type
            256, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`author`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->author->InputTextType = "text";
        $this->author->Lookup = new Lookup($this->author, 'records', true, 'author', ["author","","",""], '', '', [], [], [], [], [], [], '', '', "`author`");
        $this->author->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['author'] = &$this->author;

        // sender
        $this->sender = new DbField(
            $this, // Table
            'x_sender', // Variable name
            'sender', // Name
            '`sender`', // Expression
            '`sender`', // Basic search expression
            201, // Type
            256, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`sender`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->sender->InputTextType = "text";
        $this->sender->Lookup = new Lookup($this->sender, 'records', true, 'sender', ["sender","","",""], '', '', [], [], [], [], [], [], '', '', "`sender`");
        $this->sender->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['sender'] = &$this->sender;

        // receiver
        $this->receiver = new DbField(
            $this, // Table
            'x_receiver', // Variable name
            'receiver', // Name
            '`receiver`', // Expression
            '`receiver`', // Basic search expression
            201, // Type
            256, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`receiver`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->receiver->InputTextType = "text";
        $this->receiver->Lookup = new Lookup($this->receiver, 'records', true, 'receiver', ["receiver","","",""], '', '', [], [], [], [], [], [], '', '', "`receiver`");
        $this->receiver->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['receiver'] = &$this->receiver;

        // origin_region
        $this->origin_region = new DbField(
            $this, // Table
            'x_origin_region', // Variable name
            'origin_region', // Name
            '`origin_region`', // Expression
            '`origin_region`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`origin_region`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->origin_region->InputTextType = "text";
        $this->origin_region->Lookup = new Lookup($this->origin_region, 'records', true, 'origin_region', ["origin_region","","",""], '', '', [], [], [], [], [], [], '', '', "`origin_region`");
        $this->origin_region->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['origin_region'] = &$this->origin_region;

        // origin_city
        $this->origin_city = new DbField(
            $this, // Table
            'x_origin_city', // Variable name
            'origin_city', // Name
            '`origin_city`', // Expression
            '`origin_city`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`origin_city`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->origin_city->InputTextType = "text";
        $this->origin_city->Lookup = new Lookup($this->origin_city, 'records', true, 'origin_city', ["origin_city","","",""], '', '', [], [], [], [], [], [], '', '', "`origin_city`");
        $this->origin_city->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['origin_city'] = &$this->origin_city;

        // start_year
        $this->start_year = new DbField(
            $this, // Table
            'x_start_year', // Variable name
            'start_year', // Name
            '`start_year`', // Expression
            '`start_year`', // Basic search expression
            2, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`start_year`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->start_year->InputTextType = "text";
        $this->start_year->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->start_year->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['start_year'] = &$this->start_year;

        // start_month
        $this->start_month = new DbField(
            $this, // Table
            'x_start_month', // Variable name
            'start_month', // Name
            '`start_month`', // Expression
            '`start_month`', // Basic search expression
            2, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`start_month`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->start_month->InputTextType = "text";
        $this->start_month->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->start_month->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['start_month'] = &$this->start_month;

        // start_day
        $this->start_day = new DbField(
            $this, // Table
            'x_start_day', // Variable name
            'start_day', // Name
            '`start_day`', // Expression
            '`start_day`', // Basic search expression
            2, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`start_day`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->start_day->InputTextType = "text";
        $this->start_day->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->start_day->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['start_day'] = &$this->start_day;

        // end_year
        $this->end_year = new DbField(
            $this, // Table
            'x_end_year', // Variable name
            'end_year', // Name
            '`end_year`', // Expression
            '`end_year`', // Basic search expression
            2, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`end_year`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->end_year->InputTextType = "text";
        $this->end_year->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->end_year->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['end_year'] = &$this->end_year;

        // end_month
        $this->end_month = new DbField(
            $this, // Table
            'x_end_month', // Variable name
            'end_month', // Name
            '`end_month`', // Expression
            '`end_month`', // Basic search expression
            2, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`end_month`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->end_month->InputTextType = "text";
        $this->end_month->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->end_month->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['end_month'] = &$this->end_month;

        // end_day
        $this->end_day = new DbField(
            $this, // Table
            'x_end_day', // Variable name
            'end_day', // Name
            '`end_day`', // Expression
            '`end_day`', // Basic search expression
            2, // Type
            6, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`end_day`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->end_day->InputTextType = "text";
        $this->end_day->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->end_day->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['end_day'] = &$this->end_day;

        // record_type
        $this->record_type = new DbField(
            $this, // Table
            'x_record_type', // Variable name
            'record_type', // Name
            '`record_type`', // Expression
            '`record_type`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`record_type`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->record_type->addMethod("getViewCustomAttributes", fn() => "style='font-size:smaller;'");
        $this->record_type->InputTextType = "text";
        $this->record_type->setSelectMultiple(false); // Select one
        $this->record_type->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->record_type->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->record_type->Lookup = new Lookup($this->record_type, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->record_type->OptionCount = 3;
        $this->record_type->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->record_type->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['record_type'] = &$this->record_type;

        // status
        $this->status = new DbField(
            $this, // Table
            'x_status', // Variable name
            'status', // Name
            '`status`', // Expression
            '`status`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`status`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->status->addMethod("getViewCustomAttributes", fn() => "style='font-size:smaller;'");
        $this->status->InputTextType = "text";
        $this->status->setSelectMultiple(false); // Select one
        $this->status->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->status->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->status->Lookup = new Lookup($this->status, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->status->OptionCount = 4;
        $this->status->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->status->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['status'] = &$this->status;

        // symbol_sets
        $this->symbol_sets = new DbField(
            $this, // Table
            'x_symbol_sets', // Variable name
            'symbol_sets', // Name
            '`symbol_sets`', // Expression
            '`symbol_sets`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`symbol_sets`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->symbol_sets->InputTextType = "text";
        $this->symbol_sets->Lookup = new Lookup($this->symbol_sets, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->symbol_sets->OptionCount = 4;
        $this->symbol_sets->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['symbol_sets'] = &$this->symbol_sets;

        // cipher_types
        $this->cipher_types = new DbField(
            $this, // Table
            'x_cipher_types', // Variable name
            'cipher_types', // Name
            '`cipher_types`', // Expression
            '`cipher_types`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cipher_types`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->cipher_types->InputTextType = "text";
        $this->cipher_types->Lookup = new Lookup($this->cipher_types, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->cipher_types->OptionCount = 8;
        $this->cipher_types->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['cipher_types'] = &$this->cipher_types;

        // cipher_type_other
        $this->cipher_type_other = new DbField(
            $this, // Table
            'x_cipher_type_other', // Variable name
            'cipher_type_other', // Name
            '`cipher_type_other`', // Expression
            '`cipher_type_other`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cipher_type_other`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cipher_type_other->InputTextType = "text";
        $this->cipher_type_other->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['cipher_type_other'] = &$this->cipher_type_other;

        // symbol_set_other
        $this->symbol_set_other = new DbField(
            $this, // Table
            'x_symbol_set_other', // Variable name
            'symbol_set_other', // Name
            '`symbol_set_other`', // Expression
            '`symbol_set_other`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`symbol_set_other`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->symbol_set_other->InputTextType = "text";
        $this->symbol_set_other->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['symbol_set_other'] = &$this->symbol_set_other;

        // number_of_pages
        $this->number_of_pages = new DbField(
            $this, // Table
            'x_number_of_pages', // Variable name
            'number_of_pages', // Name
            '`number_of_pages`', // Expression
            '`number_of_pages`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`number_of_pages`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->number_of_pages->InputTextType = "text";
        $this->number_of_pages->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->number_of_pages->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['number_of_pages'] = &$this->number_of_pages;

        // inline_cleartext
        $this->inline_cleartext = new DbField(
            $this, // Table
            'x_inline_cleartext', // Variable name
            'inline_cleartext', // Name
            '`inline_cleartext`', // Expression
            '`inline_cleartext`', // Basic search expression
            129, // Type
            5, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`inline_cleartext`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->inline_cleartext->addMethod("getDefault", fn() => "False");
        $this->inline_cleartext->InputTextType = "text";
        $this->inline_cleartext->Nullable = false; // NOT NULL field
        $this->inline_cleartext->Lookup = new Lookup($this->inline_cleartext, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->inline_cleartext->OptionCount = 2;
        $this->inline_cleartext->SearchOperators = ["=", "<>"];
        $this->Fields['inline_cleartext'] = &$this->inline_cleartext;

        // inline_plaintext
        $this->inline_plaintext = new DbField(
            $this, // Table
            'x_inline_plaintext', // Variable name
            'inline_plaintext', // Name
            '`inline_plaintext`', // Expression
            '`inline_plaintext`', // Basic search expression
            129, // Type
            5, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`inline_plaintext`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->inline_plaintext->addMethod("getDefault", fn() => "False");
        $this->inline_plaintext->InputTextType = "text";
        $this->inline_plaintext->Nullable = false; // NOT NULL field
        $this->inline_plaintext->Lookup = new Lookup($this->inline_plaintext, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->inline_plaintext->OptionCount = 2;
        $this->inline_plaintext->SearchOperators = ["=", "<>"];
        $this->Fields['inline_plaintext'] = &$this->inline_plaintext;

        // cleartext_lang
        $this->cleartext_lang = new DbField(
            $this, // Table
            'x_cleartext_lang', // Variable name
            'cleartext_lang', // Name
            '`cleartext_lang`', // Expression
            '`cleartext_lang`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`cleartext_lang`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->cleartext_lang->InputTextType = "text";
        $this->cleartext_lang->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['cleartext_lang'] = &$this->cleartext_lang;

        // plaintext_lang
        $this->plaintext_lang = new DbField(
            $this, // Table
            'x_plaintext_lang', // Variable name
            'plaintext_lang', // Name
            '`plaintext_lang`', // Expression
            '`plaintext_lang`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`plaintext_lang`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->plaintext_lang->InputTextType = "text";
        $this->plaintext_lang->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['plaintext_lang'] = &$this->plaintext_lang;

        // private_ciphertext
        $this->private_ciphertext = new DbField(
            $this, // Table
            'x_private_ciphertext', // Variable name
            'private_ciphertext', // Name
            '`private_ciphertext`', // Expression
            '`private_ciphertext`', // Basic search expression
            129, // Type
            5, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`private_ciphertext`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->private_ciphertext->addMethod("getDefault", fn() => "True");
        $this->private_ciphertext->InputTextType = "text";
        $this->private_ciphertext->Nullable = false; // NOT NULL field
        $this->private_ciphertext->Lookup = new Lookup($this->private_ciphertext, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->private_ciphertext->OptionCount = 2;
        $this->private_ciphertext->SearchOperators = ["=", "<>"];
        $this->Fields['private_ciphertext'] = &$this->private_ciphertext;

        // document_types
        $this->document_types = new DbField(
            $this, // Table
            'x_document_types', // Variable name
            'document_types', // Name
            '`document_types`', // Expression
            '`document_types`', // Basic search expression
            201, // Type
            256, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`document_types`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->document_types->InputTextType = "text";
        $this->document_types->Lookup = new Lookup($this->document_types, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->document_types->OptionCount = 9;
        $this->document_types->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['document_types'] = &$this->document_types;

        // paper
        $this->paper = new DbField(
            $this, // Table
            'x_paper', // Variable name
            'paper', // Name
            '`paper`', // Expression
            '`paper`', // Basic search expression
            200, // Type
            64, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`paper`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->paper->InputTextType = "text";
        $this->paper->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['paper'] = &$this->paper;

        // additional_information
        $this->additional_information = new DbField(
            $this, // Table
            'x_additional_information', // Variable name
            'additional_information', // Name
            '`additional_information`', // Expression
            '`additional_information`', // Basic search expression
            201, // Type
            65535, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`additional_information`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXTAREA' // Edit Tag
        );
        $this->additional_information->InputTextType = "text";
        $this->additional_information->SearchOperators = ["=", "<>", "IN", "NOT IN", "STARTS WITH", "NOT STARTS WITH", "LIKE", "NOT LIKE", "ENDS WITH", "NOT ENDS WITH", "IS EMPTY", "IS NOT EMPTY", "IS NULL", "IS NOT NULL"];
        $this->Fields['additional_information'] = &$this->additional_information;

        // creator_id
        $this->creator_id = new DbField(
            $this, // Table
            'x_creator_id', // Variable name
            'creator_id', // Name
            '`creator_id`', // Expression
            '`creator_id`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`creator_id`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'HIDDEN' // Edit Tag
        );
        $this->creator_id->addMethod("getDefault", fn() => myCurrentUserID());
        $this->creator_id->InputTextType = "text";
        $this->creator_id->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->creator_id->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['creator_id'] = &$this->creator_id;

        // access_mode
        $this->access_mode = new DbField(
            $this, // Table
            'x_access_mode', // Variable name
            'access_mode', // Name
            '`access_mode`', // Expression
            '`access_mode`', // Basic search expression
            3, // Type
            11, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`access_mode`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->access_mode->addMethod("getDefault", fn() => 0);
        $this->access_mode->InputTextType = "text";
        $this->access_mode->Lookup = new Lookup($this->access_mode, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->access_mode->OptionCount = 2;
        $this->access_mode->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->access_mode->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['access_mode'] = &$this->access_mode;

        // creation_date
        $this->creation_date = new DbField(
            $this, // Table
            'x_creation_date', // Variable name
            'creation_date', // Name
            '`creation_date`', // Expression
            CastDateFieldForLike("`creation_date`", 0, "DB"), // Basic search expression
            135, // Type
            19, // Size
            0, // Date/Time format
            false, // Is upload field
            '`creation_date`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'TEXT' // Edit Tag
        );
        $this->creation_date->InputTextType = "text";
        $this->creation_date->Nullable = false; // NOT NULL field
        $this->creation_date->Required = true; // Required field
        $this->creation_date->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->creation_date->SearchOperators = ["=", "<>", "IN", "NOT IN", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN"];
        $this->Fields['creation_date'] = &$this->creation_date;

        // km_encoded_plaintext_type
        $this->km_encoded_plaintext_type = new DbField(
            $this, // Table
            'x_km_encoded_plaintext_type', // Variable name
            'km_encoded_plaintext_type', // Name
            '`km_encoded_plaintext_type`', // Expression
            '`km_encoded_plaintext_type`', // Basic search expression
            200, // Type
            32, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_encoded_plaintext_type`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->km_encoded_plaintext_type->InputTextType = "text";
        $this->km_encoded_plaintext_type->setSelectMultiple(true); // Select multiple
        $this->km_encoded_plaintext_type->Lookup = new Lookup($this->km_encoded_plaintext_type, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_encoded_plaintext_type->OptionCount = 3;
        $this->km_encoded_plaintext_type->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_encoded_plaintext_type'] = &$this->km_encoded_plaintext_type;

        // km_numbers
        $this->km_numbers = new DbField(
            $this, // Table
            'x_km_numbers', // Variable name
            'km_numbers', // Name
            '`km_numbers`', // Expression
            '`km_numbers`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_numbers`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_numbers->InputTextType = "text";
        $this->km_numbers->Lookup = new Lookup($this->km_numbers, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_numbers->OptionCount = 2;
        $this->km_numbers->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_numbers->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_numbers'] = &$this->km_numbers;

        // km_content_words
        $this->km_content_words = new DbField(
            $this, // Table
            'x_km_content_words', // Variable name
            'km_content_words', // Name
            '`km_content_words`', // Expression
            '`km_content_words`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_content_words`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_content_words->InputTextType = "text";
        $this->km_content_words->Lookup = new Lookup($this->km_content_words, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_content_words->OptionCount = 2;
        $this->km_content_words->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_content_words->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_content_words'] = &$this->km_content_words;

        // km_function_words
        $this->km_function_words = new DbField(
            $this, // Table
            'x_km_function_words', // Variable name
            'km_function_words', // Name
            '`km_function_words`', // Expression
            '`km_function_words`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_function_words`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_function_words->InputTextType = "text";
        $this->km_function_words->Lookup = new Lookup($this->km_function_words, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_function_words->OptionCount = 2;
        $this->km_function_words->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_function_words->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_function_words'] = &$this->km_function_words;

        // km_syllables
        $this->km_syllables = new DbField(
            $this, // Table
            'x_km_syllables', // Variable name
            'km_syllables', // Name
            '`km_syllables`', // Expression
            '`km_syllables`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_syllables`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_syllables->InputTextType = "text";
        $this->km_syllables->Lookup = new Lookup($this->km_syllables, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_syllables->OptionCount = 2;
        $this->km_syllables->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_syllables->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_syllables'] = &$this->km_syllables;

        // km_morphological_endings
        $this->km_morphological_endings = new DbField(
            $this, // Table
            'x_km_morphological_endings', // Variable name
            'km_morphological_endings', // Name
            '`km_morphological_endings`', // Expression
            '`km_morphological_endings`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_morphological_endings`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_morphological_endings->InputTextType = "text";
        $this->km_morphological_endings->Lookup = new Lookup($this->km_morphological_endings, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_morphological_endings->OptionCount = 2;
        $this->km_morphological_endings->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_morphological_endings->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_morphological_endings'] = &$this->km_morphological_endings;

        // km_phrases
        $this->km_phrases = new DbField(
            $this, // Table
            'x_km_phrases', // Variable name
            'km_phrases', // Name
            '`km_phrases`', // Expression
            '`km_phrases`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_phrases`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_phrases->InputTextType = "text";
        $this->km_phrases->Lookup = new Lookup($this->km_phrases, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_phrases->OptionCount = 2;
        $this->km_phrases->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_phrases->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_phrases'] = &$this->km_phrases;

        // km_sentences
        $this->km_sentences = new DbField(
            $this, // Table
            'x_km_sentences', // Variable name
            'km_sentences', // Name
            '`km_sentences`', // Expression
            '`km_sentences`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_sentences`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_sentences->InputTextType = "text";
        $this->km_sentences->Lookup = new Lookup($this->km_sentences, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_sentences->OptionCount = 2;
        $this->km_sentences->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_sentences->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_sentences'] = &$this->km_sentences;

        // km_punctuation
        $this->km_punctuation = new DbField(
            $this, // Table
            'x_km_punctuation', // Variable name
            'km_punctuation', // Name
            '`km_punctuation`', // Expression
            '`km_punctuation`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_punctuation`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_punctuation->InputTextType = "text";
        $this->km_punctuation->Lookup = new Lookup($this->km_punctuation, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_punctuation->OptionCount = 2;
        $this->km_punctuation->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_punctuation->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_punctuation'] = &$this->km_punctuation;

        // km_nomenclature_size
        $this->km_nomenclature_size = new DbField(
            $this, // Table
            'x_km_nomenclature_size', // Variable name
            'km_nomenclature_size', // Name
            '`km_nomenclature_size`', // Expression
            '`km_nomenclature_size`', // Basic search expression
            200, // Type
            32, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_nomenclature_size`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->km_nomenclature_size->InputTextType = "text";
        $this->km_nomenclature_size->setSelectMultiple(false); // Select one
        $this->km_nomenclature_size->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->km_nomenclature_size->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->km_nomenclature_size->Lookup = new Lookup($this->km_nomenclature_size, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_nomenclature_size->OptionCount = 5;
        $this->km_nomenclature_size->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_nomenclature_size'] = &$this->km_nomenclature_size;

        // km_sections
        $this->km_sections = new DbField(
            $this, // Table
            'x_km_sections', // Variable name
            'km_sections', // Name
            '`km_sections`', // Expression
            '`km_sections`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_sections`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_sections->InputTextType = "text";
        $this->km_sections->Lookup = new Lookup($this->km_sections, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_sections->OptionCount = 2;
        $this->km_sections->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_sections->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_sections'] = &$this->km_sections;

        // km_headings
        $this->km_headings = new DbField(
            $this, // Table
            'x_km_headings', // Variable name
            'km_headings', // Name
            '`km_headings`', // Expression
            '`km_headings`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_headings`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_headings->InputTextType = "text";
        $this->km_headings->Lookup = new Lookup($this->km_headings, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_headings->OptionCount = 2;
        $this->km_headings->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_headings->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_headings'] = &$this->km_headings;

        // km_plaintext_arrangement
        $this->km_plaintext_arrangement = new DbField(
            $this, // Table
            'x_km_plaintext_arrangement', // Variable name
            'km_plaintext_arrangement', // Name
            '`km_plaintext_arrangement`', // Expression
            '`km_plaintext_arrangement`', // Basic search expression
            200, // Type
            32, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_plaintext_arrangement`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->km_plaintext_arrangement->InputTextType = "text";
        $this->km_plaintext_arrangement->setSelectMultiple(true); // Select multiple
        $this->km_plaintext_arrangement->Lookup = new Lookup($this->km_plaintext_arrangement, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_plaintext_arrangement->OptionCount = 3;
        $this->km_plaintext_arrangement->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_plaintext_arrangement'] = &$this->km_plaintext_arrangement;

        // km_ciphertext_arrangement
        $this->km_ciphertext_arrangement = new DbField(
            $this, // Table
            'x_km_ciphertext_arrangement', // Variable name
            'km_ciphertext_arrangement', // Name
            '`km_ciphertext_arrangement`', // Expression
            '`km_ciphertext_arrangement`', // Basic search expression
            200, // Type
            32, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_ciphertext_arrangement`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->km_ciphertext_arrangement->InputTextType = "text";
        $this->km_ciphertext_arrangement->setSelectMultiple(true); // Select multiple
        $this->km_ciphertext_arrangement->Lookup = new Lookup($this->km_ciphertext_arrangement, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_ciphertext_arrangement->OptionCount = 3;
        $this->km_ciphertext_arrangement->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_ciphertext_arrangement'] = &$this->km_ciphertext_arrangement;

        // km_memorability
        $this->km_memorability = new DbField(
            $this, // Table
            'x_km_memorability', // Variable name
            'km_memorability', // Name
            '`km_memorability`', // Expression
            '`km_memorability`', // Basic search expression
            200, // Type
            32, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_memorability`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->km_memorability->InputTextType = "text";
        $this->km_memorability->setSelectMultiple(false); // Select one
        $this->km_memorability->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->km_memorability->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->km_memorability->Lookup = new Lookup($this->km_memorability, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_memorability->OptionCount = 3;
        $this->km_memorability->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_memorability'] = &$this->km_memorability;

        // km_symbol_set
        $this->km_symbol_set = new DbField(
            $this, // Table
            'x_km_symbol_set', // Variable name
            'km_symbol_set', // Name
            '`km_symbol_set`', // Expression
            '`km_symbol_set`', // Basic search expression
            200, // Type
            32, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_symbol_set`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'CHECKBOX' // Edit Tag
        );
        $this->km_symbol_set->InputTextType = "text";
        $this->km_symbol_set->Lookup = new Lookup($this->km_symbol_set, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_symbol_set->OptionCount = 3;
        $this->km_symbol_set->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_symbol_set'] = &$this->km_symbol_set;

        // km_diacritics
        $this->km_diacritics = new DbField(
            $this, // Table
            'x_km_diacritics', // Variable name
            'km_diacritics', // Name
            '`km_diacritics`', // Expression
            '`km_diacritics`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_diacritics`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_diacritics->InputTextType = "text";
        $this->km_diacritics->Lookup = new Lookup($this->km_diacritics, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_diacritics->OptionCount = 2;
        $this->km_diacritics->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_diacritics->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_diacritics'] = &$this->km_diacritics;

        // km_code_length
        $this->km_code_length = new DbField(
            $this, // Table
            'x_km_code_length', // Variable name
            'km_code_length', // Name
            '`km_code_length`', // Expression
            '`km_code_length`', // Basic search expression
            200, // Type
            32, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_code_length`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->km_code_length->InputTextType = "text";
        $this->km_code_length->setSelectMultiple(true); // Select multiple
        $this->km_code_length->Lookup = new Lookup($this->km_code_length, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_code_length->OptionCount = 3;
        $this->km_code_length->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_code_length'] = &$this->km_code_length;

        // km_code_type
        $this->km_code_type = new DbField(
            $this, // Table
            'x_km_code_type', // Variable name
            'km_code_type', // Name
            '`km_code_type`', // Expression
            '`km_code_type`', // Basic search expression
            200, // Type
            32, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_code_type`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->km_code_type->InputTextType = "text";
        $this->km_code_type->setSelectMultiple(true); // Select multiple
        $this->km_code_type->Lookup = new Lookup($this->km_code_type, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_code_type->OptionCount = 3;
        $this->km_code_type->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_code_type'] = &$this->km_code_type;

        // km_metaphors
        $this->km_metaphors = new DbField(
            $this, // Table
            'x_km_metaphors', // Variable name
            'km_metaphors', // Name
            '`km_metaphors`', // Expression
            '`km_metaphors`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_metaphors`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_metaphors->InputTextType = "text";
        $this->km_metaphors->Lookup = new Lookup($this->km_metaphors, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_metaphors->OptionCount = 2;
        $this->km_metaphors->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_metaphors->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_metaphors'] = &$this->km_metaphors;

        // km_material_properties
        $this->km_material_properties = new DbField(
            $this, // Table
            'x_km_material_properties', // Variable name
            'km_material_properties', // Name
            '`km_material_properties`', // Expression
            '`km_material_properties`', // Basic search expression
            200, // Type
            32, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_material_properties`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'SELECT' // Edit Tag
        );
        $this->km_material_properties->InputTextType = "text";
        $this->km_material_properties->setSelectMultiple(true); // Select multiple
        $this->km_material_properties->Lookup = new Lookup($this->km_material_properties, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_material_properties->OptionCount = 2;
        $this->km_material_properties->SearchOperators = ["=", "<>", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_material_properties'] = &$this->km_material_properties;

        // km_instructions
        $this->km_instructions = new DbField(
            $this, // Table
            'x_km_instructions', // Variable name
            'km_instructions', // Name
            '`km_instructions`', // Expression
            '`km_instructions`', // Basic search expression
            16, // Type
            4, // Size
            -1, // Date/Time format
            false, // Is upload field
            '`km_instructions`', // Virtual expression
            false, // Is virtual
            false, // Force selection
            false, // Is Virtual search
            'FORMATTED TEXT', // View Tag
            'RADIO' // Edit Tag
        );
        $this->km_instructions->InputTextType = "text";
        $this->km_instructions->Lookup = new Lookup($this->km_instructions, 'records', false, '', ["","","",""], '', '', [], [], [], [], [], [], '', '', "");
        $this->km_instructions->OptionCount = 2;
        $this->km_instructions->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->km_instructions->SearchOperators = ["=", "<>", "<", "<=", ">", ">=", "BETWEEN", "NOT BETWEEN", "IS NULL", "IS NOT NULL"];
        $this->Fields['km_instructions'] = &$this->km_instructions;

        // Add Doctrine Cache
        $this->Cache = new ArrayCache();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);

        // Call Table Load event
        $this->tableLoad();
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        $flds = GetSortFields($orderBy);
        foreach ($this->Fields as $field) {
            $fldSort = "";
            foreach ($flds as $fld) {
                if ($fld[0] == $field->Expression || $fld[0] == $field->VirtualExpression) {
                    $fldSort = $fld[1];
                }
            }
            $field->setSort($fldSort);
        }
    }

    // Current detail table name
    public function getCurrentDetailTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE")) ?? "";
    }

    public function setCurrentDetailTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE")] = $v;
    }

    // Get detail url
    public function getDetailUrl()
    {
        // Detail url
        $detailUrl = "";
        if ($this->getCurrentDetailTable() == "images") {
            $detailUrl = Container("images")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "documents") {
            $detailUrl = Container("documents")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "associated_records") {
            $detailUrl = Container("associated_records")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_id", $this->id->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "RecordsList";
        }
        return $detailUrl;
    }

    // Render X Axis for chart
    public function renderChartXAxis($chartVar, $chartRow)
    {
        return $chartRow;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "records";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*, CONCAT('<b>',IFNULL(current_city,''),'</b>',',',IFNULL(current_holder,''),' <br><small>',IFNULL(name,''),'</small>') AS `c_holder`, CONCAT(COALESCE(start_year,''), ' - ',COALESCE(end_year,'')) AS `c_cates`, CONCAT('<b>', IFNULL(author,''), '</b><br/>',IFNULL(origin_region,''),' ',IFNULL(origin_city,'')) AS `c_author`, CONCAT('<b>Cleartext:</b>', IFNULL(cleartext_lang,''),'<br/><b>Plaintext:</b>',IFNULL(plaintext_lang,'')) AS `c_lang`");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            case "lookup":
                return (($allow & 256) == 256);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlwrk);
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->getSqlAsQueryBuilder($where, $orderBy)->getSQL();
    }

    // Get QueryBuilder
    public function getSqlAsQueryBuilder($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        );
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        try {
            $success = $this->insertSql($rs)->execute();
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }
        if ($success) {
            // Get insert id if necessary
            $this->id->setDbValue($conn->lastInsertId());
            $rs['id'] = $this->id->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        try {
            $success = $this->updateSql($rs, $where, $curfilter)->execute();
            $success = ($success > 0) ? $success : true;
            $this->DbErrorMessage = "";
        } catch (\Exception $e) {
            $success = false;
            $this->DbErrorMessage = $e->getMessage();
        }

        // Return auto increment field
        if ($success) {
            if (!isset($rs['id']) && !EmptyValue($this->id->CurrentValue)) {
                $rs['id'] = $this->id->CurrentValue;
            }
        }
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('id', $rs)) {
                AddFilter($where, QuotedName('id', $this->Dbid) . '=' . QuotedValue($rs['id'], $this->id->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            try {
                $success = $this->deleteSql($rs, $where, $curfilter)->execute();
                $this->DbErrorMessage = "";
            } catch (\Exception $e) {
                $success = false;
                $this->DbErrorMessage = $e->getMessage();
            }
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->id->DbValue = $row['id'];
        $this->name->DbValue = $row['name'];
        $this->owner_id->DbValue = $row['owner_id'];
        $this->owner->DbValue = $row['owner'];
        $this->record_group_id->DbValue = $row['record_group_id'];
        $this->c_holder->DbValue = $row['c_holder'];
        $this->c_cates->DbValue = $row['c_cates'];
        $this->c_author->DbValue = $row['c_author'];
        $this->c_lang->DbValue = $row['c_lang'];
        $this->current_country->DbValue = $row['current_country'];
        $this->current_city->DbValue = $row['current_city'];
        $this->current_holder->DbValue = $row['current_holder'];
        $this->author->DbValue = $row['author'];
        $this->sender->DbValue = $row['sender'];
        $this->receiver->DbValue = $row['receiver'];
        $this->origin_region->DbValue = $row['origin_region'];
        $this->origin_city->DbValue = $row['origin_city'];
        $this->start_year->DbValue = $row['start_year'];
        $this->start_month->DbValue = $row['start_month'];
        $this->start_day->DbValue = $row['start_day'];
        $this->end_year->DbValue = $row['end_year'];
        $this->end_month->DbValue = $row['end_month'];
        $this->end_day->DbValue = $row['end_day'];
        $this->record_type->DbValue = $row['record_type'];
        $this->status->DbValue = $row['status'];
        $this->symbol_sets->DbValue = $row['symbol_sets'];
        $this->cipher_types->DbValue = $row['cipher_types'];
        $this->cipher_type_other->DbValue = $row['cipher_type_other'];
        $this->symbol_set_other->DbValue = $row['symbol_set_other'];
        $this->number_of_pages->DbValue = $row['number_of_pages'];
        $this->inline_cleartext->DbValue = $row['inline_cleartext'];
        $this->inline_plaintext->DbValue = $row['inline_plaintext'];
        $this->cleartext_lang->DbValue = $row['cleartext_lang'];
        $this->plaintext_lang->DbValue = $row['plaintext_lang'];
        $this->private_ciphertext->DbValue = $row['private_ciphertext'];
        $this->document_types->DbValue = $row['document_types'];
        $this->paper->DbValue = $row['paper'];
        $this->additional_information->DbValue = $row['additional_information'];
        $this->creator_id->DbValue = $row['creator_id'];
        $this->access_mode->DbValue = $row['access_mode'];
        $this->creation_date->DbValue = $row['creation_date'];
        $this->km_encoded_plaintext_type->DbValue = $row['km_encoded_plaintext_type'];
        $this->km_numbers->DbValue = $row['km_numbers'];
        $this->km_content_words->DbValue = $row['km_content_words'];
        $this->km_function_words->DbValue = $row['km_function_words'];
        $this->km_syllables->DbValue = $row['km_syllables'];
        $this->km_morphological_endings->DbValue = $row['km_morphological_endings'];
        $this->km_phrases->DbValue = $row['km_phrases'];
        $this->km_sentences->DbValue = $row['km_sentences'];
        $this->km_punctuation->DbValue = $row['km_punctuation'];
        $this->km_nomenclature_size->DbValue = $row['km_nomenclature_size'];
        $this->km_sections->DbValue = $row['km_sections'];
        $this->km_headings->DbValue = $row['km_headings'];
        $this->km_plaintext_arrangement->DbValue = $row['km_plaintext_arrangement'];
        $this->km_ciphertext_arrangement->DbValue = $row['km_ciphertext_arrangement'];
        $this->km_memorability->DbValue = $row['km_memorability'];
        $this->km_symbol_set->DbValue = $row['km_symbol_set'];
        $this->km_diacritics->DbValue = $row['km_diacritics'];
        $this->km_code_length->DbValue = $row['km_code_length'];
        $this->km_code_type->DbValue = $row['km_code_type'];
        $this->km_metaphors->DbValue = $row['km_metaphors'];
        $this->km_material_properties->DbValue = $row['km_material_properties'];
        $this->km_instructions->DbValue = $row['km_instructions'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`id` = @id@";
    }

    // Get Key
    public function getKey($current = false, $keySeparator = null)
    {
        $keys = [];
        $val = $current ? $this->id->CurrentValue : $this->id->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        return implode($keySeparator, $keys);
    }

    // Set Key
    public function setKey($key, $current = false, $keySeparator = null)
    {
        $keySeparator ??= Config("COMPOSITE_KEY_SEPARATOR");
        $this->OldKey = strval($key);
        $keys = explode($keySeparator, $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->id->CurrentValue = $keys[0];
            } else {
                $this->id->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null, $current = false)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('id', $row) ? $row['id'] : null;
        } else {
            $val = !EmptyValue($this->id->OldValue) && !$current ? $this->id->OldValue : $this->id->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@id@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("RecordsList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "RecordsView") {
            return $Language->phrase("View");
        } elseif ($pageName == "RecordsEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "RecordsAdd") {
            return $Language->phrase("Add");
        }
        return "";
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "RecordsView";
            case Config("API_ADD_ACTION"):
                return "RecordsAdd";
            case Config("API_EDIT_ACTION"):
                return "RecordsEdit";
            case Config("API_DELETE_ACTION"):
                return "RecordsDelete";
            case Config("API_LIST_ACTION"):
                return "RecordsList";
            default:
                return "";
        }
    }

    // Current URL
    public function getCurrentUrl($parm = "")
    {
        $url = CurrentPageUrl(false);
        if ($parm != "") {
            $url = $this->keyUrl($url, $parm);
        } else {
            $url = $this->keyUrl($url, Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // List URL
    public function getListUrl()
    {
        return "RecordsList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("RecordsView", $parm);
        } else {
            $url = $this->keyUrl("RecordsView", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "RecordsAdd?" . $parm;
        } else {
            $url = "RecordsAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("RecordsEdit", $parm);
        } else {
            $url = $this->keyUrl("RecordsEdit", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl("RecordsList", "action=edit");
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("RecordsAdd", $parm);
        } else {
            $url = $this->keyUrl("RecordsAdd", Config("TABLE_SHOW_DETAIL") . "=");
        }
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl("RecordsList", "action=copy");
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        if ($this->UseAjaxActions && ConvertToBool(Param("infinitescroll")) && CurrentPageID() == "list") {
            return $this->keyUrl(GetApiUrl(Config("API_DELETE_ACTION") . "/" . $this->TableVar));
        } else {
            return $this->keyUrl("RecordsDelete");
        }
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"id\":" . JsonEncode($this->id->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->id->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->id->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language, $Page;
        $sortUrl = "";
        $attrs = "";
        if ($fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-ew-action="sort" data-ajax="' . ($this->UseAjaxActions ? "true" : "false") . '" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
            if ($this->ContextClass) { // Add context
                $attrs .= ' data-context="' . HtmlEncode($this->ContextClass) . '"';
            }
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if (!$this->isExport() && $fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") . '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        global $DashboardReport;
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = "order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort();
            if ($DashboardReport) {
                $urlParm .= "&amp;dashboard=true";
            }
            return $this->addMasterUrl($this->CurrentPageName . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            $isApi = IsApi();
            $keyValues = $isApi
                ? (Route(0) == "export"
                    ? array_map(fn ($i) => Route($i + 3), range(0, 0))  // Export API
                    : array_map(fn ($i) => Route($i + 2), range(0, 0))) // Other API
                : []; // Non-API
            if (($keyValue = Param("id") ?? Route("id")) !== null) {
                $arKeys[] = $keyValue;
            } elseif ($isApi && (($keyValue = Key(0) ?? $keyValues[0] ?? null) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from records
    public function getFilterFromRecords($rows)
    {
        $keyFilter = "";
        foreach ($rows as $row) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            $keyFilter .= "(" . $this->getRecordFilter($row) . ")";
        }
        return $keyFilter;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->id->CurrentValue = $key;
            } else {
                $this->id->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter / sort
    public function loadRs($filter, $sort = "")
    {
        $sql = $this->getSql($filter, $sort); // Set up filter (WHERE Clause) / sort (ORDER BY Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
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

    // Render list content
    public function renderListContent($filter)
    {
        global $Response;
        $listPage = "RecordsList";
        $listClass = PROJECT_NAMESPACE . $listPage;
        $page = new $listClass();
        $page->loadRecordsetFromFilter($filter);
        $view = Container("view");
        $template = $listPage . ".php"; // View
        $GLOBALS["Title"] ??= $page->Title; // Title
        try {
            $Response = $view->render($Response, $template, $GLOBALS);
        } finally {
            $page->terminate(); // Terminate page and clean up
        }
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

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

        // id
        $this->id->ViewValue = $this->id->CurrentValue;

        // name
        $this->name->ViewValue = $this->name->CurrentValue;

        // owner_id
        $this->owner_id->ViewValue = $this->owner_id->CurrentValue;
        $this->owner_id->ViewValue = FormatNumber($this->owner_id->ViewValue, $this->owner_id->formatPattern());

        // owner
        $this->owner->ViewValue = $this->owner->CurrentValue;

        // record_group_id
        $curVal = strval($this->record_group_id->CurrentValue);
        if ($curVal != "") {
            $this->record_group_id->ViewValue = $this->record_group_id->lookupCacheOption($curVal);
            if ($this->record_group_id->ViewValue === null) { // Lookup from database
                $filterWrk = SearchFilter("`id`", "=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->record_group_id->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->record_group_id->Lookup->renderViewRow($rswrk[0]);
                    $this->record_group_id->ViewValue = $this->record_group_id->displayValue($arwrk);
                } else {
                    $this->record_group_id->ViewValue = FormatNumber($this->record_group_id->CurrentValue, $this->record_group_id->formatPattern());
                }
            }
        } else {
            $this->record_group_id->ViewValue = null;
        }
        $this->record_group_id->CssClass = "fw-bold";

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

        // name
        $this->name->HrefValue = "";
        $this->name->TooltipValue = "";

        // owner_id
        $this->owner_id->HrefValue = "";
        $this->owner_id->TooltipValue = "";

        // owner
        $this->owner->HrefValue = "";
        $this->owner->TooltipValue = "";

        // record_group_id
        $this->record_group_id->HrefValue = "";
        $this->record_group_id->TooltipValue = "";

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

        // additional_information
        $this->additional_information->HrefValue = "";
        $this->additional_information->TooltipValue = "";

        // creator_id
        $this->creator_id->HrefValue = "";
        $this->creator_id->TooltipValue = "";

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

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // id
        $this->id->setupEditAttributes();
        $this->id->EditValue = $this->id->CurrentValue;

        // name
        $this->name->setupEditAttributes();
        if (!$this->name->Raw) {
            $this->name->CurrentValue = HtmlDecode($this->name->CurrentValue);
        }
        $this->name->EditValue = $this->name->CurrentValue;
        $this->name->PlaceHolder = RemoveHtml($this->name->caption());

        // owner_id
        $this->owner_id->setupEditAttributes();
        $this->owner_id->CurrentValue = FormatNumber($this->owner_id->CurrentValue, $this->owner_id->formatPattern());
        if (strval($this->owner_id->EditValue) != "" && is_numeric($this->owner_id->EditValue)) {
            $this->owner_id->EditValue = FormatNumber($this->owner_id->EditValue, null);
        }

        // owner
        $this->owner->setupEditAttributes();
        if (!$this->owner->Raw) {
            $this->owner->CurrentValue = HtmlDecode($this->owner->CurrentValue);
        }
        $this->owner->EditValue = $this->owner->CurrentValue;
        $this->owner->PlaceHolder = RemoveHtml($this->owner->caption());

        // record_group_id
        $this->record_group_id->setupEditAttributes();
        $this->record_group_id->PlaceHolder = RemoveHtml($this->record_group_id->caption());

        // c_holder
        $this->c_holder->setupEditAttributes();
        $this->c_holder->EditValue = $this->c_holder->CurrentValue;
        $this->c_holder->PlaceHolder = RemoveHtml($this->c_holder->caption());

        // c_cates
        $this->c_cates->setupEditAttributes();
        if (!$this->c_cates->Raw) {
            $this->c_cates->CurrentValue = HtmlDecode($this->c_cates->CurrentValue);
        }
        $this->c_cates->EditValue = $this->c_cates->CurrentValue;
        $this->c_cates->PlaceHolder = RemoveHtml($this->c_cates->caption());

        // c_author
        $this->c_author->setupEditAttributes();
        $this->c_author->EditValue = $this->c_author->CurrentValue;
        $this->c_author->PlaceHolder = RemoveHtml($this->c_author->caption());

        // c_lang
        $this->c_lang->setupEditAttributes();
        if (!$this->c_lang->Raw) {
            $this->c_lang->CurrentValue = HtmlDecode($this->c_lang->CurrentValue);
        }
        $this->c_lang->EditValue = $this->c_lang->CurrentValue;
        $this->c_lang->PlaceHolder = RemoveHtml($this->c_lang->caption());

        // current_country
        $this->current_country->setupEditAttributes();
        if (!$this->current_country->Raw) {
            $this->current_country->CurrentValue = HtmlDecode($this->current_country->CurrentValue);
        }
        $this->current_country->EditValue = $this->current_country->CurrentValue;
        $this->current_country->PlaceHolder = RemoveHtml($this->current_country->caption());

        // current_city
        $this->current_city->setupEditAttributes();
        if (!$this->current_city->Raw) {
            $this->current_city->CurrentValue = HtmlDecode($this->current_city->CurrentValue);
        }
        $this->current_city->EditValue = $this->current_city->CurrentValue;
        $this->current_city->PlaceHolder = RemoveHtml($this->current_city->caption());

        // current_holder
        $this->current_holder->setupEditAttributes();
        if (!$this->current_holder->Raw) {
            $this->current_holder->CurrentValue = HtmlDecode($this->current_holder->CurrentValue);
        }
        $this->current_holder->EditValue = $this->current_holder->CurrentValue;
        $this->current_holder->PlaceHolder = RemoveHtml($this->current_holder->caption());

        // author
        $this->author->setupEditAttributes();
        if (!$this->author->Raw) {
            $this->author->CurrentValue = HtmlDecode($this->author->CurrentValue);
        }
        $this->author->EditValue = $this->author->CurrentValue;
        $this->author->PlaceHolder = RemoveHtml($this->author->caption());

        // sender
        $this->sender->setupEditAttributes();
        if (!$this->sender->Raw) {
            $this->sender->CurrentValue = HtmlDecode($this->sender->CurrentValue);
        }
        $this->sender->EditValue = $this->sender->CurrentValue;
        $this->sender->PlaceHolder = RemoveHtml($this->sender->caption());

        // receiver
        $this->receiver->setupEditAttributes();
        if (!$this->receiver->Raw) {
            $this->receiver->CurrentValue = HtmlDecode($this->receiver->CurrentValue);
        }
        $this->receiver->EditValue = $this->receiver->CurrentValue;
        $this->receiver->PlaceHolder = RemoveHtml($this->receiver->caption());

        // origin_region
        $this->origin_region->setupEditAttributes();
        if (!$this->origin_region->Raw) {
            $this->origin_region->CurrentValue = HtmlDecode($this->origin_region->CurrentValue);
        }
        $this->origin_region->EditValue = $this->origin_region->CurrentValue;
        $this->origin_region->PlaceHolder = RemoveHtml($this->origin_region->caption());

        // origin_city
        $this->origin_city->setupEditAttributes();
        if (!$this->origin_city->Raw) {
            $this->origin_city->CurrentValue = HtmlDecode($this->origin_city->CurrentValue);
        }
        $this->origin_city->EditValue = $this->origin_city->CurrentValue;
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
        $this->cipher_type_other->EditValue = $this->cipher_type_other->CurrentValue;
        $this->cipher_type_other->PlaceHolder = RemoveHtml($this->cipher_type_other->caption());

        // symbol_set_other
        $this->symbol_set_other->setupEditAttributes();
        if (!$this->symbol_set_other->Raw) {
            $this->symbol_set_other->CurrentValue = HtmlDecode($this->symbol_set_other->CurrentValue);
        }
        $this->symbol_set_other->EditValue = $this->symbol_set_other->CurrentValue;
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
        $this->cleartext_lang->EditValue = $this->cleartext_lang->CurrentValue;
        $this->cleartext_lang->PlaceHolder = RemoveHtml($this->cleartext_lang->caption());

        // plaintext_lang
        $this->plaintext_lang->setupEditAttributes();
        if (!$this->plaintext_lang->Raw) {
            $this->plaintext_lang->CurrentValue = HtmlDecode($this->plaintext_lang->CurrentValue);
        }
        $this->plaintext_lang->EditValue = $this->plaintext_lang->CurrentValue;
        $this->plaintext_lang->PlaceHolder = RemoveHtml($this->plaintext_lang->caption());

        // private_ciphertext
        $this->private_ciphertext->EditValue = $this->private_ciphertext->options(false);
        $this->private_ciphertext->PlaceHolder = RemoveHtml($this->private_ciphertext->caption());

        // document_types
        $this->document_types->EditValue = $this->document_types->options(false);
        $this->document_types->PlaceHolder = RemoveHtml($this->document_types->caption());

        // paper
        $this->paper->setupEditAttributes();
        if (!$this->paper->Raw) {
            $this->paper->CurrentValue = HtmlDecode($this->paper->CurrentValue);
        }
        $this->paper->EditValue = $this->paper->CurrentValue;
        $this->paper->PlaceHolder = RemoveHtml($this->paper->caption());

        // additional_information
        $this->additional_information->setupEditAttributes();
        $this->additional_information->EditValue = $this->additional_information->CurrentValue;
        $this->additional_information->PlaceHolder = RemoveHtml($this->additional_information->caption());

        // creator_id
        $this->creator_id->setupEditAttributes();
        $this->creator_id->CurrentValue = FormatNumber($this->creator_id->CurrentValue, $this->creator_id->formatPattern());
        if (strval($this->creator_id->EditValue) != "" && is_numeric($this->creator_id->EditValue)) {
            $this->creator_id->EditValue = FormatNumber($this->creator_id->EditValue, null);
        }

        // access_mode
        $this->access_mode->EditValue = $this->access_mode->options(false);
        $this->access_mode->PlaceHolder = RemoveHtml($this->access_mode->caption());

        // creation_date
        $this->creation_date->setupEditAttributes();
        $this->creation_date->EditValue = FormatDateTime($this->creation_date->CurrentValue, $this->creation_date->formatPattern());
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

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->name);
                    $doc->exportCaption($this->owner);
                    $doc->exportCaption($this->c_holder);
                    $doc->exportCaption($this->c_cates);
                    $doc->exportCaption($this->c_author);
                    $doc->exportCaption($this->c_lang);
                    $doc->exportCaption($this->current_country);
                    $doc->exportCaption($this->current_city);
                    $doc->exportCaption($this->current_holder);
                    $doc->exportCaption($this->author);
                    $doc->exportCaption($this->sender);
                    $doc->exportCaption($this->receiver);
                    $doc->exportCaption($this->origin_region);
                    $doc->exportCaption($this->origin_city);
                    $doc->exportCaption($this->start_year);
                    $doc->exportCaption($this->start_month);
                    $doc->exportCaption($this->start_day);
                    $doc->exportCaption($this->end_year);
                    $doc->exportCaption($this->end_month);
                    $doc->exportCaption($this->end_day);
                    $doc->exportCaption($this->record_type);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->symbol_sets);
                    $doc->exportCaption($this->cipher_types);
                    $doc->exportCaption($this->cipher_type_other);
                    $doc->exportCaption($this->symbol_set_other);
                    $doc->exportCaption($this->number_of_pages);
                    $doc->exportCaption($this->inline_cleartext);
                    $doc->exportCaption($this->inline_plaintext);
                    $doc->exportCaption($this->cleartext_lang);
                    $doc->exportCaption($this->plaintext_lang);
                    $doc->exportCaption($this->private_ciphertext);
                    $doc->exportCaption($this->document_types);
                    $doc->exportCaption($this->paper);
                    $doc->exportCaption($this->access_mode);
                    $doc->exportCaption($this->creation_date);
                    $doc->exportCaption($this->km_encoded_plaintext_type);
                    $doc->exportCaption($this->km_numbers);
                    $doc->exportCaption($this->km_content_words);
                    $doc->exportCaption($this->km_function_words);
                    $doc->exportCaption($this->km_syllables);
                    $doc->exportCaption($this->km_morphological_endings);
                    $doc->exportCaption($this->km_phrases);
                    $doc->exportCaption($this->km_sentences);
                    $doc->exportCaption($this->km_punctuation);
                    $doc->exportCaption($this->km_nomenclature_size);
                    $doc->exportCaption($this->km_sections);
                    $doc->exportCaption($this->km_headings);
                    $doc->exportCaption($this->km_plaintext_arrangement);
                    $doc->exportCaption($this->km_ciphertext_arrangement);
                    $doc->exportCaption($this->km_memorability);
                    $doc->exportCaption($this->km_symbol_set);
                    $doc->exportCaption($this->km_diacritics);
                    $doc->exportCaption($this->km_code_length);
                    $doc->exportCaption($this->km_code_type);
                    $doc->exportCaption($this->km_metaphors);
                    $doc->exportCaption($this->km_material_properties);
                    $doc->exportCaption($this->km_instructions);
                } else {
                    $doc->exportCaption($this->id);
                    $doc->exportCaption($this->name);
                    $doc->exportCaption($this->owner_id);
                    $doc->exportCaption($this->owner);
                    $doc->exportCaption($this->c_cates);
                    $doc->exportCaption($this->c_lang);
                    $doc->exportCaption($this->current_country);
                    $doc->exportCaption($this->current_city);
                    $doc->exportCaption($this->current_holder);
                    $doc->exportCaption($this->author);
                    $doc->exportCaption($this->sender);
                    $doc->exportCaption($this->receiver);
                    $doc->exportCaption($this->origin_region);
                    $doc->exportCaption($this->origin_city);
                    $doc->exportCaption($this->start_year);
                    $doc->exportCaption($this->start_month);
                    $doc->exportCaption($this->start_day);
                    $doc->exportCaption($this->end_year);
                    $doc->exportCaption($this->end_month);
                    $doc->exportCaption($this->end_day);
                    $doc->exportCaption($this->record_type);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->symbol_sets);
                    $doc->exportCaption($this->cipher_types);
                    $doc->exportCaption($this->cipher_type_other);
                    $doc->exportCaption($this->symbol_set_other);
                    $doc->exportCaption($this->number_of_pages);
                    $doc->exportCaption($this->inline_cleartext);
                    $doc->exportCaption($this->inline_plaintext);
                    $doc->exportCaption($this->cleartext_lang);
                    $doc->exportCaption($this->plaintext_lang);
                    $doc->exportCaption($this->private_ciphertext);
                    $doc->exportCaption($this->paper);
                    $doc->exportCaption($this->creator_id);
                    $doc->exportCaption($this->access_mode);
                    $doc->exportCaption($this->creation_date);
                    $doc->exportCaption($this->km_encoded_plaintext_type);
                    $doc->exportCaption($this->km_numbers);
                    $doc->exportCaption($this->km_content_words);
                    $doc->exportCaption($this->km_function_words);
                    $doc->exportCaption($this->km_syllables);
                    $doc->exportCaption($this->km_morphological_endings);
                    $doc->exportCaption($this->km_phrases);
                    $doc->exportCaption($this->km_sentences);
                    $doc->exportCaption($this->km_punctuation);
                    $doc->exportCaption($this->km_nomenclature_size);
                    $doc->exportCaption($this->km_sections);
                    $doc->exportCaption($this->km_headings);
                    $doc->exportCaption($this->km_plaintext_arrangement);
                    $doc->exportCaption($this->km_ciphertext_arrangement);
                    $doc->exportCaption($this->km_memorability);
                    $doc->exportCaption($this->km_symbol_set);
                    $doc->exportCaption($this->km_diacritics);
                    $doc->exportCaption($this->km_code_length);
                    $doc->exportCaption($this->km_code_type);
                    $doc->exportCaption($this->km_metaphors);
                    $doc->exportCaption($this->km_material_properties);
                    $doc->exportCaption($this->km_instructions);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->id);
                        $doc->exportField($this->name);
                        $doc->exportField($this->owner);
                        $doc->exportField($this->c_holder);
                        $doc->exportField($this->c_cates);
                        $doc->exportField($this->c_author);
                        $doc->exportField($this->c_lang);
                        $doc->exportField($this->current_country);
                        $doc->exportField($this->current_city);
                        $doc->exportField($this->current_holder);
                        $doc->exportField($this->author);
                        $doc->exportField($this->sender);
                        $doc->exportField($this->receiver);
                        $doc->exportField($this->origin_region);
                        $doc->exportField($this->origin_city);
                        $doc->exportField($this->start_year);
                        $doc->exportField($this->start_month);
                        $doc->exportField($this->start_day);
                        $doc->exportField($this->end_year);
                        $doc->exportField($this->end_month);
                        $doc->exportField($this->end_day);
                        $doc->exportField($this->record_type);
                        $doc->exportField($this->status);
                        $doc->exportField($this->symbol_sets);
                        $doc->exportField($this->cipher_types);
                        $doc->exportField($this->cipher_type_other);
                        $doc->exportField($this->symbol_set_other);
                        $doc->exportField($this->number_of_pages);
                        $doc->exportField($this->inline_cleartext);
                        $doc->exportField($this->inline_plaintext);
                        $doc->exportField($this->cleartext_lang);
                        $doc->exportField($this->plaintext_lang);
                        $doc->exportField($this->private_ciphertext);
                        $doc->exportField($this->document_types);
                        $doc->exportField($this->paper);
                        $doc->exportField($this->access_mode);
                        $doc->exportField($this->creation_date);
                        $doc->exportField($this->km_encoded_plaintext_type);
                        $doc->exportField($this->km_numbers);
                        $doc->exportField($this->km_content_words);
                        $doc->exportField($this->km_function_words);
                        $doc->exportField($this->km_syllables);
                        $doc->exportField($this->km_morphological_endings);
                        $doc->exportField($this->km_phrases);
                        $doc->exportField($this->km_sentences);
                        $doc->exportField($this->km_punctuation);
                        $doc->exportField($this->km_nomenclature_size);
                        $doc->exportField($this->km_sections);
                        $doc->exportField($this->km_headings);
                        $doc->exportField($this->km_plaintext_arrangement);
                        $doc->exportField($this->km_ciphertext_arrangement);
                        $doc->exportField($this->km_memorability);
                        $doc->exportField($this->km_symbol_set);
                        $doc->exportField($this->km_diacritics);
                        $doc->exportField($this->km_code_length);
                        $doc->exportField($this->km_code_type);
                        $doc->exportField($this->km_metaphors);
                        $doc->exportField($this->km_material_properties);
                        $doc->exportField($this->km_instructions);
                    } else {
                        $doc->exportField($this->id);
                        $doc->exportField($this->name);
                        $doc->exportField($this->owner_id);
                        $doc->exportField($this->owner);
                        $doc->exportField($this->c_cates);
                        $doc->exportField($this->c_lang);
                        $doc->exportField($this->current_country);
                        $doc->exportField($this->current_city);
                        $doc->exportField($this->current_holder);
                        $doc->exportField($this->author);
                        $doc->exportField($this->sender);
                        $doc->exportField($this->receiver);
                        $doc->exportField($this->origin_region);
                        $doc->exportField($this->origin_city);
                        $doc->exportField($this->start_year);
                        $doc->exportField($this->start_month);
                        $doc->exportField($this->start_day);
                        $doc->exportField($this->end_year);
                        $doc->exportField($this->end_month);
                        $doc->exportField($this->end_day);
                        $doc->exportField($this->record_type);
                        $doc->exportField($this->status);
                        $doc->exportField($this->symbol_sets);
                        $doc->exportField($this->cipher_types);
                        $doc->exportField($this->cipher_type_other);
                        $doc->exportField($this->symbol_set_other);
                        $doc->exportField($this->number_of_pages);
                        $doc->exportField($this->inline_cleartext);
                        $doc->exportField($this->inline_plaintext);
                        $doc->exportField($this->cleartext_lang);
                        $doc->exportField($this->plaintext_lang);
                        $doc->exportField($this->private_ciphertext);
                        $doc->exportField($this->paper);
                        $doc->exportField($this->creator_id);
                        $doc->exportField($this->access_mode);
                        $doc->exportField($this->creation_date);
                        $doc->exportField($this->km_encoded_plaintext_type);
                        $doc->exportField($this->km_numbers);
                        $doc->exportField($this->km_content_words);
                        $doc->exportField($this->km_function_words);
                        $doc->exportField($this->km_syllables);
                        $doc->exportField($this->km_morphological_endings);
                        $doc->exportField($this->km_phrases);
                        $doc->exportField($this->km_sentences);
                        $doc->exportField($this->km_punctuation);
                        $doc->exportField($this->km_nomenclature_size);
                        $doc->exportField($this->km_sections);
                        $doc->exportField($this->km_headings);
                        $doc->exportField($this->km_plaintext_arrangement);
                        $doc->exportField($this->km_ciphertext_arrangement);
                        $doc->exportField($this->km_memorability);
                        $doc->exportField($this->km_symbol_set);
                        $doc->exportField($this->km_diacritics);
                        $doc->exportField($this->km_code_length);
                        $doc->exportField($this->km_code_type);
                        $doc->exportField($this->km_metaphors);
                        $doc->exportField($this->km_material_properties);
                        $doc->exportField($this->km_instructions);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($doc, $row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        global $DownloadFileName;

        // No binary fields
        return false;
    }

    // Table level events

    // Table Load event
    public function tableLoad()
    {
        // Enter your code here
    }

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
