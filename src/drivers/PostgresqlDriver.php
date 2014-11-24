<?php
namespace ntentan\atiaa\drivers;

class PostgresqlDriver extends \ntentan\atiaa\Driver
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
}
