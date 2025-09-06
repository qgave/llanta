<?php

namespace Engine\Libraries\Http;

class BasicResponse implements ResponseInterface {

    protected ?int $statusCode = null;
    protected array $headers = ['Content-Type' => 'text/html'];
    protected ?string $content;

    public function __construct(?string $content = '', ?int $statusCode = null) {
        if ($statusCode !== null) $this->statusCode = $statusCode;
        $this->content = $content;
    }

    public function getStatusCode(): int|null {
        return $this->statusCode;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function sendContent(): void {
        output($this->content);
    }
}