<?php

namespace Engine\Libraries\Http;

class Response {
    protected array $headers = [];
    protected int $statusCode = 200;
    protected bool $isSent = false;

    public function setStatusCode(int $code): void {
        $this->statusCode = isHttpStatusCode($code) ? $code : 500;
    }

    protected function getStatusCode(): int {
        return $this->statusCode;
    }

    public function isSent(): bool {
        return $this->isSent;
    }

    public function prepare(mixed $response): ResponseInterface|null {
        if ($response instanceof ResponseInterface) return $response;

        if (is_string($response) || is_numeric($response) || is_null($response)) {
            return new BasicResponse((string) $response);
        }

        if (
            is_bool($response) ||
            is_array($response) ||
            $response instanceof \stdClass ||
            $response instanceof \ArrayObject ||
            $response instanceof \JsonSerializable
        ) {
            return new JsonResponse($response);
        }

        throw new \RuntimeException('The route response cannot be processed');
    }


    public function addHeaders(string $key, string $value): void {
        $this->headers[$key] = $value;
    }

    public function send(ResponseInterface $response): void {
        $this->sendHeaders($response->getHeaders(), $response->getStatusCode() ?? $this->getStatusCode());
        $response->sendContent();
        $this->isSent = true;
    }

    public function sendHeaders(array $headers, int $statusCode): void {
        if (headers_sent()) return;

        header_remove('X-Powered-By');

        $headers['cache-control'] ??= 'no-cache, private';
        $headers['date'] ??= gmdate('D, d M Y H:i:s') . ' GMT';

        foreach ($headers as $key => $value) {
            header($key . ': ' . $value, true, $statusCode);
        }
    }
}
