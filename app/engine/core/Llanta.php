<?php

namespace Engine\Core;

use Engine\Libraries\Http\Request;
use Engine\Libraries\Http\Response;
use Engine\Libraries\Http\ResponseException;
use Engine\Libraries\Routing\Route;
use Engine\Libraries\Routing\Router;
use Engine\Libraries\Utilities\Log;

class Llanta {
    protected Request $request;
    protected Response $response;
    protected Router $router;
    protected ?Route $route = null;
    protected Log $log;
    protected bool $responseException = false;

    protected array $scanRoutes = [];

    public function __construct() {
        $this->log = new Log();
        HandleExceptions::setLog($this->log);
        set_error_handler(['Engine\Core\HandleExceptions', 'handleError']);
        set_exception_handler(['Engine\Core\HandleExceptions', 'handleException']);
        register_shutdown_function(['Engine\Core\HandleExceptions', 'handleShutdown']);

        $this->importFiles('app/engine/helpers/*.php');
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router();
    }

    protected function importFiles(string ...$paths): void {
        foreach ($paths as $path) {
            foreach (glob($path) as $file) {
                require_once $file;
            }
        }
    }

    public function registerKits() {
        kit()->store('route', \Engine\Libraries\Routing\RouteRegister::class)->props($this->router);
        kit()->store('request', \Engine\Libraries\Http\Request::class)->instance($this->request);
        kit()->store('db', \Engine\Libraries\DB\Database::class);
        kit()->store('session', \Engine\Libraries\Utilities\SessionBag::class)->props($this->request);
        kit()->store('cookie', \Engine\Libraries\Utilities\CookieBag::class);
        kit()->store('response', \Engine\Libraries\Http\ResponseDispatcher::class)->props($this->response);
        kit()->store('redirect', \Engine\Libraries\Http\Redirect::class)->props($this->request);
        kit()->store('logger', \Engine\Libraries\Utilities\Log::class)->instance($this->log);
        kit()->store('clock', \Engine\Libraries\Utilities\Clock::class);
        kit()->store('encryption', \Engine\Libraries\Utilities\Encryption::class);
        kit()->store('random', \Engine\Libraries\Utilities\Random::class);
        kit()->store('serveFile', \Engine\Libraries\Http\FileResponse::class);
        kit()->store('hashing', \Engine\Libraries\Utilities\Hash::class);
        kit()->store('scope', \Engine\Libraries\Utilities\Scope::class);
        kit()->store('render', \Engine\Libraries\Http\ViewResponse::class);
        kit()->store('debug', \Engine\Libraries\Utilities\Debug::class);
    }

    public function scanForRoutes(string ...$paths) {
        $this->scanRoutes = $paths;
    }

    public function handleRequest() {
        $this->response->send($this->getResponse());
        if (!$this->responseException) {
            $this->getRoute()->afterMiddleware();
        }
        $this->log->write();
    }

    protected function getResponse() {
        $rawResponse = $this->getRawResponse();
        return $this->response->prepare($rawResponse);
    }

    protected function getRawResponse() {
        try {
            $this->importFiles(...$this->scanRoutes);
            $route = $this->getRoute();
            $rawResponse = $route->execute();
            return $rawResponse;
        } catch (ResponseException $e) {
            $this->responseException = true;
            return $e->getResponse();
        }
    }

    protected function getRoute(): Route {
        return $this->route ??= $this->prepareRoute();
    }

    public function prepareRoute(): Route {
        [$route, $params] = $this->router->get($this->request->getMethod(), $this->request->getPath());
        $this->request->setParams($params);
        return $route;
    }
}