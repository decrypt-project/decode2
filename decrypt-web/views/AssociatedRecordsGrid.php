<?php

namespace PHPMaker2023\decryptweb23;

// Set up and run Grid object
$Grid = Container("AssociatedRecordsGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fassociated_recordsgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { associated_records: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fassociated_recordsgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["linked_record_id", [fields.linked_record_id.visible && fields.linked_record_id.required ? ew.Validators.required(fields.linked_record_id.caption) : null], fields.linked_record_id.isInvalid],
            ["linktype", [fields.linktype.visible && fields.linktype.required ? ew.Validators.required(fields.linktype.caption) : null], fields.linktype.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["linked_record_id",false],["linktype",false]];
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
            "linked_record_id": <?= $Grid->linked_record_id->toClientList($Grid) ?>,
            "linktype": <?= $Grid->linktype->toClientList($Grid) ?>,
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
<div id="fassociated_recordsgrid" class="ew-form ew-list-form">
<div id="gmp_associated_records" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_associated_recordsgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->linked_record_id->Visible) { // linked_record_id ?>
        <th data-name="linked_record_id" class="<?= $Grid->linked_record_id->headerCellClass() ?>"><div id="elh_associated_records_linked_record_id" class="associated_records_linked_record_id"><?= $Grid->renderFieldHeader($Grid->linked_record_id) ?></div></th>
<?php } ?>
<?php if ($Grid->linktype->Visible) { // linktype ?>
        <th data-name="linktype" class="<?= $Grid->linktype->headerCellClass() ?>"><div id="elh_associated_records_linktype" class="associated_records_linktype"><?= $Grid->renderFieldHeader($Grid->linktype) ?></div></th>
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
    <?php if ($Grid->linked_record_id->Visible) { // linked_record_id ?>
        <td data-name="linked_record_id"<?= $Grid->linked_record_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_associated_records_linked_record_id" class="el_associated_records_linked_record_id">
    <select
        id="x<?= $Grid->RowIndex ?>_linked_record_id"
        name="x<?= $Grid->RowIndex ?>_linked_record_id"
        class="form-control ew-select<?= $Grid->linked_record_id->isInvalidClass() ?>"
        data-select2-id="fassociated_recordsgrid_x<?= $Grid->RowIndex ?>_linked_record_id"
        data-table="associated_records"
        data-field="x_linked_record_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->linked_record_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->linked_record_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->linked_record_id->getPlaceHolder()) ?>"
        <?= $Grid->linked_record_id->editAttributes() ?>>
        <?= $Grid->linked_record_id->selectOptionListHtml("x{$Grid->RowIndex}_linked_record_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->linked_record_id->getErrorMessage() ?></div>
<?= $Grid->linked_record_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_linked_record_id") ?>
<script>
loadjs.ready("fassociated_recordsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_linked_record_id", selectId: "fassociated_recordsgrid_x<?= $Grid->RowIndex ?>_linked_record_id" };
    if (fassociated_recordsgrid.lists.linked_record_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_linked_record_id", form: "fassociated_recordsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_linked_record_id", form: "fassociated_recordsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.associated_records.fields.linked_record_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="associated_records" data-field="x_linked_record_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_linked_record_id" id="o<?= $Grid->RowIndex ?>_linked_record_id" value="<?= HtmlEncode($Grid->linked_record_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_associated_records_linked_record_id" class="el_associated_records_linked_record_id">
    <select
        id="x<?= $Grid->RowIndex ?>_linked_record_id"
        name="x<?= $Grid->RowIndex ?>_linked_record_id"
        class="form-control ew-select<?= $Grid->linked_record_id->isInvalidClass() ?>"
        data-select2-id="fassociated_recordsgrid_x<?= $Grid->RowIndex ?>_linked_record_id"
        data-table="associated_records"
        data-field="x_linked_record_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->linked_record_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->linked_record_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->linked_record_id->getPlaceHolder()) ?>"
        <?= $Grid->linked_record_id->editAttributes() ?>>
        <?= $Grid->linked_record_id->selectOptionListHtml("x{$Grid->RowIndex}_linked_record_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->linked_record_id->getErrorMessage() ?></div>
<?= $Grid->linked_record_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_linked_record_id") ?>
<script>
loadjs.ready("fassociated_recordsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_linked_record_id", selectId: "fassociated_recordsgrid_x<?= $Grid->RowIndex ?>_linked_record_id" };
    if (fassociated_recordsgrid.lists.linked_record_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_linked_record_id", form: "fassociated_recordsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_linked_record_id", form: "fassociated_recordsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.associated_records.fields.linked_record_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_associated_records_linked_record_id" class="el_associated_records_linked_record_id">
<span<?= $Grid->linked_record_id->viewAttributes() ?>>
<?= $Grid->linked_record_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="associated_records" data-field="x_linked_record_id" data-hidden="1" name="fassociated_recordsgrid$x<?= $Grid->RowIndex ?>_linked_record_id" id="fassociated_recordsgrid$x<?= $Grid->RowIndex ?>_linked_record_id" value="<?= HtmlEncode($Grid->linked_record_id->FormValue) ?>">
<input type="hidden" data-table="associated_records" data-field="x_linked_record_id" data-hidden="1" data-old name="fassociated_recordsgrid$o<?= $Grid->RowIndex ?>_linked_record_id" id="fassociated_recordsgrid$o<?= $Grid->RowIndex ?>_linked_record_id" value="<?= HtmlEncode($Grid->linked_record_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->linktype->Visible) { // linktype ?>
        <td data-name="linktype"<?= $Grid->linktype->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_associated_records_linktype" class="el_associated_records_linktype">
    <select
        id="x<?= $Grid->RowIndex ?>_linktype"
        name="x<?= $Grid->RowIndex ?>_linktype"
        class="form-select ew-select<?= $Grid->linktype->isInvalidClass() ?>"
        <?php if (!$Grid->linktype->IsNativeSelect) { ?>
        data-select2-id="fassociated_recordsgrid_x<?= $Grid->RowIndex ?>_linktype"
        <?php } ?>
        data-table="associated_records"
        data-field="x_linktype"
        data-value-separator="<?= $Grid->linktype->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->linktype->getPlaceHolder()) ?>"
        <?= $Grid->linktype->editAttributes() ?>>
        <?= $Grid->linktype->selectOptionListHtml("x{$Grid->RowIndex}_linktype") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->linktype->getErrorMessage() ?></div>
<?php if (!$Grid->linktype->IsNativeSelect) { ?>
<script>
loadjs.ready("fassociated_recordsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_linktype", selectId: "fassociated_recordsgrid_x<?= $Grid->RowIndex ?>_linktype" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fassociated_recordsgrid.lists.linktype?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_linktype", form: "fassociated_recordsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_linktype", form: "fassociated_recordsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.associated_records.fields.linktype.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="associated_records" data-field="x_linktype" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_linktype" id="o<?= $Grid->RowIndex ?>_linktype" value="<?= HtmlEncode($Grid->linktype->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_associated_records_linktype" class="el_associated_records_linktype">
    <select
        id="x<?= $Grid->RowIndex ?>_linktype"
        name="x<?= $Grid->RowIndex ?>_linktype"
        class="form-select ew-select<?= $Grid->linktype->isInvalidClass() ?>"
        <?php if (!$Grid->linktype->IsNativeSelect) { ?>
        data-select2-id="fassociated_recordsgrid_x<?= $Grid->RowIndex ?>_linktype"
        <?php } ?>
        data-table="associated_records"
        data-field="x_linktype"
        data-value-separator="<?= $Grid->linktype->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->linktype->getPlaceHolder()) ?>"
        <?= $Grid->linktype->editAttributes() ?>>
        <?= $Grid->linktype->selectOptionListHtml("x{$Grid->RowIndex}_linktype") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->linktype->getErrorMessage() ?></div>
<?php if (!$Grid->linktype->IsNativeSelect) { ?>
<script>
loadjs.ready("fassociated_recordsgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_linktype", selectId: "fassociated_recordsgrid_x<?= $Grid->RowIndex ?>_linktype" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fassociated_recordsgrid.lists.linktype?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_linktype", form: "fassociated_recordsgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_linktype", form: "fassociated_recordsgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.associated_records.fields.linktype.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_associated_records_linktype" class="el_associated_records_linktype">
<span<?= $Grid->linktype->viewAttributes() ?>>
<?= $Grid->linktype->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="associated_records" data-field="x_linktype" data-hidden="1" name="fassociated_recordsgrid$x<?= $Grid->RowIndex ?>_linktype" id="fassociated_recordsgrid$x<?= $Grid->RowIndex ?>_linktype" value="<?= HtmlEncode($Grid->linktype->FormValue) ?>">
<input type="hidden" data-table="associated_records" data-field="x_linktype" data-hidden="1" data-old name="fassociated_recordsgrid$o<?= $Grid->RowIndex ?>_linktype" id="fassociated_recordsgrid$o<?= $Grid->RowIndex ?>_linktype" value="<?= HtmlEncode($Grid->linktype->OldValue) ?>">
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
loadjs.ready(["fassociated_recordsgrid","load"], () => fassociated_recordsgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fassociated_recordsgrid">
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
    ew.addEventHandlers("associated_records");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
});
</script>
<?php } ?>
