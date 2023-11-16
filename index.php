<?php
    if(!session_id()) session_start();

    require "app/plugins/router/Router.php";

    $router = new App\Router\Router();
    try {
        $router->loadPage($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    } catch (ErrorException $e) {
        echo $e;
    }
