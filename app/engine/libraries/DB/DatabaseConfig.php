<?php

namespace Engine\Libraries\DB;

class DatabaseConfig {
    protected \Closure $callBack;

    public function __construct(\Closure $callBack) {
        $this->callBack = $callBack;
    }

    protected function sendDriver(DatabaseDriver $driver): DatabaseDriver {
        call_user_func($this->callBack, $driver);
        return $driver;
    }

    public function MySQL(): MySQLDriver {
        return $this->sendDriver(new MySQLDriver());
    }

    public function PostgreSQL(): PostgreSQLDriver {
        return $this->sendDriver(new PostgreSQLDriver());
    }

    public function SQLite(): SQLiteDriver {
        return $this->sendDriver(new SQLiteDriver());
    }

    public function SQLServer(): SQLServerDriver {
        return $this->sendDriver(new SQLServerDriver());
    }

    public function OCI(): OCIDriver {
        return $this->sendDriver(new OCIDriver());
    }
}