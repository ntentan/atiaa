<?php

namespace ntentan\atiaa;

use ntentan\utils\Text;
use Psr\Log\LoggerInterface;

/**
 * Factory class responsible for creating and configuring database driver instances.
 *
 * Resolves driver classes dynamically from the connection configuration or uses
 * an explicitly provided custom driver class, configuring debug logging when enabled.
 *
 * @package ntentan\atiaa
 */
class DefaultDriverFactory implements DriverFactoryInterface
{
    /**
     * Database connection configuration parameters.
     *
     * @var array
     */
    private array $config;

    /**
     * PSR-3 logger instance for database debugging and query logging.
     *
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger;

    /**
     * Fully qualified class name of an explicit driver class to instantiate.
     *
     * @var string|null
     */
    private ?string $driverClass;

    /**
     * Create an instance of the driver factory with connection configurations.
     *
     * @param array $dbConfig Associative array of database configuration options.
     * @param string|null $driverClass Optional fully qualified driver class name to override automatic resolution.
     * @param LoggerInterface|null $logger Optional PSR-3 logger instance.
     */
    public function __construct(array $dbConfig, ?string $driverClass = null, ?LoggerInterface $logger = null)
    {
        $this->config = $dbConfig;
        $this->driverClass = $driverClass;
        $this->logger = $logger;
    }

    /**
     * Set or replace the configuration stored in the factory.
     *
     * @param array $config New database connection configuration parameters.
     * @return void
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * Return the configuration currently stored in the factory.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Create and configure a new database driver based on the factory configuration.
     *
     * Resolves the driver class name, initializes the driver instance with the connection
     * parameters, and attaches the logger if debug mode is active.
     *
     * @return Driver The configured database driver instance.
     */
    public function createDriver(): Driver
    {
        $classname = $this->driverClass ?? '\ntentan\atiaa\drivers\\' . Text::ucamelize($this->config['driver']) . 'Driver';
        $debugMode = $this->config['debug'] ?? false;
        unset($this->config['debug']);
        $driver = new $classname($this->config);
        if ($debugMode && $this->logger !== null) {
            $driver->setLogger($this->logger);
        }
        return $driver;
    }
}

