<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$FileInProjectList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { file_in_project: currentTable } });
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
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "project") {
    if ($Page->MasterRecordExists) {
        include_once "views/ProjectMaster.php";
    }
}
?>
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->showFilterList() ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="ffile_in_projectsrch" id="ffile_in_projectsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" novalidate autocomplete="off">
<div id="ffile_in_projectsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { file_in_project: currentTable } });
var currentForm;
var ffile_in_projectsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("ffile_in_projectsrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["id", [], fields.id.isInvalid],
            ["project_id", [ew.Validators.integer], fields.project_id.isInvalid],
            ["filename", [], fields.filename.isInvalid],
            ["type", [ew.Validators.integer], fields.type.isInvalid],
            ["filesize", [], fields.filesize.isInvalid],
            ["creation_date", [], fields.creation_date.isInvalid],
            ["last_updated", [], fields.last_updated.isInvalid],
            ["locked_by", [], fields.locked_by.isInvalid],
            ["lock_date", [], fields.lock_date.isInvalid]
        ])
        // Validate form
        .setValidate(
            async function () {
                if (!this.validateRequired)
                    return true; // Ignore validation
                let fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
                return true;
            }
        )

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "locked_by": <?= $Page->locked_by->toClientList($Page) ?>,
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
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->project_id->Visible) { // project_id ?>
<?php
if (!$Page->project_id->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_project_id" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->project_id->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_project_id" class="ew-search-caption ew-label"><?= $Page->project_id->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_project_id" id="z_project_id" value="=">
</div>
        </div>
        <div id="el_file_in_project_project_id" class="ew-search-field">
<input type="<?= $Page->project_id->getInputTextType() ?>" name="x_project_id" id="x_project_id" data-table="file_in_project" data-field="x_project_id" value="<?= $Page->project_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->project_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->project_id->formatPattern()) ?>"<?= $Page->project_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->project_id->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->filename->Visible) { // filename ?>
<?php
if (!$Page->filename->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_filename" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->filename->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_filename" class="ew-search-caption ew-label"><?= $Page->filename->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_filename" id="z_filename" value="LIKE">
</div>
        </div>
        <div id="el_file_in_project_filename" class="ew-search-field">
<input type="<?= $Page->filename->getInputTextType() ?>" name="x_filename" id="x_filename" data-table="file_in_project" data-field="x_filename" value="<?= $Page->filename->EditValue ?>" size="35" maxlength="256" placeholder="<?= HtmlEncode($Page->filename->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->filename->formatPattern()) ?>"<?= $Page->filename->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->filename->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->type->Visible) { // type ?>
<?php
if (!$Page->type->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_type" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->type->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_type" class="ew-search-caption ew-label"><?= $Page->type->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_type" id="z_type" value="=">
</div>
        </div>
        <div id="el_file_in_project_type" class="ew-search-field">
<input type="<?= $Page->type->getInputTextType() ?>" name="x_type" id="x_type" data-table="file_in_project" data-field="x_type" value="<?= $Page->type->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->type->formatPattern()) ?>"<?= $Page->type->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->type->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->locked_by->Visible) { // locked_by ?>
<?php
if (!$Page->locked_by->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_locked_by" class="col-sm-auto d-sm-flex align-items-start mb-3 px-0 pe-sm-2<?= $Page->locked_by->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_locked_by" class="ew-search-caption ew-label"><?= $Page->locked_by->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_locked_by" id="z_locked_by" value="=">
</div>
        </div>
        <div id="el_file_in_project_locked_by" class="ew-search-field">
    <select
        id="x_locked_by"
        name="x_locked_by"
        class="form-select ew-select<?= $Page->locked_by->isInvalidClass() ?>"
        <?php if (!$Page->locked_by->IsNativeSelect) { ?>
        data-select2-id="ffile_in_projectsrch_x_locked_by"
        <?php } ?>
        data-table="file_in_project"
        data-field="x_locked_by"
        data-value-separator="<?= $Page->locked_by->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->locked_by->getPlaceHolder()) ?>"
        <?= $Page->locked_by->editAttributes() ?>>
        <?= $Page->locked_by->selectOptionListHtml("x_locked_by") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->locked_by->getErrorMessage(false) ?></div>
<?= $Page->locked_by->Lookup->getParamTag($Page, "p_x_locked_by") ?>
<?php if (!$Page->locked_by->IsNativeSelect) { ?>
<script>
loadjs.ready("ffile_in_projectsrch", function() {
    var options = { name: "x_locked_by", selectId: "ffile_in_projectsrch_x_locked_by" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ffile_in_projectsrch.lists.locked_by?.lookupOptions.length) {
        options.data = { id: "x_locked_by", form: "ffile_in_projectsrch" };
    } else {
        options.ajax = { id: "x_locked_by", form: "ffile_in_projectsrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.file_in_project.fields.locked_by.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
</div><!-- /.row -->
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="ffile_in_projectsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="ffile_in_projectsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="ffile_in_projectsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="ffile_in_projectsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<input type="hidden" name="t" value="file_in_project">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "project" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="project">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->project_id->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_file_in_project" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_file_in_projectlist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="id" class="<?= $Page->id->headerCellClass() ?>"><div id="elh_file_in_project_id" class="file_in_project_id"><?= $Page->renderFieldHeader($Page->id) ?></div></th>
<?php } ?>
<?php if ($Page->project_id->Visible) { // project_id ?>
        <th data-name="project_id" class="<?= $Page->project_id->headerCellClass() ?>"><div id="elh_file_in_project_project_id" class="file_in_project_project_id"><?= $Page->renderFieldHeader($Page->project_id) ?></div></th>
<?php } ?>
<?php if ($Page->filename->Visible) { // filename ?>
        <th data-name="filename" class="<?= $Page->filename->headerCellClass() ?>"><div id="elh_file_in_project_filename" class="file_in_project_filename"><?= $Page->renderFieldHeader($Page->filename) ?></div></th>
<?php } ?>
<?php if ($Page->type->Visible) { // type ?>
        <th data-name="type" class="<?= $Page->type->headerCellClass() ?>"><div id="elh_file_in_project_type" class="file_in_project_type"><?= $Page->renderFieldHeader($Page->type) ?></div></th>
<?php } ?>
<?php if ($Page->filesize->Visible) { // filesize ?>
        <th data-name="filesize" class="<?= $Page->filesize->headerCellClass() ?>"><div id="elh_file_in_project_filesize" class="file_in_project_filesize"><?= $Page->renderFieldHeader($Page->filesize) ?></div></th>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
        <th data-name="creation_date" class="<?= $Page->creation_date->headerCellClass() ?>"><div id="elh_file_in_project_creation_date" class="file_in_project_creation_date"><?= $Page->renderFieldHeader($Page->creation_date) ?></div></th>
<?php } ?>
<?php if ($Page->last_updated->Visible) { // last_updated ?>
        <th data-name="last_updated" class="<?= $Page->last_updated->headerCellClass() ?>"><div id="elh_file_in_project_last_updated" class="file_in_project_last_updated"><?= $Page->renderFieldHeader($Page->last_updated) ?></div></th>
<?php } ?>
<?php if ($Page->locked_by->Visible) { // locked_by ?>
        <th data-name="locked_by" class="<?= $Page->locked_by->headerCellClass() ?>"><div id="elh_file_in_project_locked_by" class="file_in_project_locked_by"><?= $Page->renderFieldHeader($Page->locked_by) ?></div></th>
<?php } ?>
<?php if ($Page->lock_date->Visible) { // lock_date ?>
        <th data-name="lock_date" class="<?= $Page->lock_date->headerCellClass() ?>"><div id="elh_file_in_project_lock_date" class="file_in_project_lock_date"><?= $Page->renderFieldHeader($Page->lock_date) ?></div></th>
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
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_file_in_project_id" class="el_file_in_project_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->project_id->Visible) { // project_id ?>
        <td data-name="project_id"<?= $Page->project_id->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_file_in_project_project_id" class="el_file_in_project_project_id">
<span<?= $Page->project_id->viewAttributes() ?>>
<?= $Page->project_id->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->filename->Visible) { // filename ?>
        <td data-name="filename"<?= $Page->filename->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_file_in_project_filename" class="el_file_in_project_filename">
<span<?= $Page->filename->viewAttributes() ?>><?php
 echo '<a href="';
  echo '/decrypt-custom/filesrv/?file='
 . CurrentPage()->path->CurrentValue;
 echo '">';
echo CurrentPage()->filename->CurrentValue;
 echo '</a>';
?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->type->Visible) { // type ?>
        <td data-name="type"<?= $Page->type->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_file_in_project_type" class="el_file_in_project_type">
<span<?= $Page->type->viewAttributes() ?>>
<?= $Page->type->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->filesize->Visible) { // filesize ?>
        <td data-name="filesize"<?= $Page->filesize->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_file_in_project_filesize" class="el_file_in_project_filesize">
<span<?= $Page->filesize->viewAttributes() ?>>
<?= $Page->filesize->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->creation_date->Visible) { // creation_date ?>
        <td data-name="creation_date"<?= $Page->creation_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_file_in_project_creation_date" class="el_file_in_project_creation_date">
<span<?= $Page->creation_date->viewAttributes() ?>>
<?= $Page->creation_date->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->last_updated->Visible) { // last_updated ?>
        <td data-name="last_updated"<?= $Page->last_updated->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_file_in_project_last_updated" class="el_file_in_project_last_updated">
<span<?= $Page->last_updated->viewAttributes() ?>>
<?= $Page->last_updated->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->locked_by->Visible) { // locked_by ?>
        <td data-name="locked_by"<?= $Page->locked_by->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_file_in_project_locked_by" class="el_file_in_project_locked_by">
<span<?= $Page->locked_by->viewAttributes() ?>>
<?= $Page->locked_by->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->lock_date->Visible) { // lock_date ?>
        <td data-name="lock_date"<?= $Page->lock_date->cellAttributes() ?>>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_file_in_project_lock_date" class="el_file_in_project_lock_date">
<span<?= $Page->lock_date->viewAttributes() ?>>
<?= $Page->lock_date->getViewValue() ?></span>
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
    ew.addEventHandlers("file_in_project");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
