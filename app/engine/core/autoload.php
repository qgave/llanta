<?php
function resolveFilePath(string $name): string {
    return PATH . 'app/' . strtolower($name) . '.php';
}

spl_autoload_register(function ($name) {
    $file = resolveFilePath($name);
    if (!file_exists($file)) {
        throw new \RuntimeException("The file '$file' doesn't exist.");
    }
    require_once $file;
});