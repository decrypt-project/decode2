<?php

namespace PHPMaker2023\decryptweb23;

// Set up and run Grid object
$Grid = Container("FileInProjectGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var ffile_in_projectgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { file_in_project: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffile_in_projectgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["project_id", [fields.project_id.visible && fields.project_id.required ? ew.Validators.required(fields.project_id.caption) : null, ew.Validators.integer], fields.project_id.isInvalid],
            ["filename", [fields.filename.visible && fields.filename.required ? ew.Validators.required(fields.filename.caption) : null], fields.filename.isInvalid],
            ["type", [fields.type.visible && fields.type.required ? ew.Validators.required(fields.type.caption) : null, ew.Validators.integer], fields.type.isInvalid],
            ["filesize", [fields.filesize.visible && fields.filesize.required ? ew.Validators.required(fields.filesize.caption) : null, ew.Validators.integer], fields.filesize.isInvalid],
            ["creation_date", [fields.creation_date.visible && fields.creation_date.required ? ew.Validators.required(fields.creation_date.caption) : null, ew.Validators.datetime(fields.creation_date.clientFormatPattern)], fields.creation_date.isInvalid],
            ["last_updated", [fields.last_updated.visible && fields.last_updated.required ? ew.Validators.required(fields.last_updated.caption) : null, ew.Validators.datetime(fields.last_updated.clientFormatPattern)], fields.last_updated.isInvalid],
            ["locked_by", [fields.locked_by.visible && fields.locked_by.required ? ew.Validators.required(fields.locked_by.caption) : null], fields.locked_by.isInvalid],
            ["lock_date", [fields.lock_date.visible && fields.lock_date.required ? ew.Validators.required(fields.lock_date.caption) : null, ew.Validators.datetime(fields.lock_date.clientFormatPattern)], fields.lock_date.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["project_id",false],["filename",false],["type",false],["filesize",false],["creation_date",false],["last_updated",false],["locked_by",false],["lock_date",false]];
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
            "locked_by": <?= $Grid->locked_by->toClientList($Grid) ?>,
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
<div id="ffile_in_projectgrid" class="ew-form ew-list-form">
<div id="gmp_file_in_project" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_file_in_projectgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="id" class="<?= $Grid->id->headerCellClass() ?>"><div id="elh_file_in_project_id" class="file_in_project_id"><?= $Grid->renderFieldHeader($Grid->id) ?></div></th>
<?php } ?>
<?php if ($Grid->project_id->Visible) { // project_id ?>
        <th data-name="project_id" class="<?= $Grid->project_id->headerCellClass() ?>"><div id="elh_file_in_project_project_id" class="file_in_project_project_id"><?= $Grid->renderFieldHeader($Grid->project_id) ?></div></th>
<?php } ?>
<?php if ($Grid->filename->Visible) { // filename ?>
        <th data-name="filename" class="<?= $Grid->filename->headerCellClass() ?>"><div id="elh_file_in_project_filename" class="file_in_project_filename"><?= $Grid->renderFieldHeader($Grid->filename) ?></div></th>
<?php } ?>
<?php if ($Grid->type->Visible) { // type ?>
        <th data-name="type" class="<?= $Grid->type->headerCellClass() ?>"><div id="elh_file_in_project_type" class="file_in_project_type"><?= $Grid->renderFieldHeader($Grid->type) ?></div></th>
<?php } ?>
<?php if ($Grid->filesize->Visible) { // filesize ?>
        <th data-name="filesize" class="<?= $Grid->filesize->headerCellClass() ?>"><div id="elh_file_in_project_filesize" class="file_in_project_filesize"><?= $Grid->renderFieldHeader($Grid->filesize) ?></div></th>
<?php } ?>
<?php if ($Grid->creation_date->Visible) { // creation_date ?>
        <th data-name="creation_date" class="<?= $Grid->creation_date->headerCellClass() ?>"><div id="elh_file_in_project_creation_date" class="file_in_project_creation_date"><?= $Grid->renderFieldHeader($Grid->creation_date) ?></div></th>
<?php } ?>
<?php if ($Grid->last_updated->Visible) { // last_updated ?>
        <th data-name="last_updated" class="<?= $Grid->last_updated->headerCellClass() ?>"><div id="elh_file_in_project_last_updated" class="file_in_project_last_updated"><?= $Grid->renderFieldHeader($Grid->last_updated) ?></div></th>
<?php } ?>
<?php if ($Grid->locked_by->Visible) { // locked_by ?>
        <th data-name="locked_by" class="<?= $Grid->locked_by->headerCellClass() ?>"><div id="elh_file_in_project_locked_by" class="file_in_project_locked_by"><?= $Grid->renderFieldHeader($Grid->locked_by) ?></div></th>
<?php } ?>
<?php if ($Grid->lock_date->Visible) { // lock_date ?>
        <th data-name="lock_date" class="<?= $Grid->lock_date->headerCellClass() ?>"><div id="elh_file_in_project_lock_date" class="file_in_project_lock_date"><?= $Grid->renderFieldHeader($Grid->lock_date) ?></div></th>
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
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_id" class="el_file_in_project_id"></span>
<input type="hidden" data-table="file_in_project" data-field="x_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_id" id="o<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_id" class="el_file_in_project_id">
<span<?= $Grid->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id->getDisplayValue($Grid->id->EditValue))) ?>"></span>
<input type="hidden" data-table="file_in_project" data-field="x_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id" id="x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_id" class="el_file_in_project_id">
<span<?= $Grid->id->viewAttributes() ?>>
<?= $Grid->id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="file_in_project" data-field="x_id" data-hidden="1" name="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_id" id="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->FormValue) ?>">
<input type="hidden" data-table="file_in_project" data-field="x_id" data-hidden="1" data-old name="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_id" id="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="file_in_project" data-field="x_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id" id="x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->project_id->Visible) { // project_id ?>
        <td data-name="project_id"<?= $Grid->project_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->project_id->getSessionValue() != "") { ?>
<span<?= $Grid->project_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->project_id->getDisplayValue($Grid->project_id->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_project_id" name="x<?= $Grid->RowIndex ?>_project_id" value="<?= HtmlEncode($Grid->project_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_project_id" class="el_file_in_project_project_id">
<input type="<?= $Grid->project_id->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_project_id" id="x<?= $Grid->RowIndex ?>_project_id" data-table="file_in_project" data-field="x_project_id" value="<?= $Grid->project_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->project_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->project_id->formatPattern()) ?>"<?= $Grid->project_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->project_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="file_in_project" data-field="x_project_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_project_id" id="o<?= $Grid->RowIndex ?>_project_id" value="<?= HtmlEncode($Grid->project_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->project_id->getSessionValue() != "") { ?>
<span<?= $Grid->project_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->project_id->getDisplayValue($Grid->project_id->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_project_id" name="x<?= $Grid->RowIndex ?>_project_id" value="<?= HtmlEncode($Grid->project_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_project_id" class="el_file_in_project_project_id">
<input type="<?= $Grid->project_id->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_project_id" id="x<?= $Grid->RowIndex ?>_project_id" data-table="file_in_project" data-field="x_project_id" value="<?= $Grid->project_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->project_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->project_id->formatPattern()) ?>"<?= $Grid->project_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->project_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_project_id" class="el_file_in_project_project_id">
<span<?= $Grid->project_id->viewAttributes() ?>>
<?= $Grid->project_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="file_in_project" data-field="x_project_id" data-hidden="1" name="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_project_id" id="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_project_id" value="<?= HtmlEncode($Grid->project_id->FormValue) ?>">
<input type="hidden" data-table="file_in_project" data-field="x_project_id" data-hidden="1" data-old name="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_project_id" id="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_project_id" value="<?= HtmlEncode($Grid->project_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->filename->Visible) { // filename ?>
        <td data-name="filename"<?= $Grid->filename->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_filename" class="el_file_in_project_filename">
<textarea data-table="file_in_project" data-field="x_filename" name="x<?= $Grid->RowIndex ?>_filename" id="x<?= $Grid->RowIndex ?>_filename" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->filename->getPlaceHolder()) ?>"<?= $Grid->filename->editAttributes() ?>><?= $Grid->filename->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->filename->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="file_in_project" data-field="x_filename" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_filename" id="o<?= $Grid->RowIndex ?>_filename" value="<?= HtmlEncode($Grid->filename->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_filename" class="el_file_in_project_filename">
<textarea data-table="file_in_project" data-field="x_filename" name="x<?= $Grid->RowIndex ?>_filename" id="x<?= $Grid->RowIndex ?>_filename" cols="35" rows="4" placeholder="<?= HtmlEncode($Grid->filename->getPlaceHolder()) ?>"<?= $Grid->filename->editAttributes() ?>><?= $Grid->filename->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Grid->filename->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_filename" class="el_file_in_project_filename">
<span<?= $Grid->filename->viewAttributes() ?>><?php
 echo '<a href="';
  echo '/decrypt-custom/filesrv/?file='
 . CurrentPage()->path->CurrentValue;
 echo '">';
echo CurrentPage()->filename->CurrentValue;
 echo '</a>';
?>
</span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="file_in_project" data-field="x_filename" data-hidden="1" name="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_filename" id="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_filename" value="<?= HtmlEncode($Grid->filename->FormValue) ?>">
<input type="hidden" data-table="file_in_project" data-field="x_filename" data-hidden="1" data-old name="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_filename" id="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_filename" value="<?= HtmlEncode($Grid->filename->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->type->Visible) { // type ?>
        <td data-name="type"<?= $Grid->type->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_type" class="el_file_in_project_type">
<input type="<?= $Grid->type->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_type" id="x<?= $Grid->RowIndex ?>_type" data-table="file_in_project" data-field="x_type" value="<?= $Grid->type->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->type->formatPattern()) ?>"<?= $Grid->type->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->type->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="file_in_project" data-field="x_type" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_type" id="o<?= $Grid->RowIndex ?>_type" value="<?= HtmlEncode($Grid->type->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_type" class="el_file_in_project_type">
<input type="<?= $Grid->type->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_type" id="x<?= $Grid->RowIndex ?>_type" data-table="file_in_project" data-field="x_type" value="<?= $Grid->type->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->type->formatPattern()) ?>"<?= $Grid->type->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->type->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_type" class="el_file_in_project_type">
<span<?= $Grid->type->viewAttributes() ?>>
<?= $Grid->type->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="file_in_project" data-field="x_type" data-hidden="1" name="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_type" id="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_type" value="<?= HtmlEncode($Grid->type->FormValue) ?>">
<input type="hidden" data-table="file_in_project" data-field="x_type" data-hidden="1" data-old name="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_type" id="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_type" value="<?= HtmlEncode($Grid->type->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->filesize->Visible) { // filesize ?>
        <td data-name="filesize"<?= $Grid->filesize->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_filesize" class="el_file_in_project_filesize">
<input type="<?= $Grid->filesize->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_filesize" id="x<?= $Grid->RowIndex ?>_filesize" data-table="file_in_project" data-field="x_filesize" value="<?= $Grid->filesize->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->filesize->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->filesize->formatPattern()) ?>"<?= $Grid->filesize->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->filesize->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="file_in_project" data-field="x_filesize" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_filesize" id="o<?= $Grid->RowIndex ?>_filesize" value="<?= HtmlEncode($Grid->filesize->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_filesize" class="el_file_in_project_filesize">
<input type="<?= $Grid->filesize->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_filesize" id="x<?= $Grid->RowIndex ?>_filesize" data-table="file_in_project" data-field="x_filesize" value="<?= $Grid->filesize->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->filesize->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->filesize->formatPattern()) ?>"<?= $Grid->filesize->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->filesize->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_filesize" class="el_file_in_project_filesize">
<span<?= $Grid->filesize->viewAttributes() ?>>
<?= $Grid->filesize->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="file_in_project" data-field="x_filesize" data-hidden="1" name="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_filesize" id="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_filesize" value="<?= HtmlEncode($Grid->filesize->FormValue) ?>">
<input type="hidden" data-table="file_in_project" data-field="x_filesize" data-hidden="1" data-old name="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_filesize" id="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_filesize" value="<?= HtmlEncode($Grid->filesize->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->creation_date->Visible) { // creation_date ?>
        <td data-name="creation_date"<?= $Grid->creation_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_creation_date" class="el_file_in_project_creation_date">
<input type="<?= $Grid->creation_date->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_creation_date" id="x<?= $Grid->RowIndex ?>_creation_date" data-table="file_in_project" data-field="x_creation_date" value="<?= $Grid->creation_date->EditValue ?>" placeholder="<?= HtmlEncode($Grid->creation_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->creation_date->formatPattern()) ?>"<?= $Grid->creation_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->creation_date->getErrorMessage() ?></div>
<?php if (!$Grid->creation_date->ReadOnly && !$Grid->creation_date->Disabled && !isset($Grid->creation_date->EditAttrs["readonly"]) && !isset($Grid->creation_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffile_in_projectgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("ffile_in_projectgrid", "x<?= $Grid->RowIndex ?>_creation_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="file_in_project" data-field="x_creation_date" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_creation_date" id="o<?= $Grid->RowIndex ?>_creation_date" value="<?= HtmlEncode($Grid->creation_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_creation_date" class="el_file_in_project_creation_date">
<input type="<?= $Grid->creation_date->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_creation_date" id="x<?= $Grid->RowIndex ?>_creation_date" data-table="file_in_project" data-field="x_creation_date" value="<?= $Grid->creation_date->EditValue ?>" placeholder="<?= HtmlEncode($Grid->creation_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->creation_date->formatPattern()) ?>"<?= $Grid->creation_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->creation_date->getErrorMessage() ?></div>
<?php if (!$Grid->creation_date->ReadOnly && !$Grid->creation_date->Disabled && !isset($Grid->creation_date->EditAttrs["readonly"]) && !isset($Grid->creation_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffile_in_projectgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("ffile_in_projectgrid", "x<?= $Grid->RowIndex ?>_creation_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_creation_date" class="el_file_in_project_creation_date">
<span<?= $Grid->creation_date->viewAttributes() ?>>
<?= $Grid->creation_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="file_in_project" data-field="x_creation_date" data-hidden="1" name="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_creation_date" id="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_creation_date" value="<?= HtmlEncode($Grid->creation_date->FormValue) ?>">
<input type="hidden" data-table="file_in_project" data-field="x_creation_date" data-hidden="1" data-old name="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_creation_date" id="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_creation_date" value="<?= HtmlEncode($Grid->creation_date->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->last_updated->Visible) { // last_updated ?>
        <td data-name="last_updated"<?= $Grid->last_updated->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_last_updated" class="el_file_in_project_last_updated">
<input type="<?= $Grid->last_updated->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_last_updated" id="x<?= $Grid->RowIndex ?>_last_updated" data-table="file_in_project" data-field="x_last_updated" value="<?= $Grid->last_updated->EditValue ?>" placeholder="<?= HtmlEncode($Grid->last_updated->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->last_updated->formatPattern()) ?>"<?= $Grid->last_updated->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->last_updated->getErrorMessage() ?></div>
<?php if (!$Grid->last_updated->ReadOnly && !$Grid->last_updated->Disabled && !isset($Grid->last_updated->EditAttrs["readonly"]) && !isset($Grid->last_updated->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffile_in_projectgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("ffile_in_projectgrid", "x<?= $Grid->RowIndex ?>_last_updated", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="file_in_project" data-field="x_last_updated" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_last_updated" id="o<?= $Grid->RowIndex ?>_last_updated" value="<?= HtmlEncode($Grid->last_updated->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_last_updated" class="el_file_in_project_last_updated">
<input type="<?= $Grid->last_updated->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_last_updated" id="x<?= $Grid->RowIndex ?>_last_updated" data-table="file_in_project" data-field="x_last_updated" value="<?= $Grid->last_updated->EditValue ?>" placeholder="<?= HtmlEncode($Grid->last_updated->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->last_updated->formatPattern()) ?>"<?= $Grid->last_updated->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->last_updated->getErrorMessage() ?></div>
<?php if (!$Grid->last_updated->ReadOnly && !$Grid->last_updated->Disabled && !isset($Grid->last_updated->EditAttrs["readonly"]) && !isset($Grid->last_updated->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffile_in_projectgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("ffile_in_projectgrid", "x<?= $Grid->RowIndex ?>_last_updated", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_last_updated" class="el_file_in_project_last_updated">
<span<?= $Grid->last_updated->viewAttributes() ?>>
<?= $Grid->last_updated->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="file_in_project" data-field="x_last_updated" data-hidden="1" name="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_last_updated" id="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_last_updated" value="<?= HtmlEncode($Grid->last_updated->FormValue) ?>">
<input type="hidden" data-table="file_in_project" data-field="x_last_updated" data-hidden="1" data-old name="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_last_updated" id="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_last_updated" value="<?= HtmlEncode($Grid->last_updated->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->locked_by->Visible) { // locked_by ?>
        <td data-name="locked_by"<?= $Grid->locked_by->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_locked_by" class="el_file_in_project_locked_by">
    <select
        id="x<?= $Grid->RowIndex ?>_locked_by"
        name="x<?= $Grid->RowIndex ?>_locked_by"
        class="form-select ew-select<?= $Grid->locked_by->isInvalidClass() ?>"
        <?php if (!$Grid->locked_by->IsNativeSelect) { ?>
        data-select2-id="ffile_in_projectgrid_x<?= $Grid->RowIndex ?>_locked_by"
        <?php } ?>
        data-table="file_in_project"
        data-field="x_locked_by"
        data-value-separator="<?= $Grid->locked_by->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->locked_by->getPlaceHolder()) ?>"
        <?= $Grid->locked_by->editAttributes() ?>>
        <?= $Grid->locked_by->selectOptionListHtml("x{$Grid->RowIndex}_locked_by") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->locked_by->getErrorMessage() ?></div>
<?= $Grid->locked_by->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_locked_by") ?>
<?php if (!$Grid->locked_by->IsNativeSelect) { ?>
<script>
loadjs.ready("ffile_in_projectgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_locked_by", selectId: "ffile_in_projectgrid_x<?= $Grid->RowIndex ?>_locked_by" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ffile_in_projectgrid.lists.locked_by?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_locked_by", form: "ffile_in_projectgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_locked_by", form: "ffile_in_projectgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.file_in_project.fields.locked_by.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="file_in_project" data-field="x_locked_by" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_locked_by" id="o<?= $Grid->RowIndex ?>_locked_by" value="<?= HtmlEncode($Grid->locked_by->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_locked_by" class="el_file_in_project_locked_by">
    <select
        id="x<?= $Grid->RowIndex ?>_locked_by"
        name="x<?= $Grid->RowIndex ?>_locked_by"
        class="form-select ew-select<?= $Grid->locked_by->isInvalidClass() ?>"
        <?php if (!$Grid->locked_by->IsNativeSelect) { ?>
        data-select2-id="ffile_in_projectgrid_x<?= $Grid->RowIndex ?>_locked_by"
        <?php } ?>
        data-table="file_in_project"
        data-field="x_locked_by"
        data-value-separator="<?= $Grid->locked_by->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->locked_by->getPlaceHolder()) ?>"
        <?= $Grid->locked_by->editAttributes() ?>>
        <?= $Grid->locked_by->selectOptionListHtml("x{$Grid->RowIndex}_locked_by") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->locked_by->getErrorMessage() ?></div>
<?= $Grid->locked_by->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_locked_by") ?>
<?php if (!$Grid->locked_by->IsNativeSelect) { ?>
<script>
loadjs.ready("ffile_in_projectgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_locked_by", selectId: "ffile_in_projectgrid_x<?= $Grid->RowIndex ?>_locked_by" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ffile_in_projectgrid.lists.locked_by?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_locked_by", form: "ffile_in_projectgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_locked_by", form: "ffile_in_projectgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.file_in_project.fields.locked_by.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_locked_by" class="el_file_in_project_locked_by">
<span<?= $Grid->locked_by->viewAttributes() ?>>
<?= $Grid->locked_by->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="file_in_project" data-field="x_locked_by" data-hidden="1" name="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_locked_by" id="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_locked_by" value="<?= HtmlEncode($Grid->locked_by->FormValue) ?>">
<input type="hidden" data-table="file_in_project" data-field="x_locked_by" data-hidden="1" data-old name="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_locked_by" id="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_locked_by" value="<?= HtmlEncode($Grid->locked_by->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->lock_date->Visible) { // lock_date ?>
        <td data-name="lock_date"<?= $Grid->lock_date->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_lock_date" class="el_file_in_project_lock_date">
<input type="<?= $Grid->lock_date->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lock_date" id="x<?= $Grid->RowIndex ?>_lock_date" data-table="file_in_project" data-field="x_lock_date" value="<?= $Grid->lock_date->EditValue ?>" placeholder="<?= HtmlEncode($Grid->lock_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lock_date->formatPattern()) ?>"<?= $Grid->lock_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lock_date->getErrorMessage() ?></div>
<?php if (!$Grid->lock_date->ReadOnly && !$Grid->lock_date->Disabled && !isset($Grid->lock_date->EditAttrs["readonly"]) && !isset($Grid->lock_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffile_in_projectgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("ffile_in_projectgrid", "x<?= $Grid->RowIndex ?>_lock_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="file_in_project" data-field="x_lock_date" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_lock_date" id="o<?= $Grid->RowIndex ?>_lock_date" value="<?= HtmlEncode($Grid->lock_date->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_lock_date" class="el_file_in_project_lock_date">
<input type="<?= $Grid->lock_date->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lock_date" id="x<?= $Grid->RowIndex ?>_lock_date" data-table="file_in_project" data-field="x_lock_date" value="<?= $Grid->lock_date->EditValue ?>" placeholder="<?= HtmlEncode($Grid->lock_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lock_date->formatPattern()) ?>"<?= $Grid->lock_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lock_date->getErrorMessage() ?></div>
<?php if (!$Grid->lock_date->ReadOnly && !$Grid->lock_date->Disabled && !isset($Grid->lock_date->EditAttrs["readonly"]) && !isset($Grid->lock_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffile_in_projectgrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("ffile_in_projectgrid", "x<?= $Grid->RowIndex ?>_lock_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_file_in_project_lock_date" class="el_file_in_project_lock_date">
<span<?= $Grid->lock_date->viewAttributes() ?>>
<?= $Grid->lock_date->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="file_in_project" data-field="x_lock_date" data-hidden="1" name="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_lock_date" id="ffile_in_projectgrid$x<?= $Grid->RowIndex ?>_lock_date" value="<?= HtmlEncode($Grid->lock_date->FormValue) ?>">
<input type="hidden" data-table="file_in_project" data-field="x_lock_date" data-hidden="1" data-old name="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_lock_date" id="ffile_in_projectgrid$o<?= $Grid->RowIndex ?>_lock_date" value="<?= HtmlEncode($Grid->lock_date->OldValue) ?>">
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
loadjs.ready(["ffile_in_projectgrid","load"], () => ffile_in_projectgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="ffile_in_projectgrid">
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
    ew.addEventHandlers("file_in_project");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
