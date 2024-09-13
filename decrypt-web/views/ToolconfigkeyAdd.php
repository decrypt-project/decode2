<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ToolconfigkeyAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { toolconfigkey: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var ftoolconfigkeyadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftoolconfigkeyadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["tool_id", [fields.tool_id.visible && fields.tool_id.required ? ew.Validators.required(fields.tool_id.caption) : null], fields.tool_id.isInvalid],
            ["toolkey", [fields.toolkey.visible && fields.toolkey.required ? ew.Validators.required(fields.toolkey.caption) : null], fields.toolkey.isInvalid],
            ["helptext", [fields.helptext.visible && fields.helptext.required ? ew.Validators.required(fields.helptext.caption) : null], fields.helptext.isInvalid]
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
            "tool_id": <?= $Page->tool_id->toClientList($Page) ?>,
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
<form name="ftoolconfigkeyadd" id="ftoolconfigkeyadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="toolconfigkey">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->tool_id->Visible) { // tool_id ?>
    <div id="r_tool_id"<?= $Page->tool_id->rowAttributes() ?>>
        <label id="elh_toolconfigkey_tool_id" for="x_tool_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tool_id->caption() ?><?= $Page->tool_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tool_id->cellAttributes() ?>>
<span id="el_toolconfigkey_tool_id">
    <select
        id="x_tool_id"
        name="x_tool_id"
        class="form-select ew-select<?= $Page->tool_id->isInvalidClass() ?>"
        <?php if (!$Page->tool_id->IsNativeSelect) { ?>
        data-select2-id="ftoolconfigkeyadd_x_tool_id"
        <?php } ?>
        data-table="toolconfigkey"
        data-field="x_tool_id"
        data-value-separator="<?= $Page->tool_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tool_id->getPlaceHolder()) ?>"
        <?= $Page->tool_id->editAttributes() ?>>
        <?= $Page->tool_id->selectOptionListHtml("x_tool_id") ?>
    </select>
    <?= $Page->tool_id->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tool_id->getErrorMessage() ?></div>
<?= $Page->tool_id->Lookup->getParamTag($Page, "p_x_tool_id") ?>
<?php if (!$Page->tool_id->IsNativeSelect) { ?>
<script>
loadjs.ready("ftoolconfigkeyadd", function() {
    var options = { name: "x_tool_id", selectId: "ftoolconfigkeyadd_x_tool_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftoolconfigkeyadd.lists.tool_id?.lookupOptions.length) {
        options.data = { id: "x_tool_id", form: "ftoolconfigkeyadd" };
    } else {
        options.ajax = { id: "x_tool_id", form: "ftoolconfigkeyadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.toolconfigkey.fields.tool_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->toolkey->Visible) { // toolkey ?>
    <div id="r_toolkey"<?= $Page->toolkey->rowAttributes() ?>>
        <label id="elh_toolconfigkey_toolkey" for="x_toolkey" class="<?= $Page->LeftColumnClass ?>"><?= $Page->toolkey->caption() ?><?= $Page->toolkey->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->toolkey->cellAttributes() ?>>
<span id="el_toolconfigkey_toolkey">
<input type="<?= $Page->toolkey->getInputTextType() ?>" name="x_toolkey" id="x_toolkey" data-table="toolconfigkey" data-field="x_toolkey" value="<?= $Page->toolkey->EditValue ?>" size="30" maxlength="16" placeholder="<?= HtmlEncode($Page->toolkey->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->toolkey->formatPattern()) ?>"<?= $Page->toolkey->editAttributes() ?> aria-describedby="x_toolkey_help">
<?= $Page->toolkey->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->toolkey->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->helptext->Visible) { // helptext ?>
    <div id="r_helptext"<?= $Page->helptext->rowAttributes() ?>>
        <label id="elh_toolconfigkey_helptext" for="x_helptext" class="<?= $Page->LeftColumnClass ?>"><?= $Page->helptext->caption() ?><?= $Page->helptext->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->helptext->cellAttributes() ?>>
<span id="el_toolconfigkey_helptext">
<textarea data-table="toolconfigkey" data-field="x_helptext" name="x_helptext" id="x_helptext" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->helptext->getPlaceHolder()) ?>"<?= $Page->helptext->editAttributes() ?> aria-describedby="x_helptext_help"><?= $Page->helptext->EditValue ?></textarea>
<?= $Page->helptext->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->helptext->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftoolconfigkeyadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftoolconfigkeyadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("toolconfigkey");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
