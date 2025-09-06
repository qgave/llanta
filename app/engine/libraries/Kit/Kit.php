<?php

namespace Engine\Libraries\Kit;

class Kit {
    protected string $name;
    protected string $class;
    protected array $props = [];
    protected ?object $instance = null;

    public function __construct(string $name, string $class) {
        if (!classExists($class)) {
            throw new \RuntimeException("The Class [$class] is invalid.");
        }
        $this->name = $name;
        $this->class = $class;
    }

    public function props(mixed ...$values) {
        foreach ($values as $value) {
            $this->props[] = $value;
        }
        return $this;
    }

    public function instance(object $value) {
        $this->instance = $value;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    protected function createInstance(array $props) {
        return new ($this->class)(...$props);
    }

    public function call(array $props, bool $store) {
        if ($this->instance === null || !$store) {
            $instance = $this->createInstance([...$this->props, ...$props]);
            if ($store) {
                $this->instance = $instance;
            }
            return $instance;
        }
        //no guardo la instancia
        return $this->instance;
    }
}
