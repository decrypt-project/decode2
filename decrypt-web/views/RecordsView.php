<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$RecordsView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<form name="frecordsview" id="frecordsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { records: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var frecordsview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("frecordsview")
        .setPageId("view")
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
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="records">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_records_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->name->Visible) { // name ?>
    <tr id="r_name"<?= $Page->name->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_name"><?= $Page->name->caption() ?></span></td>
        <td data-name="name"<?= $Page->name->cellAttributes() ?>>
<span id="el_records_name">
<span<?= $Page->name->viewAttributes() ?>>
<?= $Page->name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->owner->Visible) { // owner ?>
    <tr id="r_owner"<?= $Page->owner->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_owner"><?= $Page->owner->caption() ?></span></td>
        <td data-name="owner"<?= $Page->owner->cellAttributes() ?>>
<span id="el_records_owner">
<span<?= $Page->owner->viewAttributes() ?>>
<?= $Page->owner->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->c_holder->Visible) { // c_holder ?>
    <tr id="r_c_holder"<?= $Page->c_holder->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_c_holder"><?= $Page->c_holder->caption() ?></span></td>
        <td data-name="c_holder"<?= $Page->c_holder->cellAttributes() ?>>
<span id="el_records_c_holder">
<span<?= $Page->c_holder->viewAttributes() ?>>
<?= $Page->c_holder->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->c_cates->Visible) { // c_cates ?>
    <tr id="r_c_cates"<?= $Page->c_cates->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_c_cates"><?= $Page->c_cates->caption() ?></span></td>
        <td data-name="c_cates"<?= $Page->c_cates->cellAttributes() ?>>
<span id="el_records_c_cates">
<span<?= $Page->c_cates->viewAttributes() ?>>
<?= $Page->c_cates->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->c_author->Visible) { // c_author ?>
    <tr id="r_c_author"<?= $Page->c_author->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_c_author"><?= $Page->c_author->caption() ?></span></td>
        <td data-name="c_author"<?= $Page->c_author->cellAttributes() ?>>
<span id="el_records_c_author">
<span<?= $Page->c_author->viewAttributes() ?>>
<?= $Page->c_author->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->c_lang->Visible) { // c_lang ?>
    <tr id="r_c_lang"<?= $Page->c_lang->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_c_lang"><?= $Page->c_lang->caption() ?></span></td>
        <td data-name="c_lang"<?= $Page->c_lang->cellAttributes() ?>>
<span id="el_records_c_lang">
<span<?= $Page->c_lang->viewAttributes() ?>>
<?= $Page->c_lang->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->current_country->Visible) { // current_country ?>
    <tr id="r_current_country"<?= $Page->current_country->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_current_country"><?= $Page->current_country->caption() ?></span></td>
        <td data-name="current_country"<?= $Page->current_country->cellAttributes() ?>>
<span id="el_records_current_country">
<span<?= $Page->current_country->viewAttributes() ?>>
<?= $Page->current_country->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->current_city->Visible) { // current_city ?>
    <tr id="r_current_city"<?= $Page->current_city->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_current_city"><?= $Page->current_city->caption() ?></span></td>
        <td data-name="current_city"<?= $Page->current_city->cellAttributes() ?>>
<span id="el_records_current_city">
<span<?= $Page->current_city->viewAttributes() ?>>
<?= $Page->current_city->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->current_holder->Visible) { // current_holder ?>
    <tr id="r_current_holder"<?= $Page->current_holder->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_current_holder"><?= $Page->current_holder->caption() ?></span></td>
        <td data-name="current_holder"<?= $Page->current_holder->cellAttributes() ?>>
<span id="el_records_current_holder">
<span<?= $Page->current_holder->viewAttributes() ?>>
<?= $Page->current_holder->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->author->Visible) { // author ?>
    <tr id="r_author"<?= $Page->author->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_author"><?= $Page->author->caption() ?></span></td>
        <td data-name="author"<?= $Page->author->cellAttributes() ?>>
<span id="el_records_author">
<span<?= $Page->author->viewAttributes() ?>>
<?= $Page->author->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sender->Visible) { // sender ?>
    <tr id="r_sender"<?= $Page->sender->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_sender"><?= $Page->sender->caption() ?></span></td>
        <td data-name="sender"<?= $Page->sender->cellAttributes() ?>>
<span id="el_records_sender">
<span<?= $Page->sender->viewAttributes() ?>>
<?= $Page->sender->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->receiver->Visible) { // receiver ?>
    <tr id="r_receiver"<?= $Page->receiver->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_receiver"><?= $Page->receiver->caption() ?></span></td>
        <td data-name="receiver"<?= $Page->receiver->cellAttributes() ?>>
<span id="el_records_receiver">
<span<?= $Page->receiver->viewAttributes() ?>>
<?= $Page->receiver->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->origin_region->Visible) { // origin_region ?>
    <tr id="r_origin_region"<?= $Page->origin_region->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_origin_region"><?= $Page->origin_region->caption() ?></span></td>
        <td data-name="origin_region"<?= $Page->origin_region->cellAttributes() ?>>
<span id="el_records_origin_region">
<span<?= $Page->origin_region->viewAttributes() ?>>
<?= $Page->origin_region->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->origin_city->Visible) { // origin_city ?>
    <tr id="r_origin_city"<?= $Page->origin_city->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_origin_city"><?= $Page->origin_city->caption() ?></span></td>
        <td data-name="origin_city"<?= $Page->origin_city->cellAttributes() ?>>
<span id="el_records_origin_city">
<span<?= $Page->origin_city->viewAttributes() ?>>
<?= $Page->origin_city->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->start_year->Visible) { // start_year ?>
    <tr id="r_start_year"<?= $Page->start_year->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_start_year"><?= $Page->start_year->caption() ?></span></td>
        <td data-name="start_year"<?= $Page->start_year->cellAttributes() ?>>
<span id="el_records_start_year">
<span<?= $Page->start_year->viewAttributes() ?>>
<?= $Page->start_year->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->start_month->Visible) { // start_month ?>
    <tr id="r_start_month"<?= $Page->start_month->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_start_month"><?= $Page->start_month->caption() ?></span></td>
        <td data-name="start_month"<?= $Page->start_month->cellAttributes() ?>>
<span id="el_records_start_month">
<span<?= $Page->start_month->viewAttributes() ?>>
<?= $Page->start_month->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->start_day->Visible) { // start_day ?>
    <tr id="r_start_day"<?= $Page->start_day->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_start_day"><?= $Page->start_day->caption() ?></span></td>
        <td data-name="start_day"<?= $Page->start_day->cellAttributes() ?>>
<span id="el_records_start_day">
<span<?= $Page->start_day->viewAttributes() ?>>
<?= $Page->start_day->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->end_year->Visible) { // end_year ?>
    <tr id="r_end_year"<?= $Page->end_year->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_end_year"><?= $Page->end_year->caption() ?></span></td>
        <td data-name="end_year"<?= $Page->end_year->cellAttributes() ?>>
<span id="el_records_end_year">
<span<?= $Page->end_year->viewAttributes() ?>>
<?= $Page->end_year->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->end_month->Visible) { // end_month ?>
    <tr id="r_end_month"<?= $Page->end_month->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_end_month"><?= $Page->end_month->caption() ?></span></td>
        <td data-name="end_month"<?= $Page->end_month->cellAttributes() ?>>
<span id="el_records_end_month">
<span<?= $Page->end_month->viewAttributes() ?>>
<?= $Page->end_month->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->end_day->Visible) { // end_day ?>
    <tr id="r_end_day"<?= $Page->end_day->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_end_day"><?= $Page->end_day->caption() ?></span></td>
        <td data-name="end_day"<?= $Page->end_day->cellAttributes() ?>>
<span id="el_records_end_day">
<span<?= $Page->end_day->viewAttributes() ?>>
<?= $Page->end_day->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->record_type->Visible) { // record_type ?>
    <tr id="r_record_type"<?= $Page->record_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_record_type"><?= $Page->record_type->caption() ?></span></td>
        <td data-name="record_type"<?= $Page->record_type->cellAttributes() ?>>
<span id="el_records_record_type">
<span<?= $Page->record_type->viewAttributes() ?>>
<?= $Page->record_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status"<?= $Page->status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el_records_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->symbol_sets->Visible) { // symbol_sets ?>
    <tr id="r_symbol_sets"<?= $Page->symbol_sets->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_symbol_sets"><?= $Page->symbol_sets->caption() ?></span></td>
        <td data-name="symbol_sets"<?= $Page->symbol_sets->cellAttributes() ?>>
<span id="el_records_symbol_sets">
<span<?= $Page->symbol_sets->viewAttributes() ?>>
<?= $Page->symbol_sets->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cipher_types->Visible) { // cipher_types ?>
    <tr id="r_cipher_types"<?= $Page->cipher_types->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_cipher_types"><?= $Page->cipher_types->caption() ?></span></td>
        <td data-name="cipher_types"<?= $Page->cipher_types->cellAttributes() ?>>
<span id="el_records_cipher_types">
<span<?= $Page->cipher_types->viewAttributes() ?>>
<?= $Page->cipher_types->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cipher_type_other->Visible) { // cipher_type_other ?>
    <tr id="r_cipher_type_other"<?= $Page->cipher_type_other->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_cipher_type_other"><?= $Page->cipher_type_other->caption() ?></span></td>
        <td data-name="cipher_type_other"<?= $Page->cipher_type_other->cellAttributes() ?>>
<span id="el_records_cipher_type_other">
<span<?= $Page->cipher_type_other->viewAttributes() ?>>
<?= $Page->cipher_type_other->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->symbol_set_other->Visible) { // symbol_set_other ?>
    <tr id="r_symbol_set_other"<?= $Page->symbol_set_other->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_symbol_set_other"><?= $Page->symbol_set_other->caption() ?></span></td>
        <td data-name="symbol_set_other"<?= $Page->symbol_set_other->cellAttributes() ?>>
<span id="el_records_symbol_set_other">
<span<?= $Page->symbol_set_other->viewAttributes() ?>>
<?= $Page->symbol_set_other->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->number_of_pages->Visible) { // number_of_pages ?>
    <tr id="r_number_of_pages"<?= $Page->number_of_pages->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_number_of_pages"><?= $Page->number_of_pages->caption() ?></span></td>
        <td data-name="number_of_pages"<?= $Page->number_of_pages->cellAttributes() ?>>
<span id="el_records_number_of_pages">
<span<?= $Page->number_of_pages->viewAttributes() ?>>
<?= $Page->number_of_pages->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->inline_cleartext->Visible) { // inline_cleartext ?>
    <tr id="r_inline_cleartext"<?= $Page->inline_cleartext->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_inline_cleartext"><?= $Page->inline_cleartext->caption() ?></span></td>
        <td data-name="inline_cleartext"<?= $Page->inline_cleartext->cellAttributes() ?>>
<span id="el_records_inline_cleartext">
<span<?= $Page->inline_cleartext->viewAttributes() ?>>
<?= $Page->inline_cleartext->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->inline_plaintext->Visible) { // inline_plaintext ?>
    <tr id="r_inline_plaintext"<?= $Page->inline_plaintext->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_inline_plaintext"><?= $Page->inline_plaintext->caption() ?></span></td>
        <td data-name="inline_plaintext"<?= $Page->inline_plaintext->cellAttributes() ?>>
<span id="el_records_inline_plaintext">
<span<?= $Page->inline_plaintext->viewAttributes() ?>>
<?= $Page->inline_plaintext->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cleartext_lang->Visible) { // cleartext_lang ?>
    <tr id="r_cleartext_lang"<?= $Page->cleartext_lang->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_cleartext_lang"><?= $Page->cleartext_lang->caption() ?></span></td>
        <td data-name="cleartext_lang"<?= $Page->cleartext_lang->cellAttributes() ?>>
<span id="el_records_cleartext_lang">
<span<?= $Page->cleartext_lang->viewAttributes() ?>>
<?= $Page->cleartext_lang->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->plaintext_lang->Visible) { // plaintext_lang ?>
    <tr id="r_plaintext_lang"<?= $Page->plaintext_lang->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_plaintext_lang"><?= $Page->plaintext_lang->caption() ?></span></td>
        <td data-name="plaintext_lang"<?= $Page->plaintext_lang->cellAttributes() ?>>
<span id="el_records_plaintext_lang">
<span<?= $Page->plaintext_lang->viewAttributes() ?>>
<?= $Page->plaintext_lang->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->private_ciphertext->Visible) { // private_ciphertext ?>
    <tr id="r_private_ciphertext"<?= $Page->private_ciphertext->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_private_ciphertext"><?= $Page->private_ciphertext->caption() ?></span></td>
        <td data-name="private_ciphertext"<?= $Page->private_ciphertext->cellAttributes() ?>>
<span id="el_records_private_ciphertext">
<span<?= $Page->private_ciphertext->viewAttributes() ?>>
<?= $Page->private_ciphertext->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->document_types->Visible) { // document_types ?>
    <tr id="r_document_types"<?= $Page->document_types->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_document_types"><?= $Page->document_types->caption() ?></span></td>
        <td data-name="document_types"<?= $Page->document_types->cellAttributes() ?>>
<span id="el_records_document_types">
<span<?= $Page->document_types->viewAttributes() ?>>
<?= $Page->document_types->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->paper->Visible) { // paper ?>
    <tr id="r_paper"<?= $Page->paper->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_paper"><?= $Page->paper->caption() ?></span></td>
        <td data-name="paper"<?= $Page->paper->cellAttributes() ?>>
<span id="el_records_paper">
<span<?= $Page->paper->viewAttributes() ?>>
<?= $Page->paper->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->access_mode->Visible) { // access_mode ?>
    <tr id="r_access_mode"<?= $Page->access_mode->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_access_mode"><?= $Page->access_mode->caption() ?></span></td>
        <td data-name="access_mode"<?= $Page->access_mode->cellAttributes() ?>>
<span id="el_records_access_mode">
<span<?= $Page->access_mode->viewAttributes() ?>>
<?= $Page->access_mode->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->creation_date->Visible) { // creation_date ?>
    <tr id="r_creation_date"<?= $Page->creation_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_creation_date"><?= $Page->creation_date->caption() ?></span></td>
        <td data-name="creation_date"<?= $Page->creation_date->cellAttributes() ?>>
<span id="el_records_creation_date">
<span<?= $Page->creation_date->viewAttributes() ?>>
<?= $Page->creation_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_encoded_plaintext_type->Visible) { // km_encoded_plaintext_type ?>
    <tr id="r_km_encoded_plaintext_type"<?= $Page->km_encoded_plaintext_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_encoded_plaintext_type"><?= $Page->km_encoded_plaintext_type->caption() ?></span></td>
        <td data-name="km_encoded_plaintext_type"<?= $Page->km_encoded_plaintext_type->cellAttributes() ?>>
<span id="el_records_km_encoded_plaintext_type">
<span<?= $Page->km_encoded_plaintext_type->viewAttributes() ?>>
<?= $Page->km_encoded_plaintext_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_numbers->Visible) { // km_numbers ?>
    <tr id="r_km_numbers"<?= $Page->km_numbers->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_numbers"><?= $Page->km_numbers->caption() ?></span></td>
        <td data-name="km_numbers"<?= $Page->km_numbers->cellAttributes() ?>>
<span id="el_records_km_numbers">
<span<?= $Page->km_numbers->viewAttributes() ?>>
<?= $Page->km_numbers->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_content_words->Visible) { // km_content_words ?>
    <tr id="r_km_content_words"<?= $Page->km_content_words->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_content_words"><?= $Page->km_content_words->caption() ?></span></td>
        <td data-name="km_content_words"<?= $Page->km_content_words->cellAttributes() ?>>
<span id="el_records_km_content_words">
<span<?= $Page->km_content_words->viewAttributes() ?>>
<?= $Page->km_content_words->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_function_words->Visible) { // km_function_words ?>
    <tr id="r_km_function_words"<?= $Page->km_function_words->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_function_words"><?= $Page->km_function_words->caption() ?></span></td>
        <td data-name="km_function_words"<?= $Page->km_function_words->cellAttributes() ?>>
<span id="el_records_km_function_words">
<span<?= $Page->km_function_words->viewAttributes() ?>>
<?= $Page->km_function_words->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_syllables->Visible) { // km_syllables ?>
    <tr id="r_km_syllables"<?= $Page->km_syllables->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_syllables"><?= $Page->km_syllables->caption() ?></span></td>
        <td data-name="km_syllables"<?= $Page->km_syllables->cellAttributes() ?>>
<span id="el_records_km_syllables">
<span<?= $Page->km_syllables->viewAttributes() ?>>
<?= $Page->km_syllables->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_morphological_endings->Visible) { // km_morphological_endings ?>
    <tr id="r_km_morphological_endings"<?= $Page->km_morphological_endings->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_morphological_endings"><?= $Page->km_morphological_endings->caption() ?></span></td>
        <td data-name="km_morphological_endings"<?= $Page->km_morphological_endings->cellAttributes() ?>>
<span id="el_records_km_morphological_endings">
<span<?= $Page->km_morphological_endings->viewAttributes() ?>>
<?= $Page->km_morphological_endings->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_phrases->Visible) { // km_phrases ?>
    <tr id="r_km_phrases"<?= $Page->km_phrases->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_phrases"><?= $Page->km_phrases->caption() ?></span></td>
        <td data-name="km_phrases"<?= $Page->km_phrases->cellAttributes() ?>>
<span id="el_records_km_phrases">
<span<?= $Page->km_phrases->viewAttributes() ?>>
<?= $Page->km_phrases->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_sentences->Visible) { // km_sentences ?>
    <tr id="r_km_sentences"<?= $Page->km_sentences->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_sentences"><?= $Page->km_sentences->caption() ?></span></td>
        <td data-name="km_sentences"<?= $Page->km_sentences->cellAttributes() ?>>
<span id="el_records_km_sentences">
<span<?= $Page->km_sentences->viewAttributes() ?>>
<?= $Page->km_sentences->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_punctuation->Visible) { // km_punctuation ?>
    <tr id="r_km_punctuation"<?= $Page->km_punctuation->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_punctuation"><?= $Page->km_punctuation->caption() ?></span></td>
        <td data-name="km_punctuation"<?= $Page->km_punctuation->cellAttributes() ?>>
<span id="el_records_km_punctuation">
<span<?= $Page->km_punctuation->viewAttributes() ?>>
<?= $Page->km_punctuation->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_nomenclature_size->Visible) { // km_nomenclature_size ?>
    <tr id="r_km_nomenclature_size"<?= $Page->km_nomenclature_size->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_nomenclature_size"><?= $Page->km_nomenclature_size->caption() ?></span></td>
        <td data-name="km_nomenclature_size"<?= $Page->km_nomenclature_size->cellAttributes() ?>>
<span id="el_records_km_nomenclature_size">
<span<?= $Page->km_nomenclature_size->viewAttributes() ?>>
<?= $Page->km_nomenclature_size->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_sections->Visible) { // km_sections ?>
    <tr id="r_km_sections"<?= $Page->km_sections->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_sections"><?= $Page->km_sections->caption() ?></span></td>
        <td data-name="km_sections"<?= $Page->km_sections->cellAttributes() ?>>
<span id="el_records_km_sections">
<span<?= $Page->km_sections->viewAttributes() ?>>
<?= $Page->km_sections->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_headings->Visible) { // km_headings ?>
    <tr id="r_km_headings"<?= $Page->km_headings->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_headings"><?= $Page->km_headings->caption() ?></span></td>
        <td data-name="km_headings"<?= $Page->km_headings->cellAttributes() ?>>
<span id="el_records_km_headings">
<span<?= $Page->km_headings->viewAttributes() ?>>
<?= $Page->km_headings->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_plaintext_arrangement->Visible) { // km_plaintext_arrangement ?>
    <tr id="r_km_plaintext_arrangement"<?= $Page->km_plaintext_arrangement->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_plaintext_arrangement"><?= $Page->km_plaintext_arrangement->caption() ?></span></td>
        <td data-name="km_plaintext_arrangement"<?= $Page->km_plaintext_arrangement->cellAttributes() ?>>
<span id="el_records_km_plaintext_arrangement">
<span<?= $Page->km_plaintext_arrangement->viewAttributes() ?>>
<?= $Page->km_plaintext_arrangement->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_ciphertext_arrangement->Visible) { // km_ciphertext_arrangement ?>
    <tr id="r_km_ciphertext_arrangement"<?= $Page->km_ciphertext_arrangement->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_ciphertext_arrangement"><?= $Page->km_ciphertext_arrangement->caption() ?></span></td>
        <td data-name="km_ciphertext_arrangement"<?= $Page->km_ciphertext_arrangement->cellAttributes() ?>>
<span id="el_records_km_ciphertext_arrangement">
<span<?= $Page->km_ciphertext_arrangement->viewAttributes() ?>>
<?= $Page->km_ciphertext_arrangement->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_memorability->Visible) { // km_memorability ?>
    <tr id="r_km_memorability"<?= $Page->km_memorability->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_memorability"><?= $Page->km_memorability->caption() ?></span></td>
        <td data-name="km_memorability"<?= $Page->km_memorability->cellAttributes() ?>>
<span id="el_records_km_memorability">
<span<?= $Page->km_memorability->viewAttributes() ?>>
<?= $Page->km_memorability->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_symbol_set->Visible) { // km_symbol_set ?>
    <tr id="r_km_symbol_set"<?= $Page->km_symbol_set->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_symbol_set"><?= $Page->km_symbol_set->caption() ?></span></td>
        <td data-name="km_symbol_set"<?= $Page->km_symbol_set->cellAttributes() ?>>
<span id="el_records_km_symbol_set">
<span<?= $Page->km_symbol_set->viewAttributes() ?>>
<?= $Page->km_symbol_set->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_diacritics->Visible) { // km_diacritics ?>
    <tr id="r_km_diacritics"<?= $Page->km_diacritics->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_diacritics"><?= $Page->km_diacritics->caption() ?></span></td>
        <td data-name="km_diacritics"<?= $Page->km_diacritics->cellAttributes() ?>>
<span id="el_records_km_diacritics">
<span<?= $Page->km_diacritics->viewAttributes() ?>>
<?= $Page->km_diacritics->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_code_length->Visible) { // km_code_length ?>
    <tr id="r_km_code_length"<?= $Page->km_code_length->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_code_length"><?= $Page->km_code_length->caption() ?></span></td>
        <td data-name="km_code_length"<?= $Page->km_code_length->cellAttributes() ?>>
<span id="el_records_km_code_length">
<span<?= $Page->km_code_length->viewAttributes() ?>>
<?= $Page->km_code_length->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_code_type->Visible) { // km_code_type ?>
    <tr id="r_km_code_type"<?= $Page->km_code_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_code_type"><?= $Page->km_code_type->caption() ?></span></td>
        <td data-name="km_code_type"<?= $Page->km_code_type->cellAttributes() ?>>
<span id="el_records_km_code_type">
<span<?= $Page->km_code_type->viewAttributes() ?>>
<?= $Page->km_code_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_metaphors->Visible) { // km_metaphors ?>
    <tr id="r_km_metaphors"<?= $Page->km_metaphors->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_metaphors"><?= $Page->km_metaphors->caption() ?></span></td>
        <td data-name="km_metaphors"<?= $Page->km_metaphors->cellAttributes() ?>>
<span id="el_records_km_metaphors">
<span<?= $Page->km_metaphors->viewAttributes() ?>>
<?= $Page->km_metaphors->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_material_properties->Visible) { // km_material_properties ?>
    <tr id="r_km_material_properties"<?= $Page->km_material_properties->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_material_properties"><?= $Page->km_material_properties->caption() ?></span></td>
        <td data-name="km_material_properties"<?= $Page->km_material_properties->cellAttributes() ?>>
<span id="el_records_km_material_properties">
<span<?= $Page->km_material_properties->viewAttributes() ?>>
<?= $Page->km_material_properties->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->km_instructions->Visible) { // km_instructions ?>
    <tr id="r_km_instructions"<?= $Page->km_instructions->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_records_km_instructions"><?= $Page->km_instructions->caption() ?></span></td>
        <td data-name="km_instructions"<?= $Page->km_instructions->cellAttributes() ?>>
<span id="el_records_km_instructions">
<span<?= $Page->km_instructions->viewAttributes() ?>>
<?= $Page->km_instructions->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("images", explode(",", $Page->getCurrentDetailTable())) && $images->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("images", "TblCaption") ?>&nbsp;<?= str_replace("%c", Container("images")->Count, $Language->phrase("DetailCount")) ?></h4>
<?php } ?>
<?php include_once "ImagesGrid.php" ?>
<?php } ?>
<?php
    if (in_array("documents", explode(",", $Page->getCurrentDetailTable())) && $documents->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("documents", "TblCaption") ?>&nbsp;<?= str_replace("%c", Container("documents")->Count, $Language->phrase("DetailCount")) ?></h4>
<?php } ?>
<?php include_once "DocumentsGrid.php" ?>
<?php } ?>
<?php
    if (in_array("associated_records", explode(",", $Page->getCurrentDetailTable())) && $associated_records->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("associated_records", "TblCaption") ?>&nbsp;<?= str_replace("%c", Container("associated_records")->Count, $Language->phrase("DetailCount")) ?></h4>
<?php } ?>
<?php include_once "AssociatedRecordsGrid.php" ?>
<?php } ?>
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
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
});
</script>
<?php } ?>
