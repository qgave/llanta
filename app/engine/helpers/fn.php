<?php
function output(mixed $value): void {
    echo $value;
}

function typeOf(mixed $value): string {
    return gettype($value);
}

function toString(mixed $value): string {
    return (string) $value;
}

function toUpperCase(string $value): string {
    return strtoupper($value);
}

function toLowerCase(string $value): string {
    return strtolower($value);
}

function length(string|array|float $value): int {
    if (typeOf($value) === 'array') {
        return count($value);
    }
    if (typeOf($value) === 'integer' || typeOf($value) === 'double' || typeOf($value) === 'float') {
        return strlen(toString($value));
    }
    return strlen($value);
}

function normalizePath(string $path): string {
    return preg_replace(['/\/+/', '/^\/*(.*?)\/*$/'], ['/', '/$1'], $path);
}

function normalizeUrl(string $url): string {
    return preg_replace('#(?<!:)//+#', '/', ltrim($url, '/'));
}

function getExtension(string $value): string {
    return toLowerCase(pathinfo(basename($value), PATHINFO_EXTENSION));
}

function isHttpStatusCode(int $code): bool {
    return preg_match('/^[1-5][0-9]{2}$/', $code);
}

function addLineBreakIfMissing($string, $lineBreak = "\n") {
    if (!preg_match('/[\r\n]$/', $string)) {
        return $string . $lineBreak;
    }
    return $string;
}

function classExists(string $class) {
    return file_exists(resolveFilePath($class)) && class_exists($class);
}