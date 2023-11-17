<?php

    DEFINE('HTTP_TYPE', $_SERVER['HTTP_X_FORWARDED_PROTO']);
    DEFINE('HTTP_ROOT', $_SERVER['HTTP_HOST']);
    DEFINE('HTTP_FOLDER', dirname($_SERVER['PHP_SELF']) . '/');
    DEFINE('BASE_URL', HTTP_TYPE . "://" . HTTP_ROOT . substr(__DIR__, strlen($_SERVER[ 'DOCUMENT_ROOT' ])) . '/');

    if(!session_id()) session_start();

    require "app/plugins/router/Router.php";

    $router = new App\Router\Router();
    try {
        $router->loadPage($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    } catch (ErrorException $e) {
        echo $e;
    }
