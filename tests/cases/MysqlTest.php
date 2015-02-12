<?php

namespace ntentan\atiaa\tests\cases;

class MysqlTest extends \ntentan\atiaa\tests\lib\DriverTest
{
    protected function getDriverConfig() 
    {
        return array(
           'driver' => 'mysql',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost',
            'dbname' => 'test'
        );
    }

    protected function getQuotedString() 
    {
        return "'string'";
    }
    
    protected function getQuotedIdentifier()
    {
        return "`identifier`";
    }

    protected function getQuotedQueryIdentifiers() 
    {
        return "SELECT `some`, `identifiers` FROM `some`.`table`";
    }

}

