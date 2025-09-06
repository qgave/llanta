<?php
if (version_compare(PHP_VERSION, '8.0', '<')) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'PHP version 8 is required';
    exit(1);
}
define('PATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once(PATH . 'app/engine/Boot.php');