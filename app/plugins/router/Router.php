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
            $json = file_get_contents( 'app/config/Routes.json');
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
                $_SESSION["router"]['lang'] = "en";
                $_SESSION["router"]['title'] = "NotFound";
                $_SESSION["router"]['icon'] = self::getGoodUrl("app/resources/images/AppMaker.ico");
                $_SESSION["router"]["layout_path"] = "app/view/layouts/based/basedLayout.php";
                $data = self::readRoutesConfig();
                $layout_read = "";
                if (key_exists("not_found", $data) && key_exists("layout", $data["not_found"])) {
                    $layout_read = $data["not_found"]["layout"];
                }
                if ($layout_read !== "") {
                    $_SESSION["router"]["layout_path"] = "app/view/layouts/$layout_read.php";
                    if (file_exists("app/resources/style/layouts/" . $layout_read . ".css")) {
                        $_SESSION["router"]["layout_css"] =  self::getGoodUrl("app/resources/style/layouts/" . $layout_read . ".css");
                    }
                    if (file_exists("app/resources/script/layouts/" . $layout_read . ".js")) {
                        $_SESSION["router"]["layout_js"] = self::getGoodUrl("app/resources/script/layouts/" . $layout_read . ".js");
                    }
                } else {
                    if (file_exists("app/resources/style/layouts/basedLayout.css")) {
                        $_SESSION["router"]["layout_css"] = self::getGoodUrl("app/resources/style/layouts/basedLayout.css");
                    }
                    if (file_exists("app/resources/script/layouts/basedLayout.js")) {
                        $_SESSION["router"]["layout_js"] = self::getGoodUrl("app/resources/script/layouts/basedLayout.js");
                    }
                }
                $_SESSION["router"]["layout_content"] = "app/view/NotFound.php";
                $_ROUTER = $_SESSION["router"];
                include "app/main.php";
            } else {
                echo "request not found.";
            }
        }

        private static function bad_gateway($method) {
            if ($method === "GET") {
                self::generate_not_found($method);
            } else {
                http_response_code(502);
                echo "Unknown method to get access to this url.";
            }
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
            if (!file_exists($path)) {
                $path = "app/view/NotFound.php";
            }
            $_SESSION["router"] = [
                "title" => $methodName,
                "icon" => self::getGoodUrl("app/resources/images/AppMaker.ico"),
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
                    $layout_css = self::getGoodUrl("app/resources/style/layouts/" . $_SESSION["layouts"] . ".css");
                    $layout_js = self::getGoodUrl("app/resources/script/layouts/" . $_SESSION["layouts"] . ".js");
                    if (file_exists($layout_css)) {
                        $_SESSION["router"]["layout_css"] = $layout_css;
                    }
                    if (file_exists($layout_js)) {
                        $_SESSION["router"]["layout_js"] = $layout_js;
                    }
                    $_SESSION["router"]["layout_path"] = $layout_path;
                    $_ROUTER = $_SESSION["router"];
                    include "app/main.php";
                } else {
                    $_SESSION["router"]["layout_content"] = $path;
                    $based_css = self::getGoodUrl("app/resources/style/layouts/basedLayout.css");
                    $based_js = self::getGoodUrl("app/resources/script/layouts/basedLayout.js");
                    $based_layout = "app/view/layouts/based/basedLayout.php";
                    $_SESSION["router"]["layout_path"] = $based_layout;
                    if (file_exists($based_css)) {
                        $_SESSION["router"]["layout_css"] = $based_css;
                    }
                    if (file_exists($based_js)) {
                        $_SESSION["router"]["layout_js"] = $based_js;
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

        public function redirect($className, $methodName, $permanent = false) {
            $urls = Router::readRoutesConfig()["urls"];
            foreach ($urls as $url => $methods) {
                foreach ($methods as $urlMethod => $data) {
                    if ($data["controller"] !== $className || $data["function"] !== $methodName)
                        continue;
                    if ($urlMethod === "GET") {
                        header('Location: ' . $url, true, $permanent ? 301 : 302);
                        exit();
                    } else {
                        Router::generate_not_found("[$urlMethod] $className::$methodName");
                    }
                }
            }
            Router::generate_not_found("$className::$methodName");
        }

        public function notFoundPage($what_not_found) {
            $_SESSION["url"] = $what_not_found;
            Router::generate_not_found("GET");
        }

        public static function getGoodUrl($rootPath) {
            $url = $_SERVER["REQUEST_URI"];
            $path = explode("/", $url);
            $start_at = 2;
            for ($i = $start_at;$i < count($path); $i++) {
                $rootPath = "../$rootPath";
            }
            return $rootPath;
        }
    }
}
