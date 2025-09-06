<?php

use Engine\Libraries\Kit\KitHandler;

function kit() {
    return KitHandler::getInstance();
}

function cookie(): object|null {
    return kit()->get();
}

function db(): object|null {
    return kit()->get();
}

function request(): object|null {
    return kit()->get();
}

function response(): object|null {
    return kit()->get();
}

function route(): object|null {
    return kit()->get();
}

function session(): object|null {
    return kit()->get();
}

function redirect(): object|null {
    return kit()->get();
}

function logger(): object|null {
    return kit()->get();
}

function clock(?string $timeZone = null): object|null {
    return kit()->get([$timeZone], false);
}

function encryption(): object|null {
    return kit()->get();
}

function random(): object|null {
    return kit()->get();
}

function serveFile(string $file, bool $download = false): object|null {
    return kit()->get([$file, $download], false);
}

function hashing(): object|null {
    return kit()->get();
}

function scope(): object|null {
    return kit()->get();
}

function render(string $view, array $data = []): object|null {
    return kit()->get([$view, $data], false);
}

function debug(): object|null {
    return kit()->get();
}