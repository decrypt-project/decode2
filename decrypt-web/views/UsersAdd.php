<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$UsersAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { users: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fusersadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fusersadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid],
            ["_password", [fields._password.visible && fields._password.required ? ew.Validators.required(fields._password.caption) : null], fields._password.isInvalid],
            ["_userlevel", [fields._userlevel.visible && fields._userlevel.required ? ew.Validators.required(fields._userlevel.caption) : null], fields._userlevel.isInvalid],
            ["parent", [fields.parent.visible && fields.parent.required ? ew.Validators.required(fields.parent.caption) : null, ew.Validators.integer], fields.parent.isInvalid],
            ["_email", [fields._email.visible && fields._email.required ? ew.Validators.required(fields._email.caption) : null, ew.Validators.email], fields._email.isInvalid],
            ["activated", [fields.activated.visible && fields.activated.required ? ew.Validators.required(fields.activated.caption) : null, ew.Validators.integer], fields.activated.isInvalid],
            ["memo", [fields.memo.visible && fields.memo.required ? ew.Validators.required(fields.memo.caption) : null], fields.memo.isInvalid],
            ["terms_of_use", [fields.terms_of_use.visible && fields.terms_of_use.required ? ew.Validators.required(fields.terms_of_use.caption) : null], fields.terms_of_use.isInvalid],
            ["updated_at", [fields.updated_at.visible && fields.updated_at.required ? ew.Validators.required(fields.updated_at.caption) : null, ew.Validators.datetime(fields.updated_at.clientFormatPattern)], fields.updated_at.isInvalid],
            ["all_access", [fields.all_access.visible && fields.all_access.required ? ew.Validators.required(fields.all_access.caption) : null], fields.all_access.isInvalid],
            ["name", [fields.name.visible && fields.name.required ? ew.Validators.required(fields.name.caption) : null], fields.name.isInvalid],
            ["affiliation", [fields.affiliation.visible && fields.affiliation.required ? ew.Validators.required(fields.affiliation.caption) : null], fields.affiliation.isInvalid],
            ["background", [fields.background.visible && fields.background.required ? ew.Validators.required(fields.background.caption) : null], fields.background.isInvalid]
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
            "_userlevel": <?= $Page->_userlevel->toClientList($Page) ?>,
            "terms_of_use": <?= $Page->terms_of_use->toClientList($Page) ?>,
            "all_access": <?= $Page->all_access->toClientList($Page) ?>,
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
<form name="fusersadd" id="fusersadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <label id="elh_users__username" for="x__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_username->cellAttributes() ?>>
<span id="el_users__username">
<input type="<?= $Page->_username->getInputTextType() ?>" name="x__username" id="x__username" data-table="users" data-field="x__username" value="<?= $Page->_username->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_username->formatPattern()) ?>"<?= $Page->_username->editAttributes() ?> aria-describedby="x__username_help">
<?= $Page->_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_password->Visible) { // password ?>
    <div id="r__password"<?= $Page->_password->rowAttributes() ?>>
        <label id="elh_users__password" for="x__password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_password->caption() ?><?= $Page->_password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_password->cellAttributes() ?>>
<span id="el_users__password">
<div class="input-group">
    <input type="password" name="x__password" id="x__password" autocomplete="new-password" data-field="x__password" maxlength="256" placeholder="<?= HtmlEncode($Page->_password->getPlaceHolder()) ?>"<?= $Page->_password->editAttributes() ?> aria-describedby="x__password_help">
    <button type="button" class="btn btn-default ew-toggle-password rounded-end" data-ew-action="password"><i class="fa-solid fa-eye"></i></button>
</div>
<?= $Page->_password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_password->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_userlevel->Visible) { // userlevel ?>
    <div id="r__userlevel"<?= $Page->_userlevel->rowAttributes() ?>>
        <label id="elh_users__userlevel" for="x__userlevel" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_userlevel->caption() ?><?= $Page->_userlevel->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_userlevel->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el_users__userlevel">
<span class="form-control-plaintext"><?= $Page->_userlevel->getDisplayValue($Page->_userlevel->EditValue) ?></span>
</span>
<?php } else { ?>
<span id="el_users__userlevel">
    <select
        id="x__userlevel"
        name="x__userlevel"
        class="form-select ew-select<?= $Page->_userlevel->isInvalidClass() ?>"
        <?php if (!$Page->_userlevel->IsNativeSelect) { ?>
        data-select2-id="fusersadd_x__userlevel"
        <?php } ?>
        data-table="users"
        data-field="x__userlevel"
        data-value-separator="<?= $Page->_userlevel->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->_userlevel->getPlaceHolder()) ?>"
        <?= $Page->_userlevel->editAttributes() ?>>
        <?= $Page->_userlevel->selectOptionListHtml("x__userlevel") ?>
    </select>
    <?= $Page->_userlevel->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->_userlevel->getErrorMessage() ?></div>
<?php if (!$Page->_userlevel->IsNativeSelect) { ?>
<script>
loadjs.ready("fusersadd", function() {
    var options = { name: "x__userlevel", selectId: "fusersadd_x__userlevel" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fusersadd.lists._userlevel?.lookupOptions.length) {
        options.data = { id: "x__userlevel", form: "fusersadd" };
    } else {
        options.ajax = { id: "x__userlevel", form: "fusersadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.users.fields._userlevel.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->parent->Visible) { // parent ?>
    <div id="r_parent"<?= $Page->parent->rowAttributes() ?>>
        <label id="elh_users_parent" for="x_parent" class="<?= $Page->LeftColumnClass ?>"><?= $Page->parent->caption() ?><?= $Page->parent->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->parent->cellAttributes() ?>>
<span id="el_users_parent">
<input type="<?= $Page->parent->getInputTextType() ?>" name="x_parent" id="x_parent" data-table="users" data-field="x_parent" value="<?= $Page->parent->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->parent->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->parent->formatPattern()) ?>"<?= $Page->parent->editAttributes() ?> aria-describedby="x_parent_help">
<?= $Page->parent->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->parent->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
    <div id="r__email"<?= $Page->_email->rowAttributes() ?>>
        <label id="elh_users__email" for="x__email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_email->caption() ?><?= $Page->_email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_email->cellAttributes() ?>>
<span id="el_users__email">
<input type="<?= $Page->_email->getInputTextType() ?>" name="x__email" id="x__email" data-table="users" data-field="x__email" value="<?= $Page->_email->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->_email->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_email->formatPattern()) ?>"<?= $Page->_email->editAttributes() ?> aria-describedby="x__email_help">
<?= $Page->_email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_email->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activated->Visible) { // activated ?>
    <div id="r_activated"<?= $Page->activated->rowAttributes() ?>>
        <label id="elh_users_activated" for="x_activated" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activated->caption() ?><?= $Page->activated->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activated->cellAttributes() ?>>
<span id="el_users_activated">
<input type="<?= $Page->activated->getInputTextType() ?>" name="x_activated" id="x_activated" data-table="users" data-field="x_activated" value="<?= $Page->activated->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->activated->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->activated->formatPattern()) ?>"<?= $Page->activated->editAttributes() ?> aria-describedby="x_activated_help">
<?= $Page->activated->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->activated->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->memo->Visible) { // memo ?>
    <div id="r_memo"<?= $Page->memo->rowAttributes() ?>>
        <label id="elh_users_memo" for="x_memo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->memo->caption() ?><?= $Page->memo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->memo->cellAttributes() ?>>
<span id="el_users_memo">
<textarea data-table="users" data-field="x_memo" name="x_memo" id="x_memo" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->memo->getPlaceHolder()) ?>"<?= $Page->memo->editAttributes() ?> aria-describedby="x_memo_help"><?= $Page->memo->EditValue ?></textarea>
<?= $Page->memo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->memo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->terms_of_use->Visible) { // terms_of_use ?>
    <div id="r_terms_of_use"<?= $Page->terms_of_use->rowAttributes() ?>>
        <label id="elh_users_terms_of_use" class="<?= $Page->LeftColumnClass ?>"><?= $Page->terms_of_use->caption() ?><?= $Page->terms_of_use->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->terms_of_use->cellAttributes() ?>>
<span id="el_users_terms_of_use">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->terms_of_use->isInvalidClass() ?>" data-table="users" data-field="x_terms_of_use" data-boolean name="x_terms_of_use" id="x_terms_of_use" value="1"<?= ConvertToBool($Page->terms_of_use->CurrentValue) ? " checked" : "" ?><?= $Page->terms_of_use->editAttributes() ?> aria-describedby="x_terms_of_use_help">
    <div class="invalid-feedback"><?= $Page->terms_of_use->getErrorMessage() ?></div>
</div>
<?= $Page->terms_of_use->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
    <div id="r_updated_at"<?= $Page->updated_at->rowAttributes() ?>>
        <label id="elh_users_updated_at" for="x_updated_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->updated_at->caption() ?><?= $Page->updated_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->updated_at->cellAttributes() ?>>
<span id="el_users_updated_at">
<input type="<?= $Page->updated_at->getInputTextType() ?>" name="x_updated_at" id="x_updated_at" data-table="users" data-field="x_updated_at" value="<?= $Page->updated_at->EditValue ?>" maxlength="19" placeholder="<?= HtmlEncode($Page->updated_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->updated_at->formatPattern()) ?>"<?= $Page->updated_at->editAttributes() ?> aria-describedby="x_updated_at_help">
<?= $Page->updated_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->updated_at->getErrorMessage() ?></div>
<?php if (!$Page->updated_at->ReadOnly && !$Page->updated_at->Disabled && !isset($Page->updated_at->EditAttrs["readonly"]) && !isset($Page->updated_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fusersadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fusersadd", "x_updated_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->all_access->Visible) { // all_access ?>
    <div id="r_all_access"<?= $Page->all_access->rowAttributes() ?>>
        <label id="elh_users_all_access" class="<?= $Page->LeftColumnClass ?>"><?= $Page->all_access->caption() ?><?= $Page->all_access->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->all_access->cellAttributes() ?>>
<span id="el_users_all_access">
<template id="tp_x_all_access">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="users" data-field="x_all_access" name="x_all_access" id="x_all_access"<?= $Page->all_access->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_all_access" class="ew-item-list"></div>
<selection-list hidden
    id="x_all_access"
    name="x_all_access"
    value="<?= HtmlEncode($Page->all_access->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_all_access"
    data-target="dsl_x_all_access"
    data-repeatcolumn="5"
    class="form-control<?= $Page->all_access->isInvalidClass() ?>"
    data-table="users"
    data-field="x_all_access"
    data-value-separator="<?= $Page->all_access->displayValueSeparatorAttribute() ?>"
    <?= $Page->all_access->editAttributes() ?>></selection-list>
<?= $Page->all_access->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->all_access->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <div id="r_name"<?= $Page->name->rowAttributes() ?>>
        <label id="elh_users_name" for="x_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->name->caption() ?><?= $Page->name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->name->cellAttributes() ?>>
<span id="el_users_name">
<input type="<?= $Page->name->getInputTextType() ?>" name="x_name" id="x_name" data-table="users" data-field="x_name" value="<?= $Page->name->EditValue ?>" size="30" maxlength="128" placeholder="<?= HtmlEncode($Page->name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->name->formatPattern()) ?>"<?= $Page->name->editAttributes() ?> aria-describedby="x_name_help">
<?= $Page->name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->affiliation->Visible) { // affiliation ?>
    <div id="r_affiliation"<?= $Page->affiliation->rowAttributes() ?>>
        <label id="elh_users_affiliation" for="x_affiliation" class="<?= $Page->LeftColumnClass ?>"><?= $Page->affiliation->caption() ?><?= $Page->affiliation->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->affiliation->cellAttributes() ?>>
<span id="el_users_affiliation">
<input type="<?= $Page->affiliation->getInputTextType() ?>" name="x_affiliation" id="x_affiliation" data-table="users" data-field="x_affiliation" value="<?= $Page->affiliation->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->affiliation->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->affiliation->formatPattern()) ?>"<?= $Page->affiliation->editAttributes() ?> aria-describedby="x_affiliation_help">
<?= $Page->affiliation->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->affiliation->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->background->Visible) { // background ?>
    <div id="r_background"<?= $Page->background->rowAttributes() ?>>
        <label id="elh_users_background" for="x_background" class="<?= $Page->LeftColumnClass ?>"><?= $Page->background->caption() ?><?= $Page->background->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->background->cellAttributes() ?>>
<span id="el_users_background">
<input type="<?= $Page->background->getInputTextType() ?>" name="x_background" id="x_background" data-table="users" data-field="x_background" value="<?= $Page->background->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->background->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->background->formatPattern()) ?>"<?= $Page->background->editAttributes() ?> aria-describedby="x_background_help">
<?= $Page->background->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->background->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fusersadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fusersadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("users");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
