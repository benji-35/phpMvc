<?php
    if (!isset($_POST) || count($_POST) <= 0) {
        \App\Router\Router::getRouter()->notFoundPage("Posted");
        return;
    }
?>

<div>
    <?= $_POST["test"] ?>
</div>
