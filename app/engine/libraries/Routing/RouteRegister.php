<?php

namespace Engine\Libraries\Routing;

use Engine\Libraries\Http\Request;

class RouteRegister {
    protected Router $Router;

    public function __construct(Router $Router) {
        $this->Router = $Router;
    }

    public function get(string $path, \Closure|array $action): Route {
        return $this->Router->store(['GET', 'HEAD'], $path, $action);
    }

    public function post(string $path, \Closure|array $action): Route {
        return $this->Router->store('POST', $path, $action);
    }

    public function put(string $path, \Closure|array $action): Route {
        return $this->Router->store('PUT', $path, $action);
    }

    public function patch(string $path, \Closure|array $action): Route {
        return $this->Router->store('PATCH', $path, $action);
    }

    public function delete(string $path, \Closure|array $action): Route {
        return $this->Router->store('DELETE', $path, $action);
    }

    public function options(string $path, \Closure|array $action): Route {
        return $this->Router->store('OPTIONS', $path, $action);
    }

    public function any(string $path, \Closure|array $action): Route {
        return $this->Router->store(Request::VERBS, $path, $action);
    }
}
