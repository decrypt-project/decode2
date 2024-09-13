<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$FileInProjectDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { file_in_project: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var ffile_in_projectdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffile_in_projectdelete")
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
<form name="ffile_in_projectdelete" id="ffile_in_projectdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="file_in_project">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_file_in_project_id" class="file_in_project_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->project_id->Visible) { // project_id ?>
        <th class="<?= $Page->project_id->headerCellClass() ?>"><span id="elh_file_in_project_project_id" class="file_in_project_project_id"><?= $Page->project_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->filename->Visible) { // filename ?>
        <th class="<?= $Page->filename->headerCellClass() ?>"><span id="elh_file_in_project_filename" class="file_in_project_filename"><?= $Page->filename->caption() ?></span></th>
<?php } ?>
<?php if ($Page->type->Visible) { // type ?>
        <th class="<?= $Page->type->headerCellClass() ?>"><span id="elh_file_in_project_type" class="file_in_project_type"><?= $Page->type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->filesize->Visible) { // filesize ?>
        <th class="<?= $Page->filesize->headerCellClass() ?>"><span id="elh_file_in_project_filesize" class="file_in_project_filesize"><?= $Page->filesize->caption() ?></span></th>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
        <th class="<?= $Page->creation_date->headerCellClass() ?>"><span id="elh_file_in_project_creation_date" class="file_in_project_creation_date"><?= $Page->creation_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->last_updated->Visible) { // last_updated ?>
        <th class="<?= $Page->last_updated->headerCellClass() ?>"><span id="elh_file_in_project_last_updated" class="file_in_project_last_updated"><?= $Page->last_updated->caption() ?></span></th>
<?php } ?>
<?php if ($Page->locked_by->Visible) { // locked_by ?>
        <th class="<?= $Page->locked_by->headerCellClass() ?>"><span id="elh_file_in_project_locked_by" class="file_in_project_locked_by"><?= $Page->locked_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lock_date->Visible) { // lock_date ?>
        <th class="<?= $Page->lock_date->headerCellClass() ?>"><span id="elh_file_in_project_lock_date" class="file_in_project_lock_date"><?= $Page->lock_date->caption() ?></span></th>
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
<?php if ($Page->project_id->Visible) { // project_id ?>
        <td<?= $Page->project_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->project_id->viewAttributes() ?>>
<?= $Page->project_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->filename->Visible) { // filename ?>
        <td<?= $Page->filename->cellAttributes() ?>>
<span id="">
<span<?= $Page->filename->viewAttributes() ?>><?php
 echo '<a href="';
  echo '/decrypt-custom/filesrv/?file='
 . CurrentPage()->path->CurrentValue;
 echo '">';
echo CurrentPage()->filename->CurrentValue;
 echo '</a>';
?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->type->Visible) { // type ?>
        <td<?= $Page->type->cellAttributes() ?>>
<span id="">
<span<?= $Page->type->viewAttributes() ?>>
<?= $Page->type->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->filesize->Visible) { // filesize ?>
        <td<?= $Page->filesize->cellAttributes() ?>>
<span id="">
<span<?= $Page->filesize->viewAttributes() ?>>
<?= $Page->filesize->getViewValue() ?></span>
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
