<?php

namespace ntentan\atiaa;

use ntentan\utils\Text;
use Psr\Log\LoggerInterface;

class DriverFactory
{
    private array $config;

    private ?LoggerInterface $logger;

    /**
     * Create an instance of the driver factory with the connection configurations.
     */
    public function __construct(array $dbConfig, ?LoggerInterface $logger = null)
    {
        $this->config = $dbConfig;
        $this->logger = $logger;
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
        $debugMode = $this->config['debug'] ?? false;
        unset($this->config['debug']);
        $driver = new $classname($this->config);
        if ($debugMode && $this->logger !== null) {
            $driver->setLogger($this->logger);
        }
        return $driver;
    }
}
