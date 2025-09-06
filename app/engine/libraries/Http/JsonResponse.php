<?php

namespace Engine\Libraries\Http;

class JsonResponse implements ResponseInterface {

    protected ?int $statusCode = null;
    protected array $headers = ['Content-Type' => 'application/json'];
    protected string $content = '';

    public function __construct(mixed $content = null) {
        $this->content = $this->prepareContent($content);
    }

    public function prepareContent(mixed $content): string {
        if (is_bool($content)) return $content ? 'true' : 'false';
        return json_encode($content);
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
