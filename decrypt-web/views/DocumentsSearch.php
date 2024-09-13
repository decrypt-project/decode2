<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$DocumentsSearch = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { documents: currentTable } });
var currentPageID = ew.PAGE_ID = "search";
var currentForm;
var fdocumentssearch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fdocumentssearch")
        .setPageId("search")
<?php if ($Page->IsModal && $Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Add fields
        .addFields([
            ["_title", [], fields._title.isInvalid],
            ["y__title", [ew.Validators.between], false],
            ["category", [], fields.category.isInvalid]
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
            "category": <?= $Page->category->toClientList($Page) ?>,
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
    // Client script
    document.title += " R" + document.location.href.replace(/.*[^0-9]([0-9]+)[^0-9]*$/,"$1");
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fdocumentssearch" id="fdocumentssearch" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="documents">
<input type="hidden" name="action" id="action" value="search">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div class="ew-search-div"><!-- page* -->
<?php if ($Page->_title->Visible) { // title ?>
    <div id="r__title" class="row"<?= $Page->_title->rowAttributes() ?>>
        <label for="x__title" class="<?= $Page->LeftColumnClass ?>"><span id="elh_documents__title"><?= $Page->_title->caption() ?></span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->_title->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                    <span class="ew-search-operator">
<select name="z__title" id="z__title" class="form-select ew-operator-select" data-ew-action="search-operator">
<?php foreach ($Page->_title->SearchOperators as $opr) { ?>
<option value="<?= HtmlEncode($opr) ?>"<?= $Page->_title->AdvancedSearch->SearchOperator == $opr ? " selected" : "" ?>><?= $Language->phrase($opr == "=" ? "EQUAL" : $opr) ?></option>
<?php } ?>
</select>
</span>
                <span id="el_documents__title" class="ew-search-field">
<input type="<?= $Page->_title->getInputTextType() ?>" name="x__title" id="x__title" data-table="documents" data-field="x__title" value="<?= $Page->_title->EditValue ?>" maxlength="256" placeholder="<?= HtmlEncode($Page->_title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_title->formatPattern()) ?>"<?= $Page->_title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_title->getErrorMessage(false) ?></div>
</span>
                    <span class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></span>
                    <span id="el2_documents__title" class="ew-search-field2 d-none">
<input type="<?= $Page->_title->getInputTextType() ?>" name="y__title" id="y__title" data-table="documents" data-field="x__title" value="<?= $Page->_title->EditValue2 ?>" maxlength="256" placeholder="<?= HtmlEncode($Page->_title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_title->formatPattern()) ?>"<?= $Page->_title->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_title->getErrorMessage(false) ?></div>
</span>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($Page->category->Visible) { // category ?>
    <div id="r_category" class="row"<?= $Page->category->rowAttributes() ?>>
        <label class="<?= $Page->LeftColumnClass ?>"><span id="elh_documents_category"><?= $Page->category->caption() ?></span>
        <span class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_category" id="z_category" value="=">
</span>
        </label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div<?= $Page->category->cellAttributes() ?>>
                <div class="d-flex align-items-start">
                <span id="el_documents_category" class="ew-search-field ew-search-field-single">
    <select
        id="x_category"
        name="x_category"
        class="form-select ew-select<?= $Page->category->isInvalidClass() ?>"
        <?php if (!$Page->category->IsNativeSelect) { ?>
        data-select2-id="fdocumentssearch_x_category"
        <?php } ?>
        data-table="documents"
        data-field="x_category"
        data-dropdown
        data-value-separator="<?= $Page->category->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->category->getPlaceHolder()) ?>"
        <?= $Page->category->editAttributes() ?>>
        <?= $Page->category->selectOptionListHtml("x_category") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->category->getErrorMessage(false) ?></div>
<?php if (!$Page->category->IsNativeSelect) { ?>
<script>
loadjs.ready("fdocumentssearch", function() {
    var options = { name: "x_category", selectId: "fdocumentssearch_x_category" },
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
    if (fdocumentssearch.lists.category?.lookupOptions.length) {
        options.data = { id: "x_category", form: "fdocumentssearch" };
    } else {
        options.ajax = { id: "x_category", form: "fdocumentssearch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.documents.fields.category.selectOptions);
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
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
        <button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fdocumentssearch"><?= $Language->phrase("Search") ?></button>
        <?php if ($Page->IsModal) { ?>
        <button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fdocumentssearch"><?= $Language->phrase("Cancel") ?></button>
        <?php } else { ?>
        <button class="btn btn-default ew-btn" name="btn-reset" id="btn-reset" type="button" form="fdocumentssearch" data-ew-action="reload"><?= $Language->phrase("Reset") ?></button>
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
    ew.addEventHandlers("documents");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
