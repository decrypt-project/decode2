<?php

namespace PHPMaker2023\decryptweb23;

// Table
$project = Container("project");
?>
<?php if ($project->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_projectmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($project->id->Visible) { // id ?>
        <tr id="r_id"<?= $project->id->rowAttributes() ?>>
            <td class="<?= $project->TableLeftColumnClass ?>"><?= $project->id->caption() ?></td>
            <td<?= $project->id->cellAttributes() ?>>
<span id="el_project_id">
<span<?= $project->id->viewAttributes() ?>>
<?= $project->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($project->name->Visible) { // name ?>
        <tr id="r_name"<?= $project->name->rowAttributes() ?>>
            <td class="<?= $project->TableLeftColumnClass ?>"><?= $project->name->caption() ?></td>
            <td<?= $project->name->cellAttributes() ?>>
<span id="el_project_name">
<span<?= $project->name->viewAttributes() ?>>
<?= $project->name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($project->owner->Visible) { // owner ?>
        <tr id="r_owner"<?= $project->owner->rowAttributes() ?>>
            <td class="<?= $project->TableLeftColumnClass ?>"><?= $project->owner->caption() ?></td>
            <td<?= $project->owner->cellAttributes() ?>>
<span id="el_project_owner">
<span<?= $project->owner->viewAttributes() ?>>
<?= $project->owner->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($project->creation_date->Visible) { // creation_date ?>
        <tr id="r_creation_date"<?= $project->creation_date->rowAttributes() ?>>
            <td class="<?= $project->TableLeftColumnClass ?>"><?= $project->creation_date->caption() ?></td>
            <td<?= $project->creation_date->cellAttributes() ?>>
<span id="el_project_creation_date">
<span<?= $project->creation_date->viewAttributes() ?>>
<?= $project->creation_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($project->last_updated->Visible) { // last_updated ?>
        <tr id="r_last_updated"<?= $project->last_updated->rowAttributes() ?>>
            <td class="<?= $project->TableLeftColumnClass ?>"><?= $project->last_updated->caption() ?></td>
            <td<?= $project->last_updated->cellAttributes() ?>>
<span id="el_project_last_updated">
<span<?= $project->last_updated->viewAttributes() ?>>
<?= $project->last_updated->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($project->locked_by->Visible) { // locked_by ?>
        <tr id="r_locked_by"<?= $project->locked_by->rowAttributes() ?>>
            <td class="<?= $project->TableLeftColumnClass ?>"><?= $project->locked_by->caption() ?></td>
            <td<?= $project->locked_by->cellAttributes() ?>>
<span id="el_project_locked_by">
<span<?= $project->locked_by->viewAttributes() ?>>
<?= $project->locked_by->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($project->lock_date->Visible) { // lock_date ?>
        <tr id="r_lock_date"<?= $project->lock_date->rowAttributes() ?>>
            <td class="<?= $project->TableLeftColumnClass ?>"><?= $project->lock_date->caption() ?></td>
            <td<?= $project->lock_date->cellAttributes() ?>>
<span id="el_project_lock_date">
<span<?= $project->lock_date->viewAttributes() ?>>
<?= $project->lock_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
