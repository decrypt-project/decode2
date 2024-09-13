<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$RecordviewSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { recordview: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var frecordviewsearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("frecordviewsearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["id", [ew.Validators.integer], fields.id.isInvalid],
            ["name", [], fields.name.isInvalid],
            ["start_year", [ew.Validators.integer], fields.start_year.isInvalid],
            ["y_start_year", [ew.Validators.between], false],
            ["start_month", [ew.Validators.integer], fields.start_month.isInvalid],
            ["y_start_month", [ew.Validators.between], false],
            ["start_day", [ew.Validators.integer], fields.start_day.isInvalid],
            ["y_start_day", [ew.Validators.between], false],
            ["end_year", [ew.Validators.integer], fields.end_year.isInvalid],
            ["y_end_year", [ew.Validators.between], false],
            ["end_month", [ew.Validators.integer], fields.end_month.isInvalid],
            ["y_end_month", [ew.Validators.between], false],
            ["end_day", [ew.Validators.integer], fields.end_day.isInvalid],
            ["y_end_day", [ew.Validators.between], false],
            ["current_country", [], fields.current_country.isInvalid],
            ["current_city", [], fields.current_city.isInvalid],
            ["current_holder", [], fields.current_holder.isInvalid],
            ["author", [], fields.author.isInvalid],
            ["sender", [], fields.sender.isInvalid],
            ["receiver", [], fields.receiver.isInvalid],
            ["origin_region", [], fields.origin_region.isInvalid],
            ["origin_city", [], fields.origin_city.isInvalid],
            ["record_type", [], fields.record_type.isInvalid],
            ["status", [], fields.status.isInvalid],
            ["number_of_pages", [ew.Validators.integer], fields.number_of_pages.isInvalid],
            ["y_number_of_pages", [ew.Validators.between], false],
            ["inline_plaintext", [], fields.inline_plaintext.isInvalid],
            ["inline_cleartext", [], fields.inline_cleartext.isInvalid],
            ["cleartext_lang", [], fields.cleartext_lang.isInvalid],
            ["plaintext_lang", [], fields.plaintext_lang.isInvalid],
            ["cipher_types", [], fields.cipher_types.isInvalid],
            ["symbol_sets", [], fields.symbol_sets.isInvalid],
            ["transc_files", [], fields.transc_files.isInvalid],
            ["paper", [], fields.paper.isInvalid],
            ["additional_information", [], fields.additional_information.isInvalid],
            ["creation_date", [ew.Validators.datetime(fields.creation_date.clientFormatPattern)], fields.creation_date.isInvalid],
            ["y_creation_date", [ew.Validators.between], false],
            ["owner", [], fields.owner.isInvalid],
            ["creator_id", [ew.Validators.integer], fields.creator_id.isInvalid],
            ["y_creator_id", [ew.Validators.between], false],
            ["access_mode", [], fields.access_mode.isInvalid],
            ["link", [], fields.link.isInvalid]
        ])
        // Validate form
        .setValidate(
            async function () {
                if (!this.validateRequired)
                    return true; // Ignore validation
                let fobj = this.getForm();

                // Validate fields
                if (!this.validateFields())
                    return false;

                // Call Form_CustomValidate event
                if (!(await this.customValidate?.(fobj) ?? true)) {
                    this.focus();
                    return false;
                }
                return true;
            }
        )

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
            "record_type": <?= $Page->record_type->toClientList($Page) ?>,
            "status": <?= $Page->status->toClientList($Page) ?>,
            "inline_plaintext": <?= $Page->inline_plaintext->toClientList($Page) ?>,
            "inline_cleartext": <?= $Page->inline_cleartext->toClientList($Page) ?>,
            "cleartext_lang": <?= $Page->cleartext_lang->toClientList($Page) ?>,
            "plaintext_lang": <?= $Page->plaintext_lang->toClientList($Page) ?>,
            "cipher_types": <?= $Page->cipher_types->toClientList($Page) ?>,
            "symbol_sets": <?= $Page->symbol_sets->toClientList($Page) ?>,
            "creator_id": <?= $Page->creator_id->toClientList($Page) ?>,
            "access_mode": <?= $Page->access_mode->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
<?php if ($Page->IsModal) { ?>
    currentAdvancedSearchForm = form;
<?php } else { ?>
    currentForm = form;
<?php } ?>
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
<form name="frecordviewsearch" id="frecordviewsearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="on">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="recordview">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="row"<?= $Page->id->rowAttributes() ?>>
        <label for="x_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_id"><?= $Page->id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_id" id="z_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->id->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_id" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->id->getInputTextType() ?>" name="x_id" id="x_id" data-table="recordview" data-field="x_id" value="<?= $Page->id->EditValue ?>" placeholder="<?= HtmlEncode($Page->id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->id->formatPattern()) ?>"<?= $Page->id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->id->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <div id="r_name" class="row"<?= $Page->name->rowAttributes() ?>>
        <label for="x_name" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_name"><?= $Page->name->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_name" id="z_name" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->name->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_name" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->name->getInputTextType() ?>" name="x_name" id="x_name" data-table="recordview" data-field="x_name" value="<?= $Page->name->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->name->formatPattern()) ?>"<?= $Page->name->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->name->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->start_year->Visible) { // start_year ?>
    <div id="r_start_year" class="row"<?= $Page->start_year->rowAttributes() ?>>
        <label for="x_start_year" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_start_year"><?= $Page->start_year->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_start_year" id="z_start_year" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->start_year->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_start_year" class="ew-search-field">
<input type="<?= $Page->start_year->getInputTextType() ?>" name="x_start_year" id="x_start_year" data-table="recordview" data-field="x_start_year" value="<?= $Page->start_year->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->start_year->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_year->formatPattern()) ?>"<?= $Page->start_year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_year->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_recordview_start_year" class="ew-search-field2">
<input type="<?= $Page->start_year->getInputTextType() ?>" name="y_start_year" id="y_start_year" data-table="recordview" data-field="x_start_year" value="<?= $Page->start_year->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->start_year->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_year->formatPattern()) ?>"<?= $Page->start_year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_year->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->start_month->Visible) { // start_month ?>
    <div id="r_start_month" class="row"<?= $Page->start_month->rowAttributes() ?>>
        <label for="x_start_month" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_start_month"><?= $Page->start_month->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_start_month" id="z_start_month" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->start_month->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_start_month" class="ew-search-field">
<input type="<?= $Page->start_month->getInputTextType() ?>" name="x_start_month" id="x_start_month" data-table="recordview" data-field="x_start_month" value="<?= $Page->start_month->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->start_month->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_month->formatPattern()) ?>"<?= $Page->start_month->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_month->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_recordview_start_month" class="ew-search-field2">
<input type="<?= $Page->start_month->getInputTextType() ?>" name="y_start_month" id="y_start_month" data-table="recordview" data-field="x_start_month" value="<?= $Page->start_month->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->start_month->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_month->formatPattern()) ?>"<?= $Page->start_month->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_month->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->start_day->Visible) { // start_day ?>
    <div id="r_start_day" class="row"<?= $Page->start_day->rowAttributes() ?>>
        <label for="x_start_day" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_start_day"><?= $Page->start_day->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_start_day" id="z_start_day" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->start_day->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_start_day" class="ew-search-field">
<input type="<?= $Page->start_day->getInputTextType() ?>" name="x_start_day" id="x_start_day" data-table="recordview" data-field="x_start_day" value="<?= $Page->start_day->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->start_day->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_day->formatPattern()) ?>"<?= $Page->start_day->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_day->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_recordview_start_day" class="ew-search-field2">
<input type="<?= $Page->start_day->getInputTextType() ?>" name="y_start_day" id="y_start_day" data-table="recordview" data-field="x_start_day" value="<?= $Page->start_day->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->start_day->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_day->formatPattern()) ?>"<?= $Page->start_day->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_day->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->end_year->Visible) { // end_year ?>
    <div id="r_end_year" class="row"<?= $Page->end_year->rowAttributes() ?>>
        <label for="x_end_year" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_end_year"><?= $Page->end_year->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_end_year" id="z_end_year" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->end_year->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_end_year" class="ew-search-field">
<input type="<?= $Page->end_year->getInputTextType() ?>" name="x_end_year" id="x_end_year" data-table="recordview" data-field="x_end_year" value="<?= $Page->end_year->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->end_year->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_year->formatPattern()) ?>"<?= $Page->end_year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_year->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_recordview_end_year" class="ew-search-field2">
<input type="<?= $Page->end_year->getInputTextType() ?>" name="y_end_year" id="y_end_year" data-table="recordview" data-field="x_end_year" value="<?= $Page->end_year->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->end_year->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_year->formatPattern()) ?>"<?= $Page->end_year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_year->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->end_month->Visible) { // end_month ?>
    <div id="r_end_month" class="row"<?= $Page->end_month->rowAttributes() ?>>
        <label for="x_end_month" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_end_month"><?= $Page->end_month->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_end_month" id="z_end_month" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->end_month->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_end_month" class="ew-search-field">
<input type="<?= $Page->end_month->getInputTextType() ?>" name="x_end_month" id="x_end_month" data-table="recordview" data-field="x_end_month" value="<?= $Page->end_month->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->end_month->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_month->formatPattern()) ?>"<?= $Page->end_month->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_month->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_recordview_end_month" class="ew-search-field2">
<input type="<?= $Page->end_month->getInputTextType() ?>" name="y_end_month" id="y_end_month" data-table="recordview" data-field="x_end_month" value="<?= $Page->end_month->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->end_month->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_month->formatPattern()) ?>"<?= $Page->end_month->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_month->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->end_day->Visible) { // end_day ?>
    <div id="r_end_day" class="row"<?= $Page->end_day->rowAttributes() ?>>
        <label for="x_end_day" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_end_day"><?= $Page->end_day->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_end_day" id="z_end_day" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->end_day->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_end_day" class="ew-search-field">
<input type="<?= $Page->end_day->getInputTextType() ?>" name="x_end_day" id="x_end_day" data-table="recordview" data-field="x_end_day" value="<?= $Page->end_day->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->end_day->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_day->formatPattern()) ?>"<?= $Page->end_day->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_day->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_recordview_end_day" class="ew-search-field2">
<input type="<?= $Page->end_day->getInputTextType() ?>" name="y_end_day" id="y_end_day" data-table="recordview" data-field="x_end_day" value="<?= $Page->end_day->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->end_day->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_day->formatPattern()) ?>"<?= $Page->end_day->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_day->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->current_country->Visible) { // current_country ?>
    <div id="r_current_country" class="row"<?= $Page->current_country->rowAttributes() ?>>
        <label for="x_current_country" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_current_country"><?= $Page->current_country->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_current_country" id="z_current_country" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->current_country->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_current_country" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->current_country->getInputTextType() ?>" name="x_current_country" id="x_current_country" data-table="recordview" data-field="x_current_country" value="<?= $Page->current_country->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->current_country->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->current_country->formatPattern()) ?>"<?= $Page->current_country->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->current_country->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->current_city->Visible) { // current_city ?>
    <div id="r_current_city" class="row"<?= $Page->current_city->rowAttributes() ?>>
        <label for="x_current_city" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_current_city"><?= $Page->current_city->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_current_city" id="z_current_city" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->current_city->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_current_city" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->current_city->getInputTextType() ?>" name="x_current_city" id="x_current_city" data-table="recordview" data-field="x_current_city" value="<?= $Page->current_city->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->current_city->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->current_city->formatPattern()) ?>"<?= $Page->current_city->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->current_city->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->current_holder->Visible) { // current_holder ?>
    <div id="r_current_holder" class="row"<?= $Page->current_holder->rowAttributes() ?>>
        <label for="x_current_holder" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_current_holder"><?= $Page->current_holder->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_current_holder" id="z_current_holder" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->current_holder->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_current_holder" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->current_holder->getInputTextType() ?>" name="x_current_holder" id="x_current_holder" data-table="recordview" data-field="x_current_holder" value="<?= $Page->current_holder->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->current_holder->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->current_holder->formatPattern()) ?>"<?= $Page->current_holder->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->current_holder->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->author->Visible) { // author ?>
    <div id="r_author" class="row"<?= $Page->author->rowAttributes() ?>>
        <label for="x_author" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_author"><?= $Page->author->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_author" id="z_author" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->author->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_author" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->author->getInputTextType() ?>" name="x_author" id="x_author" data-table="recordview" data-field="x_author" value="<?= $Page->author->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->author->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->author->formatPattern()) ?>"<?= $Page->author->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->author->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->sender->Visible) { // sender ?>
    <div id="r_sender" class="row"<?= $Page->sender->rowAttributes() ?>>
        <label for="x_sender" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_sender"><?= $Page->sender->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_sender" id="z_sender" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->sender->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_sender" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->sender->getInputTextType() ?>" name="x_sender" id="x_sender" data-table="recordview" data-field="x_sender" value="<?= $Page->sender->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->sender->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->sender->formatPattern()) ?>"<?= $Page->sender->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->sender->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->receiver->Visible) { // receiver ?>
    <div id="r_receiver" class="row"<?= $Page->receiver->rowAttributes() ?>>
        <label for="x_receiver" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_receiver"><?= $Page->receiver->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_receiver" id="z_receiver" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->receiver->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_receiver" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->receiver->getInputTextType() ?>" name="x_receiver" id="x_receiver" data-table="recordview" data-field="x_receiver" value="<?= $Page->receiver->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->receiver->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->receiver->formatPattern()) ?>"<?= $Page->receiver->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->receiver->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->origin_region->Visible) { // origin_region ?>
    <div id="r_origin_region" class="row"<?= $Page->origin_region->rowAttributes() ?>>
        <label for="x_origin_region" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_origin_region"><?= $Page->origin_region->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_origin_region" id="z_origin_region" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->origin_region->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_origin_region" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->origin_region->getInputTextType() ?>" name="x_origin_region" id="x_origin_region" data-table="recordview" data-field="x_origin_region" value="<?= $Page->origin_region->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->origin_region->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->origin_region->formatPattern()) ?>"<?= $Page->origin_region->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->origin_region->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->origin_city->Visible) { // origin_city ?>
    <div id="r_origin_city" class="row"<?= $Page->origin_city->rowAttributes() ?>>
        <label for="x_origin_city" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_origin_city"><?= $Page->origin_city->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_origin_city" id="z_origin_city" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->origin_city->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_origin_city" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->origin_city->getInputTextType() ?>" name="x_origin_city" id="x_origin_city" data-table="recordview" data-field="x_origin_city" value="<?= $Page->origin_city->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->origin_city->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->origin_city->formatPattern()) ?>"<?= $Page->origin_city->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->origin_city->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->record_type->Visible) { // record_type ?>
    <div id="r_record_type" class="row"<?= $Page->record_type->rowAttributes() ?>>
        <label for="x_record_type" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_record_type"><?= $Page->record_type->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_record_type" id="z_record_type" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->record_type->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_record_type" class="ew-search-field ew-search-field-single">
    <select
        id="x_record_type"
        name="x_record_type"
        class="form-select ew-select<?= $Page->record_type->isInvalidClass() ?>"
        <?php if (!$Page->record_type->IsNativeSelect) { ?>
        data-select2-id="frecordviewsearch_x_record_type"
        <?php } ?>
        data-table="recordview"
        data-field="x_record_type"
        data-value-separator="<?= $Page->record_type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->record_type->getPlaceHolder()) ?>"
        <?= $Page->record_type->editAttributes() ?>>
        <?= $Page->record_type->selectOptionListHtml("x_record_type") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->record_type->getErrorMessage(false) ?></div>
<?php if (!$Page->record_type->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordviewsearch", function() {
    var options = { name: "x_record_type", selectId: "frecordviewsearch_x_record_type" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordviewsearch.lists.record_type?.lookupOptions.length) {
        options.data = { id: "x_record_type", form: "frecordviewsearch" };
    } else {
        options.ajax = { id: "x_record_type", form: "frecordviewsearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.recordview.fields.record_type.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status" class="row"<?= $Page->status->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_status"><?= $Page->status->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_status" id="z_status" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->status->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_status" class="ew-search-field ew-search-field-single">
<template id="tp_x_status">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="recordview" data-field="x_status" name="x_status" id="x_status"<?= $Page->status->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_status" class="ew-item-list"></div>
<selection-list hidden
    id="x_status[]"
    name="x_status[]"
    value="<?= HtmlEncode($Page->status->AdvancedSearch->SearchValue) ?>"
    data-type="select-multiple"
    data-template="tp_x_status"
    data-target="dsl_x_status"
    data-repeatcolumn="5"
    class="form-control<?= $Page->status->isInvalidClass() ?>"
    data-table="recordview"
    data-field="x_status"
    data-value-separator="<?= $Page->status->displayValueSeparatorAttribute() ?>"
    <?= $Page->status->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->number_of_pages->Visible) { // number_of_pages ?>
    <div id="r_number_of_pages" class="row"<?= $Page->number_of_pages->rowAttributes() ?>>
        <label for="x_number_of_pages" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_number_of_pages"><?= $Page->number_of_pages->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_number_of_pages" id="z_number_of_pages" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->number_of_pages->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_number_of_pages" class="ew-search-field">
<input type="<?= $Page->number_of_pages->getInputTextType() ?>" name="x_number_of_pages" id="x_number_of_pages" data-table="recordview" data-field="x_number_of_pages" value="<?= $Page->number_of_pages->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->number_of_pages->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->number_of_pages->formatPattern()) ?>"<?= $Page->number_of_pages->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->number_of_pages->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_recordview_number_of_pages" class="ew-search-field2">
<input type="<?= $Page->number_of_pages->getInputTextType() ?>" name="y_number_of_pages" id="y_number_of_pages" data-table="recordview" data-field="x_number_of_pages" value="<?= $Page->number_of_pages->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->number_of_pages->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->number_of_pages->formatPattern()) ?>"<?= $Page->number_of_pages->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->number_of_pages->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->inline_plaintext->Visible) { // inline_plaintext ?>
    <div id="r_inline_plaintext" class="row"<?= $Page->inline_plaintext->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_inline_plaintext"><?= $Page->inline_plaintext->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_inline_plaintext" id="z_inline_plaintext" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->inline_plaintext->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_inline_plaintext" class="ew-search-field ew-search-field-single">
<template id="tp_x_inline_plaintext">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="recordview" data-field="x_inline_plaintext" name="x_inline_plaintext" id="x_inline_plaintext"<?= $Page->inline_plaintext->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_inline_plaintext" class="ew-item-list"></div>
<selection-list hidden
    id="x_inline_plaintext"
    name="x_inline_plaintext"
    value="<?= HtmlEncode($Page->inline_plaintext->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_inline_plaintext"
    data-target="dsl_x_inline_plaintext"
    data-repeatcolumn="5"
    class="form-control<?= $Page->inline_plaintext->isInvalidClass() ?>"
    data-table="recordview"
    data-field="x_inline_plaintext"
    data-value-separator="<?= $Page->inline_plaintext->displayValueSeparatorAttribute() ?>"
    <?= $Page->inline_plaintext->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->inline_plaintext->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->inline_cleartext->Visible) { // inline_cleartext ?>
    <div id="r_inline_cleartext" class="row"<?= $Page->inline_cleartext->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_inline_cleartext"><?= $Page->inline_cleartext->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_inline_cleartext" id="z_inline_cleartext" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->inline_cleartext->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_inline_cleartext" class="ew-search-field ew-search-field-single">
<template id="tp_x_inline_cleartext">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="recordview" data-field="x_inline_cleartext" name="x_inline_cleartext" id="x_inline_cleartext"<?= $Page->inline_cleartext->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_inline_cleartext" class="ew-item-list"></div>
<selection-list hidden
    id="x_inline_cleartext"
    name="x_inline_cleartext"
    value="<?= HtmlEncode($Page->inline_cleartext->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_inline_cleartext"
    data-target="dsl_x_inline_cleartext"
    data-repeatcolumn="5"
    class="form-control<?= $Page->inline_cleartext->isInvalidClass() ?>"
    data-table="recordview"
    data-field="x_inline_cleartext"
    data-value-separator="<?= $Page->inline_cleartext->displayValueSeparatorAttribute() ?>"
    <?= $Page->inline_cleartext->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->inline_cleartext->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->cleartext_lang->Visible) { // cleartext_lang ?>
    <div id="r_cleartext_lang" class="row"<?= $Page->cleartext_lang->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_cleartext_lang"><?= $Page->cleartext_lang->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_cleartext_lang" id="z_cleartext_lang" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->cleartext_lang->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_cleartext_lang" class="ew-search-field ew-search-field-single">
<?php
if (IsRTL()) {
    $Page->cleartext_lang->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_cleartext_lang" class="ew-auto-suggest">
    <input type="<?= $Page->cleartext_lang->getInputTextType() ?>" class="form-control" name="sv_x_cleartext_lang" id="sv_x_cleartext_lang" value="<?= RemoveHtml($Page->cleartext_lang->EditValue) ?>" autocomplete="off" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->cleartext_lang->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->cleartext_lang->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cleartext_lang->formatPattern()) ?>"<?= $Page->cleartext_lang->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="recordview" data-field="x_cleartext_lang" data-input="sv_x_cleartext_lang" data-value-separator="<?= $Page->cleartext_lang->displayValueSeparatorAttribute() ?>" name="x_cleartext_lang" id="x_cleartext_lang" value="<?= HtmlEncode($Page->cleartext_lang->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->cleartext_lang->getErrorMessage(false) ?></div>
<script>
loadjs.ready("frecordviewsearch", function() {
    frecordviewsearch.createAutoSuggest(Object.assign({"id":"x_cleartext_lang","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->cleartext_lang->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.recordview.fields.cleartext_lang.autoSuggestOptions));
});
</script>
<?= $Page->cleartext_lang->Lookup->getParamTag($Page, "p_x_cleartext_lang") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->plaintext_lang->Visible) { // plaintext_lang ?>
    <div id="r_plaintext_lang" class="row"<?= $Page->plaintext_lang->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_plaintext_lang"><?= $Page->plaintext_lang->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_plaintext_lang" id="z_plaintext_lang" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->plaintext_lang->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_plaintext_lang" class="ew-search-field ew-search-field-single">
<?php
if (IsRTL()) {
    $Page->plaintext_lang->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_plaintext_lang" class="ew-auto-suggest">
    <input type="<?= $Page->plaintext_lang->getInputTextType() ?>" class="form-control" name="sv_x_plaintext_lang" id="sv_x_plaintext_lang" value="<?= RemoveHtml($Page->plaintext_lang->EditValue) ?>" autocomplete="off" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->plaintext_lang->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->plaintext_lang->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->plaintext_lang->formatPattern()) ?>"<?= $Page->plaintext_lang->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="recordview" data-field="x_plaintext_lang" data-input="sv_x_plaintext_lang" data-value-separator="<?= $Page->plaintext_lang->displayValueSeparatorAttribute() ?>" name="x_plaintext_lang" id="x_plaintext_lang" value="<?= HtmlEncode($Page->plaintext_lang->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->plaintext_lang->getErrorMessage(false) ?></div>
<script>
loadjs.ready("frecordviewsearch", function() {
    frecordviewsearch.createAutoSuggest(Object.assign({"id":"x_plaintext_lang","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->plaintext_lang->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.recordview.fields.plaintext_lang.autoSuggestOptions));
});
</script>
<?= $Page->plaintext_lang->Lookup->getParamTag($Page, "p_x_plaintext_lang") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->cipher_types->Visible) { // cipher_types ?>
    <div id="r_cipher_types" class="row"<?= $Page->cipher_types->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_cipher_types"><?= $Page->cipher_types->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_cipher_types" id="z_cipher_types" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->cipher_types->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_cipher_types" class="ew-search-field ew-search-field-single">
<template id="tp_x_cipher_types">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="recordview" data-field="x_cipher_types" name="x_cipher_types" id="x_cipher_types"<?= $Page->cipher_types->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_cipher_types" class="ew-item-list"></div>
<selection-list hidden
    id="x_cipher_types[]"
    name="x_cipher_types[]"
    value="<?= HtmlEncode($Page->cipher_types->AdvancedSearch->SearchValue) ?>"
    data-type="select-multiple"
    data-template="tp_x_cipher_types"
    data-target="dsl_x_cipher_types"
    data-repeatcolumn="5"
    class="form-control<?= $Page->cipher_types->isInvalidClass() ?>"
    data-table="recordview"
    data-field="x_cipher_types"
    data-value-separator="<?= $Page->cipher_types->displayValueSeparatorAttribute() ?>"
    <?= $Page->cipher_types->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->cipher_types->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->symbol_sets->Visible) { // symbol_sets ?>
    <div id="r_symbol_sets" class="row"<?= $Page->symbol_sets->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_symbol_sets"><?= $Page->symbol_sets->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_symbol_sets" id="z_symbol_sets" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->symbol_sets->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_symbol_sets" class="ew-search-field ew-search-field-single">
<template id="tp_x_symbol_sets">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="recordview" data-field="x_symbol_sets" name="x_symbol_sets" id="x_symbol_sets"<?= $Page->symbol_sets->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_symbol_sets" class="ew-item-list"></div>
<selection-list hidden
    id="x_symbol_sets[]"
    name="x_symbol_sets[]"
    value="<?= HtmlEncode($Page->symbol_sets->AdvancedSearch->SearchValue) ?>"
    data-type="select-multiple"
    data-template="tp_x_symbol_sets"
    data-target="dsl_x_symbol_sets"
    data-repeatcolumn="5"
    class="form-control<?= $Page->symbol_sets->isInvalidClass() ?>"
    data-table="recordview"
    data-field="x_symbol_sets"
    data-value-separator="<?= $Page->symbol_sets->displayValueSeparatorAttribute() ?>"
    <?= $Page->symbol_sets->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->symbol_sets->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->transc_files->Visible) { // transc_files ?>
    <div id="r_transc_files" class="row"<?= $Page->transc_files->rowAttributes() ?>>
        <label for="x_transc_files" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_transc_files"><?= $Page->transc_files->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_transc_files" id="z_transc_files" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->transc_files->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_transc_files" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->transc_files->getInputTextType() ?>" name="x_transc_files" id="x_transc_files" data-table="recordview" data-field="x_transc_files" value="<?= $Page->transc_files->EditValue ?>" size="35" maxlength="16777215" placeholder="<?= HtmlEncode($Page->transc_files->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->transc_files->formatPattern()) ?>"<?= $Page->transc_files->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->transc_files->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->paper->Visible) { // paper ?>
    <div id="r_paper" class="row"<?= $Page->paper->rowAttributes() ?>>
        <label for="x_paper" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_paper"><?= $Page->paper->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_paper" id="z_paper" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->paper->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_paper" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->paper->getInputTextType() ?>" name="x_paper" id="x_paper" data-table="recordview" data-field="x_paper" value="<?= $Page->paper->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->paper->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->paper->formatPattern()) ?>"<?= $Page->paper->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->paper->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->additional_information->Visible) { // additional_information ?>
    <div id="r_additional_information" class="row"<?= $Page->additional_information->rowAttributes() ?>>
        <label for="x_additional_information" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_additional_information"><?= $Page->additional_information->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_additional_information" id="z_additional_information" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->additional_information->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_additional_information" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->additional_information->getInputTextType() ?>" name="x_additional_information" id="x_additional_information" data-table="recordview" data-field="x_additional_information" value="<?= $Page->additional_information->EditValue ?>" size="35" placeholder="<?= HtmlEncode($Page->additional_information->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->additional_information->formatPattern()) ?>"<?= $Page->additional_information->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->additional_information->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
    <div id="r_creation_date" class="row"<?= $Page->creation_date->rowAttributes() ?>>
        <label for="x_creation_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_creation_date"><?= $Page->creation_date->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_creation_date" id="z_creation_date" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->creation_date->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_creation_date" class="ew-search-field">
<input type="<?= $Page->creation_date->getInputTextType() ?>" name="x_creation_date" id="x_creation_date" data-table="recordview" data-field="x_creation_date" value="<?= $Page->creation_date->EditValue ?>" placeholder="<?= HtmlEncode($Page->creation_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->creation_date->formatPattern()) ?>"<?= $Page->creation_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->creation_date->getErrorMessage(false) ?></div>
<?php if (!$Page->creation_date->ReadOnly && !$Page->creation_date->Disabled && !isset($Page->creation_date->EditAttrs["readonly"]) && !isset($Page->creation_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["frecordviewsearch", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
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
                    seconds: !!format.match(/s/i),
                    useTwentyfourHour: !!format.match(/H/)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("frecordviewsearch", "x_creation_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_recordview_creation_date" class="ew-search-field2">
<input type="<?= $Page->creation_date->getInputTextType() ?>" name="y_creation_date" id="y_creation_date" data-table="recordview" data-field="x_creation_date" value="<?= $Page->creation_date->EditValue2 ?>" placeholder="<?= HtmlEncode($Page->creation_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->creation_date->formatPattern()) ?>"<?= $Page->creation_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->creation_date->getErrorMessage(false) ?></div>
<?php if (!$Page->creation_date->ReadOnly && !$Page->creation_date->Disabled && !isset($Page->creation_date->EditAttrs["readonly"]) && !isset($Page->creation_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["frecordviewsearch", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
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
                    seconds: !!format.match(/s/i),
                    useTwentyfourHour: !!format.match(/H/)
                },
                theme: ew.isDark() ? "dark" : "auto"
            }
        };
    ew.createDateTimePicker("frecordviewsearch", "y_creation_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->owner->Visible) { // owner ?>
    <div id="r_owner" class="row"<?= $Page->owner->rowAttributes() ?>>
        <label for="x_owner" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_owner"><?= $Page->owner->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_owner" id="z_owner" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->owner->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_owner" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->owner->getInputTextType() ?>" name="x_owner" id="x_owner" data-table="recordview" data-field="x_owner" value="<?= $Page->owner->EditValue ?>" size="30" maxlength="128" placeholder="<?= HtmlEncode($Page->owner->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->owner->formatPattern()) ?>"<?= $Page->owner->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->owner->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->creator_id->Visible) { // creator_id ?>
    <div id="r_creator_id" class="row"<?= $Page->creator_id->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_creator_id"><?= $Page->creator_id->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->creator_id->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                    <span class="ew-search-operator">
<select name="z_creator_id" id="z_creator_id" class="form-select ew-operator-select" data-ew-action="search-operator">
<?php foreach ($Page->creator_id->SearchOperators as $opr) { ?>
<option value="<?= HtmlEncode($opr) ?>"<?= $Page->creator_id->AdvancedSearch->SearchOperator == $opr ? " selected" : "" ?>><?= $Language->phrase($opr == "=" ? "EQUAL" : $opr) ?></option>
<?php } ?>
</select>
</span>
                <span id="el_recordview_creator_id" class="ew-search-field">
<?php
if (IsRTL()) {
    $Page->creator_id->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_creator_id" class="ew-auto-suggest">
    <input type="<?= $Page->creator_id->getInputTextType() ?>" class="form-control" name="sv_x_creator_id" id="sv_x_creator_id" value="<?= RemoveHtml($Page->creator_id->EditValue) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Page->creator_id->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->creator_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->creator_id->formatPattern()) ?>"<?= $Page->creator_id->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="recordview" data-field="x_creator_id" data-input="sv_x_creator_id" data-value-separator="<?= $Page->creator_id->displayValueSeparatorAttribute() ?>" name="x_creator_id" id="x_creator_id" value="<?= HtmlEncode($Page->creator_id->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->creator_id->getErrorMessage(false) ?></div>
<script>
loadjs.ready("frecordviewsearch", function() {
    frecordviewsearch.createAutoSuggest(Object.assign({"id":"x_creator_id","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->creator_id->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.recordview.fields.creator_id.autoSuggestOptions));
});
</script>
<?= $Page->creator_id->Lookup->getParamTag($Page, "p_x_creator_id") ?>
</span>
                    <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_recordview_creator_id" class="ew-search-field2 d-none">
<?php
if (IsRTL()) {
    $Page->creator_id->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_y_creator_id" class="ew-auto-suggest">
    <input type="<?= $Page->creator_id->getInputTextType() ?>" class="form-control" name="sv_y_creator_id" id="sv_y_creator_id" value="<?= RemoveHtml($Page->creator_id->EditValue2) ?>" autocomplete="off" size="30" placeholder="<?= HtmlEncode($Page->creator_id->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->creator_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->creator_id->formatPattern()) ?>"<?= $Page->creator_id->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="recordview" data-field="x_creator_id" data-input="sv_y_creator_id" data-value-separator="<?= $Page->creator_id->displayValueSeparatorAttribute() ?>" name="y_creator_id" id="y_creator_id" value="<?= HtmlEncode($Page->creator_id->AdvancedSearch->SearchValue2) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->creator_id->getErrorMessage(false) ?></div>
<script>
loadjs.ready("frecordviewsearch", function() {
    frecordviewsearch.createAutoSuggest(Object.assign({"id":"y_creator_id","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->creator_id->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.recordview.fields.creator_id.autoSuggestOptions));
});
</script>
<?= $Page->creator_id->Lookup->getParamTag($Page, "p_y_creator_id") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->access_mode->Visible) { // access_mode ?>
    <div id="r_access_mode" class="row"<?= $Page->access_mode->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_access_mode"><?= $Page->access_mode->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_access_mode" id="z_access_mode" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->access_mode->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_access_mode" class="ew-search-field ew-search-field-single">
<template id="tp_x_access_mode">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="recordview" data-field="x_access_mode" name="x_access_mode" id="x_access_mode"<?= $Page->access_mode->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_access_mode" class="ew-item-list"></div>
<selection-list hidden
    id="x_access_mode"
    name="x_access_mode"
    value="<?= HtmlEncode($Page->access_mode->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_access_mode"
    data-target="dsl_x_access_mode"
    data-repeatcolumn="5"
    class="form-control<?= $Page->access_mode->isInvalidClass() ?>"
    data-table="recordview"
    data-field="x_access_mode"
    data-value-separator="<?= $Page->access_mode->displayValueSeparatorAttribute() ?>"
    <?= $Page->access_mode->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->access_mode->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->link->Visible) { // link ?>
    <div id="r_link" class="row"<?= $Page->link->rowAttributes() ?>>
        <label for="x_link" class="<?= $Page->LeftColumnClass ?>"><span id="elh_recordview_link"><?= $Page->link->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_link" id="z_link" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->link->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_recordview_link" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->link->getInputTextType() ?>" name="x_link" id="x_link" data-table="recordview" data-field="x_link" value="<?= $Page->link->EditValue ?>" size="30" maxlength="62" placeholder="<?= HtmlEncode($Page->link->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->link->formatPattern()) ?>"<?= $Page->link->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->link->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="frecordviewsearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="frecordviewsearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="frecordviewsearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("recordview");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    $("<div id=\"hloc\" class=\"inlinehead\">Location</div>").insertBefore("#r_current_country");
    $("<div id=\"horig\" class=\"inlinehead\">Origin</div>").insertBefore("#r_start_date");
    $("<div id=\"hcont\" class=\"inlinehead\">Content</div>").insertBefore("#r_record_type");
    //$("<div class=\"ew-table-select-row\">Documents</div>").insertBefore("#r_current_country");
    $("<div id=\"hform\" class=\"inlinehead\">Format</div>").insertBefore("#r_paper");
    $("<div id=\"hcre\" class=\"inlinehead\">Creation</div>").insertBefore("#r_author");
    $(`
    <div class="inlinehead">
    <a href="#hloc">Location</a> | 
    <a href="#horig">Origin</a> | 
    <a href="#hcont">Content</a> | 
    <a href="#hform">Format</a> | 
    <a href="#hcre">Creation</a> | 
    </div>
    `).insertBefore("#r_id");
    $("span.ew-search-operator:contains('contains')").css("visibility","hidden");
});
</script>
