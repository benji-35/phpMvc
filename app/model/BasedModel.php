<?php

namespace App\Model {

    use App\repository\Repository;

    class BasedModel {
        public function __construct() {}

        public function serialized(): array {
            return [];
        }

        public function deserialized(array $data): BasedModel {
            return new BasedModel();
        }

        private final function modelName(): string {
            return get_class($this);
        }

        public final function save(): BasedModel {
            Repository::getInstance()->save($this->serialized(), $this->modelName());
            return $this;
        }

        public final function update(): BasedModel {
            Repository::getInstance()->update($this->serialized(), $this->modelName());
            return $this;
        }

        public final function delete() {
            Repository::getInstance()->delete($this->serialized(), $this->modelName());
        }

        public final function create(): BasedModel {
            Repository::getInstance()->create($this->serialized(), $this->modelName());
            return $this;
        }
    }
}
