<?php

namespace PHPMaker2023\decryptweb23;

// Set up and run Grid object
$Grid = Container("ToolconfigkeyGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var ftoolconfigkeygrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { toolconfigkey: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftoolconfigkeygrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["tool_id", [fields.tool_id.visible && fields.tool_id.required ? ew.Validators.required(fields.tool_id.caption) : null], fields.tool_id.isInvalid],
            ["toolkey", [fields.toolkey.visible && fields.toolkey.required ? ew.Validators.required(fields.toolkey.caption) : null], fields.toolkey.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["tool_id",false],["toolkey",false]];
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
            "tool_id": <?= $Grid->tool_id->toClientList($Grid) ?>,
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
<div id="ftoolconfigkeygrid" class="ew-form ew-list-form">
<div id="gmp_toolconfigkey" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_toolconfigkeygrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->tool_id->Visible) { // tool_id ?>
        <th data-name="tool_id" class="<?= $Grid->tool_id->headerCellClass() ?>"><div id="elh_toolconfigkey_tool_id" class="toolconfigkey_tool_id"><?= $Grid->renderFieldHeader($Grid->tool_id) ?></div></th>
<?php } ?>
<?php if ($Grid->toolkey->Visible) { // toolkey ?>
        <th data-name="toolkey" class="<?= $Grid->toolkey->headerCellClass() ?>"><div id="elh_toolconfigkey_toolkey" class="toolconfigkey_toolkey"><?= $Grid->renderFieldHeader($Grid->toolkey) ?></div></th>
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
    <?php if ($Grid->tool_id->Visible) { // tool_id ?>
        <td data-name="tool_id"<?= $Grid->tool_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfigkey_tool_id" class="el_toolconfigkey_tool_id">
    <select
        id="x<?= $Grid->RowIndex ?>_tool_id"
        name="x<?= $Grid->RowIndex ?>_tool_id"
        class="form-select ew-select<?= $Grid->tool_id->isInvalidClass() ?>"
        <?php if (!$Grid->tool_id->IsNativeSelect) { ?>
        data-select2-id="ftoolconfigkeygrid_x<?= $Grid->RowIndex ?>_tool_id"
        <?php } ?>
        data-table="toolconfigkey"
        data-field="x_tool_id"
        data-value-separator="<?= $Grid->tool_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tool_id->getPlaceHolder()) ?>"
        <?= $Grid->tool_id->editAttributes() ?>>
        <?= $Grid->tool_id->selectOptionListHtml("x{$Grid->RowIndex}_tool_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tool_id->getErrorMessage() ?></div>
<?= $Grid->tool_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tool_id") ?>
<?php if (!$Grid->tool_id->IsNativeSelect) { ?>
<script>
loadjs.ready("ftoolconfigkeygrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tool_id", selectId: "ftoolconfigkeygrid_x<?= $Grid->RowIndex ?>_tool_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftoolconfigkeygrid.lists.tool_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tool_id", form: "ftoolconfigkeygrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tool_id", form: "ftoolconfigkeygrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.toolconfigkey.fields.tool_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="toolconfigkey" data-field="x_tool_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_tool_id" id="o<?= $Grid->RowIndex ?>_tool_id" value="<?= HtmlEncode($Grid->tool_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfigkey_tool_id" class="el_toolconfigkey_tool_id">
    <select
        id="x<?= $Grid->RowIndex ?>_tool_id"
        name="x<?= $Grid->RowIndex ?>_tool_id"
        class="form-select ew-select<?= $Grid->tool_id->isInvalidClass() ?>"
        <?php if (!$Grid->tool_id->IsNativeSelect) { ?>
        data-select2-id="ftoolconfigkeygrid_x<?= $Grid->RowIndex ?>_tool_id"
        <?php } ?>
        data-table="toolconfigkey"
        data-field="x_tool_id"
        data-value-separator="<?= $Grid->tool_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->tool_id->getPlaceHolder()) ?>"
        <?= $Grid->tool_id->editAttributes() ?>>
        <?= $Grid->tool_id->selectOptionListHtml("x{$Grid->RowIndex}_tool_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->tool_id->getErrorMessage() ?></div>
<?= $Grid->tool_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_tool_id") ?>
<?php if (!$Grid->tool_id->IsNativeSelect) { ?>
<script>
loadjs.ready("ftoolconfigkeygrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_tool_id", selectId: "ftoolconfigkeygrid_x<?= $Grid->RowIndex ?>_tool_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftoolconfigkeygrid.lists.tool_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_tool_id", form: "ftoolconfigkeygrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_tool_id", form: "ftoolconfigkeygrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.toolconfigkey.fields.tool_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfigkey_tool_id" class="el_toolconfigkey_tool_id">
<span<?= $Grid->tool_id->viewAttributes() ?>>
<?= $Grid->tool_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="toolconfigkey" data-field="x_tool_id" data-hidden="1" name="ftoolconfigkeygrid$x<?= $Grid->RowIndex ?>_tool_id" id="ftoolconfigkeygrid$x<?= $Grid->RowIndex ?>_tool_id" value="<?= HtmlEncode($Grid->tool_id->FormValue) ?>">
<input type="hidden" data-table="toolconfigkey" data-field="x_tool_id" data-hidden="1" data-old name="ftoolconfigkeygrid$o<?= $Grid->RowIndex ?>_tool_id" id="ftoolconfigkeygrid$o<?= $Grid->RowIndex ?>_tool_id" value="<?= HtmlEncode($Grid->tool_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->toolkey->Visible) { // toolkey ?>
        <td data-name="toolkey"<?= $Grid->toolkey->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfigkey_toolkey" class="el_toolconfigkey_toolkey">
<input type="<?= $Grid->toolkey->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_toolkey" id="x<?= $Grid->RowIndex ?>_toolkey" data-table="toolconfigkey" data-field="x_toolkey" value="<?= $Grid->toolkey->EditValue ?>" size="30" maxlength="16" placeholder="<?= HtmlEncode($Grid->toolkey->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->toolkey->formatPattern()) ?>"<?= $Grid->toolkey->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->toolkey->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="toolconfigkey" data-field="x_toolkey" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_toolkey" id="o<?= $Grid->RowIndex ?>_toolkey" value="<?= HtmlEncode($Grid->toolkey->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfigkey_toolkey" class="el_toolconfigkey_toolkey">
<input type="<?= $Grid->toolkey->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_toolkey" id="x<?= $Grid->RowIndex ?>_toolkey" data-table="toolconfigkey" data-field="x_toolkey" value="<?= $Grid->toolkey->EditValue ?>" size="30" maxlength="16" placeholder="<?= HtmlEncode($Grid->toolkey->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->toolkey->formatPattern()) ?>"<?= $Grid->toolkey->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->toolkey->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfigkey_toolkey" class="el_toolconfigkey_toolkey">
<span<?= $Grid->toolkey->viewAttributes() ?>>
<?= $Grid->toolkey->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="toolconfigkey" data-field="x_toolkey" data-hidden="1" name="ftoolconfigkeygrid$x<?= $Grid->RowIndex ?>_toolkey" id="ftoolconfigkeygrid$x<?= $Grid->RowIndex ?>_toolkey" value="<?= HtmlEncode($Grid->toolkey->FormValue) ?>">
<input type="hidden" data-table="toolconfigkey" data-field="x_toolkey" data-hidden="1" data-old name="ftoolconfigkeygrid$o<?= $Grid->RowIndex ?>_toolkey" id="ftoolconfigkeygrid$o<?= $Grid->RowIndex ?>_toolkey" value="<?= HtmlEncode($Grid->toolkey->OldValue) ?>">
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
loadjs.ready(["ftoolconfigkeygrid","load"], () => ftoolconfigkeygrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="ftoolconfigkeygrid">
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
    ew.addEventHandlers("toolconfigkey");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
