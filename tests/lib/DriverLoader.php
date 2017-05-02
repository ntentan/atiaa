<?php

namespace ntentan\atiaa\tests\lib;

use ntentan\atiaa\DbContext;
use ntentan\panie\Container;

trait DriverLoader {

    /**
     * 
     * @return \ntentan\atiaa\Driver
     */
    public function getDriver() {
        $container = new Container();
        $context = $container->resolve(DbContext::class,
            ['config' => [
                    'driver' => getenv('ATIAA_DRIVER'),
                    'host' => getenv('ATIAA_HOST'),
                    'user' => getenv('ATIAA_USER'),
                    'password' => getenv('ATIAA_PASSWORD'),
                    'file' => getenv('ATIAA_FILE'),
                    'dbname' => getenv("ATIAA_DBNAME")
                ]
            ]        
        );
        return $context->getDriver();
    }

    public function getDriverName() {
        return getenv('ATIAA_DRIVER');
    }

}
