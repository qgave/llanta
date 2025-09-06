<?php

namespace Engine\Libraries\DB;

class DatabaseDriver {
    protected ?\PDO $connection = null;

    protected function createConnection(string $dsn, ?string $username = null, ?string $password = null, array $options = [], ?array $attributes = null) {
        try {
            $this->connection = new \PDO($dsn, $username, $password, $options);
            if ($attributes !== null) {
                foreach ($attributes as $key => $value) {
                    $this->connection->setAttribute($key, $value);
                }
            }
        } catch (\PDOException $e) {
            $pdoError = $e->getMessage();
            $err = preg_match('/SQLSTATE\[[^\]]+\] \[(\d+)\] (.+)/', $pdoError, $matches) ? $matches[2] : $pdoError;
            throw new \RuntimeException($err);
        }
        return true;
    }

    public function isConnected(): bool {
        return $this->connection !== null;
    }

    public function getConnection(): \PDO|null {
        if ($this->connection === null) {
            throw new \RuntimeException('The database is not configured.');
        }
        return $this->connection;
    }
}
