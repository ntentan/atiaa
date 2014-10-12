<?php

namespace ntentan\atiaa;

/**
 * The abstract class used by Atiaa.
 */
abstract class Driver
{
    /**
     * The internal PDO connection that is wrapped by this driver.
     * @var \PDO
     */
    private $pdo;
    
    /**
     * The default schema used in the connection.
     * @var string
     */
    protected $defaultSchema;
    
    /**
     * The connection parameters with which this connection was established.
     * @var array
     */
    private $config;
    
    /**
     * Creates a new instance of the Atiaa driver. To create a new instance of
     * this class you are advised to use the Atiaa class.
     * 
     * @param array $config The configuration with which to connect to the database.
     */
    public function __construct($config) 
    {
        $this->config = $config;
        $username = $config['user'];
        $password = $config['password'];
        
        unset($config['driver']);
        
        $this->pdo = new \PDO(
            $this->getDriverName() . ":" . $this->expand($config),
            $username,
            $password
        );
    }
    
    /**
     * Close a connection to the database server.
     */
    public function disconnect()
    {
        $this->pdo = null;
    }
    
    /**
     * Get the default schema of the current connection.
     * @return string
     */
    public function getDefaultSchema()
    {
        return $this->defaultSchema;
    }
    
    /**
     * Use the PDO driver to quote a string.
     * @param type $string
     * @return type
     */
    protected function quote($string)
    {
        return $this->pdo->quote($string);
    }
    
    /**
     * @param \PDOStatement $result
     * @param boolean $status 
     */
    private function fetchRows($status, $result)
    {
        if($status !== false)
        {
            $rows = $result->fetchAll(\PDO::FETCH_ASSOC);
        }
        else
        {
            $errorInfo = $this->pdo->errorInfo();
            throw new DatabaseDriverException("{$errorInfo[2]}. Query [$query]");
        }
        return $rows;
    }
    
    /**
     * Pepare and execute a query while binding data at the same time. Prevents
     * the writing of repetitive prepare and execute statements. This method
     * returns an array which contains the results of the query that was
     * executed. For queries which do not return any results a null is returned.
     * 
     * @todo Add a parameter to cache prepared statements so they can be reused easily.
     * 
     * @param string $query The query to be executed quoted in PDO style
     * @param array $bindData The data to be bound to the query object.
     * @return array
     */
    public function query($query, $bindData = false)
    {
        $return = array();
        
        if(is_array($bindData))
        {
            $statement = $this->pdo->prepare($query);
            $status = $statement->execute($bindData);
            $return = $this->fetchRows($status, $statement);
        }
        else
        {
            $result = $this->pdo->query($query);
            $return = $this->fetchRows($result, $result);
        }
        
        return $return;
    }
    
    /**
     * Runs a query but ensures that all identifiers are properly quoted by calling
     * the Driver::quoteQueryIdentifiers method on the query before executing it.
     * 
     * @param string $query
     * @param type $bindData
     * @return type
     */
    public function quotedQuery($query, $bindData = false)
    {
        return $this->query($this->quoteQueryIdentifiers($query), $bindData);
    }
    
    /**
     * Expands the configuration array into a format that can easily be passed
     * to PDO.
     * 
     * @param array $params The query parameters
     * @return string
     */
    private function expand($params)
    {
        $equated = array();
        foreach($params as $key => $value)
        {
            if($value == '') { continue; }
            $equated[] = "$key=$value";
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
     * @return string
     */
    public function quoteQueryIdentifiers($query)
    {
        return preg_replace_callback(
            '/\"([a-zA-Z\_ ]*)\"/', 
            function($matches) {
                return $this->quoteIdentifier($matches[1]);
            }, 
            $query
        );
    }
    
    /**
     * Returns an array description of the schema represented by the connection.
     * The description returns contains information about tables, columns, keys,
     * constraints, views and indices.
     * 
     * @return array
     */
    public function describe()
    {
        $descriptorClass = "\\ntentan\\atiaa\\descriptors\\" . ucfirst($this->config['driver']) . "Descriptor";
        $descriptor = new $descriptorClass($this);
        return $descriptor->describe();
    }
    
    /**
     * Return the underlying PDO object.
     * @return \PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }
    
    abstract protected function getDriverName();
    abstract protected function quoteIdentifier($identifier);
}
