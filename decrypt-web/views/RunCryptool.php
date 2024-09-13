<?php

namespace PHPMaker2023\decryptweb23;

// Page object
$RunCryptool = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

$nms = __NAMESPACE__;
include "../decrypt-custom/ct_tool.php";

?>

<?= GetDebugMessage() ?>
