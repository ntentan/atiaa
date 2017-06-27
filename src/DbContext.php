<?php

namespace ntentan\atiaa;

use ntentan\panie\Container;
use ntentan\utils\Text;

class DbContext {

    private $container;
    private $config;
    private $driver;

    public function __construct(Container $container, array $config) {
        $container->bind(Driver::class)
            ->to(self::getDriverClassName($config['driver']));
        $this->container = $container;
        $this->config = $config;
    }

    /**
     * 
     * @return Driver
     */
    public function getDriver() {
        if(is_null($this->driver)) {
            $this->driver = $this->container->resolve(Driver::class, ['config' => $this->config]);
        }
        return $this->driver;
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
