<?php

namespace Engine\Libraries\Utilities;

class Log {
    protected array $error = [];
    protected array $info = [];
    protected bool $enabled = true;
    protected bool $isWritten = false;

    protected const LOG_PATH = PATH . 'app/src/logs/';

    public function disabled(): void {
        $this->enabled = false;
    }

    public function error(string $value): void {
        $this->error[] = addLineBreakIfMissing($value);
    }

    public function info(string $value): void {
        $this->info[] = addLineBreakIfMissing($value);
    }

    public function write(): void {
        if ($this->isWritten || !$this->enabled) return;
        $this->isWritten = true;
        $this->writeIn($this::LOG_PATH . 'error.log', $this->error);
        $this->writeIn($this::LOG_PATH . 'info.log', $this->info);
    }

    protected function writeIn($path, $values): void {
        if (!file_exists($path)) return;
        foreach ($values as $value) {
            error_log($value, 3, $path);
        }
    }
}
