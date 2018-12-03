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

use ntentan\atiaa\exceptions\ConnectionException;
use ntentan\atiaa\exceptions\DatabaseDriverException;
use Psr\Log\LoggerInterface;

/**
 * A driver class for connecting to a specific database platform.
 * The Driver class is the main wrapper for atiaa. The driver class contains
 * an instance of PDO with which it performs its operations. Aside from wrapping
 * around PDO it also provides methods which makes it possible to quote strings
 * and identifiers in a platform independent fashion. The driver class is
 * responsible for loading the descriptors which are used for describing the
 * database schemas.
 */
abstract class Driver
{
    /**
     * The internal PDO connection that is wrapped by this driver.
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * A logger for logging queries during debug.
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * The default schema used in the connection.
     *
     * @var string
     */
    protected $defaultSchema;

    /**
     * The connection parameters with which this connection was established.
     *
     * @var array
     */
    protected $config;

    /**
     * An instance of the descriptor used internally.
     *
     * @var \ntentan\atiaa\Descriptor
     */
    private $descriptor;
    private static $transactionCount = 0;

    /**
     * Creates a new instance of the Atiaa driver. This class is usually initiated
     * through the \ntentan\atiaa\Atiaa::getConnection() method. For example
     * to create a new instance of a connection to a mysql database.
     *
     * ````php
     * use ntentan\atiaa\Driver;
     *
     * \\ This automatically insitatiates the driver class
     * $driver = Driver::getConnection(
     *     array(
     *         'driver' => 'mysql',
     *         'user' => 'root',
     *         'password' => 'rootpassy',
     *         'host' => 'localhost',
     *         'dbname' => 'somedb'
     *     )
     * );
     *
     * var_dump($driver->query("SELECT * FROM some_table");
     * var_dump($driver->describe());
     * ````
     *
     * @param array <string> $config The configuration with which to connect to the database.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        $username = isset($this->config['user']) ? $this->config['user'] : null;
        $password = isset($this->config['password']) ? $this->config['password'] : null;

        try {
            $this->pdo = new \PDO($this->getDriverName().':'.$this->expand($this->config), $username, $password);
            $this->pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new ConnectionException("PDO failed to connect: {$e->getMessage()}");
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Close a connection to the database server.
     */
    public function disconnect()
    {
        $this->pdo = null;
        $this->pdo = new NullConnection();
    }

    /**
     * Get the default schema of the current connection.
     *
     * @return string
     */
    public function getDefaultSchema()
    {
        return $this->defaultSchema;
    }

    /**
     * Use the PDO driver to quote a string.
     *
     * @param type $string
     *
     * @throws ConnectionException
     *
     * @return string
     */
    public function quote($string)
    {
        return $this->getPDO()->quote($string);
    }

    /**
     * @param $statement
     *
     * @return mixed
     */
    private function fetchRows($statement)
    {
        try {
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

            return $rows;
        } catch (\PDOException $e) {
            // Skip any exceptions from fetching rows
        }
    }

    private function prepareQuery($query, $bindData)
    {
        $statement = $this->pdo->prepare($query);
        foreach ($bindData as $key => $value) {
            switch (gettype($value)) {
                case 'integer':
                case 'boolean': // casts to boolean seems unstable
                    $type = \PDO::PARAM_INT;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
                    break;
            }
            // Bind values while adjusting numerical indices to start from 1
            $statement->bindValue(is_numeric($key) ? $key + 1 : $key, $value, $type);
        }

        return $statement;
    }

    /**
     * Pepare and execute a query, while binding data at the same time. Prevents
     * the writing of repetitive prepare and execute statements. This method
     * returns an array which contains the results of the query that was
     * executed. For queries which do not return any results a null is returned.
     *
     * @todo Add a parameter to cache prepared statements so they can be reused easily.
     *
     * @param string $query    The query to be executed quoted in PDO style
     * @param array  $bindData
     *
     * @throws DatabaseDriverException
     *
     * @return array <mixed>
     */
    public function query($query, $bindData = [])
    {
        try {
            if (empty($bindData)) {
                $statement = $this->pdo->query($query);
            } else {
                $statement = $this->prepareQuery($query, $bindData);
                $statement->execute();
                $statement->errorCode();
            }
        } catch (\PDOException $e) {
            $boundData = json_encode($bindData);

            throw new DatabaseDriverException("{$e->getMessage()} [$query] [BOUND DATA:$boundData]");
        }
        if ($this->logger) {
            $this->logger->debug($query, $bindData);
        }
        $rows = $this->fetchRows($statement);
        $statement->closeCursor();

        return $rows;
    }

    /**
     * Runs a query but ensures that all identifiers are properly quoted by calling
     * the Driver::quoteQueryIdentifiers method on the query before executing it.
     *
     * @param string $query
     * @param bool   $bindData
     *
     * @throws DatabaseDriverException
     *
     * @return array <mixed>
     */
    public function quotedQuery($query, $bindData = [])
    {
        return $this->query($this->quoteQueryIdentifiers($query), $bindData);
    }

    /**
     * Expands the configuration array into a format that can easily be passed
     * to PDO.
     *
     * @param array $params The query parameters
     *
     * @return string
     */
    private function expand($params)
    {
        unset($params['driver']);
        if (isset($params['file'])) {
            if ($params['file'] != '') {
                return $params['file'];
            }
        }

        $equated = [];
        foreach ($params as $key => $value) {
            if ($value == '') {
                continue;
            } else {
                $equated[] = "$key=$value";
            }
        }

        return implode(';', $equated);
    }

    /**
     * This method provides a system independent way of quoting identifiers in
     * queries. By default all identifiers can be quoted with double quotes (").
     * When a query quoted with double quotes is passed through this method the
     * output generated has the double quotes replaced with the quoting character
     * of the target database platform.
     *
     * @param string $query
     *
     * @return string
     */
    public function quoteQueryIdentifiers($query)
    {
        return preg_replace_callback(
            '/\"([a-zA-Z\_ ]*)\"/', function ($matches) {
                return $this->quoteIdentifier($matches[1]);
            }, $query
        );
    }

    /**
     * Returns an array description of the schema represented by the connection.
     * The description returns contains information about `tables`, `columns`, `keys`,
     * `constraints`, `views` and `indices`.
     *
     * @return array<mixed>
     */
    public function describe()
    {
        return $this->getDescriptor()->describe();
    }

    /**
     * Returns the description of a database table as an associative array.
     *
     * @param string $table
     *
     * @return array<mixed>
     */
    public function describeTable($table)
    {
        $table = explode('.', $table);
        if (count($table) > 1) {
            $schema = $table[0];
            $table = $table[1];
        } else {
            $schema = $this->getDefaultSchema();
            $table = $table[0];
        }

        return $this->getDescriptor()->describeTables($schema, [$table], true);
    }

    /**
     * A wrapper arround PDO's beginTransaction method which uses a static reference
     * counter to implement nested transactions.
     */
    public function beginTransaction()
    {
        if (self::$transactionCount++ === 0) {
            $this->pdo->beginTransaction();
        }
    }

    /**
     * A wrapper around PDO's commit transaction method which uses a static reference
     * counter to implement nested transactions.
     */
    public function commit()
    {
        if (--self::$transactionCount === 0) {
            $this->pdo->commit();
        }
    }

    /**
     * A wrapper around PDO's rollback transaction methd which rolls back all
     * activities performed since the first call to begin transaction.
     * Unfortunately, transactions cannot be rolled back in a nested fashion.
     */
    public function rollback()
    {
        $this->pdo->rollBack();
        self::$transactionCount = 0;
    }

    /**
     * Return the underlying PDO object.
     *
     * @throws ConnectionException
     *
     * @return \PDO
     */
    public function getPDO()
    {
        if ($this->pdo === null) {
            throw new ConnectionException('A connection has not been established. Please call the connect() method.');
        }

        return $this->pdo;
    }

    /**
     * Returns an instance of a descriptor for a given driver.
     *
     * @return \ntentan\atiaa\Descriptor
     */
    private function getDescriptor()
    {
        if (!is_object($this->descriptor)) {
            $descriptorClass = '\\ntentan\\atiaa\\descriptors\\'.ucfirst($this->config['driver']).'Descriptor';
            $this->descriptor = new $descriptorClass($this);
        }

        return $this->descriptor;
    }

    /**
     * A wrapper around PDO's lastInsertId() method.
     *
     * @return mixed
     */
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Specify the default schema to use in cases where a schema is not provided
     * as part of the table reference.
     *
     * @param string $defaultSchema
     */
    public function setDefaultSchema($defaultSchema)
    {
        $this->defaultSchema = $defaultSchema;
    }

    abstract protected function getDriverName();

    abstract public function quoteIdentifier($identifier);

    public function setCleanDefaults($cleanDefaults)
    {
        $this->getDescriptor()->setCleanDefaults($cleanDefaults);
    }
}
