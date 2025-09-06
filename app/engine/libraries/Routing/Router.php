<?php

namespace Engine\Libraries\Routing;

class Router {
    protected RouteBag $routeBag;

    public function __construct() {
        $this->routeBag = new RouteBag();
    }

    public function store(string|array $methods, string $path, \Closure|array $action): Route {
        return $this->routeBag->store(new Route($methods, $path, $action));
    }

    public function get($method, $path) {
        return $this->routeBag->get($method, $path);
    }
}