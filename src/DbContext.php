<?php

namespace ntentan\atiaa;

use ntentan\panie\Container;
use ntentan\utils\Text;

class DbContext {

    private $container;
    private $config;

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
        return $this->container->singleton(Driver::class, ['config' => $this->config]);
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
