<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$RecordInProjectAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { record_in_project: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var frecord_in_projectadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("frecord_in_projectadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["project_id", [fields.project_id.visible && fields.project_id.required ? ew.Validators.required(fields.project_id.caption) : null, ew.Validators.integer], fields.project_id.isInvalid],
            ["record_id", [fields.record_id.visible && fields.record_id.required ? ew.Validators.required(fields.record_id.caption) : null], fields.record_id.isInvalid]
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
            "record_id": <?= $Page->record_id->toClientList($Page) ?>,
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
<form name="frecord_in_projectadd" id="frecord_in_projectadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="record_in_project">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "project") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="project">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->project_id->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->project_id->Visible) { // project_id ?>
    <div id="r_project_id"<?= $Page->project_id->rowAttributes() ?>>
        <label id="elh_record_in_project_project_id" for="x_project_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->project_id->caption() ?><?= $Page->project_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->project_id->cellAttributes() ?>>
<?php if ($Page->project_id->getSessionValue() != "") { ?>
<span<?= $Page->project_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->project_id->getDisplayValue($Page->project_id->ViewValue))) ?>"></span>
<input type="hidden" id="x_project_id" name="x_project_id" value="<?= HtmlEncode($Page->project_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_record_in_project_project_id">
<input type="<?= $Page->project_id->getInputTextType() ?>" name="x_project_id" id="x_project_id" data-table="record_in_project" data-field="x_project_id" value="<?= $Page->project_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->project_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->project_id->formatPattern()) ?>"<?= $Page->project_id->editAttributes() ?> aria-describedby="x_project_id_help">
<?= $Page->project_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->project_id->getErrorMessage() ?></div>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->record_id->Visible) { // record_id ?>
    <div id="r_record_id"<?= $Page->record_id->rowAttributes() ?>>
        <label id="elh_record_in_project_record_id" for="x_record_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->record_id->caption() ?><?= $Page->record_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->record_id->cellAttributes() ?>>
<span id="el_record_in_project_record_id">
    <select
        id="x_record_id"
        name="x_record_id"
        class="form-control ew-select<?= $Page->record_id->isInvalidClass() ?>"
        data-select2-id="frecord_in_projectadd_x_record_id"
        data-table="record_in_project"
        data-field="x_record_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->record_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->record_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->record_id->getPlaceHolder()) ?>"
        <?= $Page->record_id->editAttributes() ?>>
        <?= $Page->record_id->selectOptionListHtml("x_record_id") ?>
    </select>
    <?= $Page->record_id->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->record_id->getErrorMessage() ?></div>
<?= $Page->record_id->Lookup->getParamTag($Page, "p_x_record_id") ?>
<script>
loadjs.ready("frecord_in_projectadd", function() {
    var options = { name: "x_record_id", selectId: "frecord_in_projectadd_x_record_id" };
    if (frecord_in_projectadd.lists.record_id?.lookupOptions.length) {
        options.data = { id: "x_record_id", form: "frecord_in_projectadd" };
    } else {
        options.ajax = { id: "x_record_id", form: "frecord_in_projectadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.record_in_project.fields.record_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="frecord_in_projectadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="frecord_in_projectadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("record_in_project");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
