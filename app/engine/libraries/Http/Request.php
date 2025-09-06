<?php

namespace Engine\Libraries\Http;

use Engine\Libraries\Utilities\Dispatcher;

class Request {
    public const VERBS = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    protected string $method;
    protected string $queryString;
    protected string $requestUri;
    protected string $fullPath;
    protected string $path;
    protected string $serverProtocol;
    protected Dispatcher $params;
    protected bool $isSecure;
    protected string $httpHost;
    protected string $scriptName;
    protected string $basePath;
    protected string $baseUrl;
    protected string $url;
    protected string $uri;
    protected ?string $previousPath = null;
    protected ?string $contentType;
    protected string $userAgent;
    protected string|false $payload;
    protected Dispatcher $body;
    protected Dispatcher $query;

    public function __construct() {
        $environment = new Environment();

        $this->method = $environment->request()->method;
        $this->queryString = $environment->request()->queryString;
        $this->requestUri = $environment->request()->uri;
        $this->scriptName = $environment->execution()->scriptName;
        $this->serverProtocol = $environment->request()->protocol;
        $this->isSecure = $environment->connection()->https;
        $this->httpHost = $environment->request()->host;
        $this->contentType = $environment->request()->contentType;
        $this->userAgent = $environment->request()->userAgent;
        $this->payload = file_get_contents('php://input');

        $this->fullPath = preg_replace(
            ['/\?.*$/', '/^(?=.+)(?!\/)(.+)$/'],
            ['', '/$1'],
            $this->getRequestUri()
        );
        $this->basePath = str_replace(basename($this->getScriptName()), '', $this->getScriptName());
        $this->path = normalizePath(substr($this->getFullPath(), strlen($this->getBasePath())));
        $this->params = new Dispatcher();
        
        $this->baseUrl = $this->getHttp() . '://' . $this->getHttpHost() . $this->getBasePath();
        $this->url = $this->baseUrl . substr($this->getPath(), 1);
        $this->uri = $this->prepareUri($this->getURL(), $this->getQueryString());
        
        $this->body = $this->prepareBody($this->getPayload(), $this->getContentType());
        $this->query = $this->prepareQuery($this->getQueryString());
    }

    protected function prepareUri($url, $queryString): string {
        return $url . ((empty($queryString)) ? '' : '?' . $queryString);
    }

    protected function prepareBody(string|false $raw, ?string $type = null): Dispatcher {
        $payload = new Payload($raw, $type);
        return new Dispatcher($payload->getFields());
    }

    protected function prepareQuery($queryString): Dispatcher {
        parse_str($queryString, $result);
        return new Dispatcher($result);
    }

    public function query(): Dispatcher {
        return $this->query;
    }

    public function getQueryString(): string {
        return $this->queryString;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getURI(): string {
        return $this->uri;
    }

    public function getRequestURI(): string {
        return $this->requestUri;
    }

    public function getFullPath(): string {
        return $this->fullPath;
    }

    public function getScriptName(): string {
        return $this->scriptName;
    }

    public function getBasePath(): string {
        return $this->basePath;
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getServerProtocol(): string {
        return $this->serverProtocol;
    }

    public function getHttp(): string {
        return 'http' . ($this->isSecure() ? 's' : '');
    }

    public function isSecure(): bool {
        return $this->isSecure;
    }

    public function getHttpHost(): string {
        return $this->httpHost;
    }

    public function getBaseUrl(): string {
        return $this->baseUrl;
    }

    public function getURL(): string {
        return $this->url;
    }

    public function getPreviousPath(): string {
        return $this->previousPath;
    }

    public function getContentType(): string|null {
        return $this->contentType;
    }

    public function getUserAgent() {
        return $this->userAgent;
    }

    public function getPayload(): string|false {
        return $this->payload;
    }

    public function body(): Dispatcher {
        return $this->body;
    }

    public function params() {
        return $this->params;
    }

    public function setParams(array $params): array|null {
        return $this->params()->setFields($params);
    }

    public function setPreviousPath(string $path) {
        if ($this->previousPath !== null) return;
        $this->previousPath = $path;
    }
}
