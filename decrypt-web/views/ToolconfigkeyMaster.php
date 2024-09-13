<?php

namespace PHPMaker2023\decryptweb23;

// Table
$toolconfigkey = Container("toolconfigkey");
?>
<?php if ($toolconfigkey->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_toolconfigkeymaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($toolconfigkey->tool_id->Visible) { // tool_id ?>
        <tr id="r_tool_id"<?= $toolconfigkey->tool_id->rowAttributes() ?>>
            <td class="<?= $toolconfigkey->TableLeftColumnClass ?>"><?= $toolconfigkey->tool_id->caption() ?></td>
            <td<?= $toolconfigkey->tool_id->cellAttributes() ?>>
<span id="el_toolconfigkey_tool_id">
<span<?= $toolconfigkey->tool_id->viewAttributes() ?>>
<?= $toolconfigkey->tool_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($toolconfigkey->toolkey->Visible) { // toolkey ?>
        <tr id="r_toolkey"<?= $toolconfigkey->toolkey->rowAttributes() ?>>
            <td class="<?= $toolconfigkey->TableLeftColumnClass ?>"><?= $toolconfigkey->toolkey->caption() ?></td>
            <td<?= $toolconfigkey->toolkey->cellAttributes() ?>>
<span id="el_toolconfigkey_toolkey">
<span<?= $toolconfigkey->toolkey->viewAttributes() ?>>
<?= $toolconfigkey->toolkey->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
