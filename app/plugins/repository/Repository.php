<?php

namespace App\repository {
    class Repository {

        private static ?Repository $repository = null;

        public function __construct() {
            if (self::$repository === null)
                self::$repository = $this;
        }

        public function save(array $serializedData, string $modelName) {
            error_log("[SAVE] " . $modelName);
        }
        public function update(array $serializedData, string $modelName) {
            error_log("[UPDATE] " . $modelName);
        }
        public function create(array $serializedData, string $modelName) {
            error_log("[CREATE] " . $modelName);
        }
        public function delete(array $serializedData, string $modelName) {
            error_log("[DELETE] " . $modelName);
        }

        public final static function getInstance(): Repository {
            return self::$repository;
        }
    }
}
