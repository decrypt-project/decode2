<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$RecordsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { records: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var frecordsdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("frecordsdelete")
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
<form name="frecordsdelete" id="frecordsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="records">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_records_id" class="records_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->c_holder->Visible) { // c_holder ?>
        <th class="<?= $Page->c_holder->headerCellClass() ?>"><span id="elh_records_c_holder" class="records_c_holder"><?= $Page->c_holder->caption() ?></span></th>
<?php } ?>
<?php if ($Page->c_cates->Visible) { // c_cates ?>
        <th class="<?= $Page->c_cates->headerCellClass() ?>"><span id="elh_records_c_cates" class="records_c_cates"><?= $Page->c_cates->caption() ?></span></th>
<?php } ?>
<?php if ($Page->c_author->Visible) { // c_author ?>
        <th class="<?= $Page->c_author->headerCellClass() ?>"><span id="elh_records_c_author" class="records_c_author"><?= $Page->c_author->caption() ?></span></th>
<?php } ?>
<?php if ($Page->c_lang->Visible) { // c_lang ?>
        <th class="<?= $Page->c_lang->headerCellClass() ?>"><span id="elh_records_c_lang" class="records_c_lang"><?= $Page->c_lang->caption() ?></span></th>
<?php } ?>
<?php if ($Page->record_type->Visible) { // record_type ?>
        <th class="<?= $Page->record_type->headerCellClass() ?>"><span id="elh_records_record_type" class="records_record_type"><?= $Page->record_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_records_status" class="records_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->number_of_pages->Visible) { // number_of_pages ?>
        <th class="<?= $Page->number_of_pages->headerCellClass() ?>"><span id="elh_records_number_of_pages" class="records_number_of_pages"><?= $Page->number_of_pages->caption() ?></span></th>
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
<?php if ($Page->c_holder->Visible) { // c_holder ?>
        <td<?= $Page->c_holder->cellAttributes() ?>>
<span id="">
<span<?= $Page->c_holder->viewAttributes() ?>>
<?= $Page->c_holder->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->c_cates->Visible) { // c_cates ?>
        <td<?= $Page->c_cates->cellAttributes() ?>>
<span id="">
<span<?= $Page->c_cates->viewAttributes() ?>>
<?= $Page->c_cates->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->c_author->Visible) { // c_author ?>
        <td<?= $Page->c_author->cellAttributes() ?>>
<span id="">
<span<?= $Page->c_author->viewAttributes() ?>>
<?= $Page->c_author->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->c_lang->Visible) { // c_lang ?>
        <td<?= $Page->c_lang->cellAttributes() ?>>
<span id="">
<span<?= $Page->c_lang->viewAttributes() ?>>
<?= $Page->c_lang->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->record_type->Visible) { // record_type ?>
        <td<?= $Page->record_type->cellAttributes() ?>>
<span id="">
<span<?= $Page->record_type->viewAttributes() ?>>
<?= $Page->record_type->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <td<?= $Page->status->cellAttributes() ?>>
<span id="">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->number_of_pages->Visible) { // number_of_pages ?>
        <td<?= $Page->number_of_pages->cellAttributes() ?>>
<span id="">
<span<?= $Page->number_of_pages->viewAttributes() ?>>
<?= $Page->number_of_pages->getViewValue() ?></span>
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
