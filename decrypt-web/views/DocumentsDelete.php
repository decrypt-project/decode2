<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$DocumentsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { documents: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fdocumentsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocumentsdelete")
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
<form name="fdocumentsdelete" id="fdocumentsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="documents">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_documents_id" class="documents_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_title->Visible) { // title ?>
        <th class="<?= $Page->_title->headerCellClass() ?>"><span id="elh_documents__title" class="documents__title"><?= $Page->_title->caption() ?></span></th>
<?php } ?>
<?php if ($Page->upload_date->Visible) { // upload_date ?>
        <th class="<?= $Page->upload_date->headerCellClass() ?>"><span id="elh_documents_upload_date" class="documents_upload_date"><?= $Page->upload_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->category->Visible) { // category ?>
        <th class="<?= $Page->category->headerCellClass() ?>"><span id="elh_documents_category" class="documents_category"><?= $Page->category->caption() ?></span></th>
<?php } ?>
<?php if ($Page->public_->Visible) { // public_ ?>
        <th class="<?= $Page->public_->headerCellClass() ?>"><span id="elh_documents_public_" class="documents_public_"><?= $Page->public_->caption() ?></span></th>
<?php } ?>
<?php if ($Page->record_id->Visible) { // record_id ?>
        <th class="<?= $Page->record_id->headerCellClass() ?>"><span id="elh_documents_record_id" class="documents_record_id"><?= $Page->record_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->uploader_id->Visible) { // uploader_id ?>
        <th class="<?= $Page->uploader_id->headerCellClass() ?>"><span id="elh_documents_uploader_id" class="documents_uploader_id"><?= $Page->uploader_id->caption() ?></span></th>
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
<?php if ($Page->_title->Visible) { // title ?>
        <td<?= $Page->_title->cellAttributes() ?>>
<span id="">
<span<?= $Page->_title->viewAttributes() ?>>
<?= $Page->_title->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->upload_date->Visible) { // upload_date ?>
        <td<?= $Page->upload_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->upload_date->viewAttributes() ?>>
<?= $Page->upload_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->category->Visible) { // category ?>
        <td<?= $Page->category->cellAttributes() ?>>
<span id="">
<span<?= $Page->category->viewAttributes() ?>>
<?= $Page->category->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->public_->Visible) { // public_ ?>
        <td<?= $Page->public_->cellAttributes() ?>>
<span id="">
<span<?= $Page->public_->viewAttributes() ?>>
<?= $Page->public_->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->record_id->Visible) { // record_id ?>
        <td<?= $Page->record_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->record_id->viewAttributes() ?>>
<?php if (!EmptyString($Page->record_id->getViewValue()) && $Page->record_id->linkAttributes() != "") { ?>
<a<?= $Page->record_id->linkAttributes() ?>><?= $Page->record_id->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->record_id->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->uploader_id->Visible) { // uploader_id ?>
        <td<?= $Page->uploader_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->uploader_id->viewAttributes() ?>>
<?= $Page->uploader_id->getViewValue() ?></span>
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
    // Startup script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
});
</script>
