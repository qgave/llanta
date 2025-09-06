<?php

namespace Engine\Libraries\DB;

class PostgreSQLDriver extends DatabaseDriver {

    protected ?string $hostname = null;
    protected ?string $port = null;
    protected ?string $username = null;
    protected ?string $password = null;
    protected ?string $database = null;

    protected array $options = [];

    public function hostname(string $value): static {
        $this->hostname = $value;
        return $this;
    }

    public function port(string $value): static {
        $this->port = $value;
        return $this;
    }

    public function username(string $value): static {
        $this->username = $value;
        return $this;
    }

    public function password(string $value): static {
        $this->password = $value;
        return $this;
    }

    public function database(string $value): static {
        $this->database = $value;
        return $this;
    }

    public function options(array $value): static {
        $this->options = $value;
        return $this;
    }

    public function connect(): bool|null {
        if ($this->hostname === null) throw new \RuntimeException('The PostgreSQL hostname is not defined.');
        if ($this->port === null) throw new \RuntimeException('The PostgreSQL port is not defined.');
        if ($this->username === null) throw new \RuntimeException('The PostgreSQL username is not defined.');
        if ($this->password === null) throw new \RuntimeException('The PostgreSQL password is not defined.');
        if ($this->database === null) throw new \RuntimeException('The PostgreSQL database is not defined.');

        $hostname = $this->hostname;
        $port = $this->port;
        $database = $this->database;

        return $this->createConnection(
            dsn: "pgsql:host=$hostname;port=$port;dbname=$database",
            username: $this->username,
            password: $this->password,
            options: $this->options
        );
    }
}
