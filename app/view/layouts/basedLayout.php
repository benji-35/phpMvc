<?php
    $title = "KapHpMvc";
    if (key_exists("title", \App\RouterApp::$DATA)) {
        $title = \App\RouterApp::$DATA["title"];
    }
    $lang = "en";
    if (key_exists("lang", \App\RouterApp::$DATA)) {
        $lang = \App\RouterApp::$DATA["lang"];
    }
    $main_js = "app/main.js";
    $main_css = "app/main.css";
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
    <head>
        <title><?= $title ?></title>
        <link rel="icon" type="image/x-icon" href="<?= \App\RouterApp::$DATA['icon'] ?>">
        <script src="<?= $main_js ?>"></script>
        <link rel="stylesheet" href="<?= $main_css ?>">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    </head>
    <body>
        <?php include \App\RouterApp::$DATA["layout_content"]; ?>
    </body>
</html>

