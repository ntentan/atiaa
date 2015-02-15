<?php

namespace ntentan\atiaa\tests\cases;

class MysqlTest extends \ntentan\atiaa\tests\lib\DriverTest
{
    public function getDriverName() 
    {
        return "mysql";
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

    protected function hasSchemata() 
    {
        return false;
    }
}
