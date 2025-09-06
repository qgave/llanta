<?php

namespace Engine\Libraries\Http;

class Payload {
    protected array $fields = [];

    public function __construct(string|false $raw, ?string $type = null) {
        if ($raw === false || $type === null) return;
        $this->fields = $this->parse($raw, $type) ?? [];
    }

    protected function parse(string $raw, string $type) {
        if (stripos($type, 'application/json') !== false) {
            return $this->parseJson($raw);
        }
        if (stripos($type, 'application/xml') !== false || stripos($type, 'text/xml') !== false) {
            return $this->parseXml($raw);
        }
        if (stripos($type, 'application/x-www-form-urlencoded') !== false) {
            return $this->parseWwwFormUrlEncoded($raw);
        }
        if (stripos($type, 'multipart/form-data') !== false) {
            return $this->parseMultiPartFormData($raw, $type);
        }
        if (stripos($type, 'text/plain') !== false) {
            return $this->parseTextPlain($raw);
        }
        if (stripos($type, 'application/octet-stream') !== false) {
            return $this->applicationOctetStream($raw);
        }
    }

    protected function parseJson(string $raw) {
        $data = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }
    }

    protected function parseXml(string $raw) {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($raw, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, true);
        if ($array !== false) {
            return $array;
        }
    }

    protected function parseWwwFormUrlEncoded(string $raw) {
        parse_str($raw, $data);
        return $data;
    }

    protected function parseMultiPartFormData(string $raw, string $contentType): array {
        $result = [];

        if (!preg_match('/boundary=(.*)$/', $contentType, $matches)) {
            return $result;
        }
        $boundary = $matches[1];

        $parts = array_slice(explode("--$boundary", $raw), 1, -1);

        foreach ($parts as $part) {
            $part = ltrim($part, "\r\n");

            if (strpos($part, "\r\n\r\n") === false) {
                continue;
            }
            list($rawHeaders, $body) = explode("\r\n\r\n", $part, 2);
            $body = rtrim($body, "\r\n");

            $headers = [];
            foreach (explode("\r\n", $rawHeaders) as $headerLine) {
                if (strpos($headerLine, ":") !== false) {
                    [$name, $value] = explode(":", $headerLine, 2);
                    $headers[strtolower(trim($name))] = trim($value);
                }
            }

            if (!isset($headers['content-disposition'])) {
                continue;
            }

            if (preg_match('/name="([^"]+)"/', $headers['content-disposition'], $nameMatch)) {
                $name = $nameMatch[1];

                if (preg_match('/filename="([^"]*)"/', $headers['content-disposition'], $fileMatch)) {
                    $filename = $fileMatch[1];
                    $result[$name] = [
                        'filename' => $filename,
                        'data' => $body,
                        'headers' => $headers
                    ];
                } else {
                    $result[$name] = $body;
                }
            }
        }

        return $result;
    }

    protected function parseTextPlain(string $raw) {
        if ($raw === '') return null;

        $raw = trim($raw);

        if (str_contains($raw, "&") && str_contains($raw, "=")) {
            parse_str($raw, $parsed);
            return $parsed;
        }

        if (preg_match_all('/^([a-zA-Z0-9_-]+)=(.*)$/m', $raw, $matches)) {
            $parsed = [];
            foreach ($matches[1] as $i => $key) {
                $parsed[$key] = $matches[2][$i];
            }
            return $parsed;
        }
    }

    protected function applicationOctetStream(string $raw) {
        return ['file' => $raw];
    }

    public function getFields() {
        return $this->fields;
    }
}
