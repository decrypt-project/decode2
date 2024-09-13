<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ImagesDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { images: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fimagesdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fimagesdelete")
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
<form name="fimagesdelete" id="fimagesdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="images">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_images_id" class="images_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
        <th class="<?= $Page->name->headerCellClass() ?>"><span id="elh_images_name" class="images_name"><?= $Page->name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->path->Visible) { // path ?>
        <th class="<?= $Page->path->headerCellClass() ?>"><span id="elh_images_path" class="images_path"><?= $Page->path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->record_id->Visible) { // record_id ?>
        <th class="<?= $Page->record_id->headerCellClass() ?>"><span id="elh_images_record_id" class="images_record_id"><?= $Page->record_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->last_modified->Visible) { // last_modified ?>
        <th class="<?= $Page->last_modified->headerCellClass() ?>"><span id="elh_images_last_modified" class="images_last_modified"><?= $Page->last_modified->caption() ?></span></th>
<?php } ?>
<?php if ($Page->processed->Visible) { // processed ?>
        <th class="<?= $Page->processed->headerCellClass() ?>"><span id="elh_images_processed" class="images_processed"><?= $Page->processed->caption() ?></span></th>
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
<?php if ($Page->path->Visible) { // path ?>
        <td<?= $Page->path->cellAttributes() ?>>
<span id="">
<span<?= $Page->path->viewAttributes() ?>><?php
 echo '<a href="';
  echo '/decrypt-custom/zoom.php?file='
 . CurrentPage()->path->CurrentValue;
 echo '">';
 echo '<img src="';
 echo '/decrypt-custom/filesrv/?file=TH_'
 . CurrentPage()->path->CurrentValue;
 echo '"/>';
 echo '</a>';
?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->record_id->Visible) { // record_id ?>
        <td<?= $Page->record_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->record_id->viewAttributes() ?>>
<?= $Page->record_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->last_modified->Visible) { // last_modified ?>
        <td<?= $Page->last_modified->cellAttributes() ?>>
<span id="">
<span<?= $Page->last_modified->viewAttributes() ?>>
<?= $Page->last_modified->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->processed->Visible) { // processed ?>
        <td<?= $Page->processed->cellAttributes() ?>>
<span id="">
<span<?= $Page->processed->viewAttributes() ?>>
<?= $Page->processed->getViewValue() ?></span>
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
