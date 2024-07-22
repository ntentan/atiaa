<?php
namespace ntentan\atiaa\drivers;

/**
 * SQLite driver implementation.
 */
class SqliteDriver extends \ntentan\atiaa\Driver
{
    public function __construct($config = null)
    {
        $this->defaultSchema = 'main';
        parent::__construct($config);
    }

    public function connect()
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
