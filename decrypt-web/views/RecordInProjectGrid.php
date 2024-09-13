<?php

namespace PHPMaker2023\decryptweb23;

// Set up and run Grid object
$Grid = Container("RecordInProjectGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var frecord_in_projectgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { record_in_project: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("frecord_in_projectgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["record_id", [fields.record_id.visible && fields.record_id.required ? ew.Validators.required(fields.record_id.caption) : null], fields.record_id.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["record_id",false]];
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
            "record_id": <?= $Grid->record_id->toClientList($Grid) ?>,
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
<div id="frecord_in_projectgrid" class="ew-form ew-list-form">
<div id="gmp_record_in_project" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_record_in_projectgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->record_id->Visible) { // record_id ?>
        <th data-name="record_id" class="<?= $Grid->record_id->headerCellClass() ?>"><div id="elh_record_in_project_record_id" class="record_in_project_record_id"><?= $Grid->renderFieldHeader($Grid->record_id) ?></div></th>
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
    <?php if ($Grid->record_id->Visible) { // record_id ?>
        <td data-name="record_id"<?= $Grid->record_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_record_in_project_record_id" class="el_record_in_project_record_id">
    <select
        id="x<?= $Grid->RowIndex ?>_record_id"
        name="x<?= $Grid->RowIndex ?>_record_id"
        class="form-control ew-select<?= $Grid->record_id->isInvalidClass() ?>"
        data-select2-id="frecord_in_projectgrid_x<?= $Grid->RowIndex ?>_record_id"
        data-table="record_in_project"
        data-field="x_record_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->record_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->record_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->record_id->getPlaceHolder()) ?>"
        <?= $Grid->record_id->editAttributes() ?>>
        <?= $Grid->record_id->selectOptionListHtml("x{$Grid->RowIndex}_record_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->record_id->getErrorMessage() ?></div>
<?= $Grid->record_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_record_id") ?>
<script>
loadjs.ready("frecord_in_projectgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_record_id", selectId: "frecord_in_projectgrid_x<?= $Grid->RowIndex ?>_record_id" };
    if (frecord_in_projectgrid.lists.record_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_record_id", form: "frecord_in_projectgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_record_id", form: "frecord_in_projectgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.record_in_project.fields.record_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="record_in_project" data-field="x_record_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_record_id" id="o<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_record_in_project_record_id" class="el_record_in_project_record_id">
    <select
        id="x<?= $Grid->RowIndex ?>_record_id"
        name="x<?= $Grid->RowIndex ?>_record_id"
        class="form-control ew-select<?= $Grid->record_id->isInvalidClass() ?>"
        data-select2-id="frecord_in_projectgrid_x<?= $Grid->RowIndex ?>_record_id"
        data-table="record_in_project"
        data-field="x_record_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->record_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->record_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->record_id->getPlaceHolder()) ?>"
        <?= $Grid->record_id->editAttributes() ?>>
        <?= $Grid->record_id->selectOptionListHtml("x{$Grid->RowIndex}_record_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->record_id->getErrorMessage() ?></div>
<?= $Grid->record_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_record_id") ?>
<script>
loadjs.ready("frecord_in_projectgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_record_id", selectId: "frecord_in_projectgrid_x<?= $Grid->RowIndex ?>_record_id" };
    if (frecord_in_projectgrid.lists.record_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_record_id", form: "frecord_in_projectgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_record_id", form: "frecord_in_projectgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.record_in_project.fields.record_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_record_in_project_record_id" class="el_record_in_project_record_id">
<span<?= $Grid->record_id->viewAttributes() ?>>
<?= $Grid->record_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="record_in_project" data-field="x_record_id" data-hidden="1" name="frecord_in_projectgrid$x<?= $Grid->RowIndex ?>_record_id" id="frecord_in_projectgrid$x<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->FormValue) ?>">
<input type="hidden" data-table="record_in_project" data-field="x_record_id" data-hidden="1" data-old name="frecord_in_projectgrid$o<?= $Grid->RowIndex ?>_record_id" id="frecord_in_projectgrid$o<?= $Grid->RowIndex ?>_record_id" value="<?= HtmlEncode($Grid->record_id->OldValue) ?>">
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
loadjs.ready(["frecord_in_projectgrid","load"], () => frecord_in_projectgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="frecord_in_projectgrid">
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
    ew.addEventHandlers("record_in_project");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
