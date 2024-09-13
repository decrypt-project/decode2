<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ProjectDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { project: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fprojectdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fprojectdelete")
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
<form name="fprojectdelete" id="fprojectdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="project">
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
<?php if ($Page->id->Visible) { // id ?>
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_project_id" class="project_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
        <th class="<?= $Page->name->headerCellClass() ?>"><span id="elh_project_name" class="project_name"><?= $Page->name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->owner->Visible) { // owner ?>
        <th class="<?= $Page->owner->headerCellClass() ?>"><span id="elh_project_owner" class="project_owner"><?= $Page->owner->caption() ?></span></th>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
        <th class="<?= $Page->creation_date->headerCellClass() ?>"><span id="elh_project_creation_date" class="project_creation_date"><?= $Page->creation_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->last_updated->Visible) { // last_updated ?>
        <th class="<?= $Page->last_updated->headerCellClass() ?>"><span id="elh_project_last_updated" class="project_last_updated"><?= $Page->last_updated->caption() ?></span></th>
<?php } ?>
<?php if ($Page->locked_by->Visible) { // locked_by ?>
        <th class="<?= $Page->locked_by->headerCellClass() ?>"><span id="elh_project_locked_by" class="project_locked_by"><?= $Page->locked_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lock_date->Visible) { // lock_date ?>
        <th class="<?= $Page->lock_date->headerCellClass() ?>"><span id="elh_project_lock_date" class="project_lock_date"><?= $Page->lock_date->caption() ?></span></th>
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
<?php if ($Page->id->Visible) { // id ?>
        <td<?= $Page->id->cellAttributes() ?>>
<span id="">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
        <td<?= $Page->name->cellAttributes() ?>>
<span id="">
<span<?= $Page->name->viewAttributes() ?>>
<?= $Page->name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->owner->Visible) { // owner ?>
        <td<?= $Page->owner->cellAttributes() ?>>
<span id="">
<span<?= $Page->owner->viewAttributes() ?>>
<?= $Page->owner->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
        <td<?= $Page->creation_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->creation_date->viewAttributes() ?>>
<?= $Page->creation_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->last_updated->Visible) { // last_updated ?>
        <td<?= $Page->last_updated->cellAttributes() ?>>
<span id="">
<span<?= $Page->last_updated->viewAttributes() ?>>
<?= $Page->last_updated->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->locked_by->Visible) { // locked_by ?>
        <td<?= $Page->locked_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->locked_by->viewAttributes() ?>>
<?= $Page->locked_by->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->lock_date->Visible) { // lock_date ?>
        <td<?= $Page->lock_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->lock_date->viewAttributes() ?>>
<?= $Page->lock_date->getViewValue() ?></span>
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
