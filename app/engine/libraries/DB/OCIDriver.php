<?php

namespace Engine\Libraries\DB;

class OCIDriver extends DatabaseDriver {

    protected ?string $hostname = null;
    protected ?string $serviceName = null;
    protected ?string $port = null;
    protected ?string $username = null;
    protected ?string $password = null;
    protected ?string $database = null;

    protected array $options = [];
    protected string $charset = 'AL32UTF8';

    public function hostname(string $value): static {
        $this->hostname = $value;
        return $this;
    }

    public function serviceName(string $value): static {
        $this->serviceName = $value;
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

    public function charset(string $value): static {
        $this->charset = $value;
        return $this;
    }

    public function connect(): bool|null {
        if ($this->hostname === null) throw new \RuntimeException('The OCI hostname is not defined.');
        if ($this->serviceName === null) throw new \RuntimeException('The OCI serviceName is not defined.');
        if ($this->username === null) throw new \RuntimeException('The OCI username is not defined.');
        if ($this->password === null) throw new \RuntimeException('The OCI password is not defined.');
        if ($this->database === null) throw new \RuntimeException('The OCI database is not defined.');
        if ($this->port === null) throw new \RuntimeException('The OCI port is not defined.');

        $hostname = $this->hostname;
        $serviceName = $this->serviceName;
        $port = $this->port;
        $charset = $this->charset;

        return $this->createConnection(
            dsn: "oci:dbname=//$hostname:$port/$serviceName;charset=$charset",
            username: $this->username,
            password: $this->password,
            options: $this->options
        );
    }
}
