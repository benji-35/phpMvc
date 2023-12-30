<?php

namespace App\Router {

    use App\RouterApp;

    class Router {

        private static ?Router $router = null;
        private static array $controllers = [];

        public function __construct() {
            if (Router::$router === null) {
                Router::$router = $this;
                Router::$controllers = Router::readRoutesConfig()["controllers"];
            }
        }

        private static function str_starts_with($haystack, $needle):bool {
            $needed_size = strlen($needle);
            if (strlen($haystack) < $needed_size)
                return false;
            $first = substr($haystack, 0, $needed_size);
            return $first === $needle;
        }

        public static function readRoutesConfig() {
            $json = file_get_contents( 'app/config/Routes.json');
            return json_decode($json,true);
        }

        private static function getRoute($url): ?array {
            $urls = self::readRoutesConfig()["urls"];
            $url_parts = explode("?", $url);
            $url_part = $url_parts[0];
            $url_word = explode("/", $url_part);
            foreach ($urls as $index_url => $_url) {
                $_url_word = explode("/", $index_url);
                if (count($_url_word) !== count($url_word))
                    continue;
                $good = true;
                foreach ($_url_word as $index => $word) {
                    if (self::str_starts_with($word, ":")) {
                        $key = substr($word, 1, strlen($word));
                        $value = $url_word[$index];
                        RouterApp::$DATA[$key] = $value;
                        continue;
                    }
                    if ($word !== $url_word[$index]) {
                        $good = false;
                        break;
                    }
                }
                if ($good) {
                    return $_url;
                }
            }
            return null;
        }

        public static function getUrlFromClassMethod($className, $methodName, $params = []):?string {
            $urls = Router::readRoutesConfig()["urls"];
            foreach ($urls as $url => $methods) {
                foreach ($methods as $method) {
                    $controllerGot = $method["controller"];
                    $functionGot = $method["function"];
                    if ($controllerGot === $className && $functionGot === $methodName) {
                        if (count($params) > 0) {
                            $content = explode("/", $url);
                            $result = "";
                            if (Router::str_starts_with($url, "/")) {
                                $result = "/";
                            }
                            foreach ($content as $item) {
                                if (self::str_starts_with($item, ":")) {
                                    $itemKey = substr($item, 1, strlen($item));
                                    if (array_key_exists($itemKey, $params)) {
                                        $result = $result . $params[$itemKey] . "/";
                                    } else {
                                        $result = $result . "NULL/";
                                    }
                                } else {
                                    $result = $result . "$item/";
                                }
                            }
                            $url = $result;
                        }
                        return $url;
                    }
                }
            }
            return null;
        }

        public function loadPage(string $url, string $method = "GET") {
            $urls = Router::readRoutesConfig()["urls"];
            RouterApp::$DATA = [];
            RouterApp::$DATA["url"] = "$url";
            RouterApp::$DATA["method"] = "$method";
            RouterApp::$DATA["params"] = $_GET;
            if (strtoupper($method) === "POST") {
                RouterApp::$DATA["post"] = $_POST;
            }
            $url_data = self::getRoute($url);
            if ($url_data === null) {
                $this->generate_not_found($method);
                return;
            }
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

        private static function generate_not_found($method, $response_code = 404) {
            http_response_code($response_code);
            if ($method === "GET") {
                RouterApp::$DATA['lang'] = "en";
                RouterApp::$DATA['title'] = "NotFound";
                RouterApp::$DATA['icon'] = self::getGoodUrl("app/resources/images/AppMaker.ico");
                RouterApp::$DATA["layout_path"] = "app/view/layouts/based/basedLayout.php";
                $data = self::readRoutesConfig();
                $layout_read = "";
                if (key_exists("not_found", $data) && key_exists("layout", $data["not_found"])) {
                    $layout_read = $data["not_found"]["layout"];
                }
                if ($layout_read !== "") {
                    RouterApp::$DATA["layout_path"] = "app/view/layouts/$layout_read.php";
                    if (file_exists("app/resources/style/layouts/" . $layout_read . ".css")) {
                        RouterApp::$DATA["layout_css"] =  self::getGoodUrl("app/resources/style/layouts/" . $layout_read . ".css");
                    }
                    if (file_exists("app/resources/script/layouts/" . $layout_read . ".js")) {
                        RouterApp::$DATA["layout_js"] = self::getGoodUrl("app/resources/script/layouts/" . $layout_read . ".js");
                    }
                } else {
                    if (file_exists("app/resources/style/layouts/basedLayout.css")) {
                        RouterApp::$DATA["layout_css"] = self::getGoodUrl("app/resources/style/layouts/basedLayout.css");
                    }
                    if (file_exists("app/resources/script/layouts/basedLayout.js")) {
                        RouterApp::$DATA["layout_js"] = self::getGoodUrl("app/resources/script/layouts/basedLayout.js");
                    }
                }
                RouterApp::$DATA["layout_content"] = "app/view/NotFound.php";
                $_ROUTER = RouterApp::$DATA;
                include "app/main.php";
            } else {
                echo "request not found.";
            }
        }

        private static function bad_gateway($method) {
            if ($method === "GET") {
                self::generate_not_found($method, 502);
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
            $urls = $this->readRoutesConfig()["urls"];
            $path = "app/view/$className/$methodName.php";
            if (!file_exists($path)) {
                $path = "app/view/NotFound.php";
            }
            if (!isset(\App\RouterApp::$DATA["title"]))
                \App\RouterApp::$DATA["title"] = $methodName;
            if (!isset(\App\RouterApp::$DATA["lang"]))
                \App\RouterApp::$DATA["lang"] = "en";
            if (!isset(\App\RouterApp::$DATA["icon"]))
                \App\RouterApp::$DATA["icon"] = self::getGoodUrl("app/resources/images/AppMaker.ico");
            foreach ($urls as $url => $data) {
                if (!key_exists("GET", $data))
                    continue;
                if ($data["GET"]["controller"] !== $className)
                    continue;
                if ($data["GET"]["function"] !== $methodName)
                    continue;
                unset($_SESSION["layout_content"]);
                $layout = "basedLayout";
                if (isset($_SESSION["layouts"])) {
                    $layout = $_SESSION["layouts"];
                    unset($_SESSION["layouts"]);
                }
                RouterApp::$DATA["layout_content"] = $path;
                $layout_path = "app/view/layouts/" . $layout . ".php";
                $layout_css = self::getGoodUrl("app/resources/style/layouts/" . $layout . ".css");
                $layout_js = self::getGoodUrl("app/resources/script/layouts/" . $layout . ".js");
                if (file_exists($layout_css)) {
                    RouterApp::$DATA["layout_css"] = $layout_css;
                }
                if (file_exists($layout_js)) {
                    RouterApp::$DATA["layout_js"] = $layout_js;
                }
                RouterApp::$DATA["layout_path"] = $layout_path;
                $_ROUTER = RouterApp::$DATA;
                include "app/main.php";
                return;
            }
            $_SESSION["url"] = $methodName;
            Router::generate_not_found("GET");
        }

        public function redirect($className, $methodName, $permanent = false, $params = []) {
            $url = Router::getUrlFromClassMethod($className, $methodName, $params);
            if ($url === null) {
                Router::generate_not_found("[GET] $className::$methodName");
                exit();
            } else {
                header('Location: ' . $url, true, $permanent ? 301 : 302);
                exit();
            }
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

namespace App {
    class RouterApp {
        static array $DATA;

        public static function LINK_TO($url, $text = "Link to", $inNewTab = false): void {
            $blank = "";
            if ($inNewTab) {
                $blank = " target='_blank'";
            }
            echo "<a href='$url'$blank>$text</a>";
        }

        public static function LINK_TO_METHOD($class, $method, $text = "Link to", $inNewTab = false, $params = []): void
        {
            $url = \App\Router\Router::getUrlFromClassMethod($class, $method, $params);
            if ($url === null) {
                $url = "#";
                $text = "[NOT FOUND] $text";
            }
            self::LINK_TO($url, $text, $inNewTab);
        }
    }
}
