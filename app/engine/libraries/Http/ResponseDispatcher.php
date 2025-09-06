<?php

namespace Engine\Libraries\Http;

class ResponseDispatcher {

    protected Response $response;

    public function __construct(Response $response) {
        $this->response = $response;
    }

    public function statusCode(int $code): void {
        $this->response->setStatusCode($code);
    }

    public function addHeaders(string $key, string $value): void {
        $this->response->addHeaders($key, $value);
    }

    public function send(mixed $response = null, int $statusCode = 200, array $headers = []): void {
        if ($this->response->isSent()) {
            throw new \RuntimeException('The response has already been sent');
        }
        $this->statusCode($statusCode);
        foreach ($headers as $key => $value) {
            $this->addHeaders($key, $value);
        }
        throw new ResponseException($response);
    }
}
