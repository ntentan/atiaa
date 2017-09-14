<?php

namespace ntentan\atiaa;

use ntentan\utils\Text;

class DriverFactory
{
    private $config;

    /**
     * Create an instance of the driver factory with the connection configurations.
     * 
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Create a new driver based on the configuration in the factory.
     * 
     * @return \ntentan\atiaa\Driver
     */
    public function createDriver() : Driver
    {
        $classname = '\ntentan\atiaa\drivers\\' . Text::ucamelize($this->config['driver']) . "Driver";
        return new $classname($this->config);
    }
}