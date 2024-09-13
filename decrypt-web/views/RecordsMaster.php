<?php

namespace PHPMaker2023\decryptweb23;

// Table
$records = Container("records");
?>
<?php if ($records->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_recordsmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($records->id->Visible) { // id ?>
        <tr id="r_id"<?= $records->id->rowAttributes() ?>>
            <td class="<?= $records->TableLeftColumnClass ?>"><?= $records->id->caption() ?></td>
            <td<?= $records->id->cellAttributes() ?>>
<span id="el_records_id">
<span<?= $records->id->viewAttributes() ?>>
<?= $records->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($records->c_holder->Visible) { // c_holder ?>
        <tr id="r_c_holder"<?= $records->c_holder->rowAttributes() ?>>
            <td class="<?= $records->TableLeftColumnClass ?>"><?= $records->c_holder->caption() ?></td>
            <td<?= $records->c_holder->cellAttributes() ?>>
<span id="el_records_c_holder">
<span<?= $records->c_holder->viewAttributes() ?>>
<?= $records->c_holder->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($records->c_cates->Visible) { // c_cates ?>
        <tr id="r_c_cates"<?= $records->c_cates->rowAttributes() ?>>
            <td class="<?= $records->TableLeftColumnClass ?>"><?= $records->c_cates->caption() ?></td>
            <td<?= $records->c_cates->cellAttributes() ?>>
<span id="el_records_c_cates">
<span<?= $records->c_cates->viewAttributes() ?>>
<?= $records->c_cates->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($records->c_author->Visible) { // c_author ?>
        <tr id="r_c_author"<?= $records->c_author->rowAttributes() ?>>
            <td class="<?= $records->TableLeftColumnClass ?>"><?= $records->c_author->caption() ?></td>
            <td<?= $records->c_author->cellAttributes() ?>>
<span id="el_records_c_author">
<span<?= $records->c_author->viewAttributes() ?>>
<?= $records->c_author->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($records->c_lang->Visible) { // c_lang ?>
        <tr id="r_c_lang"<?= $records->c_lang->rowAttributes() ?>>
            <td class="<?= $records->TableLeftColumnClass ?>"><?= $records->c_lang->caption() ?></td>
            <td<?= $records->c_lang->cellAttributes() ?>>
<span id="el_records_c_lang">
<span<?= $records->c_lang->viewAttributes() ?>>
<?= $records->c_lang->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($records->record_type->Visible) { // record_type ?>
        <tr id="r_record_type"<?= $records->record_type->rowAttributes() ?>>
            <td class="<?= $records->TableLeftColumnClass ?>"><?= $records->record_type->caption() ?></td>
            <td<?= $records->record_type->cellAttributes() ?>>
<span id="el_records_record_type">
<span<?= $records->record_type->viewAttributes() ?>>
<?= $records->record_type->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($records->status->Visible) { // status ?>
        <tr id="r_status"<?= $records->status->rowAttributes() ?>>
            <td class="<?= $records->TableLeftColumnClass ?>"><?= $records->status->caption() ?></td>
            <td<?= $records->status->cellAttributes() ?>>
<span id="el_records_status">
<span<?= $records->status->viewAttributes() ?>>
<?= $records->status->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($records->number_of_pages->Visible) { // number_of_pages ?>
        <tr id="r_number_of_pages"<?= $records->number_of_pages->rowAttributes() ?>>
            <td class="<?= $records->TableLeftColumnClass ?>"><?= $records->number_of_pages->caption() ?></td>
            <td<?= $records->number_of_pages->cellAttributes() ?>>
<span id="el_records_number_of_pages">
<span<?= $records->number_of_pages->viewAttributes() ?>>
<?= $records->number_of_pages->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
