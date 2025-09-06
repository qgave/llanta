<?php

namespace Engine\Libraries\Kit;

class KitBag {
    protected array $kits = [];

    public function store(Kit $kit) {
        $this->kits[] = $kit;
        return $kit;
    }

    protected function getAll(): array {
        return $this->kits;
    }

    public function get(string $name) {
        foreach ($this->getAll() as $kit) {
            if ($kit->getName() === $name) return $kit;
        }
        throw new \RuntimeException("The Kit ['$name'] is not registered.");
    }
}