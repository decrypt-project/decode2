<?php

namespace PHPMaker2023\decryptweb23;

// Set up and run Grid object
$Grid = Container("ToolconfigGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var ftoolconfiggrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { toolconfig: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftoolconfiggrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["project_id", [fields.project_id.visible && fields.project_id.required ? ew.Validators.required(fields.project_id.caption) : null], fields.project_id.isInvalid],
            ["toolconfigkey_id", [fields.toolconfigkey_id.visible && fields.toolconfigkey_id.required ? ew.Validators.required(fields.toolconfigkey_id.caption) : null], fields.toolconfigkey_id.isInvalid],
            ["configvalue", [fields.configvalue.visible && fields.configvalue.required ? ew.Validators.required(fields.configvalue.caption) : null], fields.configvalue.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["project_id",false],["toolconfigkey_id",false],["configvalue",false]];
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
            "project_id": <?= $Grid->project_id->toClientList($Grid) ?>,
            "toolconfigkey_id": <?= $Grid->toolconfigkey_id->toClientList($Grid) ?>,
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
<div id="ftoolconfiggrid" class="ew-form ew-list-form">
<div id="gmp_toolconfig" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_toolconfiggrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->project_id->Visible) { // project_id ?>
        <th data-name="project_id" class="<?= $Grid->project_id->headerCellClass() ?>"><div id="elh_toolconfig_project_id" class="toolconfig_project_id"><?= $Grid->renderFieldHeader($Grid->project_id) ?></div></th>
<?php } ?>
<?php if ($Grid->toolconfigkey_id->Visible) { // toolconfigkey_id ?>
        <th data-name="toolconfigkey_id" class="<?= $Grid->toolconfigkey_id->headerCellClass() ?>"><div id="elh_toolconfig_toolconfigkey_id" class="toolconfig_toolconfigkey_id"><?= $Grid->renderFieldHeader($Grid->toolconfigkey_id) ?></div></th>
<?php } ?>
<?php if ($Grid->configvalue->Visible) { // configvalue ?>
        <th data-name="configvalue" class="<?= $Grid->configvalue->headerCellClass() ?>"><div id="elh_toolconfig_configvalue" class="toolconfig_configvalue"><?= $Grid->renderFieldHeader($Grid->configvalue) ?></div></th>
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
    <?php if ($Grid->project_id->Visible) { // project_id ?>
        <td data-name="project_id"<?= $Grid->project_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->project_id->getSessionValue() != "") { ?>
<span<?= $Grid->project_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->project_id->getDisplayValue($Grid->project_id->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_project_id" name="x<?= $Grid->RowIndex ?>_project_id" value="<?= HtmlEncode($Grid->project_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfig_project_id" class="el_toolconfig_project_id">
    <select
        id="x<?= $Grid->RowIndex ?>_project_id"
        name="x<?= $Grid->RowIndex ?>_project_id"
        class="form-select ew-select<?= $Grid->project_id->isInvalidClass() ?>"
        <?php if (!$Grid->project_id->IsNativeSelect) { ?>
        data-select2-id="ftoolconfiggrid_x<?= $Grid->RowIndex ?>_project_id"
        <?php } ?>
        data-table="toolconfig"
        data-field="x_project_id"
        data-value-separator="<?= $Grid->project_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->project_id->getPlaceHolder()) ?>"
        <?= $Grid->project_id->editAttributes() ?>>
        <?= $Grid->project_id->selectOptionListHtml("x{$Grid->RowIndex}_project_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->project_id->getErrorMessage() ?></div>
<?= $Grid->project_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_project_id") ?>
<?php if (!$Grid->project_id->IsNativeSelect) { ?>
<script>
loadjs.ready("ftoolconfiggrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_project_id", selectId: "ftoolconfiggrid_x<?= $Grid->RowIndex ?>_project_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftoolconfiggrid.lists.project_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_project_id", form: "ftoolconfiggrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_project_id", form: "ftoolconfiggrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.toolconfig.fields.project_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<input type="hidden" data-table="toolconfig" data-field="x_project_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_project_id" id="o<?= $Grid->RowIndex ?>_project_id" value="<?= HtmlEncode($Grid->project_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->project_id->getSessionValue() != "") { ?>
<span<?= $Grid->project_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->project_id->getDisplayValue($Grid->project_id->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_project_id" name="x<?= $Grid->RowIndex ?>_project_id" value="<?= HtmlEncode($Grid->project_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfig_project_id" class="el_toolconfig_project_id">
    <select
        id="x<?= $Grid->RowIndex ?>_project_id"
        name="x<?= $Grid->RowIndex ?>_project_id"
        class="form-select ew-select<?= $Grid->project_id->isInvalidClass() ?>"
        <?php if (!$Grid->project_id->IsNativeSelect) { ?>
        data-select2-id="ftoolconfiggrid_x<?= $Grid->RowIndex ?>_project_id"
        <?php } ?>
        data-table="toolconfig"
        data-field="x_project_id"
        data-value-separator="<?= $Grid->project_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->project_id->getPlaceHolder()) ?>"
        <?= $Grid->project_id->editAttributes() ?>>
        <?= $Grid->project_id->selectOptionListHtml("x{$Grid->RowIndex}_project_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->project_id->getErrorMessage() ?></div>
<?= $Grid->project_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_project_id") ?>
<?php if (!$Grid->project_id->IsNativeSelect) { ?>
<script>
loadjs.ready("ftoolconfiggrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_project_id", selectId: "ftoolconfiggrid_x<?= $Grid->RowIndex ?>_project_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftoolconfiggrid.lists.project_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_project_id", form: "ftoolconfiggrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_project_id", form: "ftoolconfiggrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.toolconfig.fields.project_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfig_project_id" class="el_toolconfig_project_id">
<span<?= $Grid->project_id->viewAttributes() ?>>
<?= $Grid->project_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="toolconfig" data-field="x_project_id" data-hidden="1" name="ftoolconfiggrid$x<?= $Grid->RowIndex ?>_project_id" id="ftoolconfiggrid$x<?= $Grid->RowIndex ?>_project_id" value="<?= HtmlEncode($Grid->project_id->FormValue) ?>">
<input type="hidden" data-table="toolconfig" data-field="x_project_id" data-hidden="1" data-old name="ftoolconfiggrid$o<?= $Grid->RowIndex ?>_project_id" id="ftoolconfiggrid$o<?= $Grid->RowIndex ?>_project_id" value="<?= HtmlEncode($Grid->project_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->toolconfigkey_id->Visible) { // toolconfigkey_id ?>
        <td data-name="toolconfigkey_id"<?= $Grid->toolconfigkey_id->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->toolconfigkey_id->getSessionValue() != "") { ?>
<span<?= $Grid->toolconfigkey_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->toolconfigkey_id->getDisplayValue($Grid->toolconfigkey_id->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_toolconfigkey_id" name="x<?= $Grid->RowIndex ?>_toolconfigkey_id" value="<?= HtmlEncode($Grid->toolconfigkey_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfig_toolconfigkey_id" class="el_toolconfig_toolconfigkey_id">
    <select
        id="x<?= $Grid->RowIndex ?>_toolconfigkey_id"
        name="x<?= $Grid->RowIndex ?>_toolconfigkey_id"
        class="form-select ew-select<?= $Grid->toolconfigkey_id->isInvalidClass() ?>"
        <?php if (!$Grid->toolconfigkey_id->IsNativeSelect) { ?>
        data-select2-id="ftoolconfiggrid_x<?= $Grid->RowIndex ?>_toolconfigkey_id"
        <?php } ?>
        data-table="toolconfig"
        data-field="x_toolconfigkey_id"
        data-value-separator="<?= $Grid->toolconfigkey_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->toolconfigkey_id->getPlaceHolder()) ?>"
        <?= $Grid->toolconfigkey_id->editAttributes() ?>>
        <?= $Grid->toolconfigkey_id->selectOptionListHtml("x{$Grid->RowIndex}_toolconfigkey_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->toolconfigkey_id->getErrorMessage() ?></div>
<?= $Grid->toolconfigkey_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_toolconfigkey_id") ?>
<?php if (!$Grid->toolconfigkey_id->IsNativeSelect) { ?>
<script>
loadjs.ready("ftoolconfiggrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_toolconfigkey_id", selectId: "ftoolconfiggrid_x<?= $Grid->RowIndex ?>_toolconfigkey_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftoolconfiggrid.lists.toolconfigkey_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_toolconfigkey_id", form: "ftoolconfiggrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_toolconfigkey_id", form: "ftoolconfiggrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.toolconfig.fields.toolconfigkey_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<input type="hidden" data-table="toolconfig" data-field="x_toolconfigkey_id" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_toolconfigkey_id" id="o<?= $Grid->RowIndex ?>_toolconfigkey_id" value="<?= HtmlEncode($Grid->toolconfigkey_id->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->toolconfigkey_id->getSessionValue() != "") { ?>
<span<?= $Grid->toolconfigkey_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->toolconfigkey_id->getDisplayValue($Grid->toolconfigkey_id->ViewValue) ?></span></span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_toolconfigkey_id" name="x<?= $Grid->RowIndex ?>_toolconfigkey_id" value="<?= HtmlEncode($Grid->toolconfigkey_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfig_toolconfigkey_id" class="el_toolconfig_toolconfigkey_id">
    <select
        id="x<?= $Grid->RowIndex ?>_toolconfigkey_id"
        name="x<?= $Grid->RowIndex ?>_toolconfigkey_id"
        class="form-select ew-select<?= $Grid->toolconfigkey_id->isInvalidClass() ?>"
        <?php if (!$Grid->toolconfigkey_id->IsNativeSelect) { ?>
        data-select2-id="ftoolconfiggrid_x<?= $Grid->RowIndex ?>_toolconfigkey_id"
        <?php } ?>
        data-table="toolconfig"
        data-field="x_toolconfigkey_id"
        data-value-separator="<?= $Grid->toolconfigkey_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->toolconfigkey_id->getPlaceHolder()) ?>"
        <?= $Grid->toolconfigkey_id->editAttributes() ?>>
        <?= $Grid->toolconfigkey_id->selectOptionListHtml("x{$Grid->RowIndex}_toolconfigkey_id") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->toolconfigkey_id->getErrorMessage() ?></div>
<?= $Grid->toolconfigkey_id->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_toolconfigkey_id") ?>
<?php if (!$Grid->toolconfigkey_id->IsNativeSelect) { ?>
<script>
loadjs.ready("ftoolconfiggrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_toolconfigkey_id", selectId: "ftoolconfiggrid_x<?= $Grid->RowIndex ?>_toolconfigkey_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftoolconfiggrid.lists.toolconfigkey_id?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_toolconfigkey_id", form: "ftoolconfiggrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_toolconfigkey_id", form: "ftoolconfiggrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.toolconfig.fields.toolconfigkey_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfig_toolconfigkey_id" class="el_toolconfig_toolconfigkey_id">
<span<?= $Grid->toolconfigkey_id->viewAttributes() ?>>
<?= $Grid->toolconfigkey_id->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="toolconfig" data-field="x_toolconfigkey_id" data-hidden="1" name="ftoolconfiggrid$x<?= $Grid->RowIndex ?>_toolconfigkey_id" id="ftoolconfiggrid$x<?= $Grid->RowIndex ?>_toolconfigkey_id" value="<?= HtmlEncode($Grid->toolconfigkey_id->FormValue) ?>">
<input type="hidden" data-table="toolconfig" data-field="x_toolconfigkey_id" data-hidden="1" data-old name="ftoolconfiggrid$o<?= $Grid->RowIndex ?>_toolconfigkey_id" id="ftoolconfiggrid$o<?= $Grid->RowIndex ?>_toolconfigkey_id" value="<?= HtmlEncode($Grid->toolconfigkey_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->configvalue->Visible) { // configvalue ?>
        <td data-name="configvalue"<?= $Grid->configvalue->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfig_configvalue" class="el_toolconfig_configvalue">
<input type="<?= $Grid->configvalue->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_configvalue" id="x<?= $Grid->RowIndex ?>_configvalue" data-table="toolconfig" data-field="x_configvalue" value="<?= $Grid->configvalue->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Grid->configvalue->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->configvalue->formatPattern()) ?>"<?= $Grid->configvalue->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->configvalue->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="toolconfig" data-field="x_configvalue" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_configvalue" id="o<?= $Grid->RowIndex ?>_configvalue" value="<?= HtmlEncode($Grid->configvalue->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfig_configvalue" class="el_toolconfig_configvalue">
<input type="<?= $Grid->configvalue->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_configvalue" id="x<?= $Grid->RowIndex ?>_configvalue" data-table="toolconfig" data-field="x_configvalue" value="<?= $Grid->configvalue->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Grid->configvalue->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->configvalue->formatPattern()) ?>"<?= $Grid->configvalue->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->configvalue->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_toolconfig_configvalue" class="el_toolconfig_configvalue">
<span<?= $Grid->configvalue->viewAttributes() ?>>
<?= $Grid->configvalue->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="toolconfig" data-field="x_configvalue" data-hidden="1" name="ftoolconfiggrid$x<?= $Grid->RowIndex ?>_configvalue" id="ftoolconfiggrid$x<?= $Grid->RowIndex ?>_configvalue" value="<?= HtmlEncode($Grid->configvalue->FormValue) ?>">
<input type="hidden" data-table="toolconfig" data-field="x_configvalue" data-hidden="1" data-old name="ftoolconfiggrid$o<?= $Grid->RowIndex ?>_configvalue" id="ftoolconfiggrid$o<?= $Grid->RowIndex ?>_configvalue" value="<?= HtmlEncode($Grid->configvalue->OldValue) ?>">
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
loadjs.ready(["ftoolconfiggrid","load"], () => ftoolconfiggrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="ftoolconfiggrid">
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
    ew.addEventHandlers("toolconfig");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
