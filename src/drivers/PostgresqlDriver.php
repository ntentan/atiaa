<?php

namespace ntentan\atiaa\drivers;

use ntentan\atiaa\Driver;

/**
 * PostgreSQL driver implementation.
 */
class PostgresqlDriver extends Driver
{
    protected ?string $defaultSchema = 'public';

    protected function getDriverName(): string
    {
        return 'pgsql';
    }

    public function quoteIdentifier($identifier)
    {
        return "\"$identifier\"";
    }

    public function getLastInsertId(): mixed
    {
        $lastval = $this->query('SELECT LASTVAL() as last');
        return $lastval[0]['last'];
    }

    #[\Override]
    public function connect(): void
    {
        parent::connect();
        if (isset($this->config['schema']) && !$this->isConnected()) {
            $this->query("SET search_path TO {$this->config['schema']}");
        }
    }
}
