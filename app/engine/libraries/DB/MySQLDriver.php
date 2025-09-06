<?php

namespace Engine\Libraries\DB;

class MySQLDriver extends DatabaseDriver {

    protected ?string $hostname = null;
    protected ?string $username = null;
    protected ?string $password = null;
    protected ?string $database = null;

    protected string $charset = 'utf8';
    protected array $options = [];

    public function hostname(string $value): static {
        $this->hostname = $value;
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

    public function charset(string $value): static {
        $this->charset = $value;
        return $this;
    }

    public function options(array $value): static {
        $this->options = $value;
        return $this;
    }

    public function connect(): bool|null {
        if ($this->hostname === null) throw new \RuntimeException('The MySQL hostname is not defined.');
        if ($this->username === null) throw new \RuntimeException('The MySQL username is not defined.');
        if ($this->password === null) throw new \RuntimeException('The MySQL password is not defined.');
        if ($this->database === null) throw new \RuntimeException('The MySQL database is not defined.');

        $hostname = $this->hostname;
        $database = $this->database;
        $charset = $this->charset;

        return $this->createConnection(
            dsn: "mysql:host=$hostname;dbname=$database;charset=$charset",
            username: $this->username,
            password: $this->password,
            options: $this->options
        );
    }
}
