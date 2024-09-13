<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ProjectView = &$Page;
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
<form name="fprojectview" id="fprojectview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { project: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fprojectview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fprojectview")
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
<input type="hidden" name="t" value="project">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_project_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_project_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <tr id="r_name"<?= $Page->name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_project_name"><?= $Page->name->caption() ?></span></td>
        <td data-name="name"<?= $Page->name->cellAttributes() ?>>
<span id="el_project_name">
<span<?= $Page->name->viewAttributes() ?>>
<?= $Page->name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <tr id="r_description"<?= $Page->description->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_project_description"><?= $Page->description->caption() ?></span></td>
        <td data-name="description"<?= $Page->description->cellAttributes() ?>>
<span id="el_project_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->owner->Visible) { // owner ?>
    <tr id="r_owner"<?= $Page->owner->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_project_owner"><?= $Page->owner->caption() ?></span></td>
        <td data-name="owner"<?= $Page->owner->cellAttributes() ?>>
<span id="el_project_owner">
<span<?= $Page->owner->viewAttributes() ?>>
<?= $Page->owner->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->has_access->Visible) { // has_access ?>
    <tr id="r_has_access"<?= $Page->has_access->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_project_has_access"><?= $Page->has_access->caption() ?></span></td>
        <td data-name="has_access"<?= $Page->has_access->cellAttributes() ?>>
<span id="el_project_has_access">
<span<?= $Page->has_access->viewAttributes() ?>>
<?= $Page->has_access->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
    <tr id="r_creation_date"<?= $Page->creation_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_project_creation_date"><?= $Page->creation_date->caption() ?></span></td>
        <td data-name="creation_date"<?= $Page->creation_date->cellAttributes() ?>>
<span id="el_project_creation_date">
<span<?= $Page->creation_date->viewAttributes() ?>>
<?= $Page->creation_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->last_updated->Visible) { // last_updated ?>
    <tr id="r_last_updated"<?= $Page->last_updated->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_project_last_updated"><?= $Page->last_updated->caption() ?></span></td>
        <td data-name="last_updated"<?= $Page->last_updated->cellAttributes() ?>>
<span id="el_project_last_updated">
<span<?= $Page->last_updated->viewAttributes() ?>>
<?= $Page->last_updated->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->locked_by->Visible) { // locked_by ?>
    <tr id="r_locked_by"<?= $Page->locked_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_project_locked_by"><?= $Page->locked_by->caption() ?></span></td>
        <td data-name="locked_by"<?= $Page->locked_by->cellAttributes() ?>>
<span id="el_project_locked_by">
<span<?= $Page->locked_by->viewAttributes() ?>>
<?= $Page->locked_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lock_date->Visible) { // lock_date ?>
    <tr id="r_lock_date"<?= $Page->lock_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_project_lock_date"><?= $Page->lock_date->caption() ?></span></td>
        <td data-name="lock_date"<?= $Page->lock_date->cellAttributes() ?>>
<span id="el_project_lock_date">
<span<?= $Page->lock_date->viewAttributes() ?>>
<?= $Page->lock_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("record_in_project", explode(",", $Page->getCurrentDetailTable())) && $record_in_project->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("record_in_project", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "RecordInProjectGrid.php" ?>
<?php } ?>
<?php
    if (in_array("file_in_project", explode(",", $Page->getCurrentDetailTable())) && $file_in_project->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("file_in_project", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "FileInProjectGrid.php" ?>
<?php } ?>
<?php
    if (in_array("data_in_project", explode(",", $Page->getCurrentDetailTable())) && $data_in_project->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("data_in_project", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "DataInProjectGrid.php" ?>
<?php } ?>
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
