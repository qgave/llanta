<?php

namespace Engine\Libraries\Utilities;

class Dispatcher {
    protected array $fields;
    protected mixed $default;

    public function __construct(array $fields = [], mixed $default = null) {
        $this->setFields($fields);
        $this->default = $default;
    }

    public function setFields(array $fields): void {
        $this->fields = $fields;
    }

    public function get(): array {
        return $this->fields;
    }

    public function __get($property): mixed {
        return $this->fields[$property] ?? $this->default;
    }
}
