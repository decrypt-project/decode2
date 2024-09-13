<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$RecordviewList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { recordview: currentTable } });
var currentPageID = ew.PAGE_ID = "list";
var currentForm;
var <?= $Page->FormName ?>;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("<?= $Page->FormName ?>")
        .setPageId("list")
        .setSubmitWithFetch(<?= $Page->UseAjaxActions ? "true" : "false" ?>)
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
window.Tabulator || loadjs([
    ew.PATH_BASE + "js/tabulator.min.js?v=19.13.27",
    ew.PATH_BASE + "css/<?= CssFile("tabulator_bootstrap5.css", false) ?>?v=19.13.27"
], "import");
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="frecordviewsrch" id="frecordviewsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="frecordviewsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { recordview: currentTable } });
var currentForm;
var frecordviewsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("frecordviewsrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Dynamic selection lists
        .setLists({
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)
        .build();
    window[form.id] = form;
    currentSearchForm = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="frecordviewsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="frecordviewsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="frecordviewsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="frecordviewsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="list<?= ($Page->TotalRecords == 0 && !$Page->isAdd()) ? " ew-no-record" : "" ?>">
<div id="ew-list">
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
</div>
<?php } ?>
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="recordview">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div id="gmp_recordview" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_recordviewlist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->id->Visible) { // id ?>
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_recordview_id" class="recordview_id"><?= $Page->renderFieldHeader($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
        <th data-name="name" class="<?= $Page->name->headerCellClass() ?>"><div id="elh_recordview_name" class="recordview_name"><?= $Page->renderFieldHeader($Page->name) ?></div></th>
<?php } ?>
<?php if ($Page->owner->Visible) { // owner ?>
        <th data-name="owner" class="<?= $Page->owner->headerCellClass() ?>"><div id="elh_recordview_owner" class="recordview_owner"><?= $Page->renderFieldHeader($Page->owner) ?></div></th>
<?php } ?>
<?php if ($Page->start_year->Visible) { // start_year ?>
        <th data-name="start_year" class="<?= $Page->start_year->headerCellClass() ?>"><div id="elh_recordview_start_year" class="recordview_start_year"><?= $Page->renderFieldHeader($Page->start_year) ?></div></th>
<?php } ?>
<?php if ($Page->start_month->Visible) { // start_month ?>
        <th data-name="start_month" class="<?= $Page->start_month->headerCellClass() ?>"><div id="elh_recordview_start_month" class="recordview_start_month"><?= $Page->renderFieldHeader($Page->start_month) ?></div></th>
<?php } ?>
<?php if ($Page->start_day->Visible) { // start_day ?>
        <th data-name="start_day" class="<?= $Page->start_day->headerCellClass() ?>"><div id="elh_recordview_start_day" class="recordview_start_day"><?= $Page->renderFieldHeader($Page->start_day) ?></div></th>
<?php } ?>
<?php if ($Page->end_year->Visible) { // end_year ?>
        <th data-name="end_year" class="<?= $Page->end_year->headerCellClass() ?>"><div id="elh_recordview_end_year" class="recordview_end_year"><?= $Page->renderFieldHeader($Page->end_year) ?></div></th>
<?php } ?>
<?php if ($Page->end_month->Visible) { // end_month ?>
        <th data-name="end_month" class="<?= $Page->end_month->headerCellClass() ?>"><div id="elh_recordview_end_month" class="recordview_end_month"><?= $Page->renderFieldHeader($Page->end_month) ?></div></th>
<?php } ?>
<?php if ($Page->end_day->Visible) { // end_day ?>
        <th data-name="end_day" class="<?= $Page->end_day->headerCellClass() ?>"><div id="elh_recordview_end_day" class="recordview_end_day"><?= $Page->renderFieldHeader($Page->end_day) ?></div></th>
<?php } ?>
<?php if ($Page->creator_id->Visible) { // creator_id ?>
        <th data-name="creator_id" class="<?= $Page->creator_id->headerCellClass() ?>"><div id="elh_recordview_creator_id" class="recordview_creator_id"><?= $Page->renderFieldHeader($Page->creator_id) ?></div></th>
<?php } ?>
<?php if ($Page->inline_cleartext->Visible) { // inline_cleartext ?>
        <th data-name="inline_cleartext" class="<?= $Page->inline_cleartext->headerCellClass() ?>"><div id="elh_recordview_inline_cleartext" class="recordview_inline_cleartext"><?= $Page->renderFieldHeader($Page->inline_cleartext) ?></div></th>
<?php } ?>
<?php if ($Page->inline_plaintext->Visible) { // inline_plaintext ?>
        <th data-name="inline_plaintext" class="<?= $Page->inline_plaintext->headerCellClass() ?>"><div id="elh_recordview_inline_plaintext" class="recordview_inline_plaintext"><?= $Page->renderFieldHeader($Page->inline_plaintext) ?></div></th>
<?php } ?>
<?php if ($Page->current_country->Visible) { // current_country ?>
        <th data-name="current_country" class="<?= $Page->current_country->headerCellClass() ?>"><div id="elh_recordview_current_country" class="recordview_current_country"><?= $Page->renderFieldHeader($Page->current_country) ?></div></th>
<?php } ?>
<?php if ($Page->current_city->Visible) { // current_city ?>
        <th data-name="current_city" class="<?= $Page->current_city->headerCellClass() ?>"><div id="elh_recordview_current_city" class="recordview_current_city"><?= $Page->renderFieldHeader($Page->current_city) ?></div></th>
<?php } ?>
<?php if ($Page->current_holder->Visible) { // current_holder ?>
        <th data-name="current_holder" class="<?= $Page->current_holder->headerCellClass() ?>"><div id="elh_recordview_current_holder" class="recordview_current_holder"><?= $Page->renderFieldHeader($Page->current_holder) ?></div></th>
<?php } ?>
<?php if ($Page->number_of_pages->Visible) { // number_of_pages ?>
        <th data-name="number_of_pages" class="<?= $Page->number_of_pages->headerCellClass() ?>"><div id="elh_recordview_number_of_pages" class="recordview_number_of_pages"><?= $Page->renderFieldHeader($Page->number_of_pages) ?></div></th>
<?php } ?>
<?php if ($Page->cleartext_lang->Visible) { // cleartext_lang ?>
        <th data-name="cleartext_lang" class="<?= $Page->cleartext_lang->headerCellClass() ?>"><div id="elh_recordview_cleartext_lang" class="recordview_cleartext_lang"><?= $Page->renderFieldHeader($Page->cleartext_lang) ?></div></th>
<?php } ?>
<?php if ($Page->plaintext_lang->Visible) { // plaintext_lang ?>
        <th data-name="plaintext_lang" class="<?= $Page->plaintext_lang->headerCellClass() ?>"><div id="elh_recordview_plaintext_lang" class="recordview_plaintext_lang"><?= $Page->renderFieldHeader($Page->plaintext_lang) ?></div></th>
<?php } ?>
<?php if ($Page->origin_region->Visible) { // origin_region ?>
        <th data-name="origin_region" class="<?= $Page->origin_region->headerCellClass() ?>"><div id="elh_recordview_origin_region" class="recordview_origin_region"><?= $Page->renderFieldHeader($Page->origin_region) ?></div></th>
<?php } ?>
<?php if ($Page->origin_city->Visible) { // origin_city ?>
        <th data-name="origin_city" class="<?= $Page->origin_city->headerCellClass() ?>"><div id="elh_recordview_origin_city" class="recordview_origin_city"><?= $Page->renderFieldHeader($Page->origin_city) ?></div></th>
<?php } ?>
<?php if ($Page->paper->Visible) { // paper ?>
        <th data-name="paper" class="<?= $Page->paper->headerCellClass() ?>"><div id="elh_recordview_paper" class="recordview_paper"><?= $Page->renderFieldHeader($Page->paper) ?></div></th>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
        <th data-name="creation_date" class="<?= $Page->creation_date->headerCellClass() ?>"><div id="elh_recordview_creation_date" class="recordview_creation_date"><?= $Page->renderFieldHeader($Page->creation_date) ?></div></th>
<?php } ?>
<?php if ($Page->private_ciphertext->Visible) { // private_ciphertext ?>
        <th data-name="private_ciphertext" class="<?= $Page->private_ciphertext->headerCellClass() ?>"><div id="elh_recordview_private_ciphertext" class="recordview_private_ciphertext"><?= $Page->renderFieldHeader($Page->private_ciphertext) ?></div></th>
<?php } ?>
<?php if ($Page->cipher_types->Visible) { // cipher_types ?>
        <th data-name="cipher_types" class="<?= $Page->cipher_types->headerCellClass() ?>"><div id="elh_recordview_cipher_types" class="recordview_cipher_types"><?= $Page->renderFieldHeader($Page->cipher_types) ?></div></th>
<?php } ?>
<?php if ($Page->symbol_sets->Visible) { // symbol_sets ?>
        <th data-name="symbol_sets" class="<?= $Page->symbol_sets->headerCellClass() ?>"><div id="elh_recordview_symbol_sets" class="recordview_symbol_sets"><?= $Page->renderFieldHeader($Page->symbol_sets) ?></div></th>
<?php } ?>
<?php if ($Page->cipher_type_other->Visible) { // cipher_type_other ?>
        <th data-name="cipher_type_other" class="<?= $Page->cipher_type_other->headerCellClass() ?>"><div id="elh_recordview_cipher_type_other" class="recordview_cipher_type_other"><?= $Page->renderFieldHeader($Page->cipher_type_other) ?></div></th>
<?php } ?>
<?php if ($Page->symbol_set_other->Visible) { // symbol_set_other ?>
        <th data-name="symbol_set_other" class="<?= $Page->symbol_set_other->headerCellClass() ?>"><div id="elh_recordview_symbol_set_other" class="recordview_symbol_set_other"><?= $Page->renderFieldHeader($Page->symbol_set_other) ?></div></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th data-name="status" class="<?= $Page->status->headerCellClass() ?>"><div id="elh_recordview_status" class="recordview_status"><?= $Page->renderFieldHeader($Page->status) ?></div></th>
<?php } ?>
<?php if ($Page->record_type->Visible) { // record_type ?>
        <th data-name="record_type" class="<?= $Page->record_type->headerCellClass() ?>"><div id="elh_recordview_record_type" class="recordview_record_type"><?= $Page->renderFieldHeader($Page->record_type) ?></div></th>
<?php } ?>
<?php if ($Page->access_mode->Visible) { // access_mode ?>
        <th data-name="access_mode" class="<?= $Page->access_mode->headerCellClass() ?>"><div id="elh_recordview_access_mode" class="recordview_access_mode"><?= $Page->renderFieldHeader($Page->access_mode) ?></div></th>
<?php } ?>
<?php if ($Page->link->Visible) { // link ?>
        <th data-name="link" class="<?= $Page->link->headerCellClass() ?>"><div id="elh_recordview_link" class="recordview_link"><?= $Page->renderFieldHeader($Page->link) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Page->getPageNumber() ?>">
<?php
$Page->setupGrid();
while ($Page->RecordCount < $Page->StopRecord || $Page->RowIndex === '$rowindex$') {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->setupRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->id->Visible) { // id ?>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_id" class="el_recordview_id">
<span<?= $Page->id->viewAttributes() ?>>
<?php if (!EmptyString($Page->id->getViewValue()) && $Page->id->linkAttributes() != "") { ?>
<a<?= $Page->id->linkAttributes() ?>><?= $Page->id->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->id->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->name->Visible) { // name ?>
        <td data-name="name"<?= $Page->name->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_name" class="el_recordview_name">
<span<?= $Page->name->viewAttributes() ?>>
<?= $Page->name->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->owner->Visible) { // owner ?>
        <td data-name="owner"<?= $Page->owner->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_owner" class="el_recordview_owner">
<span<?= $Page->owner->viewAttributes() ?>>
<?= $Page->owner->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->start_year->Visible) { // start_year ?>
        <td data-name="start_year"<?= $Page->start_year->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_start_year" class="el_recordview_start_year">
<span<?= $Page->start_year->viewAttributes() ?>>
<?= $Page->start_year->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->start_month->Visible) { // start_month ?>
        <td data-name="start_month"<?= $Page->start_month->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_start_month" class="el_recordview_start_month">
<span<?= $Page->start_month->viewAttributes() ?>>
<?= $Page->start_month->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->start_day->Visible) { // start_day ?>
        <td data-name="start_day"<?= $Page->start_day->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_start_day" class="el_recordview_start_day">
<span<?= $Page->start_day->viewAttributes() ?>>
<?= $Page->start_day->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->end_year->Visible) { // end_year ?>
        <td data-name="end_year"<?= $Page->end_year->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_end_year" class="el_recordview_end_year">
<span<?= $Page->end_year->viewAttributes() ?>>
<?= $Page->end_year->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->end_month->Visible) { // end_month ?>
        <td data-name="end_month"<?= $Page->end_month->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_end_month" class="el_recordview_end_month">
<span<?= $Page->end_month->viewAttributes() ?>>
<?= $Page->end_month->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->end_day->Visible) { // end_day ?>
        <td data-name="end_day"<?= $Page->end_day->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_end_day" class="el_recordview_end_day">
<span<?= $Page->end_day->viewAttributes() ?>>
<?= $Page->end_day->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->creator_id->Visible) { // creator_id ?>
        <td data-name="creator_id"<?= $Page->creator_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_creator_id" class="el_recordview_creator_id">
<span<?= $Page->creator_id->viewAttributes() ?>>
<?= $Page->creator_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->inline_cleartext->Visible) { // inline_cleartext ?>
        <td data-name="inline_cleartext"<?= $Page->inline_cleartext->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_inline_cleartext" class="el_recordview_inline_cleartext">
<span<?= $Page->inline_cleartext->viewAttributes() ?>>
<?= $Page->inline_cleartext->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->inline_plaintext->Visible) { // inline_plaintext ?>
        <td data-name="inline_plaintext"<?= $Page->inline_plaintext->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_inline_plaintext" class="el_recordview_inline_plaintext">
<span<?= $Page->inline_plaintext->viewAttributes() ?>>
<?= $Page->inline_plaintext->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->current_country->Visible) { // current_country ?>
        <td data-name="current_country"<?= $Page->current_country->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_current_country" class="el_recordview_current_country">
<span<?= $Page->current_country->viewAttributes() ?>>
<?= $Page->current_country->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->current_city->Visible) { // current_city ?>
        <td data-name="current_city"<?= $Page->current_city->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_current_city" class="el_recordview_current_city">
<span<?= $Page->current_city->viewAttributes() ?>>
<?= $Page->current_city->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->current_holder->Visible) { // current_holder ?>
        <td data-name="current_holder"<?= $Page->current_holder->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_current_holder" class="el_recordview_current_holder">
<span<?= $Page->current_holder->viewAttributes() ?>>
<?= $Page->current_holder->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->number_of_pages->Visible) { // number_of_pages ?>
        <td data-name="number_of_pages"<?= $Page->number_of_pages->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_number_of_pages" class="el_recordview_number_of_pages">
<span<?= $Page->number_of_pages->viewAttributes() ?>>
<?= $Page->number_of_pages->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cleartext_lang->Visible) { // cleartext_lang ?>
        <td data-name="cleartext_lang"<?= $Page->cleartext_lang->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_cleartext_lang" class="el_recordview_cleartext_lang">
<span<?= $Page->cleartext_lang->viewAttributes() ?>>
<?= $Page->cleartext_lang->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->plaintext_lang->Visible) { // plaintext_lang ?>
        <td data-name="plaintext_lang"<?= $Page->plaintext_lang->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_plaintext_lang" class="el_recordview_plaintext_lang">
<span<?= $Page->plaintext_lang->viewAttributes() ?>>
<?= $Page->plaintext_lang->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->origin_region->Visible) { // origin_region ?>
        <td data-name="origin_region"<?= $Page->origin_region->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_origin_region" class="el_recordview_origin_region">
<span<?= $Page->origin_region->viewAttributes() ?>>
<?= $Page->origin_region->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->origin_city->Visible) { // origin_city ?>
        <td data-name="origin_city"<?= $Page->origin_city->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_origin_city" class="el_recordview_origin_city">
<span<?= $Page->origin_city->viewAttributes() ?>>
<?= $Page->origin_city->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->paper->Visible) { // paper ?>
        <td data-name="paper"<?= $Page->paper->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_paper" class="el_recordview_paper">
<span<?= $Page->paper->viewAttributes() ?>>
<?= $Page->paper->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->creation_date->Visible) { // creation_date ?>
        <td data-name="creation_date"<?= $Page->creation_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_creation_date" class="el_recordview_creation_date">
<span<?= $Page->creation_date->viewAttributes() ?>>
<?= $Page->creation_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->private_ciphertext->Visible) { // private_ciphertext ?>
        <td data-name="private_ciphertext"<?= $Page->private_ciphertext->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_private_ciphertext" class="el_recordview_private_ciphertext">
<span<?= $Page->private_ciphertext->viewAttributes() ?>>
<?= $Page->private_ciphertext->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cipher_types->Visible) { // cipher_types ?>
        <td data-name="cipher_types"<?= $Page->cipher_types->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_cipher_types" class="el_recordview_cipher_types">
<span<?= $Page->cipher_types->viewAttributes() ?>>
<?= $Page->cipher_types->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->symbol_sets->Visible) { // symbol_sets ?>
        <td data-name="symbol_sets"<?= $Page->symbol_sets->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_symbol_sets" class="el_recordview_symbol_sets">
<span<?= $Page->symbol_sets->viewAttributes() ?>>
<?= $Page->symbol_sets->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->cipher_type_other->Visible) { // cipher_type_other ?>
        <td data-name="cipher_type_other"<?= $Page->cipher_type_other->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_cipher_type_other" class="el_recordview_cipher_type_other">
<span<?= $Page->cipher_type_other->viewAttributes() ?>>
<?= $Page->cipher_type_other->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->symbol_set_other->Visible) { // symbol_set_other ?>
        <td data-name="symbol_set_other"<?= $Page->symbol_set_other->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_symbol_set_other" class="el_recordview_symbol_set_other">
<span<?= $Page->symbol_set_other->viewAttributes() ?>>
<?= $Page->symbol_set_other->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->status->Visible) { // status ?>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_status" class="el_recordview_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->record_type->Visible) { // record_type ?>
        <td data-name="record_type"<?= $Page->record_type->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_record_type" class="el_recordview_record_type">
<span<?= $Page->record_type->viewAttributes() ?>>
<?= $Page->record_type->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->access_mode->Visible) { // access_mode ?>
        <td data-name="access_mode"<?= $Page->access_mode->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_access_mode" class="el_recordview_access_mode">
<span<?= $Page->access_mode->viewAttributes() ?>>
<?= $Page->access_mode->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->link->Visible) { // link ?>
        <td data-name="link"<?= $Page->link->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_recordview_link" class="el_recordview_link">
<span<?= $Page->link->viewAttributes() ?>>
<?php if (!EmptyString($Page->link->getViewValue()) && $Page->link->linkAttributes() != "") { ?>
<a<?= $Page->link->linkAttributes() ?>><?= $Page->link->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->link->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (
        $Page->Recordset &&
        !$Page->Recordset->EOF &&
        $Page->RowIndex !== '$rowindex$' &&
        (!$Page->isGridAdd() || $Page->CurrentMode == "copy") &&
        (!(($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0))
    ) {
        $Page->Recordset->moveNext();
    }
    // Reset for template row
    if ($Page->RowIndex === '$rowindex$') {
        $Page->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0) {
        $Page->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction && !$Page->UseAjaxActions) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("recordview");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
