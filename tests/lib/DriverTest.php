<?php
namespace ntentan\atiaa\tests\lib;

abstract class DriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 
     * @return \ntentan\atiaa\Driver;
     */
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
    
    private function getDescriptor($driver)
    {
        $descriptorClass = "\\ntentan\\atiaa\\descriptors\\" . ucfirst($this->getDriverName()) . "Descriptor";
        $descriptor = new $descriptorClass($driver);            
        return $descriptor;
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
        
        $testDbDescription = $driver->describe();
        require "tests/outputs/database_description.php";
        $this->assertEquals($databaseDescription, $testDbDescription);
        
        $viewDbDescription = $driver->describeTable('users_view');
        require "tests/outputs/view_description.php";
        $this->assertEquals($viewDescription, $viewDbDescription);
        
        $driver->disconnect();
    }
    
    /**
     * @expectedException \ntentan\atiaa\TableNotFoundException
     */
    public function testTableNotFoundException()
    {
        $driver = $this->getConnection();
        $driver->describeTable('unknown_table');
        $driver->disconnect();
    }

    /**
     * @expectedException \ntentan\atiaa\TableNotFoundException
     */
    public function testTableNotFoundExceptionAgain()
    {
        $driver = $this->getConnection();
        $this->getDescriptor($driver)->describeTables($driver->getDefaultSchema(), array('users', 'unknown_table'));
        $driver->disconnect();
    }
}
