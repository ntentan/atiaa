<?php

namespace ntentan\atiaa;

use ntentan\config\Config;
use ntentan\panie\Container;
use ntentan\utils\Text;

class DbContext {

    private $container;

    public function __construct(Container $container, $config = null) {
        if ($config) {
            Config::set('ntentan:db', $config);
            $container->bind(Driver::class)
                ->to(self::getDriverClassName(Config::get('ntentan:db.driver')));
        }
        $this->container = $container;
    }

    /**
     * 
     * @return Driver
     */
    public function getDriver() {
        return $this->container->singleton(Driver::class);
    }

    public static function getDriverClassName($driver) {
        if ($driver == null) {
            throw new exceptions\DatabaseDriverException(
            "Please provide a valid driver name in your database config file"
            );
        }
        return '\ntentan\atiaa\drivers\\' . Text::ucamelize($driver) . "Driver";
    }

    public function query($query, $bindData = false) {
        return $this->getDriver()->query($query, $bindData);
    }

}
