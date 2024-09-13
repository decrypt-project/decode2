<?php

namespace PHPMaker2023\decryptweb23;

// Table
$images = Container("images");
?>
<?php if ($images->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_imagesmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($images->id->Visible) { // id ?>
        <tr id="r_id"<?= $images->id->rowAttributes() ?>>
            <td class="<?= $images->TableLeftColumnClass ?>"><?= $images->id->caption() ?></td>
            <td<?= $images->id->cellAttributes() ?>>
<span id="el_images_id">
<span<?= $images->id->viewAttributes() ?>>
<?= $images->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($images->name->Visible) { // name ?>
        <tr id="r_name"<?= $images->name->rowAttributes() ?>>
            <td class="<?= $images->TableLeftColumnClass ?>"><?= $images->name->caption() ?></td>
            <td<?= $images->name->cellAttributes() ?>>
<span id="el_images_name">
<span<?= $images->name->viewAttributes() ?>>
<?= $images->name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($images->path->Visible) { // path ?>
        <tr id="r_path"<?= $images->path->rowAttributes() ?>>
            <td class="<?= $images->TableLeftColumnClass ?>"><?= $images->path->caption() ?></td>
            <td<?= $images->path->cellAttributes() ?>>
<span id="el_images_path">
<span<?= $images->path->viewAttributes() ?>><?php
 echo '<a href="';
  echo '/decrypt-custom/zoom.php?file='
 . CurrentPage()->path->CurrentValue;
 echo '">';
 echo '<img src="';
 echo '/decrypt-custom/filesrv/?file=TH_'
 . CurrentPage()->path->CurrentValue;
 echo '"/>';
 echo '</a>';
?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($images->record_id->Visible) { // record_id ?>
        <tr id="r_record_id"<?= $images->record_id->rowAttributes() ?>>
            <td class="<?= $images->TableLeftColumnClass ?>"><?= $images->record_id->caption() ?></td>
            <td<?= $images->record_id->cellAttributes() ?>>
<span id="el_images_record_id">
<span<?= $images->record_id->viewAttributes() ?>>
<?= $images->record_id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($images->last_modified->Visible) { // last_modified ?>
        <tr id="r_last_modified"<?= $images->last_modified->rowAttributes() ?>>
            <td class="<?= $images->TableLeftColumnClass ?>"><?= $images->last_modified->caption() ?></td>
            <td<?= $images->last_modified->cellAttributes() ?>>
<span id="el_images_last_modified">
<span<?= $images->last_modified->viewAttributes() ?>>
<?= $images->last_modified->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($images->processed->Visible) { // processed ?>
        <tr id="r_processed"<?= $images->processed->rowAttributes() ?>>
            <td class="<?= $images->TableLeftColumnClass ?>"><?= $images->processed->caption() ?></td>
            <td<?= $images->processed->cellAttributes() ?>>
<span id="el_images_processed">
<span<?= $images->processed->viewAttributes() ?>>
<?= $images->processed->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
