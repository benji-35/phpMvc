<?php

namespace App\Controller;

use App\Model\user\UserModel;

class ExampleController extends BaseController {

    function index() {
        if (!isset($_SESSION["token"])) {
            $this->redirect("login");
            return;
        }
        \App\RouterApp::$DATA["variable1"] = "variable1 value";
    }

    function login() {
        $this->setLayout("authentication/AuthenticationLayout");
        \App\RouterApp::$DATA["title"] = "Auth - Login";
    }

    function register() {
        $this->setLayout("authentication/AuthenticationLayout");
        \App\RouterApp::$DATA["title"] = "Auth - Register";
    }

    function createUser() {
        $data = \App\RouterApp::$DATA;
        if ($data === null || $data["post"] === null || count($data["post"]) != 3) {
            \App\RouterApp::$DATA["error"] = "Missing Credentials";
            $this->render("index");
            return;
        }
        error_log("USER CREATED !");
        $this->redirectTo("ExampleController", "login");
    }

    function loginUser() {
        $data = \App\RouterApp::$DATA;
        if ($data === null || $data["post"] === null || count($data["post"]) != 2) {
            \App\RouterApp::$DATA["error"] = "Missing Credentials";
            $this->render("index");
            return;
        }
        $_SESSION["token"] = "my token";
        $this->redirectTo("ExampleController", "index");
    }

    function logout() {
        unset($_SESSION["token"]);
        $this->redirectTo("ExampleController", "login");
    }
}
