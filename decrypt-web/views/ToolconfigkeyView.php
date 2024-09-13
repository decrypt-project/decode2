<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ToolconfigkeyView = &$Page;
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
<form name="ftoolconfigkeyview" id="ftoolconfigkeyview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { toolconfigkey: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var ftoolconfigkeyview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftoolconfigkeyview")
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
<input type="hidden" name="t" value="toolconfigkey">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_toolconfigkey_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_toolconfigkey_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tool_id->Visible) { // tool_id ?>
    <tr id="r_tool_id"<?= $Page->tool_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_toolconfigkey_tool_id"><?= $Page->tool_id->caption() ?></span></td>
        <td data-name="tool_id"<?= $Page->tool_id->cellAttributes() ?>>
<span id="el_toolconfigkey_tool_id">
<span<?= $Page->tool_id->viewAttributes() ?>>
<?= $Page->tool_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->toolkey->Visible) { // toolkey ?>
    <tr id="r_toolkey"<?= $Page->toolkey->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_toolconfigkey_toolkey"><?= $Page->toolkey->caption() ?></span></td>
        <td data-name="toolkey"<?= $Page->toolkey->cellAttributes() ?>>
<span id="el_toolconfigkey_toolkey">
<span<?= $Page->toolkey->viewAttributes() ?>>
<?= $Page->toolkey->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->helptext->Visible) { // helptext ?>
    <tr id="r_helptext"<?= $Page->helptext->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_toolconfigkey_helptext"><?= $Page->helptext->caption() ?></span></td>
        <td data-name="helptext"<?= $Page->helptext->cellAttributes() ?>>
<span id="el_toolconfigkey_helptext">
<span<?= $Page->helptext->viewAttributes() ?>>
<?= $Page->helptext->getViewValue() ?></span>
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
