<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ImagesView = &$Page;
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
<form name="fimagesview" id="fimagesview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { images: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fimagesview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fimagesview")
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
<input type="hidden" name="t" value="images">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_images_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_images_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <tr id="r_name"<?= $Page->name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_images_name"><?= $Page->name->caption() ?></span></td>
        <td data-name="name"<?= $Page->name->cellAttributes() ?>>
<span id="el_images_name">
<span<?= $Page->name->viewAttributes() ?>>
<?= $Page->name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->path->Visible) { // path ?>
    <tr id="r_path"<?= $Page->path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_images_path"><?= $Page->path->caption() ?></span></td>
        <td data-name="path"<?= $Page->path->cellAttributes() ?>>
<span id="el_images_path">
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
    </tr>
<?php } ?>
<?php if ($Page->record_id->Visible) { // record_id ?>
    <tr id="r_record_id"<?= $Page->record_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_images_record_id"><?= $Page->record_id->caption() ?></span></td>
        <td data-name="record_id"<?= $Page->record_id->cellAttributes() ?>>
<span id="el_images_record_id">
<span<?= $Page->record_id->viewAttributes() ?>>
<?= $Page->record_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->page_number->Visible) { // page_number ?>
    <tr id="r_page_number"<?= $Page->page_number->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_images_page_number"><?= $Page->page_number->caption() ?></span></td>
        <td data-name="page_number"<?= $Page->page_number->cellAttributes() ?>>
<span id="el_images_page_number">
<span<?= $Page->page_number->viewAttributes() ?>>
<?= $Page->page_number->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->last_modified->Visible) { // last_modified ?>
    <tr id="r_last_modified"<?= $Page->last_modified->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_images_last_modified"><?= $Page->last_modified->caption() ?></span></td>
        <td data-name="last_modified"<?= $Page->last_modified->cellAttributes() ?>>
<span id="el_images_last_modified">
<span<?= $Page->last_modified->viewAttributes() ?>>
<?= $Page->last_modified->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->processed->Visible) { // processed ?>
    <tr id="r_processed"<?= $Page->processed->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_images_processed"><?= $Page->processed->caption() ?></span></td>
        <td data-name="processed"<?= $Page->processed->cellAttributes() ?>>
<span id="el_images_processed">
<span<?= $Page->processed->viewAttributes() ?>>
<?= $Page->processed->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("transcriptions", explode(",", $Page->getCurrentDetailTable())) && $transcriptions->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("transcriptions", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "TranscriptionsGrid.php" ?>
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
    // Startup script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
});
</script>
<?php } ?>
