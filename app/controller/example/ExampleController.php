<?php

namespace App\Controller;

class ExampleController extends BaseController {

    function index() {
        \App\RouterApp::$DATA["variable1"] = "variable1 value";
    }

}
