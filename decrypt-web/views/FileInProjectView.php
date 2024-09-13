<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$FileInProjectView = &$Page;
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
<form name="ffile_in_projectview" id="ffile_in_projectview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { file_in_project: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var ffile_in_projectview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffile_in_projectview")
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
<input type="hidden" name="t" value="file_in_project">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_file_in_project_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->project_id->Visible) { // project_id ?>
    <tr id="r_project_id"<?= $Page->project_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_project_id"><?= $Page->project_id->caption() ?></span></td>
        <td data-name="project_id"<?= $Page->project_id->cellAttributes() ?>>
<span id="el_file_in_project_project_id">
<span<?= $Page->project_id->viewAttributes() ?>>
<?= $Page->project_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->filename->Visible) { // filename ?>
    <tr id="r_filename"<?= $Page->filename->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_filename"><?= $Page->filename->caption() ?></span></td>
        <td data-name="filename"<?= $Page->filename->cellAttributes() ?>>
<span id="el_file_in_project_filename">
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
    </tr>
<?php } ?>
<?php if ($Page->path->Visible) { // path ?>
    <tr id="r_path"<?= $Page->path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_path"><?= $Page->path->caption() ?></span></td>
        <td data-name="path"<?= $Page->path->cellAttributes() ?>>
<span id="el_file_in_project_path">
<span<?= $Page->path->viewAttributes() ?>>
<?= GetFileViewTag($Page->path, $Page->path->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->type->Visible) { // type ?>
    <tr id="r_type"<?= $Page->type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_type"><?= $Page->type->caption() ?></span></td>
        <td data-name="type"<?= $Page->type->cellAttributes() ?>>
<span id="el_file_in_project_type">
<span<?= $Page->type->viewAttributes() ?>>
<?= $Page->type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->filetype->Visible) { // filetype ?>
    <tr id="r_filetype"<?= $Page->filetype->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_filetype"><?= $Page->filetype->caption() ?></span></td>
        <td data-name="filetype"<?= $Page->filetype->cellAttributes() ?>>
<span id="el_file_in_project_filetype">
<span<?= $Page->filetype->viewAttributes() ?>>
<?= $Page->filetype->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->filesize->Visible) { // filesize ?>
    <tr id="r_filesize"<?= $Page->filesize->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_filesize"><?= $Page->filesize->caption() ?></span></td>
        <td data-name="filesize"<?= $Page->filesize->cellAttributes() ?>>
<span id="el_file_in_project_filesize">
<span<?= $Page->filesize->viewAttributes() ?>>
<?= $Page->filesize->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
    <tr id="r_creation_date"<?= $Page->creation_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_creation_date"><?= $Page->creation_date->caption() ?></span></td>
        <td data-name="creation_date"<?= $Page->creation_date->cellAttributes() ?>>
<span id="el_file_in_project_creation_date">
<span<?= $Page->creation_date->viewAttributes() ?>>
<?= $Page->creation_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->last_updated->Visible) { // last_updated ?>
    <tr id="r_last_updated"<?= $Page->last_updated->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_last_updated"><?= $Page->last_updated->caption() ?></span></td>
        <td data-name="last_updated"<?= $Page->last_updated->cellAttributes() ?>>
<span id="el_file_in_project_last_updated">
<span<?= $Page->last_updated->viewAttributes() ?>>
<?= $Page->last_updated->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->locked_by->Visible) { // locked_by ?>
    <tr id="r_locked_by"<?= $Page->locked_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_locked_by"><?= $Page->locked_by->caption() ?></span></td>
        <td data-name="locked_by"<?= $Page->locked_by->cellAttributes() ?>>
<span id="el_file_in_project_locked_by">
<span<?= $Page->locked_by->viewAttributes() ?>>
<?= $Page->locked_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lock_date->Visible) { // lock_date ?>
    <tr id="r_lock_date"<?= $Page->lock_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_file_in_project_lock_date"><?= $Page->lock_date->caption() ?></span></td>
        <td data-name="lock_date"<?= $Page->lock_date->cellAttributes() ?>>
<span id="el_file_in_project_lock_date">
<span<?= $Page->lock_date->viewAttributes() ?>>
<?= $Page->lock_date->getViewValue() ?></span>
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
