<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$AuditTrailView = &$Page;
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
<form name="faudit_trailview" id="faudit_trailview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { audit_trail: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var faudit_trailview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("faudit_trailview")
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
<input type="hidden" name="t" value="audit_trail">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audit_trail_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_audit_trail_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->datetime->Visible) { // datetime ?>
    <tr id="r_datetime"<?= $Page->datetime->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audit_trail_datetime"><?= $Page->datetime->caption() ?></span></td>
        <td data-name="datetime"<?= $Page->datetime->cellAttributes() ?>>
<span id="el_audit_trail_datetime">
<span<?= $Page->datetime->viewAttributes() ?>>
<?= $Page->datetime->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->script->Visible) { // script ?>
    <tr id="r_script"<?= $Page->script->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audit_trail_script"><?= $Page->script->caption() ?></span></td>
        <td data-name="script"<?= $Page->script->cellAttributes() ?>>
<span id="el_audit_trail_script">
<span<?= $Page->script->viewAttributes() ?>>
<?= $Page->script->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user->Visible) { // user ?>
    <tr id="r_user"<?= $Page->user->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audit_trail_user"><?= $Page->user->caption() ?></span></td>
        <td data-name="user"<?= $Page->user->cellAttributes() ?>>
<span id="el_audit_trail_user">
<span<?= $Page->user->viewAttributes() ?>>
<?= $Page->user->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
    <tr id="r__action"<?= $Page->_action->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audit_trail__action"><?= $Page->_action->caption() ?></span></td>
        <td data-name="_action"<?= $Page->_action->cellAttributes() ?>>
<span id="el_audit_trail__action">
<span<?= $Page->_action->viewAttributes() ?>>
<?= $Page->_action->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_table->Visible) { // table ?>
    <tr id="r__table"<?= $Page->_table->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audit_trail__table"><?= $Page->_table->caption() ?></span></td>
        <td data-name="_table"<?= $Page->_table->cellAttributes() ?>>
<span id="el_audit_trail__table">
<span<?= $Page->_table->viewAttributes() ?>>
<?= $Page->_table->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->field->Visible) { // field ?>
    <tr id="r_field"<?= $Page->field->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audit_trail_field"><?= $Page->field->caption() ?></span></td>
        <td data-name="field"<?= $Page->field->cellAttributes() ?>>
<span id="el_audit_trail_field">
<span<?= $Page->field->viewAttributes() ?>>
<?= $Page->field->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->keyvalue->Visible) { // keyvalue ?>
    <tr id="r_keyvalue"<?= $Page->keyvalue->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audit_trail_keyvalue"><?= $Page->keyvalue->caption() ?></span></td>
        <td data-name="keyvalue"<?= $Page->keyvalue->cellAttributes() ?>>
<span id="el_audit_trail_keyvalue">
<span<?= $Page->keyvalue->viewAttributes() ?>>
<?= $Page->keyvalue->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->oldvalue->Visible) { // oldvalue ?>
    <tr id="r_oldvalue"<?= $Page->oldvalue->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audit_trail_oldvalue"><?= $Page->oldvalue->caption() ?></span></td>
        <td data-name="oldvalue"<?= $Page->oldvalue->cellAttributes() ?>>
<span id="el_audit_trail_oldvalue">
<span<?= $Page->oldvalue->viewAttributes() ?>>
<?= $Page->oldvalue->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->newvalue->Visible) { // newvalue ?>
    <tr id="r_newvalue"<?= $Page->newvalue->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_audit_trail_newvalue"><?= $Page->newvalue->caption() ?></span></td>
        <td data-name="newvalue"<?= $Page->newvalue->cellAttributes() ?>>
<span id="el_audit_trail_newvalue">
<span<?= $Page->newvalue->viewAttributes() ?>>
<?= $Page->newvalue->getViewValue() ?></span>
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
