<?php

namespace Engine\Libraries\DB;

class SQLServerDriver extends DatabaseDriver {
    
    protected ?string $hostname = null;
    protected ?string $username = null;
    protected ?string $password = null;
    protected ?string $database = null;

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

    public function options(array $value): static {
        $this->options = $value;
        return $this;
    }

    public function connect(): bool|null {
        if ($this->hostname === null) throw new \RuntimeException('The SQLServer hostname is not defined.');
        if ($this->username === null) throw new \RuntimeException('The SQLServer username is not defined.');
        if ($this->password === null) throw new \RuntimeException('The SQLServer password is not defined.');
        if ($this->database === null) throw new \RuntimeException('The SQLServer database is not defined.');

        $hostname = $this->hostname;
        $database = $this->database;

        return $this->createConnection(
            dsn: "sqlsrv:Server=$hostname;Database=$database",
            username: $this->username,
            password: $this->password,
            options: $this->options
        );
    }
}
