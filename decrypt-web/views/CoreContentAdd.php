<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$CoreContentAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { core_content: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcore_contentadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcore_contentadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["txkey", [fields.txkey.visible && fields.txkey.required ? ew.Validators.required(fields.txkey.caption) : null], fields.txkey.isInvalid],
            ["lang", [fields.lang.visible && fields.lang.required ? ew.Validators.required(fields.lang.caption) : null], fields.lang.isInvalid],
            ["_content", [fields._content.visible && fields._content.required ? ew.Validators.required(fields._content.caption) : null], fields._content.isInvalid]
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
            "lang": <?= $Page->lang->toClientList($Page) ?>,
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
<form name="fcore_contentadd" id="fcore_contentadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="core_content">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->txkey->Visible) { // txkey ?>
    <div id="r_txkey"<?= $Page->txkey->rowAttributes() ?>>
        <label id="elh_core_content_txkey" for="x_txkey" class="<?= $Page->LeftColumnClass ?>"><?= $Page->txkey->caption() ?><?= $Page->txkey->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->txkey->cellAttributes() ?>>
<span id="el_core_content_txkey">
<input type="<?= $Page->txkey->getInputTextType() ?>" name="x_txkey" id="x_txkey" data-table="core_content" data-field="x_txkey" value="<?= $Page->txkey->EditValue ?>" size="30" maxlength="128" placeholder="<?= HtmlEncode($Page->txkey->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->txkey->formatPattern()) ?>"<?= $Page->txkey->editAttributes() ?> aria-describedby="x_txkey_help">
<?= $Page->txkey->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->txkey->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lang->Visible) { // lang ?>
    <div id="r_lang"<?= $Page->lang->rowAttributes() ?>>
        <label id="elh_core_content_lang" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lang->caption() ?><?= $Page->lang->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->lang->cellAttributes() ?>>
<span id="el_core_content_lang">
<template id="tp_x_lang">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="core_content" data-field="x_lang" name="x_lang" id="x_lang"<?= $Page->lang->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_lang" class="ew-item-list"></div>
<selection-list hidden
    id="x_lang"
    name="x_lang"
    value="<?= HtmlEncode($Page->lang->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_lang"
    data-target="dsl_x_lang"
    data-repeatcolumn="5"
    class="form-control<?= $Page->lang->isInvalidClass() ?>"
    data-table="core_content"
    data-field="x_lang"
    data-value-separator="<?= $Page->lang->displayValueSeparatorAttribute() ?>"
    <?= $Page->lang->editAttributes() ?>></selection-list>
<?= $Page->lang->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lang->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_content->Visible) { // content ?>
    <div id="r__content"<?= $Page->_content->rowAttributes() ?>>
        <label id="elh_core_content__content" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_content->caption() ?><?= $Page->_content->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_content->cellAttributes() ?>>
<span id="el_core_content__content">
<?php $Page->_content->EditAttrs->appendClass("editor"); ?>
<textarea data-table="core_content" data-field="x__content" name="x__content" id="x__content" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->_content->getPlaceHolder()) ?>"<?= $Page->_content->editAttributes() ?> aria-describedby="x__content_help"><?= $Page->_content->EditValue ?></textarea>
<?= $Page->_content->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_content->getErrorMessage() ?></div>
<script>
loadjs.ready(["fcore_contentadd", "editor"], function() {
    ew.createEditor("fcore_contentadd", "x__content", 35, 4, <?= $Page->_content->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcore_contentadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcore_contentadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("core_content");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
