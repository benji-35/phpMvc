<?php

namespace App\Controller;

use App\Router\Router;

class BaseController {
    public static function clearLayout() {
        $_SESSION["layouts"] = null;
    }

    public function setLayout($layout_name) {
        $_SESSION["layouts"] = "$layout_name";
    }

    public function render($methodName) {
        $parts = explode("\\", get_class($this));
        $className = $parts[count($parts) - 1];
        Router::getRouter()->render($className, $methodName, true);
    }
}
