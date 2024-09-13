<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$RecordsSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { records: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var frecordssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("frecordssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["id", [ew.Validators.integer], fields.id.isInvalid],
            ["owner", [], fields.owner.isInvalid],
            ["c_holder", [], fields.c_holder.isInvalid],
            ["current_country", [], fields.current_country.isInvalid],
            ["author", [], fields.author.isInvalid],
            ["sender", [], fields.sender.isInvalid],
            ["receiver", [], fields.receiver.isInvalid],
            ["origin_region", [], fields.origin_region.isInvalid],
            ["origin_city", [], fields.origin_city.isInvalid],
            ["start_year", [ew.Validators.integer], fields.start_year.isInvalid],
            ["y_start_year", [ew.Validators.between], false],
            ["start_month", [ew.Validators.integer], fields.start_month.isInvalid],
            ["start_day", [ew.Validators.integer], fields.start_day.isInvalid],
            ["end_year", [ew.Validators.integer], fields.end_year.isInvalid],
            ["y_end_year", [ew.Validators.between], false],
            ["end_month", [ew.Validators.integer], fields.end_month.isInvalid],
            ["end_day", [ew.Validators.integer], fields.end_day.isInvalid],
            ["record_type", [], fields.record_type.isInvalid],
            ["status", [], fields.status.isInvalid],
            ["cipher_type_other", [], fields.cipher_type_other.isInvalid],
            ["symbol_set_other", [], fields.symbol_set_other.isInvalid],
            ["inline_cleartext", [], fields.inline_cleartext.isInvalid],
            ["inline_plaintext", [], fields.inline_plaintext.isInvalid],
            ["cleartext_lang", [], fields.cleartext_lang.isInvalid],
            ["plaintext_lang", [], fields.plaintext_lang.isInvalid],
            ["document_types", [], fields.document_types.isInvalid],
            ["paper", [], fields.paper.isInvalid],
            ["additional_information", [], fields.additional_information.isInvalid],
            ["creator_id", [], fields.creator_id.isInvalid],
            ["creation_date", [ew.Validators.datetime(fields.creation_date.clientFormatPattern)], fields.creation_date.isInvalid],
            ["y_creation_date", [ew.Validators.between], false],
            ["km_encoded_plaintext_type", [], fields.km_encoded_plaintext_type.isInvalid],
            ["km_numbers", [], fields.km_numbers.isInvalid],
            ["km_content_words", [], fields.km_content_words.isInvalid],
            ["km_function_words", [], fields.km_function_words.isInvalid],
            ["km_syllables", [], fields.km_syllables.isInvalid],
            ["km_morphological_endings", [], fields.km_morphological_endings.isInvalid],
            ["km_phrases", [], fields.km_phrases.isInvalid],
            ["km_sentences", [], fields.km_sentences.isInvalid],
            ["km_punctuation", [], fields.km_punctuation.isInvalid],
            ["km_nomenclature_size", [], fields.km_nomenclature_size.isInvalid],
            ["km_sections", [], fields.km_sections.isInvalid],
            ["km_headings", [], fields.km_headings.isInvalid],
            ["km_plaintext_arrangement", [], fields.km_plaintext_arrangement.isInvalid],
            ["km_ciphertext_arrangement", [], fields.km_ciphertext_arrangement.isInvalid],
            ["km_memorability", [], fields.km_memorability.isInvalid],
            ["km_symbol_set", [], fields.km_symbol_set.isInvalid],
            ["km_diacritics", [], fields.km_diacritics.isInvalid],
            ["km_code_length", [], fields.km_code_length.isInvalid],
            ["km_code_type", [], fields.km_code_type.isInvalid],
            ["km_metaphors", [], fields.km_metaphors.isInvalid],
            ["km_material_properties", [], fields.km_material_properties.isInvalid],
            ["km_instructions", [], fields.km_instructions.isInvalid]
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
            "current_country": <?= $Page->current_country->toClientList($Page) ?>,
            "author": <?= $Page->author->toClientList($Page) ?>,
            "sender": <?= $Page->sender->toClientList($Page) ?>,
            "receiver": <?= $Page->receiver->toClientList($Page) ?>,
            "origin_region": <?= $Page->origin_region->toClientList($Page) ?>,
            "origin_city": <?= $Page->origin_city->toClientList($Page) ?>,
            "record_type": <?= $Page->record_type->toClientList($Page) ?>,
            "status": <?= $Page->status->toClientList($Page) ?>,
            "inline_cleartext": <?= $Page->inline_cleartext->toClientList($Page) ?>,
            "inline_plaintext": <?= $Page->inline_plaintext->toClientList($Page) ?>,
            "document_types": <?= $Page->document_types->toClientList($Page) ?>,
            "km_encoded_plaintext_type": <?= $Page->km_encoded_plaintext_type->toClientList($Page) ?>,
            "km_numbers": <?= $Page->km_numbers->toClientList($Page) ?>,
            "km_content_words": <?= $Page->km_content_words->toClientList($Page) ?>,
            "km_function_words": <?= $Page->km_function_words->toClientList($Page) ?>,
            "km_syllables": <?= $Page->km_syllables->toClientList($Page) ?>,
            "km_morphological_endings": <?= $Page->km_morphological_endings->toClientList($Page) ?>,
            "km_phrases": <?= $Page->km_phrases->toClientList($Page) ?>,
            "km_sentences": <?= $Page->km_sentences->toClientList($Page) ?>,
            "km_punctuation": <?= $Page->km_punctuation->toClientList($Page) ?>,
            "km_nomenclature_size": <?= $Page->km_nomenclature_size->toClientList($Page) ?>,
            "km_sections": <?= $Page->km_sections->toClientList($Page) ?>,
            "km_headings": <?= $Page->km_headings->toClientList($Page) ?>,
            "km_plaintext_arrangement": <?= $Page->km_plaintext_arrangement->toClientList($Page) ?>,
            "km_ciphertext_arrangement": <?= $Page->km_ciphertext_arrangement->toClientList($Page) ?>,
            "km_memorability": <?= $Page->km_memorability->toClientList($Page) ?>,
            "km_symbol_set": <?= $Page->km_symbol_set->toClientList($Page) ?>,
            "km_diacritics": <?= $Page->km_diacritics->toClientList($Page) ?>,
            "km_code_length": <?= $Page->km_code_length->toClientList($Page) ?>,
            "km_code_type": <?= $Page->km_code_type->toClientList($Page) ?>,
            "km_metaphors": <?= $Page->km_metaphors->toClientList($Page) ?>,
            "km_material_properties": <?= $Page->km_material_properties->toClientList($Page) ?>,
            "km_instructions": <?= $Page->km_instructions->toClientList($Page) ?>,
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
<form name="frecordssearch" id="frecordssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="records">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="row"<?= $Page->id->rowAttributes() ?>>
        <label for="x_id" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_id"><?= $Page->id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_id" id="z_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->id->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_id" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->id->getInputTextType() ?>" name="x_id" id="x_id" data-table="records" data-field="x_id" value="<?= $Page->id->EditValue ?>" placeholder="<?= HtmlEncode($Page->id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->id->formatPattern()) ?>"<?= $Page->id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->id->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->owner->Visible) { // owner ?>
    <div id="r_owner" class="row"<?= $Page->owner->rowAttributes() ?>>
        <label for="x_owner" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_owner"><?= $Page->owner->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_owner" id="z_owner" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->owner->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_owner" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->owner->getInputTextType() ?>" name="x_owner" id="x_owner" data-table="records" data-field="x_owner" value="<?= $Page->owner->EditValue ?>" size="30" maxlength="128" placeholder="<?= HtmlEncode($Page->owner->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->owner->formatPattern()) ?>"<?= $Page->owner->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->owner->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->c_holder->Visible) { // c_holder ?>
    <div id="r_c_holder" class="row"<?= $Page->c_holder->rowAttributes() ?>>
        <label for="x_c_holder" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_c_holder"><?= $Page->c_holder->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_c_holder" id="z_c_holder" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->c_holder->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_c_holder" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->c_holder->getInputTextType() ?>" name="x_c_holder" id="x_c_holder" data-table="records" data-field="x_c_holder" value="<?= $Page->c_holder->EditValue ?>" size="35" maxlength="602" placeholder="<?= HtmlEncode($Page->c_holder->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->c_holder->formatPattern()) ?>"<?= $Page->c_holder->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->c_holder->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->current_country->Visible) { // current_country ?>
    <div id="r_current_country" class="row"<?= $Page->current_country->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_current_country"><?= $Page->current_country->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_current_country" id="z_current_country" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->current_country->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_current_country" class="ew-search-field ew-search-field-single">
<?php
if (IsRTL()) {
    $Page->current_country->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_current_country" class="ew-auto-suggest">
    <input type="<?= $Page->current_country->getInputTextType() ?>" class="form-control" name="sv_x_current_country" id="sv_x_current_country" value="<?= RemoveHtml($Page->current_country->EditValue) ?>" autocomplete="off" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->current_country->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->current_country->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->current_country->formatPattern()) ?>"<?= $Page->current_country->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_current_country" data-input="sv_x_current_country" data-value-separator="<?= $Page->current_country->displayValueSeparatorAttribute() ?>" name="x_current_country" id="x_current_country" value="<?= HtmlEncode($Page->current_country->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->current_country->getErrorMessage(false) ?></div>
<script>
loadjs.ready("frecordssearch", function() {
    frecordssearch.createAutoSuggest(Object.assign({"id":"x_current_country","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->current_country->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.current_country.autoSuggestOptions));
});
</script>
<?= $Page->current_country->Lookup->getParamTag($Page, "p_x_current_country") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->author->Visible) { // author ?>
    <div id="r_author" class="row"<?= $Page->author->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_author"><?= $Page->author->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_author" id="z_author" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->author->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_author" class="ew-search-field ew-search-field-single">
<?php
if (IsRTL()) {
    $Page->author->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_author" class="ew-auto-suggest">
    <input type="<?= $Page->author->getInputTextType() ?>" class="form-control" name="sv_x_author" id="sv_x_author" value="<?= RemoveHtml($Page->author->EditValue) ?>" autocomplete="off" size="50" maxlength="64" placeholder="<?= HtmlEncode($Page->author->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->author->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->author->formatPattern()) ?>"<?= $Page->author->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_author" data-input="sv_x_author" data-value-separator="<?= $Page->author->displayValueSeparatorAttribute() ?>" name="x_author" id="x_author" value="<?= HtmlEncode($Page->author->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->author->getErrorMessage(false) ?></div>
<script>
loadjs.ready("frecordssearch", function() {
    frecordssearch.createAutoSuggest(Object.assign({"id":"x_author","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->author->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.author.autoSuggestOptions));
});
</script>
<?= $Page->author->Lookup->getParamTag($Page, "p_x_author") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->sender->Visible) { // sender ?>
    <div id="r_sender" class="row"<?= $Page->sender->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_sender"><?= $Page->sender->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_sender" id="z_sender" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->sender->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_sender" class="ew-search-field ew-search-field-single">
<?php
if (IsRTL()) {
    $Page->sender->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_sender" class="ew-auto-suggest">
    <input type="<?= $Page->sender->getInputTextType() ?>" class="form-control" name="sv_x_sender" id="sv_x_sender" value="<?= RemoveHtml($Page->sender->EditValue) ?>" autocomplete="off" size="50" maxlength="64" placeholder="<?= HtmlEncode($Page->sender->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->sender->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->sender->formatPattern()) ?>"<?= $Page->sender->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_sender" data-input="sv_x_sender" data-value-separator="<?= $Page->sender->displayValueSeparatorAttribute() ?>" name="x_sender" id="x_sender" value="<?= HtmlEncode($Page->sender->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->sender->getErrorMessage(false) ?></div>
<script>
loadjs.ready("frecordssearch", function() {
    frecordssearch.createAutoSuggest(Object.assign({"id":"x_sender","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->sender->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.sender.autoSuggestOptions));
});
</script>
<?= $Page->sender->Lookup->getParamTag($Page, "p_x_sender") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->receiver->Visible) { // receiver ?>
    <div id="r_receiver" class="row"<?= $Page->receiver->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_receiver"><?= $Page->receiver->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_receiver" id="z_receiver" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->receiver->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_receiver" class="ew-search-field ew-search-field-single">
<?php
if (IsRTL()) {
    $Page->receiver->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_receiver" class="ew-auto-suggest">
    <input type="<?= $Page->receiver->getInputTextType() ?>" class="form-control" name="sv_x_receiver" id="sv_x_receiver" value="<?= RemoveHtml($Page->receiver->EditValue) ?>" autocomplete="off" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->receiver->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->receiver->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->receiver->formatPattern()) ?>"<?= $Page->receiver->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_receiver" data-input="sv_x_receiver" data-value-separator="<?= $Page->receiver->displayValueSeparatorAttribute() ?>" name="x_receiver" id="x_receiver" value="<?= HtmlEncode($Page->receiver->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->receiver->getErrorMessage(false) ?></div>
<script>
loadjs.ready("frecordssearch", function() {
    frecordssearch.createAutoSuggest(Object.assign({"id":"x_receiver","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->receiver->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.receiver.autoSuggestOptions));
});
</script>
<?= $Page->receiver->Lookup->getParamTag($Page, "p_x_receiver") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->origin_region->Visible) { // origin_region ?>
    <div id="r_origin_region" class="row"<?= $Page->origin_region->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_origin_region"><?= $Page->origin_region->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_origin_region" id="z_origin_region" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->origin_region->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_origin_region" class="ew-search-field ew-search-field-single">
<?php
if (IsRTL()) {
    $Page->origin_region->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_origin_region" class="ew-auto-suggest">
    <input type="<?= $Page->origin_region->getInputTextType() ?>" class="form-control" name="sv_x_origin_region" id="sv_x_origin_region" value="<?= RemoveHtml($Page->origin_region->EditValue) ?>" autocomplete="off" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->origin_region->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->origin_region->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->origin_region->formatPattern()) ?>"<?= $Page->origin_region->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_origin_region" data-input="sv_x_origin_region" data-value-separator="<?= $Page->origin_region->displayValueSeparatorAttribute() ?>" name="x_origin_region" id="x_origin_region" value="<?= HtmlEncode($Page->origin_region->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->origin_region->getErrorMessage(false) ?></div>
<script>
loadjs.ready("frecordssearch", function() {
    frecordssearch.createAutoSuggest(Object.assign({"id":"x_origin_region","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->origin_region->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.origin_region.autoSuggestOptions));
});
</script>
<?= $Page->origin_region->Lookup->getParamTag($Page, "p_x_origin_region") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->origin_city->Visible) { // origin_city ?>
    <div id="r_origin_city" class="row"<?= $Page->origin_city->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_origin_city"><?= $Page->origin_city->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_origin_city" id="z_origin_city" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->origin_city->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_origin_city" class="ew-search-field ew-search-field-single">
<?php
if (IsRTL()) {
    $Page->origin_city->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_origin_city" class="ew-auto-suggest">
    <input type="<?= $Page->origin_city->getInputTextType() ?>" class="form-control" name="sv_x_origin_city" id="sv_x_origin_city" value="<?= RemoveHtml($Page->origin_city->EditValue) ?>" autocomplete="off" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->origin_city->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->origin_city->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->origin_city->formatPattern()) ?>"<?= $Page->origin_city->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_origin_city" data-input="sv_x_origin_city" data-value-separator="<?= $Page->origin_city->displayValueSeparatorAttribute() ?>" name="x_origin_city" id="x_origin_city" value="<?= HtmlEncode($Page->origin_city->AdvancedSearch->SearchValue) ?>"></selection-list>
<div class="invalid-feedback"><?= $Page->origin_city->getErrorMessage(false) ?></div>
<script>
loadjs.ready("frecordssearch", function() {
    frecordssearch.createAutoSuggest(Object.assign({"id":"x_origin_city","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->origin_city->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.origin_city.autoSuggestOptions));
});
</script>
<?= $Page->origin_city->Lookup->getParamTag($Page, "p_x_origin_city") ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->start_year->Visible) { // start_year ?>
    <div id="r_start_year" class="row"<?= $Page->start_year->rowAttributes() ?>>
        <label for="x_start_year" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_start_year"><?= $Page->start_year->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_start_year" id="z_start_year" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->start_year->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_start_year" class="ew-search-field">
<input type="<?= $Page->start_year->getInputTextType() ?>" name="x_start_year" id="x_start_year" data-table="records" data-field="x_start_year" value="<?= $Page->start_year->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->start_year->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_year->formatPattern()) ?>"<?= $Page->start_year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_year->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_records_start_year" class="ew-search-field2">
<input type="<?= $Page->start_year->getInputTextType() ?>" name="y_start_year" id="y_start_year" data-table="records" data-field="x_start_year" value="<?= $Page->start_year->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->start_year->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_year->formatPattern()) ?>"<?= $Page->start_year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_year->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->start_month->Visible) { // start_month ?>
    <div id="r_start_month" class="row"<?= $Page->start_month->rowAttributes() ?>>
        <label for="x_start_month" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_start_month"><?= $Page->start_month->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_start_month" id="z_start_month" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->start_month->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_start_month" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->start_month->getInputTextType() ?>" name="x_start_month" id="x_start_month" data-table="records" data-field="x_start_month" value="<?= $Page->start_month->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->start_month->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_month->formatPattern()) ?>"<?= $Page->start_month->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_month->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->start_day->Visible) { // start_day ?>
    <div id="r_start_day" class="row"<?= $Page->start_day->rowAttributes() ?>>
        <label for="x_start_day" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_start_day"><?= $Page->start_day->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_start_day" id="z_start_day" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->start_day->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_start_day" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->start_day->getInputTextType() ?>" name="x_start_day" id="x_start_day" data-table="records" data-field="x_start_day" value="<?= $Page->start_day->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->start_day->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_day->formatPattern()) ?>"<?= $Page->start_day->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->start_day->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->end_year->Visible) { // end_year ?>
    <div id="r_end_year" class="row"<?= $Page->end_year->rowAttributes() ?>>
        <label for="x_end_year" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_end_year"><?= $Page->end_year->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_end_year" id="z_end_year" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->end_year->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_end_year" class="ew-search-field">
<input type="<?= $Page->end_year->getInputTextType() ?>" name="x_end_year" id="x_end_year" data-table="records" data-field="x_end_year" value="<?= $Page->end_year->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->end_year->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_year->formatPattern()) ?>"<?= $Page->end_year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_year->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_records_end_year" class="ew-search-field2">
<input type="<?= $Page->end_year->getInputTextType() ?>" name="y_end_year" id="y_end_year" data-table="records" data-field="x_end_year" value="<?= $Page->end_year->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->end_year->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_year->formatPattern()) ?>"<?= $Page->end_year->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_year->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->end_month->Visible) { // end_month ?>
    <div id="r_end_month" class="row"<?= $Page->end_month->rowAttributes() ?>>
        <label for="x_end_month" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_end_month"><?= $Page->end_month->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_end_month" id="z_end_month" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->end_month->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_end_month" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->end_month->getInputTextType() ?>" name="x_end_month" id="x_end_month" data-table="records" data-field="x_end_month" value="<?= $Page->end_month->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->end_month->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_month->formatPattern()) ?>"<?= $Page->end_month->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_month->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->end_day->Visible) { // end_day ?>
    <div id="r_end_day" class="row"<?= $Page->end_day->rowAttributes() ?>>
        <label for="x_end_day" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_end_day"><?= $Page->end_day->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_end_day" id="z_end_day" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->end_day->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_end_day" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->end_day->getInputTextType() ?>" name="x_end_day" id="x_end_day" data-table="records" data-field="x_end_day" value="<?= $Page->end_day->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->end_day->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_day->formatPattern()) ?>"<?= $Page->end_day->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->end_day->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->record_type->Visible) { // record_type ?>
    <div id="r_record_type" class="row"<?= $Page->record_type->rowAttributes() ?>>
        <label for="x_record_type" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_record_type"><?= $Page->record_type->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_record_type" id="z_record_type" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->record_type->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_record_type" class="ew-search-field ew-search-field-single">
    <select
        id="x_record_type"
        name="x_record_type"
        class="form-select ew-select<?= $Page->record_type->isInvalidClass() ?>"
        <?php if (!$Page->record_type->IsNativeSelect) { ?>
        data-select2-id="frecordssearch_x_record_type"
        <?php } ?>
        data-table="records"
        data-field="x_record_type"
        data-value-separator="<?= $Page->record_type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->record_type->getPlaceHolder()) ?>"
        <?= $Page->record_type->editAttributes() ?>>
        <?= $Page->record_type->selectOptionListHtml("x_record_type") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->record_type->getErrorMessage(false) ?></div>
<?php if (!$Page->record_type->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordssearch", function() {
    var options = { name: "x_record_type", selectId: "frecordssearch_x_record_type" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordssearch.lists.record_type?.lookupOptions.length) {
        options.data = { id: "x_record_type", form: "frecordssearch" };
    } else {
        options.ajax = { id: "x_record_type", form: "frecordssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.record_type.selectOptions);
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
        <label for="x_status" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_status"><?= $Page->status->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_status" id="z_status" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->status->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_status" class="ew-search-field ew-search-field-single">
    <select
        id="x_status"
        name="x_status"
        class="form-select ew-select<?= $Page->status->isInvalidClass() ?>"
        <?php if (!$Page->status->IsNativeSelect) { ?>
        data-select2-id="frecordssearch_x_status"
        <?php } ?>
        data-table="records"
        data-field="x_status"
        data-value-separator="<?= $Page->status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>"
        <?= $Page->status->editAttributes() ?>>
        <?= $Page->status->selectOptionListHtml("x_status") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->status->getErrorMessage(false) ?></div>
<?php if (!$Page->status->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordssearch", function() {
    var options = { name: "x_status", selectId: "frecordssearch_x_status" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordssearch.lists.status?.lookupOptions.length) {
        options.data = { id: "x_status", form: "frecordssearch" };
    } else {
        options.ajax = { id: "x_status", form: "frecordssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.status.selectOptions);
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
<?php if ($Page->cipher_type_other->Visible) { // cipher_type_other ?>
    <div id="r_cipher_type_other" class="row"<?= $Page->cipher_type_other->rowAttributes() ?>>
        <label for="x_cipher_type_other" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_cipher_type_other"><?= $Page->cipher_type_other->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_cipher_type_other" id="z_cipher_type_other" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->cipher_type_other->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_cipher_type_other" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->cipher_type_other->getInputTextType() ?>" name="x_cipher_type_other" id="x_cipher_type_other" data-table="records" data-field="x_cipher_type_other" value="<?= $Page->cipher_type_other->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->cipher_type_other->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cipher_type_other->formatPattern()) ?>"<?= $Page->cipher_type_other->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cipher_type_other->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->symbol_set_other->Visible) { // symbol_set_other ?>
    <div id="r_symbol_set_other" class="row"<?= $Page->symbol_set_other->rowAttributes() ?>>
        <label for="x_symbol_set_other" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_symbol_set_other"><?= $Page->symbol_set_other->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_symbol_set_other" id="z_symbol_set_other" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->symbol_set_other->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_symbol_set_other" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->symbol_set_other->getInputTextType() ?>" name="x_symbol_set_other" id="x_symbol_set_other" data-table="records" data-field="x_symbol_set_other" value="<?= $Page->symbol_set_other->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->symbol_set_other->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->symbol_set_other->formatPattern()) ?>"<?= $Page->symbol_set_other->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->symbol_set_other->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->inline_cleartext->Visible) { // inline_cleartext ?>
    <div id="r_inline_cleartext" class="row"<?= $Page->inline_cleartext->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_inline_cleartext"><?= $Page->inline_cleartext->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_inline_cleartext" id="z_inline_cleartext" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->inline_cleartext->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_inline_cleartext" class="ew-search-field ew-search-field-single">
<template id="tp_x_inline_cleartext">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_inline_cleartext" name="x_inline_cleartext" id="x_inline_cleartext"<?= $Page->inline_cleartext->editAttributes() ?>>
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
    data-table="records"
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
<?php if ($Page->inline_plaintext->Visible) { // inline_plaintext ?>
    <div id="r_inline_plaintext" class="row"<?= $Page->inline_plaintext->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_inline_plaintext"><?= $Page->inline_plaintext->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_inline_plaintext" id="z_inline_plaintext" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->inline_plaintext->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_inline_plaintext" class="ew-search-field ew-search-field-single">
<template id="tp_x_inline_plaintext">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_inline_plaintext" name="x_inline_plaintext" id="x_inline_plaintext"<?= $Page->inline_plaintext->editAttributes() ?>>
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
    data-table="records"
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
<?php if ($Page->cleartext_lang->Visible) { // cleartext_lang ?>
    <div id="r_cleartext_lang" class="row"<?= $Page->cleartext_lang->rowAttributes() ?>>
        <label for="x_cleartext_lang" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_cleartext_lang"><?= $Page->cleartext_lang->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_cleartext_lang" id="z_cleartext_lang" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->cleartext_lang->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_cleartext_lang" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->cleartext_lang->getInputTextType() ?>" name="x_cleartext_lang" id="x_cleartext_lang" data-table="records" data-field="x_cleartext_lang" value="<?= $Page->cleartext_lang->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->cleartext_lang->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cleartext_lang->formatPattern()) ?>"<?= $Page->cleartext_lang->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cleartext_lang->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->plaintext_lang->Visible) { // plaintext_lang ?>
    <div id="r_plaintext_lang" class="row"<?= $Page->plaintext_lang->rowAttributes() ?>>
        <label for="x_plaintext_lang" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_plaintext_lang"><?= $Page->plaintext_lang->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_plaintext_lang" id="z_plaintext_lang" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->plaintext_lang->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_plaintext_lang" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->plaintext_lang->getInputTextType() ?>" name="x_plaintext_lang" id="x_plaintext_lang" data-table="records" data-field="x_plaintext_lang" value="<?= $Page->plaintext_lang->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->plaintext_lang->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->plaintext_lang->formatPattern()) ?>"<?= $Page->plaintext_lang->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->plaintext_lang->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->document_types->Visible) { // document_types ?>
    <div id="r_document_types" class="row"<?= $Page->document_types->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_document_types"><?= $Page->document_types->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_document_types" id="z_document_types" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->document_types->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_document_types" class="ew-search-field ew-search-field-single">
<template id="tp_x_document_types">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="records" data-field="x_document_types" name="x_document_types" id="x_document_types"<?= $Page->document_types->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_document_types" class="ew-item-list"></div>
<selection-list hidden
    id="x_document_types[]"
    name="x_document_types[]"
    value="<?= HtmlEncode($Page->document_types->AdvancedSearch->SearchValue) ?>"
    data-type="select-multiple"
    data-template="tp_x_document_types"
    data-target="dsl_x_document_types"
    data-repeatcolumn="5"
    class="form-control<?= $Page->document_types->isInvalidClass() ?>"
    data-table="records"
    data-field="x_document_types"
    data-value-separator="<?= $Page->document_types->displayValueSeparatorAttribute() ?>"
    <?= $Page->document_types->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->document_types->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->paper->Visible) { // paper ?>
    <div id="r_paper" class="row"<?= $Page->paper->rowAttributes() ?>>
        <label for="x_paper" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_paper"><?= $Page->paper->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_paper" id="z_paper" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->paper->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_paper" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->paper->getInputTextType() ?>" name="x_paper" id="x_paper" data-table="records" data-field="x_paper" value="<?= $Page->paper->EditValue ?>" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->paper->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->paper->formatPattern()) ?>"<?= $Page->paper->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->paper->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->additional_information->Visible) { // additional_information ?>
    <div id="r_additional_information" class="row"<?= $Page->additional_information->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_additional_information"><?= $Page->additional_information->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_additional_information" id="z_additional_information" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->additional_information->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_additional_information" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->additional_information->getInputTextType() ?>" name="x_additional_information" id="x_additional_information" data-table="records" data-field="x_additional_information" value="<?= $Page->additional_information->EditValue ?>" size="35" maxlength="65535" placeholder="<?= HtmlEncode($Page->additional_information->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->additional_information->formatPattern()) ?>"<?= $Page->additional_information->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->additional_information->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->creator_id->Visible) { // creator_id ?>
    <div id="r_creator_id" class="row"<?= $Page->creator_id->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_creator_id"><?= $Page->creator_id->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_creator_id" id="z_creator_id" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->creator_id->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_creator_id" class="ew-search-field ew-search-field-single">
<input type="<?= $Page->creator_id->getInputTextType() ?>" name="x_creator_id" id="x_creator_id" data-table="records" data-field="x_creator_id" value="<?= $Page->creator_id->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->creator_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->creator_id->formatPattern()) ?>"<?= $Page->creator_id->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->creator_id->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
    <div id="r_creation_date" class="row"<?= $Page->creation_date->rowAttributes() ?>>
        <label for="x_creation_date" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_creation_date"><?= $Page->creation_date->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("BETWEEN") ?>
<input type="hidden" name="z_creation_date" id="z_creation_date" value="BETWEEN">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->creation_date->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_creation_date" class="ew-search-field">
<input type="<?= $Page->creation_date->getInputTextType() ?>" name="x_creation_date" id="x_creation_date" data-table="records" data-field="x_creation_date" value="<?= $Page->creation_date->EditValue ?>" maxlength="19" placeholder="<?= HtmlEncode($Page->creation_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->creation_date->formatPattern()) ?>"<?= $Page->creation_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->creation_date->getErrorMessage(false) ?></div>
<?php if (!$Page->creation_date->ReadOnly && !$Page->creation_date->Disabled && !isset($Page->creation_date->EditAttrs["readonly"]) && !isset($Page->creation_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["frecordssearch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("frecordssearch", "x_creation_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
                    <span class="ew-search-and"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_records_creation_date" class="ew-search-field2">
<input type="<?= $Page->creation_date->getInputTextType() ?>" name="y_creation_date" id="y_creation_date" data-table="records" data-field="x_creation_date" value="<?= $Page->creation_date->EditValue2 ?>" maxlength="19" placeholder="<?= HtmlEncode($Page->creation_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->creation_date->formatPattern()) ?>"<?= $Page->creation_date->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->creation_date->getErrorMessage(false) ?></div>
<?php if (!$Page->creation_date->ReadOnly && !$Page->creation_date->Disabled && !isset($Page->creation_date->EditAttrs["readonly"]) && !isset($Page->creation_date->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["frecordssearch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("frecordssearch", "y_creation_date", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_encoded_plaintext_type->Visible) { // km_encoded_plaintext_type ?>
    <div id="r_km_encoded_plaintext_type" class="row"<?= $Page->km_encoded_plaintext_type->rowAttributes() ?>>
        <label for="x_km_encoded_plaintext_type" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_encoded_plaintext_type"><?= $Page->km_encoded_plaintext_type->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_km_encoded_plaintext_type" id="z_km_encoded_plaintext_type" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_encoded_plaintext_type->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_encoded_plaintext_type" class="ew-search-field ew-search-field-single">
    <select
        id="x_km_encoded_plaintext_type[]"
        name="x_km_encoded_plaintext_type[]"
        class="form-select ew-select<?= $Page->km_encoded_plaintext_type->isInvalidClass() ?>"
        <?php if (!$Page->km_encoded_plaintext_type->IsNativeSelect) { ?>
        data-select2-id="frecordssearch_x_km_encoded_plaintext_type[]"
        <?php } ?>
        data-table="records"
        data-field="x_km_encoded_plaintext_type"
        multiple
        size="1"
        data-value-separator="<?= $Page->km_encoded_plaintext_type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->km_encoded_plaintext_type->getPlaceHolder()) ?>"
        <?= $Page->km_encoded_plaintext_type->editAttributes() ?>>
        <?= $Page->km_encoded_plaintext_type->selectOptionListHtml("x_km_encoded_plaintext_type[]") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->km_encoded_plaintext_type->getErrorMessage(false) ?></div>
<?php if (!$Page->km_encoded_plaintext_type->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordssearch", function() {
    var options = { name: "x_km_encoded_plaintext_type[]", selectId: "frecordssearch_x_km_encoded_plaintext_type[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordssearch.lists.km_encoded_plaintext_type?.lookupOptions.length) {
        options.data = { id: "x_km_encoded_plaintext_type[]", form: "frecordssearch" };
    } else {
        options.ajax = { id: "x_km_encoded_plaintext_type[]", form: "frecordssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_encoded_plaintext_type.selectOptions);
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
<?php if ($Page->km_numbers->Visible) { // km_numbers ?>
    <div id="r_km_numbers" class="row"<?= $Page->km_numbers->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_numbers"><?= $Page->km_numbers->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_numbers" id="z_km_numbers" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_numbers->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_numbers" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_numbers">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_numbers" name="x_km_numbers" id="x_km_numbers"<?= $Page->km_numbers->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_numbers" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_numbers"
    name="x_km_numbers"
    value="<?= HtmlEncode($Page->km_numbers->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_numbers"
    data-target="dsl_x_km_numbers"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_numbers->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_numbers"
    data-value-separator="<?= $Page->km_numbers->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_numbers->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_numbers->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_content_words->Visible) { // km_content_words ?>
    <div id="r_km_content_words" class="row"<?= $Page->km_content_words->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_content_words"><?= $Page->km_content_words->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_content_words" id="z_km_content_words" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_content_words->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_content_words" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_content_words">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_content_words" name="x_km_content_words" id="x_km_content_words"<?= $Page->km_content_words->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_content_words" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_content_words"
    name="x_km_content_words"
    value="<?= HtmlEncode($Page->km_content_words->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_content_words"
    data-target="dsl_x_km_content_words"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_content_words->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_content_words"
    data-value-separator="<?= $Page->km_content_words->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_content_words->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_content_words->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_function_words->Visible) { // km_function_words ?>
    <div id="r_km_function_words" class="row"<?= $Page->km_function_words->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_function_words"><?= $Page->km_function_words->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_function_words" id="z_km_function_words" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_function_words->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_function_words" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_function_words">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_function_words" name="x_km_function_words" id="x_km_function_words"<?= $Page->km_function_words->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_function_words" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_function_words"
    name="x_km_function_words"
    value="<?= HtmlEncode($Page->km_function_words->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_function_words"
    data-target="dsl_x_km_function_words"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_function_words->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_function_words"
    data-value-separator="<?= $Page->km_function_words->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_function_words->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_function_words->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_syllables->Visible) { // km_syllables ?>
    <div id="r_km_syllables" class="row"<?= $Page->km_syllables->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_syllables"><?= $Page->km_syllables->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_syllables" id="z_km_syllables" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_syllables->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_syllables" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_syllables">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_syllables" name="x_km_syllables" id="x_km_syllables"<?= $Page->km_syllables->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_syllables" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_syllables"
    name="x_km_syllables"
    value="<?= HtmlEncode($Page->km_syllables->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_syllables"
    data-target="dsl_x_km_syllables"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_syllables->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_syllables"
    data-value-separator="<?= $Page->km_syllables->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_syllables->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_syllables->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_morphological_endings->Visible) { // km_morphological_endings ?>
    <div id="r_km_morphological_endings" class="row"<?= $Page->km_morphological_endings->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_morphological_endings"><?= $Page->km_morphological_endings->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_morphological_endings" id="z_km_morphological_endings" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_morphological_endings->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_morphological_endings" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_morphological_endings">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_morphological_endings" name="x_km_morphological_endings" id="x_km_morphological_endings"<?= $Page->km_morphological_endings->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_morphological_endings" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_morphological_endings"
    name="x_km_morphological_endings"
    value="<?= HtmlEncode($Page->km_morphological_endings->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_morphological_endings"
    data-target="dsl_x_km_morphological_endings"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_morphological_endings->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_morphological_endings"
    data-value-separator="<?= $Page->km_morphological_endings->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_morphological_endings->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_morphological_endings->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_phrases->Visible) { // km_phrases ?>
    <div id="r_km_phrases" class="row"<?= $Page->km_phrases->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_phrases"><?= $Page->km_phrases->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_phrases" id="z_km_phrases" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_phrases->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_phrases" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_phrases">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_phrases" name="x_km_phrases" id="x_km_phrases"<?= $Page->km_phrases->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_phrases" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_phrases"
    name="x_km_phrases"
    value="<?= HtmlEncode($Page->km_phrases->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_phrases"
    data-target="dsl_x_km_phrases"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_phrases->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_phrases"
    data-value-separator="<?= $Page->km_phrases->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_phrases->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_phrases->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_sentences->Visible) { // km_sentences ?>
    <div id="r_km_sentences" class="row"<?= $Page->km_sentences->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_sentences"><?= $Page->km_sentences->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_sentences" id="z_km_sentences" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_sentences->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_sentences" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_sentences">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_sentences" name="x_km_sentences" id="x_km_sentences"<?= $Page->km_sentences->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_sentences" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_sentences"
    name="x_km_sentences"
    value="<?= HtmlEncode($Page->km_sentences->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_sentences"
    data-target="dsl_x_km_sentences"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_sentences->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_sentences"
    data-value-separator="<?= $Page->km_sentences->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_sentences->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_sentences->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_punctuation->Visible) { // km_punctuation ?>
    <div id="r_km_punctuation" class="row"<?= $Page->km_punctuation->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_punctuation"><?= $Page->km_punctuation->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_punctuation" id="z_km_punctuation" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_punctuation->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_punctuation" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_punctuation">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_punctuation" name="x_km_punctuation" id="x_km_punctuation"<?= $Page->km_punctuation->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_punctuation" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_punctuation"
    name="x_km_punctuation"
    value="<?= HtmlEncode($Page->km_punctuation->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_punctuation"
    data-target="dsl_x_km_punctuation"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_punctuation->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_punctuation"
    data-value-separator="<?= $Page->km_punctuation->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_punctuation->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_punctuation->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_nomenclature_size->Visible) { // km_nomenclature_size ?>
    <div id="r_km_nomenclature_size" class="row"<?= $Page->km_nomenclature_size->rowAttributes() ?>>
        <label for="x_km_nomenclature_size" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_nomenclature_size"><?= $Page->km_nomenclature_size->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_km_nomenclature_size" id="z_km_nomenclature_size" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_nomenclature_size->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_nomenclature_size" class="ew-search-field ew-search-field-single">
    <select
        id="x_km_nomenclature_size"
        name="x_km_nomenclature_size"
        class="form-select ew-select<?= $Page->km_nomenclature_size->isInvalidClass() ?>"
        <?php if (!$Page->km_nomenclature_size->IsNativeSelect) { ?>
        data-select2-id="frecordssearch_x_km_nomenclature_size"
        <?php } ?>
        data-table="records"
        data-field="x_km_nomenclature_size"
        data-value-separator="<?= $Page->km_nomenclature_size->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->km_nomenclature_size->getPlaceHolder()) ?>"
        <?= $Page->km_nomenclature_size->editAttributes() ?>>
        <?= $Page->km_nomenclature_size->selectOptionListHtml("x_km_nomenclature_size") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->km_nomenclature_size->getErrorMessage(false) ?></div>
<?php if (!$Page->km_nomenclature_size->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordssearch", function() {
    var options = { name: "x_km_nomenclature_size", selectId: "frecordssearch_x_km_nomenclature_size" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordssearch.lists.km_nomenclature_size?.lookupOptions.length) {
        options.data = { id: "x_km_nomenclature_size", form: "frecordssearch" };
    } else {
        options.ajax = { id: "x_km_nomenclature_size", form: "frecordssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_nomenclature_size.selectOptions);
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
<?php if ($Page->km_sections->Visible) { // km_sections ?>
    <div id="r_km_sections" class="row"<?= $Page->km_sections->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_sections"><?= $Page->km_sections->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_sections" id="z_km_sections" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_sections->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_sections" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_sections">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_sections" name="x_km_sections" id="x_km_sections"<?= $Page->km_sections->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_sections" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_sections"
    name="x_km_sections"
    value="<?= HtmlEncode($Page->km_sections->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_sections"
    data-target="dsl_x_km_sections"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_sections->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_sections"
    data-value-separator="<?= $Page->km_sections->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_sections->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_sections->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_headings->Visible) { // km_headings ?>
    <div id="r_km_headings" class="row"<?= $Page->km_headings->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_headings"><?= $Page->km_headings->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_headings" id="z_km_headings" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_headings->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_headings" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_headings">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_headings" name="x_km_headings" id="x_km_headings"<?= $Page->km_headings->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_headings" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_headings"
    name="x_km_headings"
    value="<?= HtmlEncode($Page->km_headings->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_headings"
    data-target="dsl_x_km_headings"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_headings->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_headings"
    data-value-separator="<?= $Page->km_headings->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_headings->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_headings->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_plaintext_arrangement->Visible) { // km_plaintext_arrangement ?>
    <div id="r_km_plaintext_arrangement" class="row"<?= $Page->km_plaintext_arrangement->rowAttributes() ?>>
        <label for="x_km_plaintext_arrangement" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_plaintext_arrangement"><?= $Page->km_plaintext_arrangement->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_km_plaintext_arrangement" id="z_km_plaintext_arrangement" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_plaintext_arrangement->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_plaintext_arrangement" class="ew-search-field ew-search-field-single">
    <select
        id="x_km_plaintext_arrangement[]"
        name="x_km_plaintext_arrangement[]"
        class="form-select ew-select<?= $Page->km_plaintext_arrangement->isInvalidClass() ?>"
        <?php if (!$Page->km_plaintext_arrangement->IsNativeSelect) { ?>
        data-select2-id="frecordssearch_x_km_plaintext_arrangement[]"
        <?php } ?>
        data-table="records"
        data-field="x_km_plaintext_arrangement"
        multiple
        size="1"
        data-value-separator="<?= $Page->km_plaintext_arrangement->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->km_plaintext_arrangement->getPlaceHolder()) ?>"
        <?= $Page->km_plaintext_arrangement->editAttributes() ?>>
        <?= $Page->km_plaintext_arrangement->selectOptionListHtml("x_km_plaintext_arrangement[]") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->km_plaintext_arrangement->getErrorMessage(false) ?></div>
<?php if (!$Page->km_plaintext_arrangement->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordssearch", function() {
    var options = { name: "x_km_plaintext_arrangement[]", selectId: "frecordssearch_x_km_plaintext_arrangement[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordssearch.lists.km_plaintext_arrangement?.lookupOptions.length) {
        options.data = { id: "x_km_plaintext_arrangement[]", form: "frecordssearch" };
    } else {
        options.ajax = { id: "x_km_plaintext_arrangement[]", form: "frecordssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_plaintext_arrangement.selectOptions);
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
<?php if ($Page->km_ciphertext_arrangement->Visible) { // km_ciphertext_arrangement ?>
    <div id="r_km_ciphertext_arrangement" class="row"<?= $Page->km_ciphertext_arrangement->rowAttributes() ?>>
        <label for="x_km_ciphertext_arrangement" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_ciphertext_arrangement"><?= $Page->km_ciphertext_arrangement->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_km_ciphertext_arrangement" id="z_km_ciphertext_arrangement" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_ciphertext_arrangement->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_ciphertext_arrangement" class="ew-search-field ew-search-field-single">
    <select
        id="x_km_ciphertext_arrangement[]"
        name="x_km_ciphertext_arrangement[]"
        class="form-select ew-select<?= $Page->km_ciphertext_arrangement->isInvalidClass() ?>"
        <?php if (!$Page->km_ciphertext_arrangement->IsNativeSelect) { ?>
        data-select2-id="frecordssearch_x_km_ciphertext_arrangement[]"
        <?php } ?>
        data-table="records"
        data-field="x_km_ciphertext_arrangement"
        multiple
        size="1"
        data-value-separator="<?= $Page->km_ciphertext_arrangement->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->km_ciphertext_arrangement->getPlaceHolder()) ?>"
        <?= $Page->km_ciphertext_arrangement->editAttributes() ?>>
        <?= $Page->km_ciphertext_arrangement->selectOptionListHtml("x_km_ciphertext_arrangement[]") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->km_ciphertext_arrangement->getErrorMessage(false) ?></div>
<?php if (!$Page->km_ciphertext_arrangement->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordssearch", function() {
    var options = { name: "x_km_ciphertext_arrangement[]", selectId: "frecordssearch_x_km_ciphertext_arrangement[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordssearch.lists.km_ciphertext_arrangement?.lookupOptions.length) {
        options.data = { id: "x_km_ciphertext_arrangement[]", form: "frecordssearch" };
    } else {
        options.ajax = { id: "x_km_ciphertext_arrangement[]", form: "frecordssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_ciphertext_arrangement.selectOptions);
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
<?php if ($Page->km_memorability->Visible) { // km_memorability ?>
    <div id="r_km_memorability" class="row"<?= $Page->km_memorability->rowAttributes() ?>>
        <label for="x_km_memorability" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_memorability"><?= $Page->km_memorability->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_km_memorability" id="z_km_memorability" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_memorability->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_memorability" class="ew-search-field ew-search-field-single">
    <select
        id="x_km_memorability"
        name="x_km_memorability"
        class="form-select ew-select<?= $Page->km_memorability->isInvalidClass() ?>"
        <?php if (!$Page->km_memorability->IsNativeSelect) { ?>
        data-select2-id="frecordssearch_x_km_memorability"
        <?php } ?>
        data-table="records"
        data-field="x_km_memorability"
        data-value-separator="<?= $Page->km_memorability->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->km_memorability->getPlaceHolder()) ?>"
        <?= $Page->km_memorability->editAttributes() ?>>
        <?= $Page->km_memorability->selectOptionListHtml("x_km_memorability") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->km_memorability->getErrorMessage(false) ?></div>
<?php if (!$Page->km_memorability->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordssearch", function() {
    var options = { name: "x_km_memorability", selectId: "frecordssearch_x_km_memorability" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordssearch.lists.km_memorability?.lookupOptions.length) {
        options.data = { id: "x_km_memorability", form: "frecordssearch" };
    } else {
        options.ajax = { id: "x_km_memorability", form: "frecordssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_memorability.selectOptions);
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
<?php if ($Page->km_symbol_set->Visible) { // km_symbol_set ?>
    <div id="r_km_symbol_set" class="row"<?= $Page->km_symbol_set->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_symbol_set"><?= $Page->km_symbol_set->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_km_symbol_set" id="z_km_symbol_set" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_symbol_set->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_symbol_set" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_symbol_set">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="records" data-field="x_km_symbol_set" name="x_km_symbol_set" id="x_km_symbol_set"<?= $Page->km_symbol_set->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_symbol_set" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_symbol_set[]"
    name="x_km_symbol_set[]"
    value="<?= HtmlEncode($Page->km_symbol_set->AdvancedSearch->SearchValue) ?>"
    data-type="select-multiple"
    data-template="tp_x_km_symbol_set"
    data-target="dsl_x_km_symbol_set"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_symbol_set->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_symbol_set"
    data-value-separator="<?= $Page->km_symbol_set->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_symbol_set->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_symbol_set->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_diacritics->Visible) { // km_diacritics ?>
    <div id="r_km_diacritics" class="row"<?= $Page->km_diacritics->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_diacritics"><?= $Page->km_diacritics->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_diacritics" id="z_km_diacritics" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_diacritics->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_diacritics" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_diacritics">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_diacritics" name="x_km_diacritics" id="x_km_diacritics"<?= $Page->km_diacritics->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_diacritics" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_diacritics"
    name="x_km_diacritics"
    value="<?= HtmlEncode($Page->km_diacritics->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_diacritics"
    data-target="dsl_x_km_diacritics"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_diacritics->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_diacritics"
    data-value-separator="<?= $Page->km_diacritics->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_diacritics->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_diacritics->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_code_length->Visible) { // km_code_length ?>
    <div id="r_km_code_length" class="row"<?= $Page->km_code_length->rowAttributes() ?>>
        <label for="x_km_code_length" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_code_length"><?= $Page->km_code_length->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_km_code_length" id="z_km_code_length" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_code_length->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_code_length" class="ew-search-field ew-search-field-single">
    <select
        id="x_km_code_length[]"
        name="x_km_code_length[]"
        class="form-select ew-select<?= $Page->km_code_length->isInvalidClass() ?>"
        <?php if (!$Page->km_code_length->IsNativeSelect) { ?>
        data-select2-id="frecordssearch_x_km_code_length[]"
        <?php } ?>
        data-table="records"
        data-field="x_km_code_length"
        multiple
        size="1"
        data-value-separator="<?= $Page->km_code_length->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->km_code_length->getPlaceHolder()) ?>"
        <?= $Page->km_code_length->editAttributes() ?>>
        <?= $Page->km_code_length->selectOptionListHtml("x_km_code_length[]") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->km_code_length->getErrorMessage(false) ?></div>
<?php if (!$Page->km_code_length->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordssearch", function() {
    var options = { name: "x_km_code_length[]", selectId: "frecordssearch_x_km_code_length[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordssearch.lists.km_code_length?.lookupOptions.length) {
        options.data = { id: "x_km_code_length[]", form: "frecordssearch" };
    } else {
        options.ajax = { id: "x_km_code_length[]", form: "frecordssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_code_length.selectOptions);
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
<?php if ($Page->km_code_type->Visible) { // km_code_type ?>
    <div id="r_km_code_type" class="row"<?= $Page->km_code_type->rowAttributes() ?>>
        <label for="x_km_code_type" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_code_type"><?= $Page->km_code_type->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_km_code_type" id="z_km_code_type" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_code_type->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_code_type" class="ew-search-field ew-search-field-single">
    <select
        id="x_km_code_type[]"
        name="x_km_code_type[]"
        class="form-select ew-select<?= $Page->km_code_type->isInvalidClass() ?>"
        <?php if (!$Page->km_code_type->IsNativeSelect) { ?>
        data-select2-id="frecordssearch_x_km_code_type[]"
        <?php } ?>
        data-table="records"
        data-field="x_km_code_type"
        multiple
        size="1"
        data-value-separator="<?= $Page->km_code_type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->km_code_type->getPlaceHolder()) ?>"
        <?= $Page->km_code_type->editAttributes() ?>>
        <?= $Page->km_code_type->selectOptionListHtml("x_km_code_type[]") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->km_code_type->getErrorMessage(false) ?></div>
<?php if (!$Page->km_code_type->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordssearch", function() {
    var options = { name: "x_km_code_type[]", selectId: "frecordssearch_x_km_code_type[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordssearch.lists.km_code_type?.lookupOptions.length) {
        options.data = { id: "x_km_code_type[]", form: "frecordssearch" };
    } else {
        options.ajax = { id: "x_km_code_type[]", form: "frecordssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_code_type.selectOptions);
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
<?php if ($Page->km_metaphors->Visible) { // km_metaphors ?>
    <div id="r_km_metaphors" class="row"<?= $Page->km_metaphors->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_metaphors"><?= $Page->km_metaphors->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_metaphors" id="z_km_metaphors" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_metaphors->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_metaphors" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_metaphors">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_metaphors" name="x_km_metaphors" id="x_km_metaphors"<?= $Page->km_metaphors->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_metaphors" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_metaphors"
    name="x_km_metaphors"
    value="<?= HtmlEncode($Page->km_metaphors->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_metaphors"
    data-target="dsl_x_km_metaphors"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_metaphors->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_metaphors"
    data-value-separator="<?= $Page->km_metaphors->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_metaphors->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_metaphors->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->km_material_properties->Visible) { // km_material_properties ?>
    <div id="r_km_material_properties" class="row"<?= $Page->km_material_properties->rowAttributes() ?>>
        <label for="x_km_material_properties" class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_material_properties"><?= $Page->km_material_properties->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("LIKE") ?>
<input type="hidden" name="z_km_material_properties" id="z_km_material_properties" value="LIKE">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_material_properties->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_material_properties" class="ew-search-field ew-search-field-single">
    <select
        id="x_km_material_properties[]"
        name="x_km_material_properties[]"
        class="form-select ew-select<?= $Page->km_material_properties->isInvalidClass() ?>"
        <?php if (!$Page->km_material_properties->IsNativeSelect) { ?>
        data-select2-id="frecordssearch_x_km_material_properties[]"
        <?php } ?>
        data-table="records"
        data-field="x_km_material_properties"
        multiple
        size="1"
        data-value-separator="<?= $Page->km_material_properties->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->km_material_properties->getPlaceHolder()) ?>"
        <?= $Page->km_material_properties->editAttributes() ?>>
        <?= $Page->km_material_properties->selectOptionListHtml("x_km_material_properties[]") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->km_material_properties->getErrorMessage(false) ?></div>
<?php if (!$Page->km_material_properties->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordssearch", function() {
    var options = { name: "x_km_material_properties[]", selectId: "frecordssearch_x_km_material_properties[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordssearch.lists.km_material_properties?.lookupOptions.length) {
        options.data = { id: "x_km_material_properties[]", form: "frecordssearch" };
    } else {
        options.ajax = { id: "x_km_material_properties[]", form: "frecordssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_material_properties.selectOptions);
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
<?php if ($Page->km_instructions->Visible) { // km_instructions ?>
    <div id="r_km_instructions" class="row"<?= $Page->km_instructions->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_records_km_instructions"><?= $Page->km_instructions->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_km_instructions" id="z_km_instructions" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->km_instructions->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_records_km_instructions" class="ew-search-field ew-search-field-single">
<template id="tp_x_km_instructions">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_km_instructions" name="x_km_instructions" id="x_km_instructions"<?= $Page->km_instructions->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_km_instructions" class="ew-item-list"></div>
<selection-list hidden
    id="x_km_instructions"
    name="x_km_instructions"
    value="<?= HtmlEncode($Page->km_instructions->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_instructions"
    data-target="dsl_x_km_instructions"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_instructions->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_instructions"
    data-value-separator="<?= $Page->km_instructions->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_instructions->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->km_instructions->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="frecordssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="frecordssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="frecordssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("records");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
    $("<tr><td align=\"center\" colspan=\"2\"><div id=\"hloc\" class=\"inlinehead\">Location</div></td></tr>").insertBefore("#r_current_country");
    $("<tr><td colspan=\"2\"><div id=\"horig\" class=\"inlinehead\">Origin</div></td></tr>").insertBefore("#r_author");
    $("<tr><td colspan=\"2\"><div id=\"hcont\" class=\"inlinehead\">Content</div></td></tr>").insertBefore("#r_record_type");
    //$("<div class=\"ew-table-select-row\">Documents</div>").insertBefore("#r_current_country");
    $("<tr><td colspan=\"2\"><div id=\"hform\" class=\"inlinehead\">Format</div></td></tr>").insertBefore("#r_paper");
    $("<tr><td colspan=\"2\"><div id=\"hadd\" class=\"inlinehead\">Additional Information</div></td></tr>").insertAfter("#r_ink_type");
    $("<tr><td colspan=\"2\"><div id=\"hmeta\" class=\"inlinehead\">Key Metadata</div></td></tr>").insertAfter("#r_end_year");
    $(`
    <tr><td colspan=\"2\"><div class="inlinehead">
    <a href="#hloc">Location</a> |
    <a href="#hdt">Doc. Types</a> |
    <a href="#horig">Origin</a> | 
    <a href="#hcont">Content</a> | 
    <a href="#hform">Format</a> | 
    <a href="#hadd">Add. Info.</a> |
    <a href="#hmeta">Key Metadata</a>
    </div></td></tr>
    `).insertBefore("#r_id");
    $("span.ew-search-operator:contains('contains')").css("visibility","hidden");
    $(".btn-toolbar").hide();

    /*
    $("<div id=\"hloc\" class=\"inlinehead\">Location</div>").insertBefore("#r_current_country");
    $("<div id=\"rt\" class=\"inlinehead\">Record type</div>").insertBefore("#r_record_type");
    $("<div id=\"hdt\" class=\"inlinehead\">Uploaded document types</div>").insertBefore("#r_document_types");
    $("<div id=\"horig\" class=\"inlinehead\">Origin</div>").insertBefore("#r_start_year");
    $("<div id=\"hcont\" class=\"inlinehead\">Content</div>").insertBefore("#r_cipher_type_other");
    //$("<div class=\"ew-table-select-row\">Documents</div>").insertBefore("#r_current_country");
    $("<div id=\"hform\" class=\"inlinehead\">Format</div>").insertBefore("#r_paper");
    $("<div id=\"hadd\" class=\"inlinehead\">Additional Information</div>").insertAfter("#r_ink_type");
    $("<div id=\"hmeta\" class=\"inlinehead\">Key Metadata</div>").insertAfter("#r_end_day");
    $(`
    <div class="inlinehead">
    <a href="#hloc">Location</a> |
    <a href="#hdt">Doc. Types</a> |
    <a href="#horig">Origin</a> | 
    <a href="#hcont">Content</a> | 
    <a href="#hform">Format</a> | 
    <a href="#hadd">Add. Info.</a> |
    <a href="#hmeta">Key Metadata</a>
    </div>
    `).insertBefore("#r_id");
    $("span.ew-search-operator:contains('contains')").css("visibility","hidden");
    */
});
</script>
