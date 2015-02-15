<?php
namespace ntentan\atiaa\tests\cases;

class MysqlTransactionsTest extends \ntentan\atiaa\tests\lib\TransactionsTest
{
    public function getDriverName() 
    {
        return "mysql";
    }
    
    public function getConnection() 
    {
        $pdo = new \PDO(
            "mysql:host={$GLOBALS['mysql_host']};dbname={$GLOBALS['mysql_dbname']};user={$GLOBALS['mysql_user']};password={$GLOBALS['mysql_password']}",
            $GLOBALS['mysql_user'], $GLOBALS['mysql_password']
        );
        return $this->createDefaultDBConnection($pdo);
    }    
}
