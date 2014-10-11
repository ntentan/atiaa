<?php
namespace ntentan\atiaa;

abstract class Descriptor
{   
    /**
     *
     * @var \ntentan\atiaa\Driver;
     */
    protected $driver;
    
    public function __construct($driver)
    {
        $this->driver = $driver;
    }
    
    abstract protected function getSchemata();
    abstract protected function getTables($schema);
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
    
    private function describeTables($schema)
    {
        $description = array();
        $tables = $this->getTables($schema);
        
        foreach($tables as $table)
        {
            $table['columns'] = $this->describeColumns($table);
            $table['primary_key'] = $this->describePrimaryKey($table); //Constraint($table, 'PRIMARY KEY');
            $table['unique_keys'] = $this->describeUniqueKeys($table); //Constraint($table, 'UNIQUE');
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
        foreach($columnDetails as $i => $column)
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

