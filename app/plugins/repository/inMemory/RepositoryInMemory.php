<?php

namespace App\repository\inMemory {

    use App\repository\Repository;

    class RepositoryInMemory extends Repository {
        private array $tables = [];

        public function __construct() {
            parent::__construct();
        }

        private function modelIdExists(string $id, string $modelName): bool {
            if (!array_key_exists($modelName, $this->tables))
                return false;
            return array_key_exists($id, $this->tables[$modelName]);
        }

        public function create(array $serializedData, string $modelName) {
            if (!isset($serializedData["id"]) || $this->modelIdExists($serializedData["id"], $modelName))
                return;
            parent::create($serializedData, $modelName);
            $this->tables[$modelName][$serializedData["id"]] = $serializedData;
        }

        public function delete(array $serializedData, string $modelName) {
            if (!isset($serializedData["id"]) || !$this->modelIdExists($serializedData["id"], $modelName))
                return;
            parent::delete($serializedData, $modelName);
            unset($this->tables[$modelName][$serializedData["id"]]);
        }

        public function save(array $serializedData, string $modelName) {
            if (!isset($serializedData["id"]))
                return;
            if (!$this->modelIdExists($serializedData["id"], $modelName)) {
                self::create($serializedData, $modelName);
            } else {
                self::update($serializedData, $modelName);
            }
            parent::save($serializedData, $modelName);
        }

        public function update(array $serializedData, string $modelName) {
            if (!isset($serializedData["id"]) || !$this->modelIdExists($serializedData["id"], $modelName))
                return;
            $this->tables[$modelName][$serializedData["id"]] = $serializedData;
            parent::update($serializedData, $modelName);
        }
    }
}
