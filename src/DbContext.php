<?php

/*
 * The MIT License
 *
 * Copyright 2014-2018 James Ekow Abaka Ainooson
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace ntentan\atiaa;

/**
 * Holds the a database connection instance.
 */
final class DbContext
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
     * DBContext constructor.
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
     *
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
     * @throws \Exception
     *
     * @return DbContext
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            throw new \Exception('The database context has not been initialized');
        }

        return self::$instance;
    }

    /**
     * Get the Driver instance wrapped in the context.
     *
     * @throws exceptions\ConnectionException
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
     * @param array  $bindData
     *
     * @throws exceptions\ConnectionException
     * @throws exceptions\DatabaseDriverException
     *
     * @return array
     */
    public function query($query, $bindData = [])
    {
        return $this->getDriver()->query($query, $bindData);
    }

    /**
     * Destroy the context.
     *
     * @throws exceptions\ConnectionException
     *
     * @return void
     */
    public static function destroy()
    {
        self::$instance->getDriver()->disconnect();
        self::$instance = null;
    }
}
