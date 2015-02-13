<?php
namespace ntentan\atiaa\tests\lib;

abstract class DriverTest extends \PHPUnit_Framework_TestCase
{
    
    protected function getConnection()
    {
        $driverName = $this->getDriverName();
        $driver = \ntentan\atiaa\Driver::getConnection(
            array(
                'driver' => $driverName,
                'host' => $GLOBALS["{$driverName}_host"],
                'user' => $GLOBALS["{$driverName}_user"],
                'password' => $GLOBALS["{$driverName}_password"],
                'dbname' => $GLOBALS["{$driverName}_dbname"]
            )
        );
        return $driver;
    }
    
    abstract protected function getQuotedString();
    abstract protected function getQuotedIdentifier();
    abstract protected function getDriverName();
    abstract protected function getQuotedQueryIdentifiers();
    
    public function testQuoting()
    {
        $driver = $this->getConnection();
        $this->assertEquals($this->getQuotedString(), $driver->quote("string"));
        $this->assertEquals($this->getQuotedIdentifier(), $driver->quoteIdentifier("identifier"));
        $this->assertEquals($this->getQuotedQueryIdentifiers(), 
            $driver->quoteQueryIdentifiers('SELECT "some", "identifiers" FROM "some"."table"')
        );
        $driver->disconnect();
    }
    
    public function testDescription()
    {
        $driver = $this->getConnection();
        $testDescription = $driver->describe();
        require "tests/outputs/description.php";
        $this->assertEquals($description, $testDescription);
        $driver->disconnect();
    }
}
