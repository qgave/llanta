<?php

namespace Engine\Libraries\Utilities;

class CookieBag {
    public function get(string $key): string|null {
        return $_COOKIE[$key] ?? null;
    }

    public function set(string $key, string $value, int $expire = 0, string $path = '', string $domain = '', bool $secure = false, bool $httponly = false): bool {
        return setcookie($key, $value, time() + $expire, $path, $domain, $secure, $httponly);
    }

    public function delete(string $key): bool {
        return setcookie($key, '', time() - 3600, '/');
    }
}
