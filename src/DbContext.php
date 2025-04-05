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
 * Holds a database connection instance.
 */
final class DbContext
{
    /**
     * The driver held by the context.
     */
    private Driver $driver;

    /**
     * Singleton instance of the context.
     */
    private static self $instance;

    /**
     * DBContext constructor.
     */
    private function __construct(Driver $driver)
    {
        $this->driver = $driver;
        self::$instance = $this;
    }

    /**
     * Create a new database context.
     */
    public static function initialize(DriverFactory $driverFactory): DbContext
    {
        self::$instance = new self($driverFactory->createDriver());
        return self::$instance;
    }

    /**
     * Get the current singleton instance of the context.
     *
     * @throws \Exception
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
     * @throws exceptions\ConnectionException
     */
    public function getDriver(): Driver
    {
        if (!$this->driver->isConnected()) {
            $this->driver->connect();
        }
        return $this->driver;
    }

    /**
     * Run a query on the database driver.
     *
     * @throws exceptions\ConnectionException
     * @throws exceptions\DatabaseDriverException
     */
    public function query(string $query, array $bindData = []): array
    {
        return $this->getDriver()->query($query, $bindData);
    }

    /**
     * Destroy the context.
     *
     * @throws exceptions\ConnectionException
     */
    public static function destroy(): void
    {
        self::$instance->getDriver()->disconnect();
    }

    public static function beginTransaction(): void
    {
        self::$instance->getDriver()->beginTransaction();
    }

    public static function commitTransaction(): void
    {
        self::$instance->getDriver()->commit();
    }

    public static function rollbackTransaction(): void
    {
        self::$instance->getDriver()->rollback();
    }
}
