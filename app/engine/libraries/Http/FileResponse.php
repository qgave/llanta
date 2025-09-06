<?php

namespace Engine\Libraries\Http;

use Engine\Libraries\Utilities\MimeType;

class FileResponse implements ResponseInterface {

    protected ?int $statusCode = null;
    protected array $headers = [];
    protected string $file;

    public function __construct(string $file, bool $download = false) {
        if (!file_exists($file)) {
            throw new \RuntimeException("The file '$file' doesn't exist.");
        }
        $this->file = $file;

        $mimeType = MimeType::get(getExtension($file)) ?? mime_content_type($file) ?: 'application/octet-stream';

        $fileName = basename($file);
        $fileSize = filesize($file);

        $this->headers['Content-Description'] = 'File Transfer';
        $this->headers['Content-Type'] = $mimeType;
        $this->headers['Content-Length'] = $fileSize;
        $this->headers['Content-Length'] = $fileSize;

        $this->headers['Content-Disposition'] = ($download)
            ? 'attachment; filename="' . $fileName . '"'
            : 'inline; filename="' . $fileName . '"';

        $this->headers['Cache-Control'] = 'must-revalidate';
    }

    public function getStatusCode(): int|null {
        return $this->statusCode;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function sendContent(): void {
        readfile($this->file);
    }
}
