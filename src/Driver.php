<?php

namespace ntentan\atiaa;

abstract class Driver
{
    /**
     *
     * @var \PDO
     */
    private $pdo;
    protected $defaultSchema;
    private $config;
    
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
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    public function disconnect()
    {
        $this->pdo = null;
    }
    
    public function getDefaultSchema()
    {
        return $this->defaultSchema;
    }
    
    protected function quote($string)
    {
        return $this->pdo->quote($string);
    }
    
    public function query($query, $bindData = false)
    {
        $return = array();
        
        if(is_array($bindData))
        {
            $statement = $this->pdo->prepare($query);
            $statement->execute($bindData);
            $return = $statement->fetchAll(\PDO::FETCH_ASSOC);
        }
        else
        {
            $result = $this->pdo->query($query, \PDO::FETCH_ASSOC);
            $return = $result->fetchAll();
        }
        
        return $return;
    }
    
    public function quotedQuery($query, $bindData = false)
    {
        return $this->query($this->quoteQueryIdentifiers($query), $bindData);
    }
    
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
    
    public function describe()
    {
        $descriptorClass = "\\ntentan\\atiaa\\descriptors\\" . ucfirst($this->config['driver']) . "Descriptor";
        $descriptor = new $descriptorClass($this);
        return $descriptor->describe();
    }
    
    abstract protected function getDriverName();
    abstract protected function quoteIdentifier($identifier);
}
