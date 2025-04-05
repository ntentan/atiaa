<?php
namespace ntentan\atiaa;

use ntentan\atiaa\exceptions\ConnectionException;
use ntentan\atiaa\exceptions\DatabaseDriverException;
use PDO;
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
     */
    private \PDO|NullConnection $pdo;

    /**
     * The default schema used in the connection.
     */
    protected ?string $defaultSchema;

    /**
     * The connection parameters with which this connection was established.
     *
     * @var array
     */
    protected array $config;

    /**
     * An instance of the descriptor used internally.
     */
    private Descriptor $descriptor;

    private static int $transactionCount = 0;

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

    public function connect(): void
    {
        $username = $this->config['user'] ?? null;
        $password = $this->config['password'] ?? null;
        $this->defaultSchema = $this->config['schema'] ?? $this->defaultSchema ?? null;

        unset($this->config['schema']);   

        try {
            $options = $this->config['options'] ?? [];

            $options[PDO::ATTR_ERRMODE] = $options[PDO::ATTR_ERRMODE] ?? PDO::ERRMODE_EXCEPTION;
            $options[PDO::ATTR_EMULATE_PREPARES] = $options[PDO::ATTR_EMULATE_PREPARES] ?? false;
            $options[PDO::ATTR_STRINGIFY_FETCHES] = $options[PDO::ATTR_STRINGIFY_FETCHES] ?? false;

            $this->pdo = new \PDO(
                $this->getDriverName().':'.$this->expand($this->config), $username, $password, $options
            );
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
    public function disconnect(): void
    {
        $this->pdo = new NullConnection();
    }

    /**
     * Get the default schema of the current connection.
     *
     * @return string
     */
    public function getDefaultSchema(): string
    {
        return $this->defaultSchema;
    }

    /**
     * Use the PDO driver to quote a string.
     *
     * @throws ConnectionException
     * @return string
     */
    public function quote(string $string): string
    {
        return $this->getPDO()->quote($string);
    }


    private function fetchRows(\PDOStatement $statement): array
    {
        try {
            $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $rows;
        } catch (\PDOException $e) {
            return [];
        }
    }

    private function prepareQuery($query, $bindData): \PDOStatement
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
     * @throws DatabaseDriverException
     */
    public function query(string $query, array $bindData = []): array
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
        $rows = $this->fetchRows($statement);
        $statement->closeCursor();

        return $rows;
    }

    /**
     * Runs a query but ensures that all identifiers are properly quoted by calling
     * the Driver::quoteQueryIdentifiers method on the query before executing it.
     *
     * @throws DatabaseDriverException
     */
    public function quotedQuery(string $query, array $bindData = []): array
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
    private function expand(array $params): string
    {
        unset($params['driver']);
        if (isset($params['file'])) {
            if ($params['file'] != '') {
                return $params['file'];
            }
        }

        $equated = [];
        foreach ($params as $key => $value) {
            if ($value == '' || $key == 'options') {
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
    public function quoteQueryIdentifiers(string $query): string
    {
        return preg_replace_callback(
            '/\"([a-zA-Z\_ ]*)\"/',
            function ($matches) {
                return $this->quoteIdentifier($matches[1]);
            },
            $query
        );
    }

    /**
     * Returns an array description of the schema represented by the connection.
     * The description returns contains information about `tables`, `columns`, `keys`,
     * `constraints`, `views` and `indices`.
     */
    public function describe(): array
    {
        return $this->getDescriptor()->describe();
    }

    /**
     * Returns the description of a database table as an associative array.
     */
    public function describeTable(string $table): array
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
        if (self::$transactionCount) {
            $this->pdo->rollBack();
            self::$transactionCount = 0;
        }
    }

    /**
     * Return the underlying PDO object.
     */
    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    /**
     * Returns an instance of a descriptor for a given driver.
     */
    private function getDescriptor(): Descriptor
    {
        if (!isset($this->descriptor)) {
            $descriptorClass = '\\ntentan\\atiaa\\descriptors\\'.ucfirst($this->config['driver']).'Descriptor';
            $this->descriptor = new $descriptorClass($this);
        }

        return $this->descriptor;
    }

    /**
     * A wrapper around PDO's lastInsertId() method.
     */
    public function getLastInsertId(): mixed
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Specify the default schema to use in cases where a schema is not provided
     * as part of the table reference.
     */
    public function setDefaultSchema(string $defaultSchema)
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
