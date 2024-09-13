<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$FileInProjectAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { file_in_project: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var ffile_in_projectadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ffile_in_projectadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["project_id", [fields.project_id.visible && fields.project_id.required ? ew.Validators.required(fields.project_id.caption) : null, ew.Validators.integer], fields.project_id.isInvalid],
            ["filename", [fields.filename.visible && fields.filename.required ? ew.Validators.required(fields.filename.caption) : null], fields.filename.isInvalid],
            ["path", [fields.path.visible && fields.path.required ? ew.Validators.fileRequired(fields.path.caption) : null], fields.path.isInvalid],
            ["type", [fields.type.visible && fields.type.required ? ew.Validators.required(fields.type.caption) : null, ew.Validators.integer], fields.type.isInvalid],
            ["filetype", [fields.filetype.visible && fields.filetype.required ? ew.Validators.required(fields.filetype.caption) : null], fields.filetype.isInvalid],
            ["filesize", [fields.filesize.visible && fields.filesize.required ? ew.Validators.required(fields.filesize.caption) : null, ew.Validators.integer], fields.filesize.isInvalid],
            ["creation_date", [fields.creation_date.visible && fields.creation_date.required ? ew.Validators.required(fields.creation_date.caption) : null, ew.Validators.datetime(fields.creation_date.clientFormatPattern)], fields.creation_date.isInvalid],
            ["last_updated", [fields.last_updated.visible && fields.last_updated.required ? ew.Validators.required(fields.last_updated.caption) : null, ew.Validators.datetime(fields.last_updated.clientFormatPattern)], fields.last_updated.isInvalid],
            ["locked_by", [fields.locked_by.visible && fields.locked_by.required ? ew.Validators.required(fields.locked_by.caption) : null], fields.locked_by.isInvalid],
            ["lock_date", [fields.lock_date.visible && fields.lock_date.required ? ew.Validators.required(fields.lock_date.caption) : null, ew.Validators.datetime(fields.lock_date.clientFormatPattern)], fields.lock_date.isInvalid]
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
            "locked_by": <?= $Page->locked_by->toClientList($Page) ?>,
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
<form name="ffile_in_projectadd" id="ffile_in_projectadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="file_in_project">
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
        <label id="elh_file_in_project_project_id" for="x_project_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->project_id->caption() ?><?= $Page->project_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->project_id->cellAttributes() ?>>
<?php if ($Page->project_id->getSessionValue() != "") { ?>
<span<?= $Page->project_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->project_id->getDisplayValue($Page->project_id->ViewValue))) ?>"></span>
<input type="hidden" id="x_project_id" name="x_project_id" value="<?= HtmlEncode($Page->project_id->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_file_in_project_project_id">
<input type="<?= $Page->project_id->getInputTextType() ?>" name="x_project_id" id="x_project_id" data-table="file_in_project" data-field="x_project_id" value="<?= $Page->project_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->project_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->project_id->formatPattern()) ?>"<?= $Page->project_id->editAttributes() ?> aria-describedby="x_project_id_help">
<?= $Page->project_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->project_id->getErrorMessage() ?></div>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->filename->Visible) { // filename ?>
    <div id="r_filename"<?= $Page->filename->rowAttributes() ?>>
        <label id="elh_file_in_project_filename" for="x_filename" class="<?= $Page->LeftColumnClass ?>"><?= $Page->filename->caption() ?><?= $Page->filename->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->filename->cellAttributes() ?>>
<span id="el_file_in_project_filename">
<textarea data-table="file_in_project" data-field="x_filename" name="x_filename" id="x_filename" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->filename->getPlaceHolder()) ?>"<?= $Page->filename->editAttributes() ?> aria-describedby="x_filename_help"><?= $Page->filename->EditValue ?></textarea>
<?= $Page->filename->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->filename->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->path->Visible) { // path ?>
    <div id="r_path"<?= $Page->path->rowAttributes() ?>>
        <label id="elh_file_in_project_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->path->caption() ?><?= $Page->path->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->path->cellAttributes() ?>>
<span id="el_file_in_project_path">
<div id="fd_x_path" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_path"
        name="x_path"
        class="form-control ew-file-input"
        title="<?= $Page->path->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="file_in_project"
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
<input type="hidden" name="fa_x_path" id= "fa_x_path" value="0">
<table id="ft_x_path" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->type->Visible) { // type ?>
    <div id="r_type"<?= $Page->type->rowAttributes() ?>>
        <label id="elh_file_in_project_type" for="x_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->type->caption() ?><?= $Page->type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->type->cellAttributes() ?>>
<span id="el_file_in_project_type">
<input type="<?= $Page->type->getInputTextType() ?>" name="x_type" id="x_type" data-table="file_in_project" data-field="x_type" value="<?= $Page->type->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->type->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->type->formatPattern()) ?>"<?= $Page->type->editAttributes() ?> aria-describedby="x_type_help">
<?= $Page->type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->type->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->filetype->Visible) { // filetype ?>
    <div id="r_filetype"<?= $Page->filetype->rowAttributes() ?>>
        <label id="elh_file_in_project_filetype" for="x_filetype" class="<?= $Page->LeftColumnClass ?>"><?= $Page->filetype->caption() ?><?= $Page->filetype->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->filetype->cellAttributes() ?>>
<span id="el_file_in_project_filetype">
<textarea data-table="file_in_project" data-field="x_filetype" name="x_filetype" id="x_filetype" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->filetype->getPlaceHolder()) ?>"<?= $Page->filetype->editAttributes() ?> aria-describedby="x_filetype_help"><?= $Page->filetype->EditValue ?></textarea>
<?= $Page->filetype->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->filetype->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->filesize->Visible) { // filesize ?>
    <div id="r_filesize"<?= $Page->filesize->rowAttributes() ?>>
        <label id="elh_file_in_project_filesize" for="x_filesize" class="<?= $Page->LeftColumnClass ?>"><?= $Page->filesize->caption() ?><?= $Page->filesize->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->filesize->cellAttributes() ?>>
<span id="el_file_in_project_filesize">
<input type="<?= $Page->filesize->getInputTextType() ?>" name="x_filesize" id="x_filesize" data-table="file_in_project" data-field="x_filesize" value="<?= $Page->filesize->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->filesize->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->filesize->formatPattern()) ?>"<?= $Page->filesize->editAttributes() ?> aria-describedby="x_filesize_help">
<?= $Page->filesize->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->filesize->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
    <div id="r_creation_date"<?= $Page->creation_date->rowAttributes() ?>>
        <label id="elh_file_in_project_creation_date" for="x_creation_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->creation_date->caption() ?><?= $Page->creation_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->creation_date->cellAttributes() ?>>
<span id="el_file_in_project_creation_date">
<input type="<?= $Page->creation_date->getInputTextType() ?>" name="x_creation_date" id="x_creation_date" data-table="file_in_project" data-field="x_creation_date" value="<?= $Page->creation_date->EditValue ?>" placeholder="<?= HtmlEncode($Page->creation_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->creation_date->formatPattern()) ?>"<?= $Page->creation_date->editAttributes() ?> aria-describedby="x_creation_date_help">
<?= $Page->creation_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->creation_date->getErrorMessage() ?></div>
<?php if (!$Page->creation_date->ReadOnly && !$Page->creation_date->Disabled && !isset($Page->creation_date->EditAttrs["readonly"]) && !isset($Page->creation_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffile_in_projectadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("ffile_in_projectadd", "x_creation_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->last_updated->Visible) { // last_updated ?>
    <div id="r_last_updated"<?= $Page->last_updated->rowAttributes() ?>>
        <label id="elh_file_in_project_last_updated" for="x_last_updated" class="<?= $Page->LeftColumnClass ?>"><?= $Page->last_updated->caption() ?><?= $Page->last_updated->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->last_updated->cellAttributes() ?>>
<span id="el_file_in_project_last_updated">
<input type="<?= $Page->last_updated->getInputTextType() ?>" name="x_last_updated" id="x_last_updated" data-table="file_in_project" data-field="x_last_updated" value="<?= $Page->last_updated->EditValue ?>" placeholder="<?= HtmlEncode($Page->last_updated->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->last_updated->formatPattern()) ?>"<?= $Page->last_updated->editAttributes() ?> aria-describedby="x_last_updated_help">
<?= $Page->last_updated->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->last_updated->getErrorMessage() ?></div>
<?php if (!$Page->last_updated->ReadOnly && !$Page->last_updated->Disabled && !isset($Page->last_updated->EditAttrs["readonly"]) && !isset($Page->last_updated->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffile_in_projectadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("ffile_in_projectadd", "x_last_updated", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->locked_by->Visible) { // locked_by ?>
    <div id="r_locked_by"<?= $Page->locked_by->rowAttributes() ?>>
        <label id="elh_file_in_project_locked_by" for="x_locked_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->locked_by->caption() ?><?= $Page->locked_by->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->locked_by->cellAttributes() ?>>
<span id="el_file_in_project_locked_by">
    <select
        id="x_locked_by"
        name="x_locked_by"
        class="form-select ew-select<?= $Page->locked_by->isInvalidClass() ?>"
        <?php if (!$Page->locked_by->IsNativeSelect) { ?>
        data-select2-id="ffile_in_projectadd_x_locked_by"
        <?php } ?>
        data-table="file_in_project"
        data-field="x_locked_by"
        data-value-separator="<?= $Page->locked_by->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->locked_by->getPlaceHolder()) ?>"
        <?= $Page->locked_by->editAttributes() ?>>
        <?= $Page->locked_by->selectOptionListHtml("x_locked_by") ?>
    </select>
    <?= $Page->locked_by->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->locked_by->getErrorMessage() ?></div>
<?= $Page->locked_by->Lookup->getParamTag($Page, "p_x_locked_by") ?>
<?php if (!$Page->locked_by->IsNativeSelect) { ?>
<script>
loadjs.ready("ffile_in_projectadd", function() {
    var options = { name: "x_locked_by", selectId: "ffile_in_projectadd_x_locked_by" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ffile_in_projectadd.lists.locked_by?.lookupOptions.length) {
        options.data = { id: "x_locked_by", form: "ffile_in_projectadd" };
    } else {
        options.ajax = { id: "x_locked_by", form: "ffile_in_projectadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.file_in_project.fields.locked_by.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lock_date->Visible) { // lock_date ?>
    <div id="r_lock_date"<?= $Page->lock_date->rowAttributes() ?>>
        <label id="elh_file_in_project_lock_date" for="x_lock_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lock_date->caption() ?><?= $Page->lock_date->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->lock_date->cellAttributes() ?>>
<span id="el_file_in_project_lock_date">
<input type="<?= $Page->lock_date->getInputTextType() ?>" name="x_lock_date" id="x_lock_date" data-table="file_in_project" data-field="x_lock_date" value="<?= $Page->lock_date->EditValue ?>" placeholder="<?= HtmlEncode($Page->lock_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->lock_date->formatPattern()) ?>"<?= $Page->lock_date->editAttributes() ?> aria-describedby="x_lock_date_help">
<?= $Page->lock_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lock_date->getErrorMessage() ?></div>
<?php if (!$Page->lock_date->ReadOnly && !$Page->lock_date->Disabled && !isset($Page->lock_date->EditAttrs["readonly"]) && !isset($Page->lock_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["ffile_in_projectadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("ffile_in_projectadd", "x_lock_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ffile_in_projectadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ffile_in_projectadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("file_in_project");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
