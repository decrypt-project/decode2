<?php

namespace PHPMaker2023\decryptweb23;

// Set up and run Grid object
$Grid = Container("ImagesGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fimagesgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { images: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fimagesgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["name", [fields.name.visible && fields.name.required ? ew.Validators.required(fields.name.caption) : null], fields.name.isInvalid],
            ["path", [fields.path.visible && fields.path.required ? ew.Validators.fileRequired(fields.path.caption) : null], fields.path.isInvalid],
            ["record_id", [fields.record_id.visible && fields.record_id.required ? ew.Validators.required(fields.record_id.caption) : null, ew.Validators.integer], fields.record_id.isInvalid],
            ["last_modified", [fields.last_modified.visible && fields.last_modified.required ? ew.Validators.required(fields.last_modified.caption) : null, ew.Validators.datetime(fields.last_modified.clientFormatPattern)], fields.last_modified.isInvalid],
            ["processed", [fields.processed.visible && fields.processed.required ? ew.Validators.required(fields.processed.caption) : null], fields.processed.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["name",false],["path",false],["record_id",false],["last_modified",false],["processed[]",false]];
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
            "processed": <?= $Grid->processed->toClientList($Grid) ?>,
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
<div id="fimagesgrid" class="ew-form ew-list-form">
<div id="gmp_images" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_imagesgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
        <th data-name="id" class="<?= $Grid->id->headerCellClass() ?>"><div id="elh_images_id" class="images_id"><?= $Grid->renderFieldHeader($Grid->id) ?></div></th>
<?php } ?>
<?php if ($Grid->name->Visible) { // name ?>
        <th data-name="name" class="<?= $Grid->name->headerCellClass() ?>"><div id="elh_images_name" class="images_name"><?= $Grid->renderFieldHeader($Grid->name) ?></div></th>
<?php } ?>
<?php if ($Grid->path->Visible) { // path ?>
        <th data-name="path" class="<?= $Grid->path->headerCellClass() ?>"><div id="elh_images_path" class="images_path"><?= $Grid->renderFieldHeader($Grid->path) ?></div></th>
<?php } ?>
<?php if ($Grid->record_id->Visible) { // record_id ?>
        <th data-name="record_id" class="<?= $Grid->record_id->headerCellClass() ?>"><div id="elh_images_record_id" class="images_record_id"><?= $Grid->renderFieldHeader($Grid->record_id) ?></div></th>
<?php } ?>
<?php if ($Grid->last_modified->Visible) { // last_modified ?>
        <th data-name="last_modified" class="<?= $Grid->last_modified->headerCellClass() ?>"><div id="elh_images_last_modified" class="images_last_modified"><?= $Grid->renderFieldHeader($Grid->last_modified) ?></div></th>
<?php } ?>
<?php if ($Grid->processed->Visible) { // processed ?>
        <th data-name="processed" class="<?= $Grid->processed->headerCellClass() ?>"><div id="elh_images_processed" class="images_processed"><?= $Grid->renderFieldHeader($Grid->processed) ?></div></th>
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
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_id" class="el_images_id"></span>
<input type="hidden" data-table="images" data-field="x_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_id" id="o<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_id" class="el_images_id">
<span<?= $Grid->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id->getDisplayValue($Grid->id->EditValue))) ?>"></span>
<input type="hidden" data-table="images" data-field="x_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id" id="x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_id" class="el_images_id">
<span<?= $Grid->id->viewAttributes() ?>>
<?= $Grid->id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="images" data-field="x_id" data-hidden="1" name="fimagesgrid$x<?= $Grid->RowIndex ?>_id" id="fimagesgrid$x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->FormValue) ?>">
<input type="hidden" data-table="images" data-field="x_id" data-hidden="1" data-old name="fimagesgrid$o<?= $Grid->RowIndex ?>_id" id="fimagesgrid$o<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="images" data-field="x_id" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id" id="x<?= $Grid->RowIndex ?>_id" value="<?= HtmlEncode($Grid->id->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->name->Visible) { // name ?>
        <td data-name="name"<?= $Grid->name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_name" class="el_images_name">
<input type="<?= $Grid->name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_name" id="x<?= $Grid->RowIndex ?>_name" data-table="images" data-field="x_name" value="<?= $Grid->name->EditValue ?>" size="30" maxlength="128" placeholder="<?= HtmlEncode($Grid->name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->name->formatPattern()) ?>"<?= $Grid->name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->name->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="images" data-field="x_name" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_name" id="o<?= $Grid->RowIndex ?>_name" value="<?= HtmlEncode($Grid->name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_name" class="el_images_name">
<input type="<?= $Grid->name->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_name" id="x<?= $Grid->RowIndex ?>_name" data-table="images" data-field="x_name" value="<?= $Grid->name->EditValue ?>" size="30" maxlength="128" placeholder="<?= HtmlEncode($Grid->name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->name->formatPattern()) ?>"<?= $Grid->name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->name->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_name" class="el_images_name">
<span<?= $Grid->name->viewAttributes() ?>>
<?= $Grid->name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="images" data-field="x_name" data-hidden="1" name="fimagesgrid$x<?= $Grid->RowIndex ?>_name" id="fimagesgrid$x<?= $Grid->RowIndex ?>_name" value="<?= HtmlEncode($Grid->name->FormValue) ?>">
<input type="hidden" data-table="images" data-field="x_name" data-hidden="1" data-old name="fimagesgrid$o<?= $Grid->RowIndex ?>_name" id="fimagesgrid$o<?= $Grid->RowIndex ?>_name" value="<?= HtmlEncode($Grid->name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->path->Visible) { // path ?>
        <td data-name="path"<?= $Grid->path->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<?php if (!$Grid->isConfirm()) { ?>
<span id="el<?= $Grid->RowIndex ?>_images_path" class="el_images_path">
<div id="fd_x<?= $Grid->RowIndex ?>_path" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x<?= $Grid->RowIndex ?>_path"
        name="x<?= $Grid->RowIndex ?>_path"
        class="form-control ew-file-input"
        title="<?= $Grid->path->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="images"
        data-field="x_path"
        data-size="65535"
        data-accept-file-types="<?= $Grid->path->acceptFileTypes() ?>"
        data-max-file-size="<?= $Grid->path->UploadMaxFileSize ?>"
        data-max-number-of-files="<?= $Grid->path->UploadMaxFileCount ?>"
        data-disable-image-crop="<?= $Grid->path->ImageCropper ? 0 : 1 ?>"
        multiple
        <?= ($Grid->path->ReadOnly || $Grid->path->Disabled) ? " disabled" : "" ?>
        <?= $Grid->path->editAttributes() ?>
    >
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
    <div class="invalid-feedback"><?= $Grid->path->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_path" id= "fn_x<?= $Grid->RowIndex ?>_path" value="<?= $Grid->path->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_path" id= "fa_x<?= $Grid->RowIndex ?>_path" value="0">
<table id="ft_x<?= $Grid->RowIndex ?>_path" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex ?>_images_path" class="el_images_path">
<div id="fd_x<?= $Grid->RowIndex ?>_path">
    <input
        type="file"
        id="x<?= $Grid->RowIndex ?>_path"
        name="x<?= $Grid->RowIndex ?>_path"
        class="form-control ew-file-input d-none"
        title="<?= $Grid->path->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="images"
        data-field="x_path"
        data-size="65535"
        data-accept-file-types="<?= $Grid->path->acceptFileTypes() ?>"
        data-max-file-size="<?= $Grid->path->UploadMaxFileSize ?>"
        data-max-number-of-files="<?= $Grid->path->UploadMaxFileCount ?>"
        data-disable-image-crop="<?= $Grid->path->ImageCropper ? 0 : 1 ?>"
        multiple
        <?= $Grid->path->editAttributes() ?>
    >
    <div class="invalid-feedback"><?= $Grid->path->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_path" id= "fn_x<?= $Grid->RowIndex ?>_path" value="<?= $Grid->path->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_path" id= "fa_x<?= $Grid->RowIndex ?>_path" value="0">
<table id="ft_x<?= $Grid->RowIndex ?>_path" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<input type="hidden" data-table="images" data-field="x_path" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_path" id="o<?= $Grid->RowIndex ?>_path" value="<?= HtmlEncode($Grid->path->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_path" class="el_images_path">
<span<?= $Grid->path->viewAttributes() ?>><?php
 echo '<a href="';
  echo '/decrypt-custom/zoom.php?file='
 . CurrentPage()->path->CurrentValue;
 echo '">';
 echo '<img src="';
 echo '/decrypt-custom/filesrv/?file=TH_'
 . CurrentPage()->path->CurrentValue;
 echo '"/>';
 echo '</a>';
?>
</span>
</span>
<?php } else  { // Edit record ?>
<?php if (!$Grid->isConfirm()) { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_path" class="el_images_path">
<div id="fd_x<?= $Grid->RowIndex ?>_path">
    <input
        type="file"
        id="x<?= $Grid->RowIndex ?>_path"
        name="x<?= $Grid->RowIndex ?>_path"
        class="form-control ew-file-input d-none"
        title="<?= $Grid->path->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="images"
        data-field="x_path"
        data-size="65535"
        data-accept-file-types="<?= $Grid->path->acceptFileTypes() ?>"
        data-max-file-size="<?= $Grid->path->UploadMaxFileSize ?>"
        data-max-number-of-files="<?= $Grid->path->UploadMaxFileCount ?>"
        data-disable-image-crop="<?= $Grid->path->ImageCropper ? 0 : 1 ?>"
        multiple
        <?= $Grid->path->editAttributes() ?>
    >
    <div class="invalid-feedback"><?= $Grid->path->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_path" id= "fn_x<?= $Grid->RowIndex ?>_path" value="<?= $Grid->path->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_path" id= "fa_x<?= $Grid->RowIndex ?>_path" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_path") == "0") ? "0" : "1" ?>">
<table id="ft_x<?= $Grid->RowIndex ?>_path" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_path" class="el_images_path">
<div id="fd_x<?= $Grid->RowIndex ?>_path">
    <input
        type="file"
        id="x<?= $Grid->RowIndex ?>_path"
        name="x<?= $Grid->RowIndex ?>_path"
        class="form-control ew-file-input d-none"
        title="<?= $Grid->path->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="images"
        data-field="x_path"
        data-size="65535"
        data-accept-file-types="<?= $Grid->path->acceptFileTypes() ?>"
        data-max-file-size="<?= $Grid->path->UploadMaxFileSize ?>"
        data-max-number-of-files="<?= $Grid->path->UploadMaxFileCount ?>"
        data-disable-image-crop="<?= $Grid->path->ImageCropper ? 0 : 1 ?>"
        multiple
        <?= $Grid->path->editAttributes() ?>
    >
    <div class="invalid-feedback"><?= $Grid->path->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_path" id= "fn_x<?= $Grid->RowIndex ?>_path" value="<?= $Grid->path->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_path" id= "fa_x<?= $Grid->RowIndex ?>_path" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_path") == "0") ? "0" : "1" ?>">
<table id="ft_x<?= $Grid->RowIndex ?>_path" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->record_id->Visible) { // record_id ?>
        <td data-name="record_id"<?= $Grid->record_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->record_id->getSessionValue() != "") { ?>
<span<?= $Grid->record_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->record_id->getDisplayValue($Grid->record_id->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_record_id" name="x<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_record_id" class="el_images_record_id">
<input type="<?= $Grid->record_id->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_record_id" id="x<?= $Grid->RowIndex ?>_record_id" data-table="images" data-field="x_record_id" value="<?= $Grid->record_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->record_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->record_id->formatPattern()) ?>"<?= $Grid->record_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->record_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="images" data-field="x_record_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_record_id" id="o<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->record_id->getSessionValue() != "") { ?>
<span<?= $Grid->record_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->record_id->getDisplayValue($Grid->record_id->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_record_id" name="x<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_record_id" class="el_images_record_id">
<input type="<?= $Grid->record_id->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_record_id" id="x<?= $Grid->RowIndex ?>_record_id" data-table="images" data-field="x_record_id" value="<?= $Grid->record_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->record_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->record_id->formatPattern()) ?>"<?= $Grid->record_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->record_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_record_id" class="el_images_record_id">
<span<?= $Grid->record_id->viewAttributes() ?>>
<?= $Grid->record_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="images" data-field="x_record_id" data-hidden="1" name="fimagesgrid$x<?= $Grid->RowIndex ?>_record_id" id="fimagesgrid$x<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->FormValue) ?>">
<input type="hidden" data-table="images" data-field="x_record_id" data-hidden="1" data-old name="fimagesgrid$o<?= $Grid->RowIndex ?>_record_id" id="fimagesgrid$o<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->last_modified->Visible) { // last_modified ?>
        <td data-name="last_modified"<?= $Grid->last_modified->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_last_modified" class="el_images_last_modified">
<input type="<?= $Grid->last_modified->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_last_modified" id="x<?= $Grid->RowIndex ?>_last_modified" data-table="images" data-field="x_last_modified" value="<?= $Grid->last_modified->EditValue ?>" maxlength="19" placeholder="<?= HtmlEncode($Grid->last_modified->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->last_modified->formatPattern()) ?>"<?= $Grid->last_modified->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->last_modified->getErrorMessage() ?></div>
<?php if (!$Grid->last_modified->ReadOnly && !$Grid->last_modified->Disabled && !isset($Grid->last_modified->EditAttrs["readonly"]) && !isset($Grid->last_modified->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fimagesgrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fimagesgrid", "x<?= $Grid->RowIndex ?>_last_modified", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="images" data-field="x_last_modified" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_last_modified" id="o<?= $Grid->RowIndex ?>_last_modified" value="<?= HtmlEncode($Grid->last_modified->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_last_modified" class="el_images_last_modified">
<input type="<?= $Grid->last_modified->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_last_modified" id="x<?= $Grid->RowIndex ?>_last_modified" data-table="images" data-field="x_last_modified" value="<?= $Grid->last_modified->EditValue ?>" maxlength="19" placeholder="<?= HtmlEncode($Grid->last_modified->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->last_modified->formatPattern()) ?>"<?= $Grid->last_modified->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->last_modified->getErrorMessage() ?></div>
<?php if (!$Grid->last_modified->ReadOnly && !$Grid->last_modified->Disabled && !isset($Grid->last_modified->EditAttrs["readonly"]) && !isset($Grid->last_modified->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fimagesgrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fimagesgrid", "x<?= $Grid->RowIndex ?>_last_modified", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_last_modified" class="el_images_last_modified">
<span<?= $Grid->last_modified->viewAttributes() ?>>
<?= $Grid->last_modified->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="images" data-field="x_last_modified" data-hidden="1" name="fimagesgrid$x<?= $Grid->RowIndex ?>_last_modified" id="fimagesgrid$x<?= $Grid->RowIndex ?>_last_modified" value="<?= HtmlEncode($Grid->last_modified->FormValue) ?>">
<input type="hidden" data-table="images" data-field="x_last_modified" data-hidden="1" data-old name="fimagesgrid$o<?= $Grid->RowIndex ?>_last_modified" id="fimagesgrid$o<?= $Grid->RowIndex ?>_last_modified" value="<?= HtmlEncode($Grid->last_modified->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->processed->Visible) { // processed ?>
        <td data-name="processed"<?= $Grid->processed->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_processed" class="el_images_processed">
<template id="tp_x<?= $Grid->RowIndex ?>_processed">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="images" data-field="x_processed" name="x<?= $Grid->RowIndex ?>_processed" id="x<?= $Grid->RowIndex ?>_processed"<?= $Grid->processed->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_processed" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_processed[]"
    name="x<?= $Grid->RowIndex ?>_processed[]"
    value="<?= HtmlEncode($Grid->processed->CurrentValue) ?>"
    data-type="select-multiple"
    data-template="tp_x<?= $Grid->RowIndex ?>_processed"
    data-target="dsl_x<?= $Grid->RowIndex ?>_processed"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->processed->isInvalidClass() ?>"
    data-table="images"
    data-field="x_processed"
    data-value-separator="<?= $Grid->processed->displayValueSeparatorAttribute() ?>"
    <?= $Grid->processed->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->processed->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="images" data-field="x_processed" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_processed[]" id="o<?= $Grid->RowIndex ?>_processed[]" value="<?= HtmlEncode($Grid->processed->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_processed" class="el_images_processed">
<template id="tp_x<?= $Grid->RowIndex ?>_processed">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="images" data-field="x_processed" name="x<?= $Grid->RowIndex ?>_processed" id="x<?= $Grid->RowIndex ?>_processed"<?= $Grid->processed->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_processed" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_processed[]"
    name="x<?= $Grid->RowIndex ?>_processed[]"
    value="<?= HtmlEncode($Grid->processed->CurrentValue) ?>"
    data-type="select-multiple"
    data-template="tp_x<?= $Grid->RowIndex ?>_processed"
    data-target="dsl_x<?= $Grid->RowIndex ?>_processed"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->processed->isInvalidClass() ?>"
    data-table="images"
    data-field="x_processed"
    data-value-separator="<?= $Grid->processed->displayValueSeparatorAttribute() ?>"
    <?= $Grid->processed->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->processed->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_images_processed" class="el_images_processed">
<span<?= $Grid->processed->viewAttributes() ?>>
<?= $Grid->processed->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="images" data-field="x_processed" data-hidden="1" name="fimagesgrid$x<?= $Grid->RowIndex ?>_processed" id="fimagesgrid$x<?= $Grid->RowIndex ?>_processed" value="<?= HtmlEncode($Grid->processed->FormValue) ?>">
<input type="hidden" data-table="images" data-field="x_processed" data-hidden="1" data-old name="fimagesgrid$o<?= $Grid->RowIndex ?>_processed[]" id="fimagesgrid$o<?= $Grid->RowIndex ?>_processed[]" value="<?= HtmlEncode($Grid->processed->OldValue) ?>">
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
loadjs.ready(["fimagesgrid","load"], () => fimagesgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fimagesgrid">
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
    ew.addEventHandlers("images");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
    $("#tbl_recordsmaster").hide();
});
</script>
<?php } ?>
