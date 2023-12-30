<?php

namespace App\Controller;

use App\Model\user\UserModel;
use App\RouterApp;

class ExampleController extends BaseController {

    function index() {
        \App\RouterApp::$DATA["variable1"] = "variable1 value";
    }

    function createUser() {
        $data = RouterApp::$DATA;
        if ($data === null || $data["post"] === null || count($data["post"]) != 3) {
            RouterApp::$DATA["error"] = "Problem of data";
            $this->render("index");
            return;
        }
        error_log("USER CREATED !");
        $this->redirectTo("ExampleController", "index");
    }
}
