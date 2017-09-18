<?php

namespace ntentan\atiaa\tests\lib;

use ntentan\atiaa\DbContext;
use ntentan\atiaa\DriverFactory;

trait DriverLoader {

    /**
     * 
     * @return \ntentan\atiaa\Driver
     */
    public function getDriver() {
        $factory = new DriverFactory([
            'driver' => getenv('ATIAA_DRIVER'),
            'host' => getenv('ATIAA_HOST'),
            'user' => getenv('ATIAA_USER'),
            'password' => getenv('ATIAA_PASSWORD'),
            'file' => getenv('ATIAA_FILE'),
            'dbname' => getenv("ATIAA_DBNAME")
        ]);
        $driver = $factory->createDriver();
        $driver->connect();
        return $driver;
    }

    public function getDriverName() {
        return getenv('ATIAA_DRIVER');
    }

}
