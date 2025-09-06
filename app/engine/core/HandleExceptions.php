<?php

namespace Engine\Core;

use Engine\Libraries\Utilities\Log;

class HandleExceptions {
    protected static ?Log $log;
    protected static bool $showErrors = true;

    public function __construct(object $error) {
        if ($error instanceof \RuntimeException) {
            $errorTrace = $error->getTrace();
            foreach ($errorTrace as $value) {
                $regex = '/^' . preg_quote(PATH_SRC, '/') . '/';
                if (preg_match($regex, $value['file'])) {
                    return $this->handleError(0, $error->getMessage(), $value['file'], $value['line']);
                }
            }
        }
        return self::handleException($error);
    }

    public static function setLog(Log $log): void {
        self::$log = $log;
    }

    public static function showErrors(bool $bool): void {
        self::$showErrors = $bool;
    }

    protected static function createLog($message, $filepath, $line, $date): void {
        if (self::$log === null) return;
        self::$log->error("[$date Europe/Berlin] Error: $message in $filepath on line $line");
        self::$log->write();
    }

    protected static function sendError($message, $filepath, $line) {
        if (!self::$showErrors) return;
        echo '<b>Application Error</b><br>' . "\n";
        echo '<span>Message: ' . $message . '</span><br>' . "\n";
        echo '<span>Filename: ' . $filepath . '</span><br>' . "\n";
        echo '<span>Line Number: ' . $line . '</span><br>';
    }

    public static function handleError($severity, $message, $filepath, $line): void {
        self::createLog($message, $filepath, $line, date('d-M-Y H:i:s'));
        self::sendError($message, $filepath, $line);
        header('HTTP/1.1 500 Internal Server Error');
        exit(1);
    }

    public static function handleException($exception): void {
        static::handleError(0, $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }

    public static function handleShutdown(): void {
        $error = error_get_last();
        if ($error === null) return;
        self::handleError($error['type'], $error['message'], $error['file'], $error['line']);
    }
}
