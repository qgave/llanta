<?php

use Engine\Core\HandleExceptions;
use Engine\Core\Llanta;

try {
    require_once PATH . 'app/engine/core/autoload.php';

    $llanta = new Llanta();
    $llanta->registerKits();
    $llanta->scanForRoutes(PATH . 'app/src/helpers/*.php', PATH . 'app/src/app.php');
    $llanta->handleRequest();
} catch (\Throwable $e) {
    new HandleExceptions($e);
}