<?php
namespace ntentan\atiaa\drivers;

use ntentan\atiaa\Driver;

/**
 * SQLite driver implementation.
 */
class SqliteDriver extends Driver
{
    public function __construct($config = null)
    {
        $this->defaultSchema = 'main';
        parent::__construct($config);
    }

    #[\Override]
    public function connect(): void
    {
        parent::connect();
        $this->query('PRAGMA foreign_keys=ON');
    }

    protected function getDriverName()
    {
        return 'sqlite';
    }

    public function quoteIdentifier($identifier)
    {
        return "\"$identifier\"";
    }
}
