<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ToolconfigView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<form name="ftoolconfigview" id="ftoolconfigview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { toolconfig: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var ftoolconfigview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftoolconfigview")
        .setPageId("view")
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
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="toolconfig">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_toolconfig_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_toolconfig_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->project_id->Visible) { // project_id ?>
    <tr id="r_project_id"<?= $Page->project_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_toolconfig_project_id"><?= $Page->project_id->caption() ?></span></td>
        <td data-name="project_id"<?= $Page->project_id->cellAttributes() ?>>
<span id="el_toolconfig_project_id">
<span<?= $Page->project_id->viewAttributes() ?>>
<?= $Page->project_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->toolconfigkey_id->Visible) { // toolconfigkey_id ?>
    <tr id="r_toolconfigkey_id"<?= $Page->toolconfigkey_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_toolconfig_toolconfigkey_id"><?= $Page->toolconfigkey_id->caption() ?></span></td>
        <td data-name="toolconfigkey_id"<?= $Page->toolconfigkey_id->cellAttributes() ?>>
<span id="el_toolconfig_toolconfigkey_id">
<span<?= $Page->toolconfigkey_id->viewAttributes() ?>>
<?= $Page->toolconfigkey_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->configvalue->Visible) { // configvalue ?>
    <tr id="r_configvalue"<?= $Page->configvalue->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_toolconfig_configvalue"><?= $Page->configvalue->caption() ?></span></td>
        <td data-name="configvalue"<?= $Page->configvalue->cellAttributes() ?>>
<span id="el_toolconfig_configvalue">
<span<?= $Page->configvalue->viewAttributes() ?>>
<?= $Page->configvalue->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
