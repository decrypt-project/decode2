<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ToolconfigDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { toolconfig: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var ftoolconfigdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftoolconfigdelete")
        .setPageId("delete")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="ftoolconfigdelete" id="ftoolconfigdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="toolconfig">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->project_id->Visible) { // project_id ?>
        <th class="<?= $Page->project_id->headerCellClass() ?>"><span id="elh_toolconfig_project_id" class="toolconfig_project_id"><?= $Page->project_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->toolconfigkey_id->Visible) { // toolconfigkey_id ?>
        <th class="<?= $Page->toolconfigkey_id->headerCellClass() ?>"><span id="elh_toolconfig_toolconfigkey_id" class="toolconfig_toolconfigkey_id"><?= $Page->toolconfigkey_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->configvalue->Visible) { // configvalue ?>
        <th class="<?= $Page->configvalue->headerCellClass() ?>"><span id="elh_toolconfig_configvalue" class="toolconfig_configvalue"><?= $Page->configvalue->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->project_id->Visible) { // project_id ?>
        <td<?= $Page->project_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->project_id->viewAttributes() ?>>
<?= $Page->project_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->toolconfigkey_id->Visible) { // toolconfigkey_id ?>
        <td<?= $Page->toolconfigkey_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->toolconfigkey_id->viewAttributes() ?>>
<?= $Page->toolconfigkey_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->configvalue->Visible) { // configvalue ?>
        <td<?= $Page->configvalue->cellAttributes() ?>>
<span id="">
<span<?= $Page->configvalue->viewAttributes() ?>>
<?= $Page->configvalue->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
