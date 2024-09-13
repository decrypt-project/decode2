<?php

namespace PHPMaker2023\decryptweb23;

// Set up and run Grid object
$Grid = Container("DocumentsGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fdocumentsgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { documents: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocumentsgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["_title", [fields._title.visible && fields._title.required ? ew.Validators.required(fields._title.caption) : null], fields._title.isInvalid],
            ["upload_date", [fields.upload_date.visible && fields.upload_date.required ? ew.Validators.required(fields.upload_date.caption) : null], fields.upload_date.isInvalid],
            ["category", [fields.category.visible && fields.category.required ? ew.Validators.required(fields.category.caption) : null], fields.category.isInvalid],
            ["public_", [fields.public_.visible && fields.public_.required ? ew.Validators.required(fields.public_.caption) : null], fields.public_.isInvalid],
            ["record_id", [fields.record_id.visible && fields.record_id.required ? ew.Validators.required(fields.record_id.caption) : null, ew.Validators.integer], fields.record_id.isInvalid],
            ["uploader_id", [fields.uploader_id.visible && fields.uploader_id.required ? ew.Validators.required(fields.uploader_id.caption) : null], fields.uploader_id.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["_title",false],["upload_date",false],["category",false],["public_",false],["record_id",false],["uploader_id",false]];
                if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
                    return false;
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
            "category": <?= $Grid->category->toClientList($Grid) ?>,
            "public_": <?= $Grid->public_->toClientList($Grid) ?>,
        })
        .build();
    window[form.id] = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<main class="list">
<div id="ew-list">
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Grid->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Grid->TableGridClass ?>">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<div id="fdocumentsgrid" class="ew-form ew-list-form">
<div id="gmp_documents" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_documentsgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = ROWTYPE_HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->id->Visible) { // id ?>
        <th data-name="id" class="<?= $Grid->id->headerCellClass() ?>"><div id="elh_documents_id" class="documents_id"><?= $Grid->renderFieldHeader($Grid->id) ?></div></th>
<?php } ?>
<?php if ($Grid->_title->Visible) { // title ?>
        <th data-name="_title" class="<?= $Grid->_title->headerCellClass() ?>"><div id="elh_documents__title" class="documents__title"><?= $Grid->renderFieldHeader($Grid->_title) ?></div></th>
<?php } ?>
<?php if ($Grid->upload_date->Visible) { // upload_date ?>
        <th data-name="upload_date" class="<?= $Grid->upload_date->headerCellClass() ?>"><div id="elh_documents_upload_date" class="documents_upload_date"><?= $Grid->renderFieldHeader($Grid->upload_date) ?></div></th>
<?php } ?>
<?php if ($Grid->category->Visible) { // category ?>
        <th data-name="category" class="<?= $Grid->category->headerCellClass() ?>"><div id="elh_documents_category" class="documents_category"><?= $Grid->renderFieldHeader($Grid->category) ?></div></th>
<?php } ?>
<?php if ($Grid->public_->Visible) { // public_ ?>
        <th data-name="public_" class="<?= $Grid->public_->headerCellClass() ?>"><div id="elh_documents_public_" class="documents_public_"><?= $Grid->renderFieldHeader($Grid->public_) ?></div></th>
<?php } ?>
<?php if ($Grid->record_id->Visible) { // record_id ?>
        <th data-name="record_id" class="<?= $Grid->record_id->headerCellClass() ?>"><div id="elh_documents_record_id" class="documents_record_id"><?= $Grid->renderFieldHeader($Grid->record_id) ?></div></th>
<?php } ?>
<?php if ($Grid->uploader_id->Visible) { // uploader_id ?>
        <th data-name="uploader_id" class="<?= $Grid->uploader_id->headerCellClass() ?>"><div id="elh_documents_uploader_id" class="documents_uploader_id"><?= $Grid->renderFieldHeader($Grid->uploader_id) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Grid->getPageNumber() ?>">
<?php
$Grid->setupGrid();
while ($Grid->RecordCount < $Grid->StopRecord || $Grid->RowIndex === '$rowindex$') {
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->setupRow();

        // Skip 1) delete row / empty row for confirm page, 2) hidden row
        if (
            $Grid->RowAction != "delete" &&
            $Grid->RowAction != "insertdelete" &&
            !($Grid->RowAction == "insert" && $Grid->isConfirm() && $Grid->emptyRow()) &&
            $Grid->RowAction != "hide"
        ) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->id->Visible) { // id ?>
        <td data-name="id"<?= $Grid->id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_id" class="el_documents_id"></span>
<input type="hidden" data-table="documents" data-field="x_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_id" id="o<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_id" class="el_documents_id">
<span<?= $Grid->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id->getDisplayValue($Grid->id->EditValue))) ?>"></span>
<input type="hidden" data-table="documents" data-field="x_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id" id="x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_id" class="el_documents_id">
<span<?= $Grid->id->viewAttributes() ?>>
<?= $Grid->id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="documents" data-field="x_id" data-hidden="1" name="fdocumentsgrid$x<?= $Grid->RowIndex ?>_id" id="fdocumentsgrid$x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->FormValue) ?>">
<input type="hidden" data-table="documents" data-field="x_id" data-hidden="1" data-old name="fdocumentsgrid$o<?= $Grid->RowIndex ?>_id" id="fdocumentsgrid$o<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="documents" data-field="x_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id" id="x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->_title->Visible) { // title ?>
        <td data-name="_title"<?= $Grid->_title->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents__title" class="el_documents__title">
<input type="<?= $Grid->_title->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__title" id="x<?= $Grid->RowIndex ?>__title" data-table="documents" data-field="x__title" value="<?= $Grid->_title->EditValue ?>" maxlength="256" placeholder="<?= HtmlEncode($Grid->_title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->_title->formatPattern()) ?>"<?= $Grid->_title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_title->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="documents" data-field="x__title" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>__title" id="o<?= $Grid->RowIndex ?>__title" value="<?= HtmlEncode($Grid->_title->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents__title" class="el_documents__title">
<input type="<?= $Grid->_title->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>__title" id="x<?= $Grid->RowIndex ?>__title" data-table="documents" data-field="x__title" value="<?= $Grid->_title->EditValue ?>" maxlength="256" placeholder="<?= HtmlEncode($Grid->_title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->_title->formatPattern()) ?>"<?= $Grid->_title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->_title->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents__title" class="el_documents__title">
<span<?= $Grid->_title->viewAttributes() ?>>
<?= $Grid->_title->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="documents" data-field="x__title" data-hidden="1" name="fdocumentsgrid$x<?= $Grid->RowIndex ?>__title" id="fdocumentsgrid$x<?= $Grid->RowIndex ?>__title" value="<?= HtmlEncode($Grid->_title->FormValue) ?>">
<input type="hidden" data-table="documents" data-field="x__title" data-hidden="1" data-old name="fdocumentsgrid$o<?= $Grid->RowIndex ?>__title" id="fdocumentsgrid$o<?= $Grid->RowIndex ?>__title" value="<?= HtmlEncode($Grid->_title->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->upload_date->Visible) { // upload_date ?>
        <td data-name="upload_date"<?= $Grid->upload_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_upload_date" class="el_documents_upload_date">
<input type="hidden" data-table="documents" data-field="x_upload_date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_upload_date" id="x<?= $Grid->RowIndex ?>_upload_date" value="<?= HtmlEncode($Grid->upload_date->CurrentValue) ?>">
</span>
<input type="hidden" data-table="documents" data-field="x_upload_date" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_upload_date" id="o<?= $Grid->RowIndex ?>_upload_date" value="<?= HtmlEncode($Grid->upload_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_upload_date" class="el_documents_upload_date">
<input type="hidden" data-table="documents" data-field="x_upload_date" data-hidden="1" name="x<?= $Grid->RowIndex ?>_upload_date" id="x<?= $Grid->RowIndex ?>_upload_date" value="<?= HtmlEncode($Grid->upload_date->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_upload_date" class="el_documents_upload_date">
<span<?= $Grid->upload_date->viewAttributes() ?>>
<?= $Grid->upload_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="documents" data-field="x_upload_date" data-hidden="1" name="fdocumentsgrid$x<?= $Grid->RowIndex ?>_upload_date" id="fdocumentsgrid$x<?= $Grid->RowIndex ?>_upload_date" value="<?= HtmlEncode($Grid->upload_date->FormValue) ?>">
<input type="hidden" data-table="documents" data-field="x_upload_date" data-hidden="1" data-old name="fdocumentsgrid$o<?= $Grid->RowIndex ?>_upload_date" id="fdocumentsgrid$o<?= $Grid->RowIndex ?>_upload_date" value="<?= HtmlEncode($Grid->upload_date->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->category->Visible) { // category ?>
        <td data-name="category"<?= $Grid->category->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_category" class="el_documents_category">
    <select
        id="x<?= $Grid->RowIndex ?>_category"
        name="x<?= $Grid->RowIndex ?>_category"
        class="form-select ew-select<?= $Grid->category->isInvalidClass() ?>"
        <?php if (!$Grid->category->IsNativeSelect) { ?>
        data-select2-id="fdocumentsgrid_x<?= $Grid->RowIndex ?>_category"
        <?php } ?>
        data-table="documents"
        data-field="x_category"
        data-dropdown
        data-value-separator="<?= $Grid->category->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->category->getPlaceHolder()) ?>"
        <?= $Grid->category->editAttributes() ?>>
        <?= $Grid->category->selectOptionListHtml("x{$Grid->RowIndex}_category") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->category->getErrorMessage() ?></div>
<?php if (!$Grid->category->IsNativeSelect) { ?>
<script>
loadjs.ready("fdocumentsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_category", selectId: "fdocumentsgrid_x<?= $Grid->RowIndex ?>_category" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.columns = el.dataset.repeatcolumn || 5;
    options.dropdown = !ew.IS_MOBILE && options.columns > 0; // Use custom dropdown
    el.dataset.dropdown = options.dropdown;
    if (options.dropdown) {
        options.dropdownAutoWidth = true;
        options.dropdownCssClass = "ew-select-dropdown documents-x_category-dropdown ew-select-" + (options.multiple ? "multiple" : "one");
        if (options.columns > 1)
            options.dropdownCssClass += " ew-repeat-column";
    }
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fdocumentsgrid.lists.category?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_category", form: "fdocumentsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_category", form: "fdocumentsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.documents.fields.category.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="documents" data-field="x_category" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_category" id="o<?= $Grid->RowIndex ?>_category" value="<?= HtmlEncode($Grid->category->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_category" class="el_documents_category">
    <select
        id="x<?= $Grid->RowIndex ?>_category"
        name="x<?= $Grid->RowIndex ?>_category"
        class="form-select ew-select<?= $Grid->category->isInvalidClass() ?>"
        <?php if (!$Grid->category->IsNativeSelect) { ?>
        data-select2-id="fdocumentsgrid_x<?= $Grid->RowIndex ?>_category"
        <?php } ?>
        data-table="documents"
        data-field="x_category"
        data-dropdown
        data-value-separator="<?= $Grid->category->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->category->getPlaceHolder()) ?>"
        <?= $Grid->category->editAttributes() ?>>
        <?= $Grid->category->selectOptionListHtml("x{$Grid->RowIndex}_category") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->category->getErrorMessage() ?></div>
<?php if (!$Grid->category->IsNativeSelect) { ?>
<script>
loadjs.ready("fdocumentsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_category", selectId: "fdocumentsgrid_x<?= $Grid->RowIndex ?>_category" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.columns = el.dataset.repeatcolumn || 5;
    options.dropdown = !ew.IS_MOBILE && options.columns > 0; // Use custom dropdown
    el.dataset.dropdown = options.dropdown;
    if (options.dropdown) {
        options.dropdownAutoWidth = true;
        options.dropdownCssClass = "ew-select-dropdown documents-x_category-dropdown ew-select-" + (options.multiple ? "multiple" : "one");
        if (options.columns > 1)
            options.dropdownCssClass += " ew-repeat-column";
    }
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fdocumentsgrid.lists.category?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_category", form: "fdocumentsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_category", form: "fdocumentsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.documents.fields.category.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_category" class="el_documents_category">
<span<?= $Grid->category->viewAttributes() ?>>
<?= $Grid->category->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="documents" data-field="x_category" data-hidden="1" name="fdocumentsgrid$x<?= $Grid->RowIndex ?>_category" id="fdocumentsgrid$x<?= $Grid->RowIndex ?>_category" value="<?= HtmlEncode($Grid->category->FormValue) ?>">
<input type="hidden" data-table="documents" data-field="x_category" data-hidden="1" data-old name="fdocumentsgrid$o<?= $Grid->RowIndex ?>_category" id="fdocumentsgrid$o<?= $Grid->RowIndex ?>_category" value="<?= HtmlEncode($Grid->category->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->public_->Visible) { // public_ ?>
        <td data-name="public_"<?= $Grid->public_->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_public_" class="el_documents_public_">
<template id="tp_x<?= $Grid->RowIndex ?>_public_">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="documents" data-field="x_public_" name="x<?= $Grid->RowIndex ?>_public_" id="x<?= $Grid->RowIndex ?>_public_"<?= $Grid->public_->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_public_" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_public_"
    name="x<?= $Grid->RowIndex ?>_public_"
    value="<?= HtmlEncode($Grid->public_->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_public_"
    data-target="dsl_x<?= $Grid->RowIndex ?>_public_"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->public_->isInvalidClass() ?>"
    data-table="documents"
    data-field="x_public_"
    data-value-separator="<?= $Grid->public_->displayValueSeparatorAttribute() ?>"
    <?= $Grid->public_->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->public_->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="documents" data-field="x_public_" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_public_" id="o<?= $Grid->RowIndex ?>_public_" value="<?= HtmlEncode($Grid->public_->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_public_" class="el_documents_public_">
<template id="tp_x<?= $Grid->RowIndex ?>_public_">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="documents" data-field="x_public_" name="x<?= $Grid->RowIndex ?>_public_" id="x<?= $Grid->RowIndex ?>_public_"<?= $Grid->public_->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_public_" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_public_"
    name="x<?= $Grid->RowIndex ?>_public_"
    value="<?= HtmlEncode($Grid->public_->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_public_"
    data-target="dsl_x<?= $Grid->RowIndex ?>_public_"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->public_->isInvalidClass() ?>"
    data-table="documents"
    data-field="x_public_"
    data-value-separator="<?= $Grid->public_->displayValueSeparatorAttribute() ?>"
    <?= $Grid->public_->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->public_->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_public_" class="el_documents_public_">
<span<?= $Grid->public_->viewAttributes() ?>>
<?= $Grid->public_->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="documents" data-field="x_public_" data-hidden="1" name="fdocumentsgrid$x<?= $Grid->RowIndex ?>_public_" id="fdocumentsgrid$x<?= $Grid->RowIndex ?>_public_" value="<?= HtmlEncode($Grid->public_->FormValue) ?>">
<input type="hidden" data-table="documents" data-field="x_public_" data-hidden="1" data-old name="fdocumentsgrid$o<?= $Grid->RowIndex ?>_public_" id="fdocumentsgrid$o<?= $Grid->RowIndex ?>_public_" value="<?= HtmlEncode($Grid->public_->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->record_id->Visible) { // record_id ?>
        <td data-name="record_id"<?= $Grid->record_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->record_id->getSessionValue() != "") { ?>
<span<?= $Grid->record_id->viewAttributes() ?>>
<?php if (!EmptyString($Grid->record_id->ViewValue) && $Grid->record_id->linkAttributes() != "") { ?>
<a<?= $Grid->record_id->linkAttributes() ?>><input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->record_id->getDisplayValue($Grid->record_id->ViewValue))) ?>"></a>
<?php } else { ?>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->record_id->getDisplayValue($Grid->record_id->ViewValue))) ?>">
<?php } ?>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_record_id" name="x<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_record_id" class="el_documents_record_id">
<input type="<?= $Grid->record_id->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_record_id" id="x<?= $Grid->RowIndex ?>_record_id" data-table="documents" data-field="x_record_id" value="<?= $Grid->record_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->record_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->record_id->formatPattern()) ?>"<?= $Grid->record_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->record_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="documents" data-field="x_record_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_record_id" id="o<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->record_id->getSessionValue() != "") { ?>
<span<?= $Grid->record_id->viewAttributes() ?>>
<?php if (!EmptyString($Grid->record_id->ViewValue) && $Grid->record_id->linkAttributes() != "") { ?>
<a<?= $Grid->record_id->linkAttributes() ?>><input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->record_id->getDisplayValue($Grid->record_id->ViewValue))) ?>"></a>
<?php } else { ?>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->record_id->getDisplayValue($Grid->record_id->ViewValue))) ?>">
<?php } ?>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_record_id" name="x<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_record_id" class="el_documents_record_id">
<input type="<?= $Grid->record_id->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_record_id" id="x<?= $Grid->RowIndex ?>_record_id" data-table="documents" data-field="x_record_id" value="<?= $Grid->record_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->record_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->record_id->formatPattern()) ?>"<?= $Grid->record_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->record_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_record_id" class="el_documents_record_id">
<span<?= $Grid->record_id->viewAttributes() ?>>
<?php if (!EmptyString($Grid->record_id->getViewValue()) && $Grid->record_id->linkAttributes() != "") { ?>
<a<?= $Grid->record_id->linkAttributes() ?>><?= $Grid->record_id->getViewValue() ?></a>
<?php } else { ?>
<?= $Grid->record_id->getViewValue() ?>
<?php } ?>
</span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="documents" data-field="x_record_id" data-hidden="1" name="fdocumentsgrid$x<?= $Grid->RowIndex ?>_record_id" id="fdocumentsgrid$x<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->FormValue) ?>">
<input type="hidden" data-table="documents" data-field="x_record_id" data-hidden="1" data-old name="fdocumentsgrid$o<?= $Grid->RowIndex ?>_record_id" id="fdocumentsgrid$o<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->uploader_id->Visible) { // uploader_id ?>
        <td data-name="uploader_id"<?= $Grid->uploader_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_uploader_id" class="el_documents_uploader_id">
<input type="hidden" data-table="documents" data-field="x_uploader_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_uploader_id" id="x<?= $Grid->RowIndex ?>_uploader_id" value="<?= HtmlEncode($Grid->uploader_id->CurrentValue) ?>">
</span>
<input type="hidden" data-table="documents" data-field="x_uploader_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_uploader_id" id="o<?= $Grid->RowIndex ?>_uploader_id" value="<?= HtmlEncode($Grid->uploader_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_uploader_id" class="el_documents_uploader_id">
<input type="hidden" data-table="documents" data-field="x_uploader_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_uploader_id" id="x<?= $Grid->RowIndex ?>_uploader_id" value="<?= HtmlEncode($Grid->uploader_id->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_documents_uploader_id" class="el_documents_uploader_id">
<span<?= $Grid->uploader_id->viewAttributes() ?>>
<?= $Grid->uploader_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="documents" data-field="x_uploader_id" data-hidden="1" name="fdocumentsgrid$x<?= $Grid->RowIndex ?>_uploader_id" id="fdocumentsgrid$x<?= $Grid->RowIndex ?>_uploader_id" value="<?= HtmlEncode($Grid->uploader_id->FormValue) ?>">
<input type="hidden" data-table="documents" data-field="x_uploader_id" data-hidden="1" data-old name="fdocumentsgrid$o<?= $Grid->RowIndex ?>_uploader_id" id="fdocumentsgrid$o<?= $Grid->RowIndex ?>_uploader_id" value="<?= HtmlEncode($Grid->uploader_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == ROWTYPE_ADD || $Grid->RowType == ROWTYPE_EDIT) { ?>
<script data-rowindex="<?= $Grid->RowIndex ?>">
loadjs.ready(["fdocumentsgrid","load"], () => fdocumentsgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (
        $Grid->Recordset &&
        !$Grid->Recordset->EOF &&
        $Grid->RowIndex !== '$rowindex$' &&
        (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy") &&
        (!(($Grid->isCopy() || $Grid->isAdd()) && $Grid->RowIndex == 0))
    ) {
        $Grid->Recordset->moveNext();
    }
    // Reset for template row
    if ($Grid->RowIndex === '$rowindex$') {
        $Grid->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Grid->isCopy() || $Grid->isAdd()) && $Grid->RowIndex == 0) {
        $Grid->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdocumentsgrid">
</div><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Grid->Recordset) {
    $Grid->Recordset->close();
}
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
</main>
<?php if (!$Grid->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("documents");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
    $("#tbl_recordsmaster").hide()
});
</script>
<?php } ?>
