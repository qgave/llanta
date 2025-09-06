<?php

namespace Engine\Libraries\Routing;

use Engine\Libraries\Http\ResponseException;

class Route {
    protected array $methods;
    protected string $path;
    protected \Closure|array $action;
    protected ?array $params;
    protected array $middlewares = [];
    protected array $processedMiddlewares = [];

    public function __construct(string|array $methods, string $path, \Closure|array $action) {
        $this->methods = (array) $methods;
        $this->path = normalizePath($path);
        $this->action = $action;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getMethods(): array {
        return $this->methods;
    }

    protected function getAction(): \Closure|array {
        return $this->action;
    }

    protected function getMiddlewares(): array {
        return $this->middlewares;
    }

    public function middleware(string ...$values): static {
        foreach ($values as $value) {
            $this->middlewares[] = $value;
        }
        return $this;
    }

    public function execute(): mixed {
        return $this->next(0);
    }

    protected function next($i): mixed {
        if ($i < length($this->middlewares)) {
            $middleware = $this->resolveMiddleware($this->middlewares[$i]);
            $this->processedMiddlewares[] = $middleware;
            $i++;

            if (!method_exists($middleware, 'before')) {
                return $this->next($i);
            }

            try {
                $this->resolveMiddlewareMethod($middleware, function () use ($i) {
                    $result = $this->next($i);
                    throw new NextException($result);
                });
                throw new ResponseException(null);
            } catch (NextException $e) {
                return $e->getResult();
            }
        } else {
            return $this->executeAction();
        }
    }

    protected function resolveMiddlewareMethod(object $instance, $next, $method = 'before') {
        $refMethod = new \ReflectionMethod($instance, $method);
        $listParams = $refMethod->getParameters();
        $params = $this->resolveParams($refMethod);
        if (array_key_last($listParams) !== null) {
            $lastParam = $listParams[array_key_last($listParams)];
            $params[$lastParam->getName()] = $next;
        }
        return call_user_func_array([$instance, $method], $params);
    }

    protected function executeAction(): mixed {
        $action = $this->getAction();
        if ($action instanceof \Closure) {
            return $this->executeCallable($action);
        }
        return $this->executeController($action);
    }

    private function executeCallable($action) {
        $reflection = new \ReflectionFunction($action);
        $params = $this->resolveParams($reflection);

        return call_user_func_array($action, $params);
    }

    protected function executeController($callable): mixed {
        if (length($callable) < 2) throw new \RuntimeException('The Controller is invalid.');
        [$class, $method] = $callable;
        $this->checkController($class, $method);
        $controller = $this->resolveClass($class);
        return $this->resolveClassMethod($controller, $method);
    }

    protected function checkController($class, $method) {
        if (!is_string($class)) {
            throw new \RuntimeException('The Controller is invalid.');
        }
        if (!classExists($class)) {
            throw new \RuntimeException("The controller ['$class'] doesn't exist.");
        }
        if (!is_string($method)) {
            throw new \RuntimeException("The controller method ['$class'] is invalid.");
        }
        if (!method_exists($class, $method)) {
            throw new \RuntimeException("The method ['$method'] of the controller ['$class'] doesn't exist.");
        }
    }

    protected function resolveClass($class) {
        $refClass = new \ReflectionClass($class);
        $constructor = $refClass->getConstructor();
        if (!$constructor) return $refClass->newInstance();
        $params = $this->resolveParams($constructor);
        return $refClass->newInstance(...$params);
    }

    protected function resolveParams($refMethod) {
        $params = [];
        foreach ($refMethod->getParameters() as $param) {
            if ($param->isOptional()) continue;
            $type = $param->getType();
            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin() && $type->getName() !== 'Closure') {
                $params[$param->getName()] = $this->resolveClass($type->getName());
            }
        }
        return $params;
    }

    function resolveClassMethod(object $instance, string $method) {
        $refMethod = new \ReflectionMethod($instance, $method);
        $params = $this->resolveParams($refMethod);
        return call_user_func_array([$instance, $method], $params);
    }

    protected function resolveMiddleware($middleware): object|null {
        if (!is_string($middleware)) {
            throw new \RuntimeException('The Middleware is invalid.');
        }
        if (!classExists($middleware)) {
            throw new \RuntimeException("The Middleware ['$middleware'] doesn't exist.");
        }
        return $this->resolveClass($middleware);
    }

    public function afterMiddleware(): void {
        foreach ($this->processedMiddlewares as $middleware) {
            if (!method_exists($middleware, 'after')) continue;
            $this->resolveClassMethod($middleware, 'after');
        }
    }
}
