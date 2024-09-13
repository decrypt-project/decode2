<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$AssociatedRecordsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { associated_records: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fassociated_recordsadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fassociated_recordsadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["record_id", [fields.record_id.visible && fields.record_id.required ? ew.Validators.required(fields.record_id.caption) : null, ew.Validators.integer], fields.record_id.isInvalid],
            ["linked_record_id", [fields.linked_record_id.visible && fields.linked_record_id.required ? ew.Validators.required(fields.linked_record_id.caption) : null], fields.linked_record_id.isInvalid],
            ["linktype", [fields.linktype.visible && fields.linktype.required ? ew.Validators.required(fields.linktype.caption) : null], fields.linktype.isInvalid]
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
            "linked_record_id": <?= $Page->linked_record_id->toClientList($Page) ?>,
            "linktype": <?= $Page->linktype->toClientList($Page) ?>,
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
<form name="fassociated_recordsadd" id="fassociated_recordsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="associated_records">
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
<?php if ($Page->record_id->Visible) { // record_id ?>
    <div id="r_record_id"<?= $Page->record_id->rowAttributes() ?>>
        <label id="elh_associated_records_record_id" for="x_record_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->record_id->caption() ?><?= $Page->record_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->record_id->cellAttributes() ?>>
<?php if ($Page->record_id->getSessionValue() != "") { ?>
<span<?= $Page->record_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->record_id->getDisplayValue($Page->record_id->ViewValue))) ?>"></span>
<input type="hidden" id="x_record_id" name="x_record_id" value="<?= HtmlEncode($Page->record_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_associated_records_record_id">
<input type="<?= $Page->record_id->getInputTextType() ?>" name="x_record_id" id="x_record_id" data-table="associated_records" data-field="x_record_id" value="<?= $Page->record_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->record_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->record_id->formatPattern()) ?>"<?= $Page->record_id->editAttributes() ?> aria-describedby="x_record_id_help">
<?= $Page->record_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->record_id->getErrorMessage() ?></div>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->linked_record_id->Visible) { // linked_record_id ?>
    <div id="r_linked_record_id"<?= $Page->linked_record_id->rowAttributes() ?>>
        <label id="elh_associated_records_linked_record_id" for="x_linked_record_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->linked_record_id->caption() ?><?= $Page->linked_record_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->linked_record_id->cellAttributes() ?>>
<span id="el_associated_records_linked_record_id">
    <select
        id="x_linked_record_id"
        name="x_linked_record_id"
        class="form-control ew-select<?= $Page->linked_record_id->isInvalidClass() ?>"
        data-select2-id="fassociated_recordsadd_x_linked_record_id"
        data-table="associated_records"
        data-field="x_linked_record_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->linked_record_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->linked_record_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->linked_record_id->getPlaceHolder()) ?>"
        <?= $Page->linked_record_id->editAttributes() ?>>
        <?= $Page->linked_record_id->selectOptionListHtml("x_linked_record_id") ?>
    </select>
    <?= $Page->linked_record_id->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->linked_record_id->getErrorMessage() ?></div>
<?= $Page->linked_record_id->Lookup->getParamTag($Page, "p_x_linked_record_id") ?>
<script>
loadjs.ready("fassociated_recordsadd", function() {
    var options = { name: "x_linked_record_id", selectId: "fassociated_recordsadd_x_linked_record_id" };
    if (fassociated_recordsadd.lists.linked_record_id?.lookupOptions.length) {
        options.data = { id: "x_linked_record_id", form: "fassociated_recordsadd" };
    } else {
        options.ajax = { id: "x_linked_record_id", form: "fassociated_recordsadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.associated_records.fields.linked_record_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->linktype->Visible) { // linktype ?>
    <div id="r_linktype"<?= $Page->linktype->rowAttributes() ?>>
        <label id="elh_associated_records_linktype" for="x_linktype" class="<?= $Page->LeftColumnClass ?>"><?= $Page->linktype->caption() ?><?= $Page->linktype->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->linktype->cellAttributes() ?>>
<span id="el_associated_records_linktype">
    <select
        id="x_linktype"
        name="x_linktype"
        class="form-select ew-select<?= $Page->linktype->isInvalidClass() ?>"
        <?php if (!$Page->linktype->IsNativeSelect) { ?>
        data-select2-id="fassociated_recordsadd_x_linktype"
        <?php } ?>
        data-table="associated_records"
        data-field="x_linktype"
        data-value-separator="<?= $Page->linktype->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->linktype->getPlaceHolder()) ?>"
        <?= $Page->linktype->editAttributes() ?>>
        <?= $Page->linktype->selectOptionListHtml("x_linktype") ?>
    </select>
    <?= $Page->linktype->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->linktype->getErrorMessage() ?></div>
<?php if (!$Page->linktype->IsNativeSelect) { ?>
<script>
loadjs.ready("fassociated_recordsadd", function() {
    var options = { name: "x_linktype", selectId: "fassociated_recordsadd_x_linktype" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fassociated_recordsadd.lists.linktype?.lookupOptions.length) {
        options.data = { id: "x_linktype", form: "fassociated_recordsadd" };
    } else {
        options.ajax = { id: "x_linktype", form: "fassociated_recordsadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.associated_records.fields.linktype.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fassociated_recordsadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fassociated_recordsadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("associated_records");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
});
</script>
