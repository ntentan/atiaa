<?php
namespace ntentan\atiaa\tests\cases;
use ntentan\atiaa\tests\lib\DriverLoader;

class DriverTest extends \PHPUnit_Framework_TestCase
{    
    use DriverLoader;
    
    private function getDescriptor($driver)
    {
        $descriptorClass = "\\ntentan\\atiaa\\descriptors\\" . ucfirst($this->getDriverName()) . "Descriptor";
        $descriptor = new $descriptorClass($driver);            
        return $descriptor;
    }
    
    public function testFunctions()
    {
        $driverName = $this->getDriverName();
        $driver = $this->getDriver($this);
        
        $strings = json_decode(file_get_contents("tests/fixtures/$driverName/strings.json"), true);
        
        $this->assertEquals($strings['quoted_string'], $driver->quote("string"));
        $this->assertEquals($strings['quoted_identifier'], $driver->quoteIdentifier("identifier"));
        $this->assertEquals($strings['quoted_query_identifiers'], 
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
        $views = $testDbDescription['views'];
        unset($testDbDescription['views']);
        require "tests/fixtures/{$type}/database_description.php";
        $this->assertEquals($databaseDescription, $testDbDescription);
        $this->assertArrayHasKey('users_view', $views);
        $this->assertArrayHasKey('definition', $views['users_view']);
        $this->assertEquals('users_view', $views['users_view']['name']);
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
    
    private function hasSchemata()
    {
        return strtolower(getenv('ATIAA_HAS_SCHEMAS')) === 'yes';
    }
}
