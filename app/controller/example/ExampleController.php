<?php

namespace App\Controller;

class ExampleController extends BaseController {

    function index() {
        $_SESSION["data"]["variable1"] = "variable1 value";
    }

}
