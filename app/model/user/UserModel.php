<?php

namespace App\Model\user;

use App\Model\BasedModel;

class UserModel extends BasedModel {
    public string $name;
    public string $email;
    public string $password;

    public function __construct($name = "", $email = "", $password = "") {
        parent::__construct();
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function serialized(): array {
        return [
            "name" => $this->name,
            "email" => $this->email,
            "password" => $this->password,
        ];
    }

    public function deserialized(array $data): UserModel {
        return new UserModel($data["name"], $data["email"], $data["password"]);
    }

}
