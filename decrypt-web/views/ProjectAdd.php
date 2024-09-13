<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$ProjectAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { project: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fprojectadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fprojectadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["name", [fields.name.visible && fields.name.required ? ew.Validators.required(fields.name.caption) : null], fields.name.isInvalid],
            ["description", [fields.description.visible && fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid],
            ["owner", [fields.owner.visible && fields.owner.required ? ew.Validators.required(fields.owner.caption) : null], fields.owner.isInvalid],
            ["has_access", [fields.has_access.visible && fields.has_access.required ? ew.Validators.required(fields.has_access.caption) : null], fields.has_access.isInvalid]
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
            "owner": <?= $Page->owner->toClientList($Page) ?>,
            "has_access": <?= $Page->has_access->toClientList($Page) ?>,
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
<form name="fprojectadd" id="fprojectadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="project">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->name->Visible) { // name ?>
    <div id="r_name"<?= $Page->name->rowAttributes() ?>>
        <label id="elh_project_name" for="x_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->name->caption() ?><?= $Page->name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->name->cellAttributes() ?>>
<span id="el_project_name">
<input type="<?= $Page->name->getInputTextType() ?>" name="x_name" id="x_name" data-table="project" data-field="x_name" value="<?= $Page->name->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->name->formatPattern()) ?>"<?= $Page->name->editAttributes() ?> aria-describedby="x_name_help">
<?= $Page->name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description"<?= $Page->description->rowAttributes() ?>>
        <label id="elh_project_description" for="x_description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->description->caption() ?><?= $Page->description->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->description->cellAttributes() ?>>
<span id="el_project_description">
<textarea data-table="project" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>"<?= $Page->description->editAttributes() ?> aria-describedby="x_description_help"><?= $Page->description->EditValue ?></textarea>
<?= $Page->description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->owner->Visible) { // owner ?>
    <div id="r_owner"<?= $Page->owner->rowAttributes() ?>>
        <label id="elh_project_owner" for="x_owner" class="<?= $Page->LeftColumnClass ?>"><?= $Page->owner->caption() ?><?= $Page->owner->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->owner->cellAttributes() ?>>
<span id="el_project_owner">
    <select
        id="x_owner"
        name="x_owner"
        class="form-select ew-select<?= $Page->owner->isInvalidClass() ?>"
        <?php if (!$Page->owner->IsNativeSelect) { ?>
        data-select2-id="fprojectadd_x_owner"
        <?php } ?>
        data-table="project"
        data-field="x_owner"
        data-value-separator="<?= $Page->owner->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->owner->getPlaceHolder()) ?>"
        <?= $Page->owner->editAttributes() ?>>
        <?= $Page->owner->selectOptionListHtml("x_owner") ?>
    </select>
    <?= $Page->owner->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->owner->getErrorMessage() ?></div>
<?= $Page->owner->Lookup->getParamTag($Page, "p_x_owner") ?>
<?php if (!$Page->owner->IsNativeSelect) { ?>
<script>
loadjs.ready("fprojectadd", function() {
    var options = { name: "x_owner", selectId: "fprojectadd_x_owner" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fprojectadd.lists.owner?.lookupOptions.length) {
        options.data = { id: "x_owner", form: "fprojectadd" };
    } else {
        options.ajax = { id: "x_owner", form: "fprojectadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.project.fields.owner.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->has_access->Visible) { // has_access ?>
    <div id="r_has_access"<?= $Page->has_access->rowAttributes() ?>>
        <label id="elh_project_has_access" class="<?= $Page->LeftColumnClass ?>"><?= $Page->has_access->caption() ?><?= $Page->has_access->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->has_access->cellAttributes() ?>>
<span id="el_project_has_access">
    <select
        id="x_has_access[]"
        name="x_has_access[]"
        class="form-select ew-select<?= $Page->has_access->isInvalidClass() ?>"
        <?php if (!$Page->has_access->IsNativeSelect) { ?>
        data-select2-id="fprojectadd_x_has_access[]"
        <?php } ?>
        data-table="project"
        data-field="x_has_access"
        data-dropdown
        multiple
        size="1"
        data-value-separator="<?= $Page->has_access->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->has_access->getPlaceHolder()) ?>"
        <?= $Page->has_access->editAttributes() ?>>
        <?= $Page->has_access->selectOptionListHtml("x_has_access[]") ?>
    </select>
    <?= $Page->has_access->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->has_access->getErrorMessage() ?></div>
<?= $Page->has_access->Lookup->getParamTag($Page, "p_x_has_access") ?>
<?php if (!$Page->has_access->IsNativeSelect) { ?>
<script>
loadjs.ready("fprojectadd", function() {
    var options = { name: "x_has_access[]", selectId: "fprojectadd_x_has_access[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.columns = el.dataset.repeatcolumn || 5;
    options.dropdown = !ew.IS_MOBILE && options.columns > 0; // Use custom dropdown
    el.dataset.dropdown = options.dropdown;
    if (options.dropdown) {
        options.dropdownAutoWidth = true;
        options.dropdownCssClass = "ew-select-dropdown project-x_has_access-dropdown ew-select-" + (options.multiple ? "multiple" : "one");
        if (options.columns > 1)
            options.dropdownCssClass += " ew-repeat-column";
    }
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fprojectadd.lists.has_access?.lookupOptions.length) {
        options.data = { id: "x_has_access[]", form: "fprojectadd" };
    } else {
        options.ajax = { id: "x_has_access[]", form: "fprojectadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.project.fields.has_access.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("record_in_project", explode(",", $Page->getCurrentDetailTable())) && $record_in_project->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("record_in_project", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "RecordInProjectGrid.php" ?>
<?php } ?>
<?php
    if (in_array("file_in_project", explode(",", $Page->getCurrentDetailTable())) && $file_in_project->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("file_in_project", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "FileInProjectGrid.php" ?>
<?php } ?>
<?php
    if (in_array("data_in_project", explode(",", $Page->getCurrentDetailTable())) && $data_in_project->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("data_in_project", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "DataInProjectGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fprojectadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fprojectadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("project");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
