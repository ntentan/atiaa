<?php
namespace ntentan\atiaa\tests\lib;

trait DriverLoader
{
    /**
     * 
     * @return \ntentan\atiaa\Driver
     */
    public function getDriver()
    {
        $driver = \ntentan\atiaa\Driver::getConnection(
            array(
                'driver' => getenv('ATIAA_DRIVER'),
                'host' => getenv('ATIAA_HOST'),
                'user' => getenv('ATIAA_USER'),
                'password' => getenv('ATIAA_PASSWORD'),
                'file' => getenv('ATIAA_FILE'),
                'dbname' => getenv("ATIAA_DBNAME")
            )
        );
        return $driver;        
    }
    
    public function getDriverName()
    {
        return getenv('ATIAA_DRIVER');
    }
}
