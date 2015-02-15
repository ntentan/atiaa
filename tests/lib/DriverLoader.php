<?php
namespace ntentan\atiaa\tests\lib;

trait DriverLoader
{
    /**
     * 
     * @return \ntentan\atiaa\Driver;
     */    
    public static function getDriver($test)
    {
        $driverName = $test->getDriverName();
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
}

