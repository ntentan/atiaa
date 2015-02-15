<?php
namespace ntentan\atiaa\tests\lib;

abstract class TransactionsTest extends \PHPUnit_Extensions_Database_TestCase implements AtiaaTest
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
    
    
    protected function getSetUpOperation()
    {
        return \PHPUnit_Extensions_Database_Operation_Factory::DELETE_ALL();
    }    
}
