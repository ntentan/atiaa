<?php
namespace ntentan\atiaa\drivers;

use ntentan\atiaa\Driver;

/**
 * PostgreSQL driver implementation.
 */
class PostgresqlDriver extends Driver
{
    protected $defaultSchema = 'public';

    protected function getDriverName()
    {
        return 'pgsql';
    }

    public function quoteIdentifier($identifier)
    {
        return "\"$identifier\"";
    }

    public function getLastInsertId()
    {
        $lastval = $this->query('SELECT LASTVAL() as last');
        return $lastval[0]['last'];
    }
}
