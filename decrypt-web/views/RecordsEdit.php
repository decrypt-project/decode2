<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$RecordsEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="frecordsedit" id="frecordsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { records: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var frecordsedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("frecordsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["name", [fields.name.visible && fields.name.required ? ew.Validators.required(fields.name.caption) : null], fields.name.isInvalid],
            ["owner", [fields.owner.visible && fields.owner.required ? ew.Validators.required(fields.owner.caption) : null], fields.owner.isInvalid],
            ["c_cates", [fields.c_cates.visible && fields.c_cates.required ? ew.Validators.required(fields.c_cates.caption) : null], fields.c_cates.isInvalid],
            ["current_country", [fields.current_country.visible && fields.current_country.required ? ew.Validators.required(fields.current_country.caption) : null], fields.current_country.isInvalid],
            ["current_city", [fields.current_city.visible && fields.current_city.required ? ew.Validators.required(fields.current_city.caption) : null], fields.current_city.isInvalid],
            ["current_holder", [fields.current_holder.visible && fields.current_holder.required ? ew.Validators.required(fields.current_holder.caption) : null], fields.current_holder.isInvalid],
            ["author", [fields.author.visible && fields.author.required ? ew.Validators.required(fields.author.caption) : null], fields.author.isInvalid],
            ["sender", [fields.sender.visible && fields.sender.required ? ew.Validators.required(fields.sender.caption) : null], fields.sender.isInvalid],
            ["receiver", [fields.receiver.visible && fields.receiver.required ? ew.Validators.required(fields.receiver.caption) : null], fields.receiver.isInvalid],
            ["origin_region", [fields.origin_region.visible && fields.origin_region.required ? ew.Validators.required(fields.origin_region.caption) : null], fields.origin_region.isInvalid],
            ["origin_city", [fields.origin_city.visible && fields.origin_city.required ? ew.Validators.required(fields.origin_city.caption) : null], fields.origin_city.isInvalid],
            ["start_year", [fields.start_year.visible && fields.start_year.required ? ew.Validators.required(fields.start_year.caption) : null, ew.Validators.integer], fields.start_year.isInvalid],
            ["start_month", [fields.start_month.visible && fields.start_month.required ? ew.Validators.required(fields.start_month.caption) : null, ew.Validators.integer], fields.start_month.isInvalid],
            ["start_day", [fields.start_day.visible && fields.start_day.required ? ew.Validators.required(fields.start_day.caption) : null, ew.Validators.integer], fields.start_day.isInvalid],
            ["end_year", [fields.end_year.visible && fields.end_year.required ? ew.Validators.required(fields.end_year.caption) : null, ew.Validators.integer], fields.end_year.isInvalid],
            ["end_month", [fields.end_month.visible && fields.end_month.required ? ew.Validators.required(fields.end_month.caption) : null, ew.Validators.integer], fields.end_month.isInvalid],
            ["end_day", [fields.end_day.visible && fields.end_day.required ? ew.Validators.required(fields.end_day.caption) : null, ew.Validators.integer], fields.end_day.isInvalid],
            ["record_type", [fields.record_type.visible && fields.record_type.required ? ew.Validators.required(fields.record_type.caption) : null], fields.record_type.isInvalid],
            ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
            ["symbol_sets", [fields.symbol_sets.visible && fields.symbol_sets.required ? ew.Validators.required(fields.symbol_sets.caption) : null], fields.symbol_sets.isInvalid],
            ["cipher_types", [fields.cipher_types.visible && fields.cipher_types.required ? ew.Validators.required(fields.cipher_types.caption) : null], fields.cipher_types.isInvalid],
            ["cipher_type_other", [fields.cipher_type_other.visible && fields.cipher_type_other.required ? ew.Validators.required(fields.cipher_type_other.caption) : null], fields.cipher_type_other.isInvalid],
            ["symbol_set_other", [fields.symbol_set_other.visible && fields.symbol_set_other.required ? ew.Validators.required(fields.symbol_set_other.caption) : null], fields.symbol_set_other.isInvalid],
            ["number_of_pages", [fields.number_of_pages.visible && fields.number_of_pages.required ? ew.Validators.required(fields.number_of_pages.caption) : null, ew.Validators.integer], fields.number_of_pages.isInvalid],
            ["inline_cleartext", [fields.inline_cleartext.visible && fields.inline_cleartext.required ? ew.Validators.required(fields.inline_cleartext.caption) : null], fields.inline_cleartext.isInvalid],
            ["inline_plaintext", [fields.inline_plaintext.visible && fields.inline_plaintext.required ? ew.Validators.required(fields.inline_plaintext.caption) : null], fields.inline_plaintext.isInvalid],
            ["cleartext_lang", [fields.cleartext_lang.visible && fields.cleartext_lang.required ? ew.Validators.required(fields.cleartext_lang.caption) : null], fields.cleartext_lang.isInvalid],
            ["plaintext_lang", [fields.plaintext_lang.visible && fields.plaintext_lang.required ? ew.Validators.required(fields.plaintext_lang.caption) : null], fields.plaintext_lang.isInvalid],
            ["document_types", [fields.document_types.visible && fields.document_types.required ? ew.Validators.required(fields.document_types.caption) : null], fields.document_types.isInvalid],
            ["paper", [fields.paper.visible && fields.paper.required ? ew.Validators.required(fields.paper.caption) : null], fields.paper.isInvalid],
            ["additional_information", [fields.additional_information.visible && fields.additional_information.required ? ew.Validators.required(fields.additional_information.caption) : null], fields.additional_information.isInvalid],
            ["creator_id", [fields.creator_id.visible && fields.creator_id.required ? ew.Validators.required(fields.creator_id.caption) : null], fields.creator_id.isInvalid],
            ["access_mode", [fields.access_mode.visible && fields.access_mode.required ? ew.Validators.required(fields.access_mode.caption) : null], fields.access_mode.isInvalid],
            ["km_encoded_plaintext_type", [fields.km_encoded_plaintext_type.visible && fields.km_encoded_plaintext_type.required ? ew.Validators.required(fields.km_encoded_plaintext_type.caption) : null], fields.km_encoded_plaintext_type.isInvalid],
            ["km_numbers", [fields.km_numbers.visible && fields.km_numbers.required ? ew.Validators.required(fields.km_numbers.caption) : null], fields.km_numbers.isInvalid],
            ["km_content_words", [fields.km_content_words.visible && fields.km_content_words.required ? ew.Validators.required(fields.km_content_words.caption) : null], fields.km_content_words.isInvalid],
            ["km_function_words", [fields.km_function_words.visible && fields.km_function_words.required ? ew.Validators.required(fields.km_function_words.caption) : null], fields.km_function_words.isInvalid],
            ["km_syllables", [fields.km_syllables.visible && fields.km_syllables.required ? ew.Validators.required(fields.km_syllables.caption) : null], fields.km_syllables.isInvalid],
            ["km_morphological_endings", [fields.km_morphological_endings.visible && fields.km_morphological_endings.required ? ew.Validators.required(fields.km_morphological_endings.caption) : null], fields.km_morphological_endings.isInvalid],
            ["km_phrases", [fields.km_phrases.visible && fields.km_phrases.required ? ew.Validators.required(fields.km_phrases.caption) : null], fields.km_phrases.isInvalid],
            ["km_sentences", [fields.km_sentences.visible && fields.km_sentences.required ? ew.Validators.required(fields.km_sentences.caption) : null], fields.km_sentences.isInvalid],
            ["km_punctuation", [fields.km_punctuation.visible && fields.km_punctuation.required ? ew.Validators.required(fields.km_punctuation.caption) : null], fields.km_punctuation.isInvalid],
            ["km_nomenclature_size", [fields.km_nomenclature_size.visible && fields.km_nomenclature_size.required ? ew.Validators.required(fields.km_nomenclature_size.caption) : null], fields.km_nomenclature_size.isInvalid],
            ["km_sections", [fields.km_sections.visible && fields.km_sections.required ? ew.Validators.required(fields.km_sections.caption) : null], fields.km_sections.isInvalid],
            ["km_headings", [fields.km_headings.visible && fields.km_headings.required ? ew.Validators.required(fields.km_headings.caption) : null], fields.km_headings.isInvalid],
            ["km_plaintext_arrangement", [fields.km_plaintext_arrangement.visible && fields.km_plaintext_arrangement.required ? ew.Validators.required(fields.km_plaintext_arrangement.caption) : null], fields.km_plaintext_arrangement.isInvalid],
            ["km_ciphertext_arrangement", [fields.km_ciphertext_arrangement.visible && fields.km_ciphertext_arrangement.required ? ew.Validators.required(fields.km_ciphertext_arrangement.caption) : null], fields.km_ciphertext_arrangement.isInvalid],
            ["km_memorability", [fields.km_memorability.visible && fields.km_memorability.required ? ew.Validators.required(fields.km_memorability.caption) : null], fields.km_memorability.isInvalid],
            ["km_symbol_set", [fields.km_symbol_set.visible && fields.km_symbol_set.required ? ew.Validators.required(fields.km_symbol_set.caption) : null], fields.km_symbol_set.isInvalid],
            ["km_diacritics", [fields.km_diacritics.visible && fields.km_diacritics.required ? ew.Validators.required(fields.km_diacritics.caption) : null], fields.km_diacritics.isInvalid],
            ["km_code_length", [fields.km_code_length.visible && fields.km_code_length.required ? ew.Validators.required(fields.km_code_length.caption) : null], fields.km_code_length.isInvalid],
            ["km_code_type", [fields.km_code_type.visible && fields.km_code_type.required ? ew.Validators.required(fields.km_code_type.caption) : null], fields.km_code_type.isInvalid],
            ["km_metaphors", [fields.km_metaphors.visible && fields.km_metaphors.required ? ew.Validators.required(fields.km_metaphors.caption) : null], fields.km_metaphors.isInvalid],
            ["km_material_properties", [fields.km_material_properties.visible && fields.km_material_properties.required ? ew.Validators.required(fields.km_material_properties.caption) : null], fields.km_material_properties.isInvalid],
            ["km_instructions", [fields.km_instructions.visible && fields.km_instructions.required ? ew.Validators.required(fields.km_instructions.caption) : null], fields.km_instructions.isInvalid]
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
            "name": <?= $Page->name->toClientList($Page) ?>,
            "current_country": <?= $Page->current_country->toClientList($Page) ?>,
            "current_city": <?= $Page->current_city->toClientList($Page) ?>,
            "current_holder": <?= $Page->current_holder->toClientList($Page) ?>,
            "author": <?= $Page->author->toClientList($Page) ?>,
            "sender": <?= $Page->sender->toClientList($Page) ?>,
            "receiver": <?= $Page->receiver->toClientList($Page) ?>,
            "origin_region": <?= $Page->origin_region->toClientList($Page) ?>,
            "origin_city": <?= $Page->origin_city->toClientList($Page) ?>,
            "record_type": <?= $Page->record_type->toClientList($Page) ?>,
            "status": <?= $Page->status->toClientList($Page) ?>,
            "symbol_sets": <?= $Page->symbol_sets->toClientList($Page) ?>,
            "cipher_types": <?= $Page->cipher_types->toClientList($Page) ?>,
            "inline_cleartext": <?= $Page->inline_cleartext->toClientList($Page) ?>,
            "inline_plaintext": <?= $Page->inline_plaintext->toClientList($Page) ?>,
            "document_types": <?= $Page->document_types->toClientList($Page) ?>,
            "access_mode": <?= $Page->access_mode->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="records">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_records_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_records_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
<input type="hidden" data-table="records" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <div id="r_name"<?= $Page->name->rowAttributes() ?>>
        <label id="elh_records_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->name->caption() ?><?= $Page->name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->name->cellAttributes() ?>>
<span id="el_records_name">
<?php
if (IsRTL()) {
    $Page->name->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_name" class="ew-auto-suggest">
    <input type="<?= $Page->name->getInputTextType() ?>" class="form-control" name="sv_x_name" id="sv_x_name" value="<?= RemoveHtml($Page->name->EditValue) ?>" autocomplete="off" size="60" maxlength="255" placeholder="<?= HtmlEncode($Page->name->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->name->formatPattern()) ?>"<?= $Page->name->editAttributes() ?> aria-describedby="x_name_help">
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_name" data-input="sv_x_name" data-value-separator="<?= $Page->name->displayValueSeparatorAttribute() ?>" name="x_name" id="x_name" value="<?= HtmlEncode($Page->name->CurrentValue) ?>"></selection-list>
<?= $Page->name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->name->getErrorMessage() ?></div>
<script>
loadjs.ready("frecordsedit", function() {
    frecordsedit.createAutoSuggest(Object.assign({"id":"x_name","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->name->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.name.autoSuggestOptions));
});
</script>
<?= $Page->name->Lookup->getParamTag($Page, "p_x_name") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->owner->Visible) { // owner ?>
    <div id="r_owner"<?= $Page->owner->rowAttributes() ?>>
        <label id="elh_records_owner" for="x_owner" class="<?= $Page->LeftColumnClass ?>"><?= $Page->owner->caption() ?><?= $Page->owner->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->owner->cellAttributes() ?>>
<span id="el_records_owner">
<input type="<?= $Page->owner->getInputTextType() ?>" name="x_owner" id="x_owner" data-table="records" data-field="x_owner" value="<?= $Page->owner->EditValue ?>" size="30" maxlength="128" placeholder="<?= HtmlEncode($Page->owner->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->owner->formatPattern()) ?>"<?= $Page->owner->editAttributes() ?> aria-describedby="x_owner_help">
<?= $Page->owner->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->owner->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->c_cates->Visible) { // c_cates ?>
    <div id="r_c_cates"<?= $Page->c_cates->rowAttributes() ?>>
        <label id="elh_records_c_cates" for="x_c_cates" class="<?= $Page->LeftColumnClass ?>"><?= $Page->c_cates->caption() ?><?= $Page->c_cates->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->c_cates->cellAttributes() ?>>
<span id="el_records_c_cates">
<input type="<?= $Page->c_cates->getInputTextType() ?>" name="x_c_cates" id="x_c_cates" data-table="records" data-field="x_c_cates" value="<?= $Page->c_cates->EditValue ?>" size="30" maxlength="15" placeholder="<?= HtmlEncode($Page->c_cates->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->c_cates->formatPattern()) ?>"<?= $Page->c_cates->editAttributes() ?> aria-describedby="x_c_cates_help">
<?= $Page->c_cates->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->c_cates->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->current_country->Visible) { // current_country ?>
    <div id="r_current_country"<?= $Page->current_country->rowAttributes() ?>>
        <label id="elh_records_current_country" class="<?= $Page->LeftColumnClass ?>"><?= $Page->current_country->caption() ?><?= $Page->current_country->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->current_country->cellAttributes() ?>>
<span id="el_records_current_country">
<?php
if (IsRTL()) {
    $Page->current_country->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_current_country" class="ew-auto-suggest">
    <input type="<?= $Page->current_country->getInputTextType() ?>" class="form-control" name="sv_x_current_country" id="sv_x_current_country" value="<?= RemoveHtml($Page->current_country->EditValue) ?>" autocomplete="off" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->current_country->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->current_country->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->current_country->formatPattern()) ?>"<?= $Page->current_country->editAttributes() ?> aria-describedby="x_current_country_help">
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_current_country" data-input="sv_x_current_country" data-value-separator="<?= $Page->current_country->displayValueSeparatorAttribute() ?>" name="x_current_country" id="x_current_country" value="<?= HtmlEncode($Page->current_country->CurrentValue) ?>"></selection-list>
<?= $Page->current_country->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->current_country->getErrorMessage() ?></div>
<script>
loadjs.ready("frecordsedit", function() {
    frecordsedit.createAutoSuggest(Object.assign({"id":"x_current_country","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->current_country->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.current_country.autoSuggestOptions));
});
</script>
<?= $Page->current_country->Lookup->getParamTag($Page, "p_x_current_country") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->current_city->Visible) { // current_city ?>
    <div id="r_current_city"<?= $Page->current_city->rowAttributes() ?>>
        <label id="elh_records_current_city" class="<?= $Page->LeftColumnClass ?>"><?= $Page->current_city->caption() ?><?= $Page->current_city->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->current_city->cellAttributes() ?>>
<span id="el_records_current_city">
<?php
if (IsRTL()) {
    $Page->current_city->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_current_city" class="ew-auto-suggest">
    <input type="<?= $Page->current_city->getInputTextType() ?>" class="form-control" name="sv_x_current_city" id="sv_x_current_city" value="<?= RemoveHtml($Page->current_city->EditValue) ?>" autocomplete="off" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->current_city->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->current_city->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->current_city->formatPattern()) ?>"<?= $Page->current_city->editAttributes() ?> aria-describedby="x_current_city_help">
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_current_city" data-input="sv_x_current_city" data-value-separator="<?= $Page->current_city->displayValueSeparatorAttribute() ?>" name="x_current_city" id="x_current_city" value="<?= HtmlEncode($Page->current_city->CurrentValue) ?>"></selection-list>
<?= $Page->current_city->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->current_city->getErrorMessage() ?></div>
<script>
loadjs.ready("frecordsedit", function() {
    frecordsedit.createAutoSuggest(Object.assign({"id":"x_current_city","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->current_city->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.current_city.autoSuggestOptions));
});
</script>
<?= $Page->current_city->Lookup->getParamTag($Page, "p_x_current_city") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->current_holder->Visible) { // current_holder ?>
    <div id="r_current_holder"<?= $Page->current_holder->rowAttributes() ?>>
        <label id="elh_records_current_holder" class="<?= $Page->LeftColumnClass ?>"><?= $Page->current_holder->caption() ?><?= $Page->current_holder->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->current_holder->cellAttributes() ?>>
<span id="el_records_current_holder">
<?php
if (IsRTL()) {
    $Page->current_holder->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_current_holder" class="ew-auto-suggest">
    <input type="<?= $Page->current_holder->getInputTextType() ?>" class="form-control" name="sv_x_current_holder" id="sv_x_current_holder" value="<?= RemoveHtml($Page->current_holder->EditValue) ?>" autocomplete="off" size="60" maxlength="255" placeholder="<?= HtmlEncode($Page->current_holder->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->current_holder->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->current_holder->formatPattern()) ?>"<?= $Page->current_holder->editAttributes() ?> aria-describedby="x_current_holder_help">
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_current_holder" data-input="sv_x_current_holder" data-value-separator="<?= $Page->current_holder->displayValueSeparatorAttribute() ?>" name="x_current_holder" id="x_current_holder" value="<?= HtmlEncode($Page->current_holder->CurrentValue) ?>"></selection-list>
<?= $Page->current_holder->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->current_holder->getErrorMessage() ?></div>
<script>
loadjs.ready("frecordsedit", function() {
    frecordsedit.createAutoSuggest(Object.assign({"id":"x_current_holder","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->current_holder->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.current_holder.autoSuggestOptions));
});
</script>
<?= $Page->current_holder->Lookup->getParamTag($Page, "p_x_current_holder") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->author->Visible) { // author ?>
    <div id="r_author"<?= $Page->author->rowAttributes() ?>>
        <label id="elh_records_author" class="<?= $Page->LeftColumnClass ?>"><?= $Page->author->caption() ?><?= $Page->author->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->author->cellAttributes() ?>>
<span id="el_records_author">
<?php
if (IsRTL()) {
    $Page->author->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_author" class="ew-auto-suggest">
    <input type="<?= $Page->author->getInputTextType() ?>" class="form-control" name="sv_x_author" id="sv_x_author" value="<?= RemoveHtml($Page->author->EditValue) ?>" autocomplete="off" size="50" maxlength="64" placeholder="<?= HtmlEncode($Page->author->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->author->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->author->formatPattern()) ?>"<?= $Page->author->editAttributes() ?> aria-describedby="x_author_help">
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_author" data-input="sv_x_author" data-value-separator="<?= $Page->author->displayValueSeparatorAttribute() ?>" name="x_author" id="x_author" value="<?= HtmlEncode($Page->author->CurrentValue) ?>"></selection-list>
<?= $Page->author->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->author->getErrorMessage() ?></div>
<script>
loadjs.ready("frecordsedit", function() {
    frecordsedit.createAutoSuggest(Object.assign({"id":"x_author","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->author->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.author.autoSuggestOptions));
});
</script>
<?= $Page->author->Lookup->getParamTag($Page, "p_x_author") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sender->Visible) { // sender ?>
    <div id="r_sender"<?= $Page->sender->rowAttributes() ?>>
        <label id="elh_records_sender" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sender->caption() ?><?= $Page->sender->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sender->cellAttributes() ?>>
<span id="el_records_sender">
<?php
if (IsRTL()) {
    $Page->sender->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_sender" class="ew-auto-suggest">
    <input type="<?= $Page->sender->getInputTextType() ?>" class="form-control" name="sv_x_sender" id="sv_x_sender" value="<?= RemoveHtml($Page->sender->EditValue) ?>" autocomplete="off" size="50" maxlength="64" placeholder="<?= HtmlEncode($Page->sender->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->sender->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->sender->formatPattern()) ?>"<?= $Page->sender->editAttributes() ?> aria-describedby="x_sender_help">
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_sender" data-input="sv_x_sender" data-value-separator="<?= $Page->sender->displayValueSeparatorAttribute() ?>" name="x_sender" id="x_sender" value="<?= HtmlEncode($Page->sender->CurrentValue) ?>"></selection-list>
<?= $Page->sender->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sender->getErrorMessage() ?></div>
<script>
loadjs.ready("frecordsedit", function() {
    frecordsedit.createAutoSuggest(Object.assign({"id":"x_sender","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->sender->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.sender.autoSuggestOptions));
});
</script>
<?= $Page->sender->Lookup->getParamTag($Page, "p_x_sender") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->receiver->Visible) { // receiver ?>
    <div id="r_receiver"<?= $Page->receiver->rowAttributes() ?>>
        <label id="elh_records_receiver" class="<?= $Page->LeftColumnClass ?>"><?= $Page->receiver->caption() ?><?= $Page->receiver->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->receiver->cellAttributes() ?>>
<span id="el_records_receiver">
<?php
if (IsRTL()) {
    $Page->receiver->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_receiver" class="ew-auto-suggest">
    <input type="<?= $Page->receiver->getInputTextType() ?>" class="form-control" name="sv_x_receiver" id="sv_x_receiver" value="<?= RemoveHtml($Page->receiver->EditValue) ?>" autocomplete="off" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->receiver->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->receiver->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->receiver->formatPattern()) ?>"<?= $Page->receiver->editAttributes() ?> aria-describedby="x_receiver_help">
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_receiver" data-input="sv_x_receiver" data-value-separator="<?= $Page->receiver->displayValueSeparatorAttribute() ?>" name="x_receiver" id="x_receiver" value="<?= HtmlEncode($Page->receiver->CurrentValue) ?>"></selection-list>
<?= $Page->receiver->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->receiver->getErrorMessage() ?></div>
<script>
loadjs.ready("frecordsedit", function() {
    frecordsedit.createAutoSuggest(Object.assign({"id":"x_receiver","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->receiver->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.receiver.autoSuggestOptions));
});
</script>
<?= $Page->receiver->Lookup->getParamTag($Page, "p_x_receiver") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->origin_region->Visible) { // origin_region ?>
    <div id="r_origin_region"<?= $Page->origin_region->rowAttributes() ?>>
        <label id="elh_records_origin_region" class="<?= $Page->LeftColumnClass ?>"><?= $Page->origin_region->caption() ?><?= $Page->origin_region->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->origin_region->cellAttributes() ?>>
<span id="el_records_origin_region">
<?php
if (IsRTL()) {
    $Page->origin_region->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_origin_region" class="ew-auto-suggest">
    <input type="<?= $Page->origin_region->getInputTextType() ?>" class="form-control" name="sv_x_origin_region" id="sv_x_origin_region" value="<?= RemoveHtml($Page->origin_region->EditValue) ?>" autocomplete="off" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->origin_region->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->origin_region->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->origin_region->formatPattern()) ?>"<?= $Page->origin_region->editAttributes() ?> aria-describedby="x_origin_region_help">
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_origin_region" data-input="sv_x_origin_region" data-value-separator="<?= $Page->origin_region->displayValueSeparatorAttribute() ?>" name="x_origin_region" id="x_origin_region" value="<?= HtmlEncode($Page->origin_region->CurrentValue) ?>"></selection-list>
<?= $Page->origin_region->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->origin_region->getErrorMessage() ?></div>
<script>
loadjs.ready("frecordsedit", function() {
    frecordsedit.createAutoSuggest(Object.assign({"id":"x_origin_region","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->origin_region->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.origin_region.autoSuggestOptions));
});
</script>
<?= $Page->origin_region->Lookup->getParamTag($Page, "p_x_origin_region") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->origin_city->Visible) { // origin_city ?>
    <div id="r_origin_city"<?= $Page->origin_city->rowAttributes() ?>>
        <label id="elh_records_origin_city" class="<?= $Page->LeftColumnClass ?>"><?= $Page->origin_city->caption() ?><?= $Page->origin_city->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->origin_city->cellAttributes() ?>>
<span id="el_records_origin_city">
<?php
if (IsRTL()) {
    $Page->origin_city->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_origin_city" class="ew-auto-suggest">
    <input type="<?= $Page->origin_city->getInputTextType() ?>" class="form-control" name="sv_x_origin_city" id="sv_x_origin_city" value="<?= RemoveHtml($Page->origin_city->EditValue) ?>" autocomplete="off" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->origin_city->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->origin_city->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->origin_city->formatPattern()) ?>"<?= $Page->origin_city->editAttributes() ?> aria-describedby="x_origin_city_help">
</span>
<selection-list hidden class="form-control" data-table="records" data-field="x_origin_city" data-input="sv_x_origin_city" data-value-separator="<?= $Page->origin_city->displayValueSeparatorAttribute() ?>" name="x_origin_city" id="x_origin_city" value="<?= HtmlEncode($Page->origin_city->CurrentValue) ?>"></selection-list>
<?= $Page->origin_city->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->origin_city->getErrorMessage() ?></div>
<script>
loadjs.ready("frecordsedit", function() {
    frecordsedit.createAutoSuggest(Object.assign({"id":"x_origin_city","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->origin_city->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.records.fields.origin_city.autoSuggestOptions));
});
</script>
<?= $Page->origin_city->Lookup->getParamTag($Page, "p_x_origin_city") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->start_year->Visible) { // start_year ?>
    <div id="r_start_year"<?= $Page->start_year->rowAttributes() ?>>
        <label id="elh_records_start_year" for="x_start_year" class="<?= $Page->LeftColumnClass ?>"><?= $Page->start_year->caption() ?><?= $Page->start_year->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->start_year->cellAttributes() ?>>
<span id="el_records_start_year">
<input type="<?= $Page->start_year->getInputTextType() ?>" name="x_start_year" id="x_start_year" data-table="records" data-field="x_start_year" value="<?= $Page->start_year->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->start_year->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_year->formatPattern()) ?>"<?= $Page->start_year->editAttributes() ?> aria-describedby="x_start_year_help">
<?= $Page->start_year->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->start_year->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->start_month->Visible) { // start_month ?>
    <div id="r_start_month"<?= $Page->start_month->rowAttributes() ?>>
        <label id="elh_records_start_month" for="x_start_month" class="<?= $Page->LeftColumnClass ?>"><?= $Page->start_month->caption() ?><?= $Page->start_month->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->start_month->cellAttributes() ?>>
<span id="el_records_start_month">
<input type="<?= $Page->start_month->getInputTextType() ?>" name="x_start_month" id="x_start_month" data-table="records" data-field="x_start_month" value="<?= $Page->start_month->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->start_month->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_month->formatPattern()) ?>"<?= $Page->start_month->editAttributes() ?> aria-describedby="x_start_month_help">
<?= $Page->start_month->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->start_month->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->start_day->Visible) { // start_day ?>
    <div id="r_start_day"<?= $Page->start_day->rowAttributes() ?>>
        <label id="elh_records_start_day" for="x_start_day" class="<?= $Page->LeftColumnClass ?>"><?= $Page->start_day->caption() ?><?= $Page->start_day->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->start_day->cellAttributes() ?>>
<span id="el_records_start_day">
<input type="<?= $Page->start_day->getInputTextType() ?>" name="x_start_day" id="x_start_day" data-table="records" data-field="x_start_day" value="<?= $Page->start_day->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->start_day->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->start_day->formatPattern()) ?>"<?= $Page->start_day->editAttributes() ?> aria-describedby="x_start_day_help">
<?= $Page->start_day->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->start_day->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->end_year->Visible) { // end_year ?>
    <div id="r_end_year"<?= $Page->end_year->rowAttributes() ?>>
        <label id="elh_records_end_year" for="x_end_year" class="<?= $Page->LeftColumnClass ?>"><?= $Page->end_year->caption() ?><?= $Page->end_year->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->end_year->cellAttributes() ?>>
<span id="el_records_end_year">
<input type="<?= $Page->end_year->getInputTextType() ?>" name="x_end_year" id="x_end_year" data-table="records" data-field="x_end_year" value="<?= $Page->end_year->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->end_year->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_year->formatPattern()) ?>"<?= $Page->end_year->editAttributes() ?> aria-describedby="x_end_year_help">
<?= $Page->end_year->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->end_year->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->end_month->Visible) { // end_month ?>
    <div id="r_end_month"<?= $Page->end_month->rowAttributes() ?>>
        <label id="elh_records_end_month" for="x_end_month" class="<?= $Page->LeftColumnClass ?>"><?= $Page->end_month->caption() ?><?= $Page->end_month->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->end_month->cellAttributes() ?>>
<span id="el_records_end_month">
<input type="<?= $Page->end_month->getInputTextType() ?>" name="x_end_month" id="x_end_month" data-table="records" data-field="x_end_month" value="<?= $Page->end_month->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->end_month->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_month->formatPattern()) ?>"<?= $Page->end_month->editAttributes() ?> aria-describedby="x_end_month_help">
<?= $Page->end_month->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->end_month->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->end_day->Visible) { // end_day ?>
    <div id="r_end_day"<?= $Page->end_day->rowAttributes() ?>>
        <label id="elh_records_end_day" for="x_end_day" class="<?= $Page->LeftColumnClass ?>"><?= $Page->end_day->caption() ?><?= $Page->end_day->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->end_day->cellAttributes() ?>>
<span id="el_records_end_day">
<input type="<?= $Page->end_day->getInputTextType() ?>" name="x_end_day" id="x_end_day" data-table="records" data-field="x_end_day" value="<?= $Page->end_day->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->end_day->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->end_day->formatPattern()) ?>"<?= $Page->end_day->editAttributes() ?> aria-describedby="x_end_day_help">
<?= $Page->end_day->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->end_day->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->record_type->Visible) { // record_type ?>
    <div id="r_record_type"<?= $Page->record_type->rowAttributes() ?>>
        <label id="elh_records_record_type" for="x_record_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->record_type->caption() ?><?= $Page->record_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->record_type->cellAttributes() ?>>
<span id="el_records_record_type">
    <select
        id="x_record_type"
        name="x_record_type"
        class="form-select ew-select<?= $Page->record_type->isInvalidClass() ?>"
        <?php if (!$Page->record_type->IsNativeSelect) { ?>
        data-select2-id="frecordsedit_x_record_type"
        <?php } ?>
        data-table="records"
        data-field="x_record_type"
        data-value-separator="<?= $Page->record_type->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->record_type->getPlaceHolder()) ?>"
        <?= $Page->record_type->editAttributes() ?>>
        <?= $Page->record_type->selectOptionListHtml("x_record_type") ?>
    </select>
    <?= $Page->record_type->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->record_type->getErrorMessage() ?></div>
<?php if (!$Page->record_type->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordsedit", function() {
    var options = { name: "x_record_type", selectId: "frecordsedit_x_record_type" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordsedit.lists.record_type?.lookupOptions.length) {
        options.data = { id: "x_record_type", form: "frecordsedit" };
    } else {
        options.ajax = { id: "x_record_type", form: "frecordsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.record_type.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status"<?= $Page->status->rowAttributes() ?>>
        <label id="elh_records_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status->cellAttributes() ?>>
<span id="el_records_status">
    <select
        id="x_status"
        name="x_status"
        class="form-select ew-select<?= $Page->status->isInvalidClass() ?>"
        <?php if (!$Page->status->IsNativeSelect) { ?>
        data-select2-id="frecordsedit_x_status"
        <?php } ?>
        data-table="records"
        data-field="x_status"
        data-value-separator="<?= $Page->status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>"
        <?= $Page->status->editAttributes() ?>>
        <?= $Page->status->selectOptionListHtml("x_status") ?>
    </select>
    <?= $Page->status->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
<?php if (!$Page->status->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordsedit", function() {
    var options = { name: "x_status", selectId: "frecordsedit_x_status" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordsedit.lists.status?.lookupOptions.length) {
        options.data = { id: "x_status", form: "frecordsedit" };
    } else {
        options.ajax = { id: "x_status", form: "frecordsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.status.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->symbol_sets->Visible) { // symbol_sets ?>
    <div id="r_symbol_sets"<?= $Page->symbol_sets->rowAttributes() ?>>
        <label id="elh_records_symbol_sets" class="<?= $Page->LeftColumnClass ?>"><?= $Page->symbol_sets->caption() ?><?= $Page->symbol_sets->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->symbol_sets->cellAttributes() ?>>
<span id="el_records_symbol_sets">
<template id="tp_x_symbol_sets">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="records" data-field="x_symbol_sets" name="x_symbol_sets" id="x_symbol_sets"<?= $Page->symbol_sets->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_symbol_sets" class="ew-item-list"></div>
<selection-list hidden
    id="x_symbol_sets[]"
    name="x_symbol_sets[]"
    value="<?= HtmlEncode($Page->symbol_sets->CurrentValue) ?>"
    data-type="select-multiple"
    data-template="tp_x_symbol_sets"
    data-target="dsl_x_symbol_sets"
    data-repeatcolumn="5"
    class="form-control<?= $Page->symbol_sets->isInvalidClass() ?>"
    data-table="records"
    data-field="x_symbol_sets"
    data-value-separator="<?= $Page->symbol_sets->displayValueSeparatorAttribute() ?>"
    <?= $Page->symbol_sets->editAttributes() ?>></selection-list>
<?= $Page->symbol_sets->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->symbol_sets->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cipher_types->Visible) { // cipher_types ?>
    <div id="r_cipher_types"<?= $Page->cipher_types->rowAttributes() ?>>
        <label id="elh_records_cipher_types" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cipher_types->caption() ?><?= $Page->cipher_types->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cipher_types->cellAttributes() ?>>
<span id="el_records_cipher_types">
<template id="tp_x_cipher_types">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="records" data-field="x_cipher_types" name="x_cipher_types" id="x_cipher_types"<?= $Page->cipher_types->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_cipher_types" class="ew-item-list"></div>
<selection-list hidden
    id="x_cipher_types[]"
    name="x_cipher_types[]"
    value="<?= HtmlEncode($Page->cipher_types->CurrentValue) ?>"
    data-type="select-multiple"
    data-template="tp_x_cipher_types"
    data-target="dsl_x_cipher_types"
    data-repeatcolumn="5"
    class="form-control<?= $Page->cipher_types->isInvalidClass() ?>"
    data-table="records"
    data-field="x_cipher_types"
    data-value-separator="<?= $Page->cipher_types->displayValueSeparatorAttribute() ?>"
    <?= $Page->cipher_types->editAttributes() ?>></selection-list>
<?= $Page->cipher_types->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cipher_types->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cipher_type_other->Visible) { // cipher_type_other ?>
    <div id="r_cipher_type_other"<?= $Page->cipher_type_other->rowAttributes() ?>>
        <label id="elh_records_cipher_type_other" for="x_cipher_type_other" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cipher_type_other->caption() ?><?= $Page->cipher_type_other->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cipher_type_other->cellAttributes() ?>>
<span id="el_records_cipher_type_other">
<input type="<?= $Page->cipher_type_other->getInputTextType() ?>" name="x_cipher_type_other" id="x_cipher_type_other" data-table="records" data-field="x_cipher_type_other" value="<?= $Page->cipher_type_other->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->cipher_type_other->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cipher_type_other->formatPattern()) ?>"<?= $Page->cipher_type_other->editAttributes() ?> aria-describedby="x_cipher_type_other_help">
<?= $Page->cipher_type_other->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cipher_type_other->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->symbol_set_other->Visible) { // symbol_set_other ?>
    <div id="r_symbol_set_other"<?= $Page->symbol_set_other->rowAttributes() ?>>
        <label id="elh_records_symbol_set_other" for="x_symbol_set_other" class="<?= $Page->LeftColumnClass ?>"><?= $Page->symbol_set_other->caption() ?><?= $Page->symbol_set_other->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->symbol_set_other->cellAttributes() ?>>
<span id="el_records_symbol_set_other">
<input type="<?= $Page->symbol_set_other->getInputTextType() ?>" name="x_symbol_set_other" id="x_symbol_set_other" data-table="records" data-field="x_symbol_set_other" value="<?= $Page->symbol_set_other->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->symbol_set_other->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->symbol_set_other->formatPattern()) ?>"<?= $Page->symbol_set_other->editAttributes() ?> aria-describedby="x_symbol_set_other_help">
<?= $Page->symbol_set_other->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->symbol_set_other->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->number_of_pages->Visible) { // number_of_pages ?>
    <div id="r_number_of_pages"<?= $Page->number_of_pages->rowAttributes() ?>>
        <label id="elh_records_number_of_pages" for="x_number_of_pages" class="<?= $Page->LeftColumnClass ?>"><?= $Page->number_of_pages->caption() ?><?= $Page->number_of_pages->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->number_of_pages->cellAttributes() ?>>
<span id="el_records_number_of_pages">
<input type="<?= $Page->number_of_pages->getInputTextType() ?>" name="x_number_of_pages" id="x_number_of_pages" data-table="records" data-field="x_number_of_pages" value="<?= $Page->number_of_pages->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->number_of_pages->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->number_of_pages->formatPattern()) ?>"<?= $Page->number_of_pages->editAttributes() ?> aria-describedby="x_number_of_pages_help">
<?= $Page->number_of_pages->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->number_of_pages->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->inline_cleartext->Visible) { // inline_cleartext ?>
    <div id="r_inline_cleartext"<?= $Page->inline_cleartext->rowAttributes() ?>>
        <label id="elh_records_inline_cleartext" class="<?= $Page->LeftColumnClass ?>"><?= $Page->inline_cleartext->caption() ?><?= $Page->inline_cleartext->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->inline_cleartext->cellAttributes() ?>>
<span id="el_records_inline_cleartext">
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
    value="<?= HtmlEncode($Page->inline_cleartext->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_inline_cleartext"
    data-target="dsl_x_inline_cleartext"
    data-repeatcolumn="5"
    class="form-control<?= $Page->inline_cleartext->isInvalidClass() ?>"
    data-table="records"
    data-field="x_inline_cleartext"
    data-value-separator="<?= $Page->inline_cleartext->displayValueSeparatorAttribute() ?>"
    <?= $Page->inline_cleartext->editAttributes() ?>></selection-list>
<?= $Page->inline_cleartext->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->inline_cleartext->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->inline_plaintext->Visible) { // inline_plaintext ?>
    <div id="r_inline_plaintext"<?= $Page->inline_plaintext->rowAttributes() ?>>
        <label id="elh_records_inline_plaintext" class="<?= $Page->LeftColumnClass ?>"><?= $Page->inline_plaintext->caption() ?><?= $Page->inline_plaintext->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->inline_plaintext->cellAttributes() ?>>
<span id="el_records_inline_plaintext">
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
    value="<?= HtmlEncode($Page->inline_plaintext->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_inline_plaintext"
    data-target="dsl_x_inline_plaintext"
    data-repeatcolumn="5"
    class="form-control<?= $Page->inline_plaintext->isInvalidClass() ?>"
    data-table="records"
    data-field="x_inline_plaintext"
    data-value-separator="<?= $Page->inline_plaintext->displayValueSeparatorAttribute() ?>"
    <?= $Page->inline_plaintext->editAttributes() ?>></selection-list>
<?= $Page->inline_plaintext->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->inline_plaintext->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cleartext_lang->Visible) { // cleartext_lang ?>
    <div id="r_cleartext_lang"<?= $Page->cleartext_lang->rowAttributes() ?>>
        <label id="elh_records_cleartext_lang" for="x_cleartext_lang" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cleartext_lang->caption() ?><?= $Page->cleartext_lang->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cleartext_lang->cellAttributes() ?>>
<span id="el_records_cleartext_lang">
<input type="<?= $Page->cleartext_lang->getInputTextType() ?>" name="x_cleartext_lang" id="x_cleartext_lang" data-table="records" data-field="x_cleartext_lang" value="<?= $Page->cleartext_lang->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->cleartext_lang->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cleartext_lang->formatPattern()) ?>"<?= $Page->cleartext_lang->editAttributes() ?> aria-describedby="x_cleartext_lang_help">
<?= $Page->cleartext_lang->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cleartext_lang->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->plaintext_lang->Visible) { // plaintext_lang ?>
    <div id="r_plaintext_lang"<?= $Page->plaintext_lang->rowAttributes() ?>>
        <label id="elh_records_plaintext_lang" for="x_plaintext_lang" class="<?= $Page->LeftColumnClass ?>"><?= $Page->plaintext_lang->caption() ?><?= $Page->plaintext_lang->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->plaintext_lang->cellAttributes() ?>>
<span id="el_records_plaintext_lang">
<input type="<?= $Page->plaintext_lang->getInputTextType() ?>" name="x_plaintext_lang" id="x_plaintext_lang" data-table="records" data-field="x_plaintext_lang" value="<?= $Page->plaintext_lang->EditValue ?>" size="30" maxlength="64" placeholder="<?= HtmlEncode($Page->plaintext_lang->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->plaintext_lang->formatPattern()) ?>"<?= $Page->plaintext_lang->editAttributes() ?> aria-describedby="x_plaintext_lang_help">
<?= $Page->plaintext_lang->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->plaintext_lang->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->document_types->Visible) { // document_types ?>
    <div id="r_document_types"<?= $Page->document_types->rowAttributes() ?>>
        <label id="elh_records_document_types" class="<?= $Page->LeftColumnClass ?>"><?= $Page->document_types->caption() ?><?= $Page->document_types->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->document_types->cellAttributes() ?>>
<span id="el_records_document_types">
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
    value="<?= HtmlEncode($Page->document_types->CurrentValue) ?>"
    data-type="select-multiple"
    data-template="tp_x_document_types"
    data-target="dsl_x_document_types"
    data-repeatcolumn="5"
    class="form-control<?= $Page->document_types->isInvalidClass() ?>"
    data-table="records"
    data-field="x_document_types"
    data-value-separator="<?= $Page->document_types->displayValueSeparatorAttribute() ?>"
    <?= $Page->document_types->editAttributes() ?>></selection-list>
<?= $Page->document_types->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->document_types->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->paper->Visible) { // paper ?>
    <div id="r_paper"<?= $Page->paper->rowAttributes() ?>>
        <label id="elh_records_paper" for="x_paper" class="<?= $Page->LeftColumnClass ?>"><?= $Page->paper->caption() ?><?= $Page->paper->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->paper->cellAttributes() ?>>
<span id="el_records_paper">
<input type="<?= $Page->paper->getInputTextType() ?>" name="x_paper" id="x_paper" data-table="records" data-field="x_paper" value="<?= $Page->paper->EditValue ?>" size="60" maxlength="64" placeholder="<?= HtmlEncode($Page->paper->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->paper->formatPattern()) ?>"<?= $Page->paper->editAttributes() ?> aria-describedby="x_paper_help">
<?= $Page->paper->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->paper->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->additional_information->Visible) { // additional_information ?>
    <div id="r_additional_information"<?= $Page->additional_information->rowAttributes() ?>>
        <label id="elh_records_additional_information" class="<?= $Page->LeftColumnClass ?>"><?= $Page->additional_information->caption() ?><?= $Page->additional_information->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->additional_information->cellAttributes() ?>>
<span id="el_records_additional_information">
<?php $Page->additional_information->EditAttrs->appendClass("editor"); ?>
<textarea data-table="records" data-field="x_additional_information" name="x_additional_information" id="x_additional_information" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->additional_information->getPlaceHolder()) ?>"<?= $Page->additional_information->editAttributes() ?> aria-describedby="x_additional_information_help"><?= $Page->additional_information->EditValue ?></textarea>
<?= $Page->additional_information->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->additional_information->getErrorMessage() ?></div>
<script>
loadjs.ready(["frecordsedit", "editor"], function() {
    ew.createEditor("frecordsedit", "x_additional_information", 35, 4, <?= $Page->additional_information->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->access_mode->Visible) { // access_mode ?>
    <div id="r_access_mode"<?= $Page->access_mode->rowAttributes() ?>>
        <label id="elh_records_access_mode" class="<?= $Page->LeftColumnClass ?>"><?= $Page->access_mode->caption() ?><?= $Page->access_mode->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->access_mode->cellAttributes() ?>>
<span id="el_records_access_mode">
<template id="tp_x_access_mode">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="records" data-field="x_access_mode" name="x_access_mode" id="x_access_mode"<?= $Page->access_mode->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_access_mode" class="ew-item-list"></div>
<selection-list hidden
    id="x_access_mode"
    name="x_access_mode"
    value="<?= HtmlEncode($Page->access_mode->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_access_mode"
    data-target="dsl_x_access_mode"
    data-repeatcolumn="5"
    class="form-control<?= $Page->access_mode->isInvalidClass() ?>"
    data-table="records"
    data-field="x_access_mode"
    data-value-separator="<?= $Page->access_mode->displayValueSeparatorAttribute() ?>"
    <?= $Page->access_mode->editAttributes() ?>></selection-list>
<?= $Page->access_mode->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->access_mode->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_encoded_plaintext_type->Visible) { // km_encoded_plaintext_type ?>
    <div id="r_km_encoded_plaintext_type"<?= $Page->km_encoded_plaintext_type->rowAttributes() ?>>
        <label id="elh_records_km_encoded_plaintext_type" for="x_km_encoded_plaintext_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_encoded_plaintext_type->caption() ?><?= $Page->km_encoded_plaintext_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_encoded_plaintext_type->cellAttributes() ?>>
<span id="el_records_km_encoded_plaintext_type">
    <select
        id="x_km_encoded_plaintext_type[]"
        name="x_km_encoded_plaintext_type[]"
        class="form-select ew-select<?= $Page->km_encoded_plaintext_type->isInvalidClass() ?>"
        <?php if (!$Page->km_encoded_plaintext_type->IsNativeSelect) { ?>
        data-select2-id="frecordsedit_x_km_encoded_plaintext_type[]"
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
    <?= $Page->km_encoded_plaintext_type->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->km_encoded_plaintext_type->getErrorMessage() ?></div>
<?php if (!$Page->km_encoded_plaintext_type->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordsedit", function() {
    var options = { name: "x_km_encoded_plaintext_type[]", selectId: "frecordsedit_x_km_encoded_plaintext_type[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordsedit.lists.km_encoded_plaintext_type?.lookupOptions.length) {
        options.data = { id: "x_km_encoded_plaintext_type[]", form: "frecordsedit" };
    } else {
        options.ajax = { id: "x_km_encoded_plaintext_type[]", form: "frecordsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_encoded_plaintext_type.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_numbers->Visible) { // km_numbers ?>
    <div id="r_km_numbers"<?= $Page->km_numbers->rowAttributes() ?>>
        <label id="elh_records_km_numbers" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_numbers->caption() ?><?= $Page->km_numbers->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_numbers->cellAttributes() ?>>
<span id="el_records_km_numbers">
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
    value="<?= HtmlEncode($Page->km_numbers->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_numbers"
    data-target="dsl_x_km_numbers"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_numbers->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_numbers"
    data-value-separator="<?= $Page->km_numbers->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_numbers->editAttributes() ?>></selection-list>
<?= $Page->km_numbers->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_numbers->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_content_words->Visible) { // km_content_words ?>
    <div id="r_km_content_words"<?= $Page->km_content_words->rowAttributes() ?>>
        <label id="elh_records_km_content_words" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_content_words->caption() ?><?= $Page->km_content_words->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_content_words->cellAttributes() ?>>
<span id="el_records_km_content_words">
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
    value="<?= HtmlEncode($Page->km_content_words->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_content_words"
    data-target="dsl_x_km_content_words"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_content_words->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_content_words"
    data-value-separator="<?= $Page->km_content_words->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_content_words->editAttributes() ?>></selection-list>
<?= $Page->km_content_words->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_content_words->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_function_words->Visible) { // km_function_words ?>
    <div id="r_km_function_words"<?= $Page->km_function_words->rowAttributes() ?>>
        <label id="elh_records_km_function_words" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_function_words->caption() ?><?= $Page->km_function_words->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_function_words->cellAttributes() ?>>
<span id="el_records_km_function_words">
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
    value="<?= HtmlEncode($Page->km_function_words->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_function_words"
    data-target="dsl_x_km_function_words"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_function_words->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_function_words"
    data-value-separator="<?= $Page->km_function_words->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_function_words->editAttributes() ?>></selection-list>
<?= $Page->km_function_words->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_function_words->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_syllables->Visible) { // km_syllables ?>
    <div id="r_km_syllables"<?= $Page->km_syllables->rowAttributes() ?>>
        <label id="elh_records_km_syllables" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_syllables->caption() ?><?= $Page->km_syllables->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_syllables->cellAttributes() ?>>
<span id="el_records_km_syllables">
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
    value="<?= HtmlEncode($Page->km_syllables->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_syllables"
    data-target="dsl_x_km_syllables"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_syllables->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_syllables"
    data-value-separator="<?= $Page->km_syllables->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_syllables->editAttributes() ?>></selection-list>
<?= $Page->km_syllables->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_syllables->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_morphological_endings->Visible) { // km_morphological_endings ?>
    <div id="r_km_morphological_endings"<?= $Page->km_morphological_endings->rowAttributes() ?>>
        <label id="elh_records_km_morphological_endings" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_morphological_endings->caption() ?><?= $Page->km_morphological_endings->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_morphological_endings->cellAttributes() ?>>
<span id="el_records_km_morphological_endings">
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
    value="<?= HtmlEncode($Page->km_morphological_endings->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_morphological_endings"
    data-target="dsl_x_km_morphological_endings"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_morphological_endings->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_morphological_endings"
    data-value-separator="<?= $Page->km_morphological_endings->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_morphological_endings->editAttributes() ?>></selection-list>
<?= $Page->km_morphological_endings->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_morphological_endings->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_phrases->Visible) { // km_phrases ?>
    <div id="r_km_phrases"<?= $Page->km_phrases->rowAttributes() ?>>
        <label id="elh_records_km_phrases" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_phrases->caption() ?><?= $Page->km_phrases->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_phrases->cellAttributes() ?>>
<span id="el_records_km_phrases">
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
    value="<?= HtmlEncode($Page->km_phrases->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_phrases"
    data-target="dsl_x_km_phrases"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_phrases->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_phrases"
    data-value-separator="<?= $Page->km_phrases->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_phrases->editAttributes() ?>></selection-list>
<?= $Page->km_phrases->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_phrases->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_sentences->Visible) { // km_sentences ?>
    <div id="r_km_sentences"<?= $Page->km_sentences->rowAttributes() ?>>
        <label id="elh_records_km_sentences" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_sentences->caption() ?><?= $Page->km_sentences->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_sentences->cellAttributes() ?>>
<span id="el_records_km_sentences">
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
    value="<?= HtmlEncode($Page->km_sentences->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_sentences"
    data-target="dsl_x_km_sentences"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_sentences->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_sentences"
    data-value-separator="<?= $Page->km_sentences->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_sentences->editAttributes() ?>></selection-list>
<?= $Page->km_sentences->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_sentences->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_punctuation->Visible) { // km_punctuation ?>
    <div id="r_km_punctuation"<?= $Page->km_punctuation->rowAttributes() ?>>
        <label id="elh_records_km_punctuation" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_punctuation->caption() ?><?= $Page->km_punctuation->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_punctuation->cellAttributes() ?>>
<span id="el_records_km_punctuation">
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
    value="<?= HtmlEncode($Page->km_punctuation->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_punctuation"
    data-target="dsl_x_km_punctuation"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_punctuation->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_punctuation"
    data-value-separator="<?= $Page->km_punctuation->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_punctuation->editAttributes() ?>></selection-list>
<?= $Page->km_punctuation->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_punctuation->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_nomenclature_size->Visible) { // km_nomenclature_size ?>
    <div id="r_km_nomenclature_size"<?= $Page->km_nomenclature_size->rowAttributes() ?>>
        <label id="elh_records_km_nomenclature_size" for="x_km_nomenclature_size" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_nomenclature_size->caption() ?><?= $Page->km_nomenclature_size->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_nomenclature_size->cellAttributes() ?>>
<span id="el_records_km_nomenclature_size">
    <select
        id="x_km_nomenclature_size"
        name="x_km_nomenclature_size"
        class="form-select ew-select<?= $Page->km_nomenclature_size->isInvalidClass() ?>"
        <?php if (!$Page->km_nomenclature_size->IsNativeSelect) { ?>
        data-select2-id="frecordsedit_x_km_nomenclature_size"
        <?php } ?>
        data-table="records"
        data-field="x_km_nomenclature_size"
        data-value-separator="<?= $Page->km_nomenclature_size->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->km_nomenclature_size->getPlaceHolder()) ?>"
        <?= $Page->km_nomenclature_size->editAttributes() ?>>
        <?= $Page->km_nomenclature_size->selectOptionListHtml("x_km_nomenclature_size") ?>
    </select>
    <?= $Page->km_nomenclature_size->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->km_nomenclature_size->getErrorMessage() ?></div>
<?php if (!$Page->km_nomenclature_size->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordsedit", function() {
    var options = { name: "x_km_nomenclature_size", selectId: "frecordsedit_x_km_nomenclature_size" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordsedit.lists.km_nomenclature_size?.lookupOptions.length) {
        options.data = { id: "x_km_nomenclature_size", form: "frecordsedit" };
    } else {
        options.ajax = { id: "x_km_nomenclature_size", form: "frecordsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_nomenclature_size.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_sections->Visible) { // km_sections ?>
    <div id="r_km_sections"<?= $Page->km_sections->rowAttributes() ?>>
        <label id="elh_records_km_sections" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_sections->caption() ?><?= $Page->km_sections->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_sections->cellAttributes() ?>>
<span id="el_records_km_sections">
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
    value="<?= HtmlEncode($Page->km_sections->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_sections"
    data-target="dsl_x_km_sections"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_sections->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_sections"
    data-value-separator="<?= $Page->km_sections->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_sections->editAttributes() ?>></selection-list>
<?= $Page->km_sections->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_sections->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_headings->Visible) { // km_headings ?>
    <div id="r_km_headings"<?= $Page->km_headings->rowAttributes() ?>>
        <label id="elh_records_km_headings" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_headings->caption() ?><?= $Page->km_headings->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_headings->cellAttributes() ?>>
<span id="el_records_km_headings">
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
    value="<?= HtmlEncode($Page->km_headings->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_headings"
    data-target="dsl_x_km_headings"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_headings->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_headings"
    data-value-separator="<?= $Page->km_headings->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_headings->editAttributes() ?>></selection-list>
<?= $Page->km_headings->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_headings->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_plaintext_arrangement->Visible) { // km_plaintext_arrangement ?>
    <div id="r_km_plaintext_arrangement"<?= $Page->km_plaintext_arrangement->rowAttributes() ?>>
        <label id="elh_records_km_plaintext_arrangement" for="x_km_plaintext_arrangement" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_plaintext_arrangement->caption() ?><?= $Page->km_plaintext_arrangement->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_plaintext_arrangement->cellAttributes() ?>>
<span id="el_records_km_plaintext_arrangement">
    <select
        id="x_km_plaintext_arrangement[]"
        name="x_km_plaintext_arrangement[]"
        class="form-select ew-select<?= $Page->km_plaintext_arrangement->isInvalidClass() ?>"
        <?php if (!$Page->km_plaintext_arrangement->IsNativeSelect) { ?>
        data-select2-id="frecordsedit_x_km_plaintext_arrangement[]"
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
    <?= $Page->km_plaintext_arrangement->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->km_plaintext_arrangement->getErrorMessage() ?></div>
<?php if (!$Page->km_plaintext_arrangement->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordsedit", function() {
    var options = { name: "x_km_plaintext_arrangement[]", selectId: "frecordsedit_x_km_plaintext_arrangement[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordsedit.lists.km_plaintext_arrangement?.lookupOptions.length) {
        options.data = { id: "x_km_plaintext_arrangement[]", form: "frecordsedit" };
    } else {
        options.ajax = { id: "x_km_plaintext_arrangement[]", form: "frecordsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_plaintext_arrangement.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_ciphertext_arrangement->Visible) { // km_ciphertext_arrangement ?>
    <div id="r_km_ciphertext_arrangement"<?= $Page->km_ciphertext_arrangement->rowAttributes() ?>>
        <label id="elh_records_km_ciphertext_arrangement" for="x_km_ciphertext_arrangement" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_ciphertext_arrangement->caption() ?><?= $Page->km_ciphertext_arrangement->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_ciphertext_arrangement->cellAttributes() ?>>
<span id="el_records_km_ciphertext_arrangement">
    <select
        id="x_km_ciphertext_arrangement[]"
        name="x_km_ciphertext_arrangement[]"
        class="form-select ew-select<?= $Page->km_ciphertext_arrangement->isInvalidClass() ?>"
        <?php if (!$Page->km_ciphertext_arrangement->IsNativeSelect) { ?>
        data-select2-id="frecordsedit_x_km_ciphertext_arrangement[]"
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
    <?= $Page->km_ciphertext_arrangement->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->km_ciphertext_arrangement->getErrorMessage() ?></div>
<?php if (!$Page->km_ciphertext_arrangement->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordsedit", function() {
    var options = { name: "x_km_ciphertext_arrangement[]", selectId: "frecordsedit_x_km_ciphertext_arrangement[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordsedit.lists.km_ciphertext_arrangement?.lookupOptions.length) {
        options.data = { id: "x_km_ciphertext_arrangement[]", form: "frecordsedit" };
    } else {
        options.ajax = { id: "x_km_ciphertext_arrangement[]", form: "frecordsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_ciphertext_arrangement.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_memorability->Visible) { // km_memorability ?>
    <div id="r_km_memorability"<?= $Page->km_memorability->rowAttributes() ?>>
        <label id="elh_records_km_memorability" for="x_km_memorability" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_memorability->caption() ?><?= $Page->km_memorability->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_memorability->cellAttributes() ?>>
<span id="el_records_km_memorability">
    <select
        id="x_km_memorability"
        name="x_km_memorability"
        class="form-select ew-select<?= $Page->km_memorability->isInvalidClass() ?>"
        <?php if (!$Page->km_memorability->IsNativeSelect) { ?>
        data-select2-id="frecordsedit_x_km_memorability"
        <?php } ?>
        data-table="records"
        data-field="x_km_memorability"
        data-value-separator="<?= $Page->km_memorability->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->km_memorability->getPlaceHolder()) ?>"
        <?= $Page->km_memorability->editAttributes() ?>>
        <?= $Page->km_memorability->selectOptionListHtml("x_km_memorability") ?>
    </select>
    <?= $Page->km_memorability->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->km_memorability->getErrorMessage() ?></div>
<?php if (!$Page->km_memorability->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordsedit", function() {
    var options = { name: "x_km_memorability", selectId: "frecordsedit_x_km_memorability" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordsedit.lists.km_memorability?.lookupOptions.length) {
        options.data = { id: "x_km_memorability", form: "frecordsedit" };
    } else {
        options.ajax = { id: "x_km_memorability", form: "frecordsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_memorability.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_symbol_set->Visible) { // km_symbol_set ?>
    <div id="r_km_symbol_set"<?= $Page->km_symbol_set->rowAttributes() ?>>
        <label id="elh_records_km_symbol_set" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_symbol_set->caption() ?><?= $Page->km_symbol_set->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_symbol_set->cellAttributes() ?>>
<span id="el_records_km_symbol_set">
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
    value="<?= HtmlEncode($Page->km_symbol_set->CurrentValue) ?>"
    data-type="select-multiple"
    data-template="tp_x_km_symbol_set"
    data-target="dsl_x_km_symbol_set"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_symbol_set->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_symbol_set"
    data-value-separator="<?= $Page->km_symbol_set->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_symbol_set->editAttributes() ?>></selection-list>
<?= $Page->km_symbol_set->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_symbol_set->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_diacritics->Visible) { // km_diacritics ?>
    <div id="r_km_diacritics"<?= $Page->km_diacritics->rowAttributes() ?>>
        <label id="elh_records_km_diacritics" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_diacritics->caption() ?><?= $Page->km_diacritics->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_diacritics->cellAttributes() ?>>
<span id="el_records_km_diacritics">
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
    value="<?= HtmlEncode($Page->km_diacritics->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_diacritics"
    data-target="dsl_x_km_diacritics"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_diacritics->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_diacritics"
    data-value-separator="<?= $Page->km_diacritics->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_diacritics->editAttributes() ?>></selection-list>
<?= $Page->km_diacritics->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_diacritics->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_code_length->Visible) { // km_code_length ?>
    <div id="r_km_code_length"<?= $Page->km_code_length->rowAttributes() ?>>
        <label id="elh_records_km_code_length" for="x_km_code_length" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_code_length->caption() ?><?= $Page->km_code_length->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_code_length->cellAttributes() ?>>
<span id="el_records_km_code_length">
    <select
        id="x_km_code_length[]"
        name="x_km_code_length[]"
        class="form-select ew-select<?= $Page->km_code_length->isInvalidClass() ?>"
        <?php if (!$Page->km_code_length->IsNativeSelect) { ?>
        data-select2-id="frecordsedit_x_km_code_length[]"
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
    <?= $Page->km_code_length->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->km_code_length->getErrorMessage() ?></div>
<?php if (!$Page->km_code_length->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordsedit", function() {
    var options = { name: "x_km_code_length[]", selectId: "frecordsedit_x_km_code_length[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordsedit.lists.km_code_length?.lookupOptions.length) {
        options.data = { id: "x_km_code_length[]", form: "frecordsedit" };
    } else {
        options.ajax = { id: "x_km_code_length[]", form: "frecordsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_code_length.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_code_type->Visible) { // km_code_type ?>
    <div id="r_km_code_type"<?= $Page->km_code_type->rowAttributes() ?>>
        <label id="elh_records_km_code_type" for="x_km_code_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_code_type->caption() ?><?= $Page->km_code_type->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_code_type->cellAttributes() ?>>
<span id="el_records_km_code_type">
    <select
        id="x_km_code_type[]"
        name="x_km_code_type[]"
        class="form-select ew-select<?= $Page->km_code_type->isInvalidClass() ?>"
        <?php if (!$Page->km_code_type->IsNativeSelect) { ?>
        data-select2-id="frecordsedit_x_km_code_type[]"
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
    <?= $Page->km_code_type->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->km_code_type->getErrorMessage() ?></div>
<?php if (!$Page->km_code_type->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordsedit", function() {
    var options = { name: "x_km_code_type[]", selectId: "frecordsedit_x_km_code_type[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordsedit.lists.km_code_type?.lookupOptions.length) {
        options.data = { id: "x_km_code_type[]", form: "frecordsedit" };
    } else {
        options.ajax = { id: "x_km_code_type[]", form: "frecordsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_code_type.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_metaphors->Visible) { // km_metaphors ?>
    <div id="r_km_metaphors"<?= $Page->km_metaphors->rowAttributes() ?>>
        <label id="elh_records_km_metaphors" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_metaphors->caption() ?><?= $Page->km_metaphors->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_metaphors->cellAttributes() ?>>
<span id="el_records_km_metaphors">
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
    value="<?= HtmlEncode($Page->km_metaphors->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_metaphors"
    data-target="dsl_x_km_metaphors"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_metaphors->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_metaphors"
    data-value-separator="<?= $Page->km_metaphors->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_metaphors->editAttributes() ?>></selection-list>
<?= $Page->km_metaphors->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_metaphors->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_material_properties->Visible) { // km_material_properties ?>
    <div id="r_km_material_properties"<?= $Page->km_material_properties->rowAttributes() ?>>
        <label id="elh_records_km_material_properties" for="x_km_material_properties" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_material_properties->caption() ?><?= $Page->km_material_properties->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_material_properties->cellAttributes() ?>>
<span id="el_records_km_material_properties">
    <select
        id="x_km_material_properties[]"
        name="x_km_material_properties[]"
        class="form-select ew-select<?= $Page->km_material_properties->isInvalidClass() ?>"
        <?php if (!$Page->km_material_properties->IsNativeSelect) { ?>
        data-select2-id="frecordsedit_x_km_material_properties[]"
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
    <?= $Page->km_material_properties->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->km_material_properties->getErrorMessage() ?></div>
<?php if (!$Page->km_material_properties->IsNativeSelect) { ?>
<script>
loadjs.ready("frecordsedit", function() {
    var options = { name: "x_km_material_properties[]", selectId: "frecordsedit_x_km_material_properties[]" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.multiple = true;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (frecordsedit.lists.km_material_properties?.lookupOptions.length) {
        options.data = { id: "x_km_material_properties[]", form: "frecordsedit" };
    } else {
        options.ajax = { id: "x_km_material_properties[]", form: "frecordsedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.records.fields.km_material_properties.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->km_instructions->Visible) { // km_instructions ?>
    <div id="r_km_instructions"<?= $Page->km_instructions->rowAttributes() ?>>
        <label id="elh_records_km_instructions" class="<?= $Page->LeftColumnClass ?>"><?= $Page->km_instructions->caption() ?><?= $Page->km_instructions->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->km_instructions->cellAttributes() ?>>
<span id="el_records_km_instructions">
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
    value="<?= HtmlEncode($Page->km_instructions->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_km_instructions"
    data-target="dsl_x_km_instructions"
    data-repeatcolumn="5"
    class="form-control<?= $Page->km_instructions->isInvalidClass() ?>"
    data-table="records"
    data-field="x_km_instructions"
    data-value-separator="<?= $Page->km_instructions->displayValueSeparatorAttribute() ?>"
    <?= $Page->km_instructions->editAttributes() ?>></selection-list>
<?= $Page->km_instructions->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->km_instructions->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<span id="el_records_creator_id">
<input type="hidden" data-table="records" data-field="x_creator_id" data-hidden="1" name="x_creator_id" id="x_creator_id" value="<?= HtmlEncode($Page->creator_id->CurrentValue) ?>">
</span>
<?php
    if (in_array("images", explode(",", $Page->getCurrentDetailTable())) && $images->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("images", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ImagesGrid.php" ?>
<?php } ?>
<?php
    if (in_array("documents", explode(",", $Page->getCurrentDetailTable())) && $documents->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("documents", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "DocumentsGrid.php" ?>
<?php } ?>
<?php
    if (in_array("associated_records", explode(",", $Page->getCurrentDetailTable())) && $associated_records->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("associated_records", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "AssociatedRecordsGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="frecordsedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="frecordsedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
    $("<div id=\"hloc\" class=\"inlinehead\">Location</div>").insertBefore("#r_current_country");
    $("<div id=\"hdt\" class=\"inlinehead\">Uploaded document types Types</div>").insertBefore("#r_document_types");
    $("<div id=\"horig\" class=\"inlinehead\">Origin</div>").insertBefore("#r_start_date");
    $("<div id=\"hcont\" class=\"inlinehead\">Content</div>").insertBefore("#r_record_type");
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
