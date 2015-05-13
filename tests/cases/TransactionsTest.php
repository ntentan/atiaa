<?php
namespace ntentan\atiaa\tests\cases;
use ntentan\atiaa\tests\lib\DriverLoader;

class TransactionsTest extends \PHPUnit_Extensions_Database_TestCase
{   
    use DriverLoader;
    
    public function getDataSet() 
    {
        return $this->createFlatXMLDataSet("tests/fixtures/xml/transactions.xml");
    }
    
    public function testTransactions()
    {
        $driver = $this->getDriver($this);
        $this->assertEquals(0, $this->getConnection()->getRowCount('roles'));
        $driver->beginTransaction();
        $driver->query("INSERT INTO roles(name) VALUES(?)", array('hello'));
        $driver->commit();
        $this->assertEquals(1, $this->getConnection()->getRowCount('roles'));
    }

    public function testTransactionsRollback()
    {
        $driver = $this->getDriver($this);
        $this->assertEquals(0, $this->getConnection()->getRowCount('roles'));
        $driver->beginTransaction();
        $driver->query("INSERT INTO roles(name) VALUES(?)", array('hello'));
        $driver->rollback();
        $this->assertEquals(0, $this->getConnection()->getRowCount('roles'));
    }
    
    public function getConnection() 
    {
        $pdo = new \PDO(getenv('ATIAA_PDO_DSN'), getenv('ATIAA_USER'), getenv('ATIAA_PASSWORD'));
        return $this->createDefaultDBConnection($pdo);
    }
    
    protected function getSetUpOperation()
    {
        if($GLOBALS['TEST_SCOPE'] === 'local')
        {
            return \PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL();
        }
    }    
}
