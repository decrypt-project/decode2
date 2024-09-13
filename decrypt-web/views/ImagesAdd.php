<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ImagesAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { images: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fimagesadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fimagesadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["path", [fields.path.visible && fields.path.required ? ew.Validators.fileRequired(fields.path.caption) : null], fields.path.isInvalid],
            ["record_id", [fields.record_id.visible && fields.record_id.required ? ew.Validators.required(fields.record_id.caption) : null, ew.Validators.integer], fields.record_id.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
        })
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
<form name="fimagesadd" id="fimagesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="images">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "records") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="records">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->record_id->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->path->Visible) { // path ?>
    <div id="r_path"<?= $Page->path->rowAttributes() ?>>
        <label id="elh_images_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->path->caption() ?><?= $Page->path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->path->cellAttributes() ?>>
<span id="el_images_path">
<div id="fd_x_path" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_path"
        name="x_path"
        class="form-control ew-file-input"
        title="<?= $Page->path->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="images"
        data-field="x_path"
        data-size="65535"
        data-accept-file-types="<?= $Page->path->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->path->UploadMaxFileSize ?>"
        data-max-number-of-files="<?= $Page->path->UploadMaxFileCount ?>"
        data-disable-image-crop="<?= $Page->path->ImageCropper ? 0 : 1 ?>"
        multiple
        aria-describedby="x_path_help"
        <?= ($Page->path->ReadOnly || $Page->path->Disabled) ? " disabled" : "" ?>
        <?= $Page->path->editAttributes() ?>
    >
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
    <?= $Page->path->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->path->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_path" id= "fn_x_path" value="<?= $Page->path->Upload->FileName ?>">
<input type="hidden" name="fa_x_path" id= "fa_x_path" value="0">
<table id="ft_x_path" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->record_id->Visible) { // record_id ?>
    <div id="r_record_id"<?= $Page->record_id->rowAttributes() ?>>
        <label id="elh_images_record_id" for="x_record_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->record_id->caption() ?><?= $Page->record_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->record_id->cellAttributes() ?>>
<?php if ($Page->record_id->getSessionValue() != "") { ?>
<span<?= $Page->record_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->record_id->getDisplayValue($Page->record_id->ViewValue))) ?>"></span>
<input type="hidden" id="x_record_id" name="x_record_id" value="<?= HtmlEncode($Page->record_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_images_record_id">
<input type="<?= $Page->record_id->getInputTextType() ?>" name="x_record_id" id="x_record_id" data-table="images" data-field="x_record_id" value="<?= $Page->record_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->record_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->record_id->formatPattern()) ?>"<?= $Page->record_id->editAttributes() ?> aria-describedby="x_record_id_help">
<?= $Page->record_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->record_id->getErrorMessage() ?></div>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("transcriptions", explode(",", $Page->getCurrentDetailTable())) && $transcriptions->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("transcriptions", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "TranscriptionsGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fimagesadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fimagesadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("images");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
});
</script>
