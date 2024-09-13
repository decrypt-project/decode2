<?php

namespace PHPMaker2023\decryptweb23;

// Table
$tool = Container("tool");
?>
<?php if ($tool->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_toolmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($tool->name->Visible) { // name ?>
        <tr id="r_name"<?= $tool->name->rowAttributes() ?>>
            <td class="<?= $tool->TableLeftColumnClass ?>"><?= $tool->name->caption() ?></td>
            <td<?= $tool->name->cellAttributes() ?>>
<span id="el_tool_name">
<span<?= $tool->name->viewAttributes() ?>>
<?= $tool->name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
