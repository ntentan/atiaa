<?php
namespace ntentan\atiaa\tests\lib;

abstract class DriverTest extends \PHPUnit_Framework_TestCase implements AtiaaTest
{    
    use DriverLoader;
    
    private function getDescriptor($driver)
    {
        $descriptorClass = "\\ntentan\\atiaa\\descriptors\\" . ucfirst($this->getDriverName()) . "Descriptor";
        $descriptor = new $descriptorClass($driver);            
        return $descriptor;
    }
    
    
    abstract protected function getQuotedString();
    abstract protected function getQuotedIdentifier();
    abstract protected function getQuotedQueryIdentifiers();
    abstract protected function hasSchemata();
    
    public function testFunctions()
    {
        $driver = $this->getDriver($this);
        $this->assertEquals($this->getQuotedString(), $driver->quote("string"));
        $this->assertEquals($this->getQuotedIdentifier(), $driver->quoteIdentifier("identifier"));
        $this->assertEquals($this->getQuotedQueryIdentifiers(), 
            $driver->quoteQueryIdentifiers('SELECT "some", "identifiers" FROM "some"."table"')
        );
        $pdo = $driver->getPDO();
        $this->assertInstanceOf("PDO", $pdo);
        $driver->disconnect();
    }
    
    public function testFullDescription()
    {
        $driver = $this->getDriver($this);
        $type = $this->getDriverName();
        
        $testDbDescription = $driver->describe();
        require "tests/fixtures/{$type}/database_description.php";
        $this->assertEquals($databaseDescription, $testDbDescription);
        $driver->disconnect();
    }
    
    public function testViewDescriptionAsTable()
    {
        $driver = $this->getDriver($this);
        $type = $this->getDriverName();
        
        $viewDbDescription = $driver->describeTable('users_view');
        require "tests/fixtures/{$type}/view_description.php";
        $this->assertEquals($viewDescription, $viewDbDescription);
                
        $driver->disconnect();
    }
    
    public function testStringSchema()
    {
        if(!$this->hasSchemata()) 
        {
            $this->markTestSkipped ();
            return;
        }
        
        $driver = $this->getDriver($this);
        $type = $this->getDriverName();
        
        $employeesDbDescription = $driver->describeTable('hr.employees');
        require "tests/fixtures/{$type}/employees_description.php";
        $this->assertEquals($employeesDescription, $employeesDbDescription);
                
        $driver->disconnect();
    }
    
    /**
     * @expectedException \ntentan\atiaa\TableNotFoundException
     */
    public function testTableNotFoundException()
    {
        $driver = $this->getDriver($this);
        $driver->describeTable('unknown_table');
        $driver->disconnect();
    }

    /**
     * @expectedException \ntentan\atiaa\TableNotFoundException
     */
    public function testTableNotFoundExceptionAgain()
    {
        $driver = $this->getDriver($this);
        $this->getDescriptor($driver)->describeTables($driver->getDefaultSchema(), array('users', 'unknown_table'));
        $driver->disconnect();
    }
    
    /**
     * @expectedException \ntentan\atiaa\DatabaseDriverException
     */
    public function testFaultyQueryException()
    {
        $driver = $this->getDriver($this);
        $driver->query("SPELECT * FROM dummy");
        $driver->disconnect();
    }
    
    /**
     * @expectedException \ntentan\atiaa\DatabaseDriverException
     */    
    public function testDisconnect()
    {
        $driver = $this->getDriver($this);
        $driver->disconnect();
        $driver->query("SELECT * FROM users");
    }
}
