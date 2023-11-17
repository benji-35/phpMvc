<?php
    $main_js = \App\Router\Router::getGoodUrl("app/main.js");
    $main_css = \App\Router\Router::getGoodUrl("app/main.css");
?>
<html lang="<?= $_ROUTER['lang'] ?>">
<head>
    <title><?= $_ROUTER['title'] ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $_ROUTER['icon'] ?>">
    <script src="<?= $main_js ?>"></script>
    <link rel="stylesheet" href="<?= $main_css ?>">
    <?php
    if (isset($_ROUTER["layout_css"])) {
        ?>
        <link rel="stylesheet" href="<?= $_ROUTER["layout_css"] ?>">
        <?php
    }
    ?>
    <?php
    if (isset($_ROUTER["layout_js"])) {
        ?>
        <script src="<?= $_ROUTER["layout_js"] ?>"></script>
        <?php
    }
    ?>
    <?php
    if (isset($_ROUTER["page_css"])) {
        ?>
        <link rel="stylesheet" href="<?= $_ROUTER["page_css"] ?>">
        <?php
    }
    ?>
    <?php
    if (isset($_ROUTER["page_js"])) {
        ?>
        <script src="<?= $_ROUTER["page_js"] ?>"></script>
        <?php
    }
    ?>
</head>
<body>
    <?php include $_ROUTER["layout_path"] ?>
</body>

