<?php

namespace ntentan\atiaa;

use ntentan\panie\Container;
use ntentan\utils\Text;

/**
 * Holds the a database connection instance.
 */
class DbContext
{

    /**
     * The driver held by the context.
     *
     * @var Driver
     */
    private $driver;

    /**
     * The factory responsible for creating the driver when needed.
     *
     * @var DriverFactory
     */
    private $driverFactory;

    /**
     * Singleton instance of the context.
     *
     * @var DbContext
     */
    private static $instance;

    /**
     * DBContext constructor
     *
     * @param DriverFactory $driverFactory
     */
    private function __construct(DriverFactory $driverFactory)
    {
        $this->driverFactory = $driverFactory;
        self::$instance = $this;
    }

    /**
     * Create a new database context.
     *
     * @param DriverFactory $driverFactory
     * @return DbContext
     */
    public static function initialize(DriverFactory $driverFactory)
    {
        self::$instance = new self($driverFactory);
        return self::$instance;
    }

    /**
     * Get the current singleton instance of the context.
     *
     * @return DbContext
     */
    public static function getInstance(): DbContext
    {
        if(!self::$instance) {
            throw new \Exception("The database context has not been initialized");  
        }
        return self::$instance;
    }

    /**
     * Get the Driver instance wrapped in the context.
     *
     * @return Driver
     */
    public function getDriver() : Driver
    {
        if (is_null($this->driver)) {
            $this->driver = $this->driverFactory->createDriver();
            $this->driver->connect();
        }
        return $this->driver;
    }

    /**
     * Run a query on the database driver.
     *
     * @param string $query
     * @param array $bindData
     * @return array
     */
    public function query($query, $bindData = [])
    {
        return $this->getDriver()->query($query, $bindData);
    }

    /**
     * Destroy the context.
     *
     * @return void
     */
    public static function destroy()
    {
        self::$instance->getDriver()->disconnect();
        self::$instance = null;
    }

}
