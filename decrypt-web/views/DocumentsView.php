<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$DocumentsView = &$Page;
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
<form name="fdocumentsview" id="fdocumentsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { documents: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fdocumentsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocumentsview")
        .setPageId("view")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Client script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
});
</script>
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="documents">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_documents_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_title->Visible) { // title ?>
    <tr id="r__title"<?= $Page->_title->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents__title"><?= $Page->_title->caption() ?></span></td>
        <td data-name="_title"<?= $Page->_title->cellAttributes() ?>>
<span id="el_documents__title">
<span<?= $Page->_title->viewAttributes() ?>>
<?= $Page->_title->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->filetype->Visible) { // filetype ?>
    <tr id="r_filetype"<?= $Page->filetype->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_filetype"><?= $Page->filetype->caption() ?></span></td>
        <td data-name="filetype"<?= $Page->filetype->cellAttributes() ?>>
<span id="el_documents_filetype">
<span<?= $Page->filetype->viewAttributes() ?>>
<?= $Page->filetype->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->size->Visible) { // size ?>
    <tr id="r_size"<?= $Page->size->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_size"><?= $Page->size->caption() ?></span></td>
        <td data-name="size"<?= $Page->size->cellAttributes() ?>>
<span id="el_documents_size">
<span<?= $Page->size->viewAttributes() ?>>
<?= $Page->size->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->upload_date->Visible) { // upload_date ?>
    <tr id="r_upload_date"<?= $Page->upload_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_upload_date"><?= $Page->upload_date->caption() ?></span></td>
        <td data-name="upload_date"<?= $Page->upload_date->cellAttributes() ?>>
<span id="el_documents_upload_date">
<span<?= $Page->upload_date->viewAttributes() ?>>
<?= $Page->upload_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->category->Visible) { // category ?>
    <tr id="r_category"<?= $Page->category->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_category"><?= $Page->category->caption() ?></span></td>
        <td data-name="category"<?= $Page->category->cellAttributes() ?>>
<span id="el_documents_category">
<span<?= $Page->category->viewAttributes() ?>>
<?= $Page->category->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->order_->Visible) { // order_ ?>
    <tr id="r_order_"<?= $Page->order_->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_order_"><?= $Page->order_->caption() ?></span></td>
        <td data-name="order_"<?= $Page->order_->cellAttributes() ?>>
<span id="el_documents_order_">
<span<?= $Page->order_->viewAttributes() ?>>
<?= $Page->order_->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->public_->Visible) { // public_ ?>
    <tr id="r_public_"<?= $Page->public_->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_public_"><?= $Page->public_->caption() ?></span></td>
        <td data-name="public_"<?= $Page->public_->cellAttributes() ?>>
<span id="el_documents_public_">
<span<?= $Page->public_->viewAttributes() ?>>
<?= $Page->public_->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->record_id->Visible) { // record_id ?>
    <tr id="r_record_id"<?= $Page->record_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_record_id"><?= $Page->record_id->caption() ?></span></td>
        <td data-name="record_id"<?= $Page->record_id->cellAttributes() ?>>
<span id="el_documents_record_id">
<span<?= $Page->record_id->viewAttributes() ?>>
<?php if (!EmptyString($Page->record_id->getViewValue()) && $Page->record_id->linkAttributes() != "") { ?>
<a<?= $Page->record_id->linkAttributes() ?>><?= $Page->record_id->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->record_id->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->uploader_id->Visible) { // uploader_id ?>
    <tr id="r_uploader_id"<?= $Page->uploader_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_uploader_id"><?= $Page->uploader_id->caption() ?></span></td>
        <td data-name="uploader_id"<?= $Page->uploader_id->cellAttributes() ?>>
<span id="el_documents_uploader_id">
<span<?= $Page->uploader_id->viewAttributes() ?>>
<?= $Page->uploader_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->path->Visible) { // path ?>
    <tr id="r_path"<?= $Page->path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_documents_path"><?= $Page->path->caption() ?></span></td>
        <td data-name="path"<?= $Page->path->cellAttributes() ?>>
<span id="el_documents_path">
<span<?= $Page->path->viewAttributes() ?>>
<?= GetFileViewTag($Page->path, $Page->path->getViewValue(), false) ?>
</span>
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
