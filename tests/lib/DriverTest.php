<?php
namespace ntentan\atiaa\tests\lib;

abstract class DriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \ntentan\atiaa\Driver
     */
    protected $driver;
    
    public function setup()
    {
        $this->driver = \ntentan\atiaa\Driver::getConnection($this->getDriverConfig());
    }
    
    public function tearDown() 
    {
        $this->driver->disconnect();
    }
    
    abstract protected function getQuotedString();
    abstract protected function getQuotedIdentifier();
    abstract protected function getDriverConfig();
    abstract protected function getQuotedQueryIdentifiers();
    
    public function testQuoting()
    {
        $this->assertEquals($this->getQuotedString(), $this->driver->quote("string"));
        $this->assertEquals($this->getQuotedIdentifier(), $this->driver->quoteIdentifier("identifier"));
        $this->assertEquals($this->getQuotedQueryIdentifiers(), 
            $this->driver->quoteQueryIdentifiers('SELECT "some", "identifiers" FROM "some"."table"')
        );
    }
}
