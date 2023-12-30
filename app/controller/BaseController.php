<?php

namespace App\Controller;

use App\Router\Router;

class BaseController {
    public final static function clearLayout() {
        $_SESSION["layouts"] = null;
    }

    public final function setLayout($layout_name) {
        $_SESSION["layouts"] = "$layout_name";
    }

    public final function render($methodName) {
        $parts = explode("\\", get_class($this));
        $className = $parts[count($parts) - 1];
        Router::getRouter()->render($className, $methodName, true);
    }

    public final function redirect($methodName, $permanent = false) {
        $parts = explode("\\", get_class($this));
        $className = $parts[count($parts) - 1];
        Router::getRouter()->redirect($className, $methodName, $permanent);
    }

    public final function redirectTo($className, $methodName, $permanent = false) {
        Router::getRouter()->redirect($className, $methodName, $permanent);
    }
}
