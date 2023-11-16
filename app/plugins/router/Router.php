<?php


namespace App\Router {

    use ErrorException;

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    class Router {

        private static ?Router $router = null;
        private static array $controllers = [];

        public function __construct() {
            if (Router::$router === null) {
                Router::$router = $this;
                Router::$controllers = Router::readRoutesConfig()["controllers"];
            }
        }

        private static function readRoutesConfig() {
            $json = file_get_contents('app/config/Routes.json');
            return json_decode($json,true);
        }

        public function loadPage(string $url, string $method = "GET") {
            unset($_SESSION["url"]);
            $_SESSION["url"] = $url;
            $urls = Router::readRoutesConfig()["urls"];
            if (!array_key_exists($url, $urls)) {
                $this->generate_not_found($method);
                return;
            }
            $url_data = $urls[$url];
            if (!array_key_exists($method, $url_data)) {
                $this->bad_gateway($method);
                return;
            }
            $request_data = $url_data[$method];
            if (!array_key_exists($request_data["controller"], Router::$controllers)) {
                $this->generate_controller_not_found($method, $request_data["controller"]);
                return;
            }
            $controller = Router::$controllers[$request_data["controller"]];
            $func = $request_data["function"];
            if ($method === "GET") {
                self::drawPage($controller["namespace"], $request_data["controller"], $func);
            } else {
                self::callMethod($controller["namespace"], $request_data["controller"], $func);
            }
        }

        public static function drawPage($namespace, $className, $methodName) {
            self::callMethod($namespace, $className, $methodName);
            Router::getRouter()->render($className, $methodName);
        }

        private static function callMethod($namespace, $className, $methodName) {
            unset($_SESSION["data"]);
            $path = "app/controller/" . Router::$controllers[$className]["location"];
            if (!key_exists("app/controller/BaseController.php", get_included_files()))
                include "app/controller/BaseController.php";
            if (!key_exists($path, get_included_files()))
                include $path;
            $fullClassName = $namespace . "\\" . $className;
            if (class_exists($fullClassName)) {
                $instance = new $fullClassName();
                if (method_exists($instance, $methodName)) {
                    $instance->$methodName();
                } else {
                    self::generate_class_method_cannot_load_exception($namespace, $className, $methodName);
                }
            } else {
                self::generate_class_method_cannot_load_exception($namespace, $className, $methodName);
            }
        }

        private static function generate_not_found($method) {
            http_response_code(404);
            if ($method === "GET") {
                include "app/view/NotFound.php";
            } else {
                echo "request not found.";
            }
        }

        private static function bad_gateway($method) {
            if ($method === "GET") {
                self::generate_not_found($method);
                return;
            }
            http_response_code(502);
            echo "Unknown method to get access to this url.";
        }

        private static function generate_controller_not_found($method, $controller) {
            http_response_code(500);
            if ($method === "GET") {
                echo "Hey ! Controller $controller not found !";
            } else {
                echo "Controller $controller not found.";
            }
        }

        private static function generate_class_method_cannot_load_exception($namespace, $className, $methodName) {
            $fullClassName = $namespace . "\\" . $className;
            http_response_code(503);
            echo "$fullClassName not found.";
        }

        public function handleForm() {
        }

        public static function getRouter(): ?Router {
            return Router::$router;
        }

        public function render($className, $methodName, $rendering = false) {
            unset($_SESSION["router"]);
            $urls = $this->readRoutesConfig()["urls"];
            $path = "app/view/$className/$methodName.php";
            $_SESSION["router"] = [
                "title" => $methodName,
                "icon" => "app/resources/images/AppMaker.ico",
                "lang" => "en"
            ];
            if (isset($_SESSION["title"])) {
                $_SESSION["router"]["title"] = $_SESSION["title"];
            }
            if (isset($_SESSION["lang"])) {
                $_SESSION["router"]["lang"] = $_SESSION["lang"];
            }
            if (isset($_SESSION["icon"])) {
                $_SESSION["router"]["icon"] = $_SESSION["icon"];
            }
            foreach ($urls as $url => $data) {
                if (!key_exists("GET", $data))
                    continue;
                if ($data["GET"]["controller"] !== $className)
                    continue;
                if ($data["GET"]["function"] !== $methodName)
                    continue;
                unset($_SESSION["layout_content"]);
                if (isset($_SESSION["layouts"])) {
                    $_SESSION["router"]["layout_content"] = $path;
                    $layout_path = "app/view/layouts/" . $_SESSION["layouts"] . ".php";
                    if (file_exists("app/resources/style/layouts/" . $_SESSION["layouts"] . ".css")) {
                        $_SESSION["router"]["layout_css"] = "app/resources/style/layouts/" . $_SESSION["layouts"] . ".css";
                    }
                    if (file_exists("app/resources/script/layouts/" . $_SESSION["layouts"] . ".css")) {
                        $_SESSION["router"]["layout_js"] = "app/resources/script/layouts/" . $_SESSION["layouts"] . ".css";
                    }
                    $_SESSION["router"]["layout_path"] = $layout_path;
                    $_ROUTER = $_SESSION["router"];
                    include "app/main.php";
                } else {
                    $_SESSION["router"]["layout_content"] = $path;
                    $_SESSION["router"]["layout_path"] = "app/view/layouts/based/basedLayout.php";
                    if (file_exists("app/resources/style/layouts/basedLayout.css")) {
                        $_SESSION["router"]["layout_css"] = "app/resources/style/layouts/basedLayout.css";
                    }
                    if (file_exists("app/resources/script/layouts/basedLayout.js")) {
                        $_SESSION["router"]["layout_js"] = "app/resources/script/layouts/basedLayout.js";
                    }
                    $_ROUTER = $_SESSION["router"];
                    include "app/main.php";
                }
                unset($_SESSION["layouts"]);
                unset($_SESSION["data"]);
                return;
            }
            $_SESSION["url"] = $methodName;
            Router::generate_not_found("GET");
        }

        public function notFoundPage($what_not_found) {
            $_SESSION["url"] = $what_not_found;
            Router::generate_not_found("GET");
        }
    }
}
