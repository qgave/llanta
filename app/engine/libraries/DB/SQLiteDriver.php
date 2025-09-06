<?php

namespace Engine\Libraries\DB;

class SQLiteDriver extends DatabaseDriver {

    protected ?string $path = null;

    protected array $attributes = [];

    public function path(string $value): static {
        $this->path = $value;
        return $this;
    }

    public function setAttribute(int $key, mixed $value): static {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function connect(): bool|null {
        if ($this->path === null) throw new \RuntimeException('The SQLite path is not defined.');

        $path = $this->path;

        return $this->createConnection(dsn: "sqlite:$path", attributes: $this->attributes);
    }
}
