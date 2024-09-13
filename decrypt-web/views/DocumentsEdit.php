<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$DocumentsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fdocumentsedit" id="fdocumentsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { documents: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fdocumentsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fdocumentsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["_title", [fields._title.visible && fields._title.required ? ew.Validators.required(fields._title.caption) : null], fields._title.isInvalid],
            ["category", [fields.category.visible && fields.category.required ? ew.Validators.required(fields.category.caption) : null], fields.category.isInvalid],
            ["public_", [fields.public_.visible && fields.public_.required ? ew.Validators.required(fields.public_.caption) : null], fields.public_.isInvalid],
            ["record_id", [fields.record_id.visible && fields.record_id.required ? ew.Validators.required(fields.record_id.caption) : null, ew.Validators.integer], fields.record_id.isInvalid],
            ["uploader_id", [fields.uploader_id.visible && fields.uploader_id.required ? ew.Validators.required(fields.uploader_id.caption) : null], fields.uploader_id.isInvalid],
            ["path", [fields.path.visible && fields.path.required ? ew.Validators.fileRequired(fields.path.caption) : null], fields.path.isInvalid]
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
            "category": <?= $Page->category->toClientList($Page) ?>,
            "public_": <?= $Page->public_->toClientList($Page) ?>,
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="documents">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "records") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="records">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->record_id->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->_title->Visible) { // title ?>
    <div id="r__title"<?= $Page->_title->rowAttributes() ?>>
        <label id="elh_documents__title" for="x__title" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_title->caption() ?><?= $Page->_title->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_title->cellAttributes() ?>>
<span id="el_documents__title">
<input type="<?= $Page->_title->getInputTextType() ?>" name="x__title" id="x__title" data-table="documents" data-field="x__title" value="<?= $Page->_title->EditValue ?>" maxlength="256" placeholder="<?= HtmlEncode($Page->_title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_title->formatPattern()) ?>"<?= $Page->_title->editAttributes() ?> aria-describedby="x__title_help">
<?= $Page->_title->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_title->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->category->Visible) { // category ?>
    <div id="r_category"<?= $Page->category->rowAttributes() ?>>
        <label id="elh_documents_category" class="<?= $Page->LeftColumnClass ?>"><?= $Page->category->caption() ?><?= $Page->category->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->category->cellAttributes() ?>>
<span id="el_documents_category">
    <select
        id="x_category"
        name="x_category"
        class="form-select ew-select<?= $Page->category->isInvalidClass() ?>"
        <?php if (!$Page->category->IsNativeSelect) { ?>
        data-select2-id="fdocumentsedit_x_category"
        <?php } ?>
        data-table="documents"
        data-field="x_category"
        data-dropdown
        data-value-separator="<?= $Page->category->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->category->getPlaceHolder()) ?>"
        <?= $Page->category->editAttributes() ?>>
        <?= $Page->category->selectOptionListHtml("x_category") ?>
    </select>
    <?= $Page->category->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->category->getErrorMessage() ?></div>
<?php if (!$Page->category->IsNativeSelect) { ?>
<script>
loadjs.ready("fdocumentsedit", function() {
    var options = { name: "x_category", selectId: "fdocumentsedit_x_category" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.columns = el.dataset.repeatcolumn || 5;
    options.dropdown = !ew.IS_MOBILE && options.columns > 0; // Use custom dropdown
    el.dataset.dropdown = options.dropdown;
    if (options.dropdown) {
        options.dropdownAutoWidth = true;
        options.dropdownCssClass = "ew-select-dropdown documents-x_category-dropdown ew-select-" + (options.multiple ? "multiple" : "one");
        if (options.columns > 1)
            options.dropdownCssClass += " ew-repeat-column";
    }
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fdocumentsedit.lists.category?.lookupOptions.length) {
        options.data = { id: "x_category", form: "fdocumentsedit" };
    } else {
        options.ajax = { id: "x_category", form: "fdocumentsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.documents.fields.category.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->public_->Visible) { // public_ ?>
    <div id="r_public_"<?= $Page->public_->rowAttributes() ?>>
        <label id="elh_documents_public_" class="<?= $Page->LeftColumnClass ?>"><?= $Page->public_->caption() ?><?= $Page->public_->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->public_->cellAttributes() ?>>
<span id="el_documents_public_">
<template id="tp_x_public_">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="documents" data-field="x_public_" name="x_public_" id="x_public_"<?= $Page->public_->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_public_" class="ew-item-list"></div>
<selection-list hidden
    id="x_public_"
    name="x_public_"
    value="<?= HtmlEncode($Page->public_->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_public_"
    data-target="dsl_x_public_"
    data-repeatcolumn="5"
    class="form-control<?= $Page->public_->isInvalidClass() ?>"
    data-table="documents"
    data-field="x_public_"
    data-value-separator="<?= $Page->public_->displayValueSeparatorAttribute() ?>"
    <?= $Page->public_->editAttributes() ?>></selection-list>
<?= $Page->public_->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->public_->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->record_id->Visible) { // record_id ?>
    <div id="r_record_id"<?= $Page->record_id->rowAttributes() ?>>
        <label id="elh_documents_record_id" for="x_record_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->record_id->caption() ?><?= $Page->record_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->record_id->cellAttributes() ?>>
<?php if ($Page->record_id->getSessionValue() != "") { ?>
<span<?= $Page->record_id->viewAttributes() ?>>
<?php if (!EmptyString($Page->record_id->ViewValue) && $Page->record_id->linkAttributes() != "") { ?>
<a<?= $Page->record_id->linkAttributes() ?>><input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->record_id->getDisplayValue($Page->record_id->ViewValue))) ?>"></a>
<?php } else { ?>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->record_id->getDisplayValue($Page->record_id->ViewValue))) ?>">
<?php } ?>
</span>
<input type="hidden" id="x_record_id" name="x_record_id" value="<?= HtmlEncode($Page->record_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_documents_record_id">
<input type="<?= $Page->record_id->getInputTextType() ?>" name="x_record_id" id="x_record_id" data-table="documents" data-field="x_record_id" value="<?= $Page->record_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->record_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->record_id->formatPattern()) ?>"<?= $Page->record_id->editAttributes() ?> aria-describedby="x_record_id_help">
<?= $Page->record_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->record_id->getErrorMessage() ?></div>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->path->Visible) { // path ?>
    <div id="r_path"<?= $Page->path->rowAttributes() ?>>
        <label id="elh_documents_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->path->caption() ?><?= $Page->path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->path->cellAttributes() ?>>
<span id="el_documents_path">
<div id="fd_x_path" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_path"
        name="x_path"
        class="form-control ew-file-input"
        title="<?= $Page->path->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="documents"
        data-field="x_path"
        data-size="256"
        data-accept-file-types="<?= $Page->path->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->path->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->path->ImageCropper ? 0 : 1 ?>"
        aria-describedby="x_path_help"
        <?= ($Page->path->ReadOnly || $Page->path->Disabled) ? " disabled" : "" ?>
        <?= $Page->path->editAttributes() ?>
    >
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->path->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->path->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_path" id= "fn_x_path" value="<?= $Page->path->Upload->FileName ?>">
<input type="hidden" name="fa_x_path" id= "fa_x_path" value="<?= (Post("fa_x_path") == "0") ? "0" : "1" ?>">
<table id="ft_x_path" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<span id="el_documents_uploader_id">
<input type="hidden" data-table="documents" data-field="x_uploader_id" data-hidden="1" name="x_uploader_id" id="x_uploader_id" value="<?= HtmlEncode($Page->uploader_id->CurrentValue) ?>">
</span>
    <input type="hidden" data-table="documents" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdocumentsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdocumentsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("documents");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
});
</script>
