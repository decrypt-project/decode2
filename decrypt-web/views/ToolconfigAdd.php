<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ToolconfigAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { toolconfig: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var ftoolconfigadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftoolconfigadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["project_id", [fields.project_id.visible && fields.project_id.required ? ew.Validators.required(fields.project_id.caption) : null], fields.project_id.isInvalid],
            ["toolconfigkey_id", [fields.toolconfigkey_id.visible && fields.toolconfigkey_id.required ? ew.Validators.required(fields.toolconfigkey_id.caption) : null], fields.toolconfigkey_id.isInvalid],
            ["configvalue", [fields.configvalue.visible && fields.configvalue.required ? ew.Validators.required(fields.configvalue.caption) : null], fields.configvalue.isInvalid]
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
            "project_id": <?= $Page->project_id->toClientList($Page) ?>,
            "toolconfigkey_id": <?= $Page->toolconfigkey_id->toClientList($Page) ?>,
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
<form name="ftoolconfigadd" id="ftoolconfigadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="toolconfig">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "toolconfigkey") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="toolconfigkey">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->toolconfigkey_id->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "project") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="project">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->project_id->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->project_id->Visible) { // project_id ?>
    <div id="r_project_id"<?= $Page->project_id->rowAttributes() ?>>
        <label id="elh_toolconfig_project_id" for="x_project_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->project_id->caption() ?><?= $Page->project_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->project_id->cellAttributes() ?>>
<?php if ($Page->project_id->getSessionValue() != "") { ?>
<span<?= $Page->project_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->project_id->getDisplayValue($Page->project_id->ViewValue) ?></span></span>
<input type="hidden" id="x_project_id" name="x_project_id" value="<?= HtmlEncode($Page->project_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_toolconfig_project_id">
    <select
        id="x_project_id"
        name="x_project_id"
        class="form-select ew-select<?= $Page->project_id->isInvalidClass() ?>"
        <?php if (!$Page->project_id->IsNativeSelect) { ?>
        data-select2-id="ftoolconfigadd_x_project_id"
        <?php } ?>
        data-table="toolconfig"
        data-field="x_project_id"
        data-value-separator="<?= $Page->project_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->project_id->getPlaceHolder()) ?>"
        <?= $Page->project_id->editAttributes() ?>>
        <?= $Page->project_id->selectOptionListHtml("x_project_id") ?>
    </select>
    <?= $Page->project_id->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->project_id->getErrorMessage() ?></div>
<?= $Page->project_id->Lookup->getParamTag($Page, "p_x_project_id") ?>
<?php if (!$Page->project_id->IsNativeSelect) { ?>
<script>
loadjs.ready("ftoolconfigadd", function() {
    var options = { name: "x_project_id", selectId: "ftoolconfigadd_x_project_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftoolconfigadd.lists.project_id?.lookupOptions.length) {
        options.data = { id: "x_project_id", form: "ftoolconfigadd" };
    } else {
        options.ajax = { id: "x_project_id", form: "ftoolconfigadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.toolconfig.fields.project_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->toolconfigkey_id->Visible) { // toolconfigkey_id ?>
    <div id="r_toolconfigkey_id"<?= $Page->toolconfigkey_id->rowAttributes() ?>>
        <label id="elh_toolconfig_toolconfigkey_id" for="x_toolconfigkey_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->toolconfigkey_id->caption() ?><?= $Page->toolconfigkey_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->toolconfigkey_id->cellAttributes() ?>>
<?php if ($Page->toolconfigkey_id->getSessionValue() != "") { ?>
<span<?= $Page->toolconfigkey_id->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->toolconfigkey_id->getDisplayValue($Page->toolconfigkey_id->ViewValue) ?></span></span>
<input type="hidden" id="x_toolconfigkey_id" name="x_toolconfigkey_id" value="<?= HtmlEncode($Page->toolconfigkey_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_toolconfig_toolconfigkey_id">
    <select
        id="x_toolconfigkey_id"
        name="x_toolconfigkey_id"
        class="form-select ew-select<?= $Page->toolconfigkey_id->isInvalidClass() ?>"
        <?php if (!$Page->toolconfigkey_id->IsNativeSelect) { ?>
        data-select2-id="ftoolconfigadd_x_toolconfigkey_id"
        <?php } ?>
        data-table="toolconfig"
        data-field="x_toolconfigkey_id"
        data-value-separator="<?= $Page->toolconfigkey_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->toolconfigkey_id->getPlaceHolder()) ?>"
        <?= $Page->toolconfigkey_id->editAttributes() ?>>
        <?= $Page->toolconfigkey_id->selectOptionListHtml("x_toolconfigkey_id") ?>
    </select>
    <?= $Page->toolconfigkey_id->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->toolconfigkey_id->getErrorMessage() ?></div>
<?= $Page->toolconfigkey_id->Lookup->getParamTag($Page, "p_x_toolconfigkey_id") ?>
<?php if (!$Page->toolconfigkey_id->IsNativeSelect) { ?>
<script>
loadjs.ready("ftoolconfigadd", function() {
    var options = { name: "x_toolconfigkey_id", selectId: "ftoolconfigadd_x_toolconfigkey_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftoolconfigadd.lists.toolconfigkey_id?.lookupOptions.length) {
        options.data = { id: "x_toolconfigkey_id", form: "ftoolconfigadd" };
    } else {
        options.ajax = { id: "x_toolconfigkey_id", form: "ftoolconfigadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.toolconfig.fields.toolconfigkey_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->configvalue->Visible) { // configvalue ?>
    <div id="r_configvalue"<?= $Page->configvalue->rowAttributes() ?>>
        <label id="elh_toolconfig_configvalue" for="x_configvalue" class="<?= $Page->LeftColumnClass ?>"><?= $Page->configvalue->caption() ?><?= $Page->configvalue->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->configvalue->cellAttributes() ?>>
<span id="el_toolconfig_configvalue">
<input type="<?= $Page->configvalue->getInputTextType() ?>" name="x_configvalue" id="x_configvalue" data-table="toolconfig" data-field="x_configvalue" value="<?= $Page->configvalue->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->configvalue->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->configvalue->formatPattern()) ?>"<?= $Page->configvalue->editAttributes() ?> aria-describedby="x_configvalue_help">
<?= $Page->configvalue->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->configvalue->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftoolconfigadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftoolconfigadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("toolconfig");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
