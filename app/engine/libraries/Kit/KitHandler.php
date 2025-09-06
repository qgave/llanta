<?php

namespace Engine\Libraries\Kit;

class KitHandler {
    protected static KitHandler $instance;
    protected KitBag $kitBag;

    public function __construct() {
        $this->kitBag = new KitBag();
    }

    public static function getInstance(): static {
        return self::$instance ??= new self();
    }

    public function store(string $name, string $class) {
        return $this->kitBag->store(new Kit($name, $class));
    }

    public function get(array $props = [], bool $store = true) {
        $kit = $this->kitBag->get(debug_backtrace()[1]['function'] ?? '');
        return $kit->call($props, $store);
    }
}
