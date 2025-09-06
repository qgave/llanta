<?php

namespace Engine\Libraries\Routing;

use Engine\Libraries\Http\Request;
use Engine\Libraries\Http\BasicResponse;

class RouteBag {
    protected array $routes = [];

    public function store(Route $route): Route {
        $this->routes[] = $route;
        return $route;
    }

    public function getAllRoutes(): array {
        return $this->routes;
    }

    public function get($method, $path) {
        $routeNotFound = null;
        foreach ($this->getAllRoutes() as $route) {
            if (!in_array($method, $route->getMethods())) continue;
            if ($route->getPath() === '*' || $route->getPath() === '/*') $routeNotFound = $route;
            if (preg_match($this->toRegex($route->getPath()), rawurldecode($path), $matches)) {
                return [$route, $matches];
            }
        }
        $routeNotFound ??= $this->createRouteNotFound($method);
        return [$routeNotFound, []];
    }

    public function createRouteNotFound($method): Route {
        return new Route([$method], '/*', function () {
            return new BasicResponse('Error 404', 404);
        });
    }

    public function toRegex(string $path): string {
        $regex = preg_replace_callback(
            '/\{([A-Za-z_][A-Za-z0-9_]*)\}/',
            fn($m) => '(?P<' . $m[1] . '>[^/]++)',
            $path
        );
        return '{^' . $regex . '$}sDu';
    }
}
