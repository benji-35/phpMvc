<?php

namespace App\Controller;

class ExampleController extends BaseController {
    public function view1() {
        $this->setLayout("NavBar");
    }
    public function view2() {
        $this->setLayout("NavBar");
    }
    public function postMethod() {
        $this->setLayout("NavBar");
        $_SESSION["data"] = [
            "title" => "ViewPosted"
        ];
        $this->render("viewPosted");
    }
    public function viewPosted() {
        $this->setLayout("NavBar");
        $_SESSION["data"] = [
            "title" => "ViewPosted"
        ];
    }

    public function receivedPosted() {

    }
}
