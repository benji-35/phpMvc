<?php
    $_ROUTER = $_SESSION["router"];
    if (key_exists("data", $_SESSION)) {
        $data = $_SESSION["data"];
        if (key_exists("title", $data)) {
            $title = $data["title"];
        }
        if (key_exists("lang", $data)) {
            $lang = $data["lang"];
        }
    }
    $main_js = "app/main.js";
    $main_css = "app/main.css";
?>
<!DOCTYPE html>
<html lang="<?= $_ROUTER['lang'] ?>">
    <head>
        <title><?= $_ROUTER['title'] ?></title>
        <link rel="icon" type="image/x-icon" href="<?= $_ROUTER['icon'] ?>">
        <script src="<?= $main_js ?>"></script>
        <link rel="stylesheet" href="<?= $main_css ?>">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    </head>
    <body>
        <?php include $_ROUTER["layout_content"]; ?>
    </body>
</html>

