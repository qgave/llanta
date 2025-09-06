<?php

namespace Engine\Libraries\Http;

use Engine\Libraries\Utilities\Dispatcher;

class Environment {

    protected ?Dispatcher $request;
    protected ?Dispatcher $execution;
    protected ?Dispatcher $connection;
    protected ?Dispatcher $server;
    protected ?Dispatcher $cli;

    /*
    public function __construct() {
        $this->request = new Dispatcher([
            'method' => $this->requestMethod(),
            'uri' => $this->requestUri(),
            'queryString' => $this->queryString(),
            'protocol' => $this->serverProtocol(),
            'host' => $this->httpHost(),
            'userAgent' => $this->userAgent(),
            'referer' => $this->httpReferer(),
            'contentType' => $this->contentType(),
        ], '');

        $this->execution = new Dispatcher([
            'scriptName' => $this->scriptName(),
            'scriptFileName' => $this->scriptFileName(),
            'phpSelf' => $this->phpSelf(),
            'requestTime' => $this->requestTime(),
            'documentRoot' => $this->documentRoot(),
        ], '');

        $this->connection = new Dispatcher([
            'remoteAddr' => $this->remoteAddr(),
            'remotePort' => $this->remotePort(),
            'https' => $this->https(),
        ], '');

        $this->server = new Dispatcher([
            'name' => $this->serverName(),
            'addr' => $this->serverAddr(),
            'port' => $this->serverPort(),
            'software' => $this->serverSoftware(),
        ], '');

        $this->cli = new Dispatcher([
            'argc' => $this->argc(),
            'argv' => $this->argv(),
        ], '');
    }
    */

    protected function getRequest() {
        return new Dispatcher([
            'method' => $this->requestMethod(),
            'uri' => $this->requestUri(),
            'queryString' => $this->queryString(),
            'protocol' => $this->serverProtocol(),
            'host' => $this->httpHost(),
            'userAgent' => $this->userAgent(),
            'referer' => $this->httpReferer(),
            'contentType' => $this->contentType(),
        ], '');
    }

    protected function getExecution() {
        return new Dispatcher([
            'scriptName' => $this->scriptName(),
            'scriptFileName' => $this->scriptFileName(),
            'phpSelf' => $this->phpSelf(),
            'requestTime' => $this->requestTime(),
            'documentRoot' => $this->documentRoot(),
        ], '');
    }

    protected function getConnection() {
        return new Dispatcher([
            'remoteAddr' => $this->remoteAddr(),
            'remotePort' => $this->remotePort(),
            'https' => $this->https(),
        ], '');
    }

    protected function getServer() {
        return new Dispatcher([
            'name' => $this->serverName(),
            'addr' => $this->serverAddr(),
            'port' => $this->serverPort(),
            'software' => $this->serverSoftware(),
        ], '');
    }

    protected function getCli() {
        return new Dispatcher([
            'argc' => $this->argc(),
            'argv' => $this->argv(),
        ], '');
    }

    /* -- */

    public function request() {
        return $this->request ??= $this->getRequest();
    }

    public function execution() {
        return $this->execution ??= $this->getExecution();
    }

    public function connection() {
        return $this->connection ??= $this->getConnection();
    }

    public function server() {
        return $this->server ??= $this->getServer();
    }

    public function cli() {
        return $this->cli ??= $this->getCli();
    }

    /* Request */

    protected function requestMethod() {
        return toUpperCase($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    protected function requestUri() {
        return $_SERVER['REQUEST_URI'] ?? null;
    }

    protected function queryString() {
        return $_SERVER['QUERY_STRING'] ?? null;
    }

    protected function serverProtocol() {
        return $_SERVER['SERVER_PROTOCOL'] ?? null;
    }

    protected function httpHost() {
        return $_SERVER['HTTP_HOST'] ?? null;
    }

    protected function userAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    protected function httpReferer() {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }

    protected function contentType() {
        return $_SERVER['CONTENT_TYPE'] ?? null;
    }

    /* Execution */

    protected function scriptName() {
        return $_SERVER['SCRIPT_NAME'] ?? null;
    }

    protected function scriptFileName() {
        return $_SERVER['SCRIPT_FILENAME'] ?? null;
    }

    protected function phpSelf() {
        return $_SERVER['PHP_SELF'] ?? null;
    }

    protected function requestTime() {
        return $_SERVER['REQUEST_TIME'] ?? null;
    }

    protected function documentRoot() {
        return $_SERVER['DOCUMENT_ROOT'] ?? null;
    }

    /* Connection */

    protected function remoteAddr() {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    protected function remotePort() {
        return $_SERVER['REMOTE_PORT'] ?? null;
    }

    protected function https() {
        return isset($_SERVER['HTTPS']);
    }

    /* Server */

    protected function serverName() {
        return $_SERVER['SERVER_NAME'] ?? null;
    }

    protected function serverAddr() {
        return $_SERVER['SERVER_ADDR'] ?? null;
    }

    protected function serverPort() {
        return $_SERVER['SERVER_PORT'] ?? null;
    }

    protected function serverSoftware() {
        return $_SERVER['SERVER_SOFTWARE'] ?? null;
    }

    /* Cli */

    protected function argc() {
        return $_SERVER['argc'] ?? null;
    }

    protected function argv() {
        return $_SERVER['argv'] ?? null;
    }
}
