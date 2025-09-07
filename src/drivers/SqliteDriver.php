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
        if (!$this->isConnected()) {
            // Connection is still in progress and an Exception has not been thrown.
            $this->query('PRAGMA foreign_keys=ON');
        }
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
