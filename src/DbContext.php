<?php

namespace ntentan\atiaa;

use ntentan\panie\Container;
use ntentan\utils\Text;

class DbContext
{

    private $driver;
    private $driverFactory;
    private static $instance;

    private function __construct(DriverFactory $driverFactory)
    {
        $this->driverFactory = $driverFactory;
        self::$instance = $this;
    }

    public static function initialize(DriverFactory $driverFactory)
    {
        self::$instance = new self($driverFactory);
        return self::$instance;
    }

    public static function getInstance(): DbContext
    {
        if(!self::$instance) {
            throw new \Exception("The database context has not been initialized");  
        }
        return self::$instance;
    }

    /**
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

    public function query($query, $bindData = false)
    {
        return $this->getDriver()->query($query, $bindData);
    }

    public static function destroy()
    {
        self::$instance->getDriver()->disconnect();
        self::$instance = null;
    }

}
