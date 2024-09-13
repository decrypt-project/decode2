<?php

namespace PHPMaker2023\decryptweb23;

// Set up and run Grid object
$Grid = Container("TranscriptionsGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var ftranscriptionsgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { transcriptions: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftranscriptionsgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["image_id", [fields.image_id.visible && fields.image_id.required ? ew.Validators.required(fields.image_id.caption) : null, ew.Validators.integer], fields.image_id.isInvalid],
            ["description", [fields.description.visible && fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["image_id",false],["description",false]];
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
<div id="ftranscriptionsgrid" class="ew-form ew-list-form">
<div id="gmp_transcriptions" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_transcriptionsgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->image_id->Visible) { // image_id ?>
        <th data-name="image_id" class="<?= $Grid->image_id->headerCellClass() ?>"><div id="elh_transcriptions_image_id" class="transcriptions_image_id"><?= $Grid->renderFieldHeader($Grid->image_id) ?></div></th>
<?php } ?>
<?php if ($Grid->description->Visible) { // description ?>
        <th data-name="description" class="<?= $Grid->description->headerCellClass() ?>"><div id="elh_transcriptions_description" class="transcriptions_description"><?= $Grid->renderFieldHeader($Grid->description) ?></div></th>
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
    <?php if ($Grid->image_id->Visible) { // image_id ?>
        <td data-name="image_id"<?= $Grid->image_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->image_id->getSessionValue() != "") { ?>
<span<?= $Grid->image_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->image_id->getDisplayValue($Grid->image_id->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_image_id" name="x<?= $Grid->RowIndex ?>_image_id" value="<?= HtmlEncode($Grid->image_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_transcriptions_image_id" class="el_transcriptions_image_id">
<input type="<?= $Grid->image_id->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_image_id" id="x<?= $Grid->RowIndex ?>_image_id" data-table="transcriptions" data-field="x_image_id" value="<?= $Grid->image_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->image_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->image_id->formatPattern()) ?>"<?= $Grid->image_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->image_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="transcriptions" data-field="x_image_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_image_id" id="o<?= $Grid->RowIndex ?>_image_id" value="<?= HtmlEncode($Grid->image_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->image_id->getSessionValue() != "") { ?>
<span<?= $Grid->image_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->image_id->getDisplayValue($Grid->image_id->ViewValue))) ?>"></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_image_id" name="x<?= $Grid->RowIndex ?>_image_id" value="<?= HtmlEncode($Grid->image_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_transcriptions_image_id" class="el_transcriptions_image_id">
<input type="<?= $Grid->image_id->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_image_id" id="x<?= $Grid->RowIndex ?>_image_id" data-table="transcriptions" data-field="x_image_id" value="<?= $Grid->image_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->image_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->image_id->formatPattern()) ?>"<?= $Grid->image_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->image_id->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_transcriptions_image_id" class="el_transcriptions_image_id">
<span<?= $Grid->image_id->viewAttributes() ?>>
<?= $Grid->image_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="transcriptions" data-field="x_image_id" data-hidden="1" name="ftranscriptionsgrid$x<?= $Grid->RowIndex ?>_image_id" id="ftranscriptionsgrid$x<?= $Grid->RowIndex ?>_image_id" value="<?= HtmlEncode($Grid->image_id->FormValue) ?>">
<input type="hidden" data-table="transcriptions" data-field="x_image_id" data-hidden="1" data-old name="ftranscriptionsgrid$o<?= $Grid->RowIndex ?>_image_id" id="ftranscriptionsgrid$o<?= $Grid->RowIndex ?>_image_id" value="<?= HtmlEncode($Grid->image_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->description->Visible) { // description ?>
        <td data-name="description"<?= $Grid->description->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_transcriptions_description" class="el_transcriptions_description">
<input type="<?= $Grid->description->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_description" id="x<?= $Grid->RowIndex ?>_description" data-table="transcriptions" data-field="x_description" value="<?= $Grid->description->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Grid->description->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->description->formatPattern()) ?>"<?= $Grid->description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->description->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="transcriptions" data-field="x_description" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_description" id="o<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_transcriptions_description" class="el_transcriptions_description">
<input type="<?= $Grid->description->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_description" id="x<?= $Grid->RowIndex ?>_description" data-table="transcriptions" data-field="x_description" value="<?= $Grid->description->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Grid->description->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->description->formatPattern()) ?>"<?= $Grid->description->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->description->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_transcriptions_description" class="el_transcriptions_description">
<span<?= $Grid->description->viewAttributes() ?>>
<?= $Grid->description->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="transcriptions" data-field="x_description" data-hidden="1" name="ftranscriptionsgrid$x<?= $Grid->RowIndex ?>_description" id="ftranscriptionsgrid$x<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->FormValue) ?>">
<input type="hidden" data-table="transcriptions" data-field="x_description" data-hidden="1" data-old name="ftranscriptionsgrid$o<?= $Grid->RowIndex ?>_description" id="ftranscriptionsgrid$o<?= $Grid->RowIndex ?>_description" value="<?= HtmlEncode($Grid->description->OldValue) ?>">
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
loadjs.ready(["ftranscriptionsgrid","load"], () => ftranscriptionsgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="ftranscriptionsgrid">
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
    ew.addEventHandlers("transcriptions");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
