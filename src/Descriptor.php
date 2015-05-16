<?php
namespace ntentan\atiaa;

use ntentan\atiaa\TableNotFoundException;

/**
 * Does the job of describing the schema of the underlying database. This 
 * abstract class is usually extended by database platform specific descriptor
 * classes which provide the details of the actual database items. The main work
 * of this class is to format the description into a common format.
 */
abstract class Descriptor
{   
    /**
     * An instance of the database driver used for accessing the database
     * system.
     * @var \ntentan\atiaa\Driver;
     */
    protected $driver;
    
    public function __construct($driver)
    {
        $this->driver = $driver;
    }
    
    /**
     * Returns a list of schemata available on the database.
     * @return array
     */
    abstract protected function getSchemata();
    
    /**
     * Retrieve the names of all the tables in a given schema. 
     * The array returned is a list of structured arrays which have `name`
     * and `schema` as keys. The `name` key represents the name of the table and
     * the `schema` key represents the name of the schema (which is the same
     * as the schema which was passed to the function.
     * 
     * @param string $schema The name of the schema from which the tab
     * @param array<string> An array contianing names of specific tables 
     *     who's information should be retrieved. 
     * @return array<array>  
     */
    abstract protected function getTables($schema, $requestedTables, $includeViews);
    
    /**
     * Retrieve all the columns available in a given table. 
     * The array returned contains structured arrays with the following keys.
     */
    abstract protected function getColumns(&$table);
    abstract protected function getViews(&$schema);
    abstract protected function getPrimaryKey(&$table);
    abstract protected function getUniqueKeys(&$table);
    abstract protected function getForeignKeys(&$table);
    abstract protected function getIndices(&$table);
    abstract protected function hasAutoIncrementingKey(&$table);

    public function describe()
    {
        $defaultSchema = $this->driver->getDefaultSchema();
        $description = array(
            'schemata' => array(),
        );
        
        $schemata = $this->getSchemata();
        
        foreach($schemata as $schema)
        {
            if($schema['name'] == $defaultSchema)
            {
                $description['tables'] = $this->describeTables($defaultSchema);
                $description['views'] = $this->describeViews($defaultSchema);
            }
            else
            {
                $description['schemata'][$schema['name']]['name'] = $schema['name'];
                $description['schemata'][$schema['name']]['tables'] = $this->describeTables($schema['name']);                
                $description['schemata'][$schema['name']]['views'] = $this->describeViews($schema['name']);                
            }
        }
        
        return $description;       
    }
    
    private function throwTableExceptions($tables, $requestedTables)
    {
        $foundTables = array();
        foreach($tables as $table)
        {
            $foundTables[] = $table['name'];
        }
        
        foreach($requestedTables as $requestedTable)
        {
            if(array_search($requestedTable, $foundTables) === false)
            {
                throw new TableNotFoundException("$requestedTable not found on target database.");
            }
        }
    }
    
    public function describeTables($schema, $requestedTables = array(), $includeViews = false)
    {
        $description = array();
        $tables = $this->getTables($schema, $requestedTables, $includeViews);
        
        if(count($requestedTables) > 0 && count($tables) < count($requestedTables))
        {
            $this->throwTableExceptions($tables, $requestedTables);
        }
        
        foreach($tables as $table)
        {
            $table['columns'] = $this->describeColumns($table);
            $table['primary_key'] = $this->describePrimaryKey($table);
            $table['unique_keys'] = $this->describeUniqueKeys($table);
            $table['foreign_keys'] = $this->describeForeignKeys($table);
            $table['indices'] = $this->describeIndices($table);
            $table['auto_increment'] = $this->hasAutoIncrementingKey($table);
            $table['schema'] = $this->fixSchema($table['schema']);
            
            $description[$table['name']] = $table;
        }
        return $description;        
    }
    
    private function describeColumns($table)
    {
        $columns = array();
        $columnDetails = $this->getColumns($table);
        foreach($columnDetails as $column)
        {
            $columns[$column['name']] = $column;
            $columns[$column['name']]['nulls'] = $columns[$column['name']]['nulls'] == 'YES' ? true : false;
        }
        
        return $columns;        
    }
    
    private function describeViews($schema)
    {
        $description = array();
        $views = $this->getViews($schema);
        foreach($views as $view)
        {
            $description[$view['name']] = array(
                'name' => $view['name'],
                'schema' => $view['schema'],
                'definition' => $view['definition']
            );
        }
        return $description;        
    }
    
    private function describePrimaryKey($table)
    {
        return $this->describeKey($this->getPrimaryKey($table));
    }

    private function describeUniqueKeys($table)
    {
        return $this->describeKey($this->getUniqueKeys($table));
    }
    
    private function describeForeignKeys($table)
    {
        return $this->describeKey($this->getForeignKeys($table));
    }
    
    private function describeIndices($table)
    {
        return $this->describeKey($this->getIndices($table));
    }
    
    private function describeKey($constraintColumns)
    {
        $constraints = array();
        foreach($constraintColumns as $column)
        {
            $name = $column['name'];
            unset($column['name']);
            foreach($column as $key => $value)
            {
                if($key == 'column' || $key == 'foreign_column')
                {
                    $constraints[$name]["{$key}s"][] = $value;
                }
                else
                {
                    if($key === 'schema' || $key === 'foreign_schema') 
                    {
                        $value = $this->fixSchema($value);
                    }
                    $constraints[$name][$key] = $value;
                }
            }
        }
        return $constraints;    
    }
    
    private function fixSchema($schema)
    {
        $defaultSchema = $this->driver->getDefaultSchema();        
        if($schema == false || $schema == $defaultSchema)
        {
            return '';
        }
        else
        {
            return $schema;
        }
    }    
}
