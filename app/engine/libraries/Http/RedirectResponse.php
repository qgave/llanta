<?php


namespace Engine\Libraries\Http;

class RedirectResponse implements ResponseInterface {

    protected int $statusCode = 302;
    protected array $headers = [];
    protected mixed $content;

    public function __construct(string $url, ?array $headers = null) {
        if ($headers === null) {
            $this->headers['Location'] = $url;
        } else {
            $this->headers = $headers;
        }
    }
    
    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function sendContent(): void {
    }
}
