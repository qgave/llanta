<?php

namespace Engine\Libraries\Utilities;

class Scope {
    protected array $fields = [];

    public function store(string $key, mixed $value): void {
        $this->fields[$key] = $value;
    }

    public function collect(?string $key = null): mixed {
        if ($key === null) return $this->fields;
        return $this->fields[$key] ?? null;
    }
}
