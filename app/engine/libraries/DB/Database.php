<?php

namespace Engine\Libraries\DB;

class Database {

    protected DatabaseConfig $config;
    protected ?DatabaseDriver $driver = null;

    public function __construct() {
        $this->config = new DatabaseConfig(function (DatabaseDriver $driver) {
            $this->driver = $driver;
        });
    }

    public function config(): DatabaseConfig|DatabaseDriver|null {
        if ($this->driver === null) {
            return $this->config;
        }
        if (!$this->driver->isConnected()) {
            return $this->driver;
        }
        throw new \RuntimeException('The database is already configured.');
    }

    protected function getDriver(): DatabaseDriver|null {
        if ($this->driver === null) {
            throw new \RuntimeException('The database is not configured.');
        }
        return $this->driver;
    }

    public function query(string $query) {
        $connection = $this->getDriver()->getConnection();
        try {
            return $connection->query(htmlspecialchars($query))->fetchAll();
        } catch (\PDOException $e) {
            if (typeOf($e->getMessage()) === 'string') {
                $err = preg_match('/SQLSTATE\[[^\]]+\]: [^\d]+(?:\d+ )?(.*)/', $e->getMessage(), $matches) ? $matches[1] : $e->getMessage();
                throw new \RuntimeException($err);
            }
        }
    }
}
