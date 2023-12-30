<?php
    highlight_string("<?php\n\$data =\n" . var_export(\App\RouterApp::$DATA, true) . ";\n?>");
?>

<div>
    <?= \App\RouterApp::$DATA["variable1"] ?>
</div>
