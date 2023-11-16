<?php
    $main_js = "app/main.js";
    $main_css = "app/main.css";
?>
<html lang="<?= $_ROUTER['lang'] ?>">
<head>
    <title><?= $_ROUTER['title'] ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $_ROUTER['icon'] ?>">
    <script src="<?= $main_js ?>"></script>
    <link rel="stylesheet" href="<?= $main_css ?>">
    <link href="app/resources/style/bootstrap-5.0.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="app/resources/style/bootstrap-5.0.2/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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

