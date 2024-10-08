<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$AuditTrailEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="faudit_trailedit" id="faudit_trailedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { audit_trail: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var faudit_trailedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("faudit_trailedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["datetime", [fields.datetime.visible && fields.datetime.required ? ew.Validators.required(fields.datetime.caption) : null, ew.Validators.datetime(fields.datetime.clientFormatPattern)], fields.datetime.isInvalid],
            ["script", [fields.script.visible && fields.script.required ? ew.Validators.required(fields.script.caption) : null], fields.script.isInvalid],
            ["user", [fields.user.visible && fields.user.required ? ew.Validators.required(fields.user.caption) : null], fields.user.isInvalid],
            ["_action", [fields._action.visible && fields._action.required ? ew.Validators.required(fields._action.caption) : null], fields._action.isInvalid],
            ["_table", [fields._table.visible && fields._table.required ? ew.Validators.required(fields._table.caption) : null], fields._table.isInvalid],
            ["field", [fields.field.visible && fields.field.required ? ew.Validators.required(fields.field.caption) : null], fields.field.isInvalid],
            ["keyvalue", [fields.keyvalue.visible && fields.keyvalue.required ? ew.Validators.required(fields.keyvalue.caption) : null], fields.keyvalue.isInvalid],
            ["oldvalue", [fields.oldvalue.visible && fields.oldvalue.required ? ew.Validators.required(fields.oldvalue.caption) : null], fields.oldvalue.isInvalid],
            ["newvalue", [fields.newvalue.visible && fields.newvalue.required ? ew.Validators.required(fields.newvalue.caption) : null], fields.newvalue.isInvalid]
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="audit_trail">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_audit_trail_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_audit_trail_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
<input type="hidden" data-table="audit_trail" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->datetime->Visible) { // datetime ?>
    <div id="r_datetime"<?= $Page->datetime->rowAttributes() ?>>
        <label id="elh_audit_trail_datetime" for="x_datetime" class="<?= $Page->LeftColumnClass ?>"><?= $Page->datetime->caption() ?><?= $Page->datetime->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->datetime->cellAttributes() ?>>
<span id="el_audit_trail_datetime">
<input type="<?= $Page->datetime->getInputTextType() ?>" name="x_datetime" id="x_datetime" data-table="audit_trail" data-field="x_datetime" value="<?= $Page->datetime->EditValue ?>" maxlength="19" placeholder="<?= HtmlEncode($Page->datetime->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->datetime->formatPattern()) ?>"<?= $Page->datetime->editAttributes() ?> aria-describedby="x_datetime_help">
<?= $Page->datetime->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->datetime->getErrorMessage() ?></div>
<?php if (!$Page->datetime->ReadOnly && !$Page->datetime->Disabled && !isset($Page->datetime->EditAttrs["readonly"]) && !isset($Page->datetime->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["faudit_trailedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("faudit_trailedit", "x_datetime", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->script->Visible) { // script ?>
    <div id="r_script"<?= $Page->script->rowAttributes() ?>>
        <label id="elh_audit_trail_script" for="x_script" class="<?= $Page->LeftColumnClass ?>"><?= $Page->script->caption() ?><?= $Page->script->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->script->cellAttributes() ?>>
<span id="el_audit_trail_script">
<input type="<?= $Page->script->getInputTextType() ?>" name="x_script" id="x_script" data-table="audit_trail" data-field="x_script" value="<?= $Page->script->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->script->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->script->formatPattern()) ?>"<?= $Page->script->editAttributes() ?> aria-describedby="x_script_help">
<?= $Page->script->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->script->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user->Visible) { // user ?>
    <div id="r_user"<?= $Page->user->rowAttributes() ?>>
        <label id="elh_audit_trail_user" for="x_user" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user->caption() ?><?= $Page->user->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user->cellAttributes() ?>>
<span id="el_audit_trail_user">
<input type="<?= $Page->user->getInputTextType() ?>" name="x_user" id="x_user" data-table="audit_trail" data-field="x_user" value="<?= $Page->user->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->user->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user->formatPattern()) ?>"<?= $Page->user->editAttributes() ?> aria-describedby="x_user_help">
<?= $Page->user->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
    <div id="r__action"<?= $Page->_action->rowAttributes() ?>>
        <label id="elh_audit_trail__action" for="x__action" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_action->caption() ?><?= $Page->_action->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_action->cellAttributes() ?>>
<span id="el_audit_trail__action">
<input type="<?= $Page->_action->getInputTextType() ?>" name="x__action" id="x__action" data-table="audit_trail" data-field="x__action" value="<?= $Page->_action->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->_action->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_action->formatPattern()) ?>"<?= $Page->_action->editAttributes() ?> aria-describedby="x__action_help">
<?= $Page->_action->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_action->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_table->Visible) { // table ?>
    <div id="r__table"<?= $Page->_table->rowAttributes() ?>>
        <label id="elh_audit_trail__table" for="x__table" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_table->caption() ?><?= $Page->_table->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_table->cellAttributes() ?>>
<span id="el_audit_trail__table">
<input type="<?= $Page->_table->getInputTextType() ?>" name="x__table" id="x__table" data-table="audit_trail" data-field="x__table" value="<?= $Page->_table->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->_table->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_table->formatPattern()) ?>"<?= $Page->_table->editAttributes() ?> aria-describedby="x__table_help">
<?= $Page->_table->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_table->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->field->Visible) { // field ?>
    <div id="r_field"<?= $Page->field->rowAttributes() ?>>
        <label id="elh_audit_trail_field" for="x_field" class="<?= $Page->LeftColumnClass ?>"><?= $Page->field->caption() ?><?= $Page->field->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->field->cellAttributes() ?>>
<span id="el_audit_trail_field">
<input type="<?= $Page->field->getInputTextType() ?>" name="x_field" id="x_field" data-table="audit_trail" data-field="x_field" value="<?= $Page->field->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->field->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->field->formatPattern()) ?>"<?= $Page->field->editAttributes() ?> aria-describedby="x_field_help">
<?= $Page->field->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->field->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->keyvalue->Visible) { // keyvalue ?>
    <div id="r_keyvalue"<?= $Page->keyvalue->rowAttributes() ?>>
        <label id="elh_audit_trail_keyvalue" for="x_keyvalue" class="<?= $Page->LeftColumnClass ?>"><?= $Page->keyvalue->caption() ?><?= $Page->keyvalue->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->keyvalue->cellAttributes() ?>>
<span id="el_audit_trail_keyvalue">
<textarea data-table="audit_trail" data-field="x_keyvalue" name="x_keyvalue" id="x_keyvalue" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->keyvalue->getPlaceHolder()) ?>"<?= $Page->keyvalue->editAttributes() ?> aria-describedby="x_keyvalue_help"><?= $Page->keyvalue->EditValue ?></textarea>
<?= $Page->keyvalue->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->keyvalue->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->oldvalue->Visible) { // oldvalue ?>
    <div id="r_oldvalue"<?= $Page->oldvalue->rowAttributes() ?>>
        <label id="elh_audit_trail_oldvalue" for="x_oldvalue" class="<?= $Page->LeftColumnClass ?>"><?= $Page->oldvalue->caption() ?><?= $Page->oldvalue->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->oldvalue->cellAttributes() ?>>
<span id="el_audit_trail_oldvalue">
<textarea data-table="audit_trail" data-field="x_oldvalue" name="x_oldvalue" id="x_oldvalue" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->oldvalue->getPlaceHolder()) ?>"<?= $Page->oldvalue->editAttributes() ?> aria-describedby="x_oldvalue_help"><?= $Page->oldvalue->EditValue ?></textarea>
<?= $Page->oldvalue->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->oldvalue->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->newvalue->Visible) { // newvalue ?>
    <div id="r_newvalue"<?= $Page->newvalue->rowAttributes() ?>>
        <label id="elh_audit_trail_newvalue" for="x_newvalue" class="<?= $Page->LeftColumnClass ?>"><?= $Page->newvalue->caption() ?><?= $Page->newvalue->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->newvalue->cellAttributes() ?>>
<span id="el_audit_trail_newvalue">
<textarea data-table="audit_trail" data-field="x_newvalue" name="x_newvalue" id="x_newvalue" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->newvalue->getPlaceHolder()) ?>"<?= $Page->newvalue->editAttributes() ?> aria-describedby="x_newvalue_help"><?= $Page->newvalue->EditValue ?></textarea>
<?= $Page->newvalue->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->newvalue->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="faudit_trailedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="faudit_trailedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("audit_trail");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
