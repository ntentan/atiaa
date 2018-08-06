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
    public function __construct(array $config = null)
    {
        $this->config = $config;
    }

    /**
     * Set or replace the configuration found in the factory.
     *
     * @param $config
     */
    public function setConfig($config) : void
    {
        $this->config = $config;
    }

    /**
     * Return the configuration currently stored in the factory.
     *
     * @return array
     */
    public function getConfig() : array
    {
        return $this->config;
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