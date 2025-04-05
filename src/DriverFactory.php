<?php

namespace ntentan\atiaa;

use ntentan\utils\Text;

class DriverFactory
{
    private array $config;

    /**
     * Create an instance of the driver factory with the connection configurations.
     */
    public function __construct(array $dbConfig)
    {
        $this->config = $dbConfig;
    }

    /**
     * Set or replace the configuration found in the factory.
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * Return the configuration currently stored in the factory.
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Create a new driver based on the configuration in the factory.
     */
    public function createDriver(): Driver
    {
        $classname = '\ntentan\atiaa\drivers\\'.Text::ucamelize($this->config['driver']).'Driver';
        return new $classname($this->config);
    }
}
