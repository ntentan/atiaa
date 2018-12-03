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
     *
     * @var \ntentan\atiaa\Driver;
     */
    protected $driver;
    private $cleanDefaults = false;

    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    /**
     * Returns a list of schemata available on the database.
     *
     * @return array
     */
    abstract protected function getSchemata();

    /**
     * Retrieve the names of all the tables in a given schema.
     * The array returned must be a list of structured arrays which have `name`
     * and `schema` as keys. The `name` key should represent the name of the table and
     * the `schema` key should represent the name of the schema (which is the same
     * as the schema which was passed to the function).
     *
     * @param string $schema The name of the schema whose tables should be
     *                       describled.
     * @param array<string> An array contianing names of specific tables
     *     who's descriptions should be retrieved.
     *
     * @return array<array>
     */
    abstract protected function getTables($schema, $requestedTables, $includeViews);

    /**
     * Retrieve descriptions of all the columns available in a given table as an array.
     * The array returned must contain structured arrays with the following keys.
     *
     * name
     * : The name of the column.
     *
     * type
     * : The system specific datatype of the column.
     *
     * nulls
     * : A boolean which is true for columsn which can contain null values
     *   and false for columns which can't.
     *
     * default
     * : The default value of the column. In cases where there is no default
     *   this column is set to null.
     *
     * length
     * : The maximum character lenght of the column.
     *
     * @param array $table An array which contains the name of the table as it's
     *                     `name` key and the schema of the table as it's `schema` key.
     *
     * @return array<array<string>>
     */
    abstract protected function getColumns(&$table);

    /**
     * Retrieve the descriptions of all the views of a given schema in a array.
     * The array returned must contain structured arrays with the following keys.
     *
     * name
     * : The name of the view.
     *
     * schema
     * : The schema to which the view belongs.
     *
     * definition
     * : The SQL query which represents the definition of the view.
     *
     * @param string $schema The name of the database schema
     *
     * @return array<array<string>>
     */
    abstract protected function getViews(&$schema);

    /**
     * Retrieve the description of a primary key on a given table.
     * The description returned must be an array which contains structured
     * arrays with the following keys.
     *
     * column
     * : The name of a column which is part of the primary key
     *
     * name
     * : The name of the primary key constraint (must be the same throughout
     *   all the items returned).
     *
     * For primary keys with multiple columns, the array returned would contain
     * one entry for each column.
     *
     * @param array $table An array which contains the name of the table as it's
     *                     `name` key and the schema of the table as it's `schema` key.
     *
     * @return array<array<string>>
     */
    abstract protected function getPrimaryKey(&$table);

    /**
     * Retrieve the description of unique keys on a given table.
     * The description returned must be an array which contains structured
     * arrays with the following keys.
     *
     * column
     * : The name of a column which is part of a unique key
     *
     * name
     * : The name of the unique key constraint.
     *
     * For unique keys with multiple columns, the value of the `name` key must
     * be the same for only the columns in the key.
     *
     * @param array $table An array which contains the name of the table as it's
     *                     `name` key and the schema of the table as it's `schema` key.
     *
     * @return array<array<string>>
     */
    abstract protected function getUniqueKeys(&$table);

    /**
     * Retrieve the description of foreign keys on a given table.
     * The description returned must be an array which contains structured
     * arrays with the following keys.
     *
     * name
     * : The name of the foreign key constraint.
     *
     * table
     * : The name of the database table (should be same as passed to the function)
     *
     * schema
     * : The schema of the database table (should be same as passed to the
     *   function)
     *
     * column
     * : The foreign key column on the table.
     *
     * foreign_table
     * : The name of the database table to be referenced.
     *
     * foreign_schema
     * : The schema which contains the database table to be referenced.
     *
     * foreign_column:
     * : The column to be refereced on the foreign table.
     *
     * For foreign keys with multiple columns, the value of the `name` key must
     * be the same for only the columns in the key.
     *
     * @param array $table An array which contains the name of the table as it's
     *                     `name` key and the schema of the table as it's `schema` key.
     *
     * @return array<array<string>>
     */
    abstract protected function getForeignKeys(&$table);

    /**
     * Retrieve the description of indices on a given table.
     * The description returned must be an array which contains structured
     * arrays with the following keys.
     *
     * column
     * : The name of a column which is part of an index
     *
     * name
     * : The name of the index.
     *
     * For unique keys with multiple columns, the value of the `name` key must
     * be the same for only the columns in the key.
     *
     * @param array $table An array which contains the name of the table as it's
     *                     `name` key and the schema of the table as it's `schema` key.
     *
     * @return array<array<string>>
     */
    abstract protected function getIndices(&$table);

    /**
     * Returns a boolean value which tells whether a table has an auto incrementing
     * key or not.
     *
     * @return bool
     */
    abstract protected function hasAutoIncrementingKey(&$table);

    /**
     * Returns the description of the database as an array.
     *
     * @return array
     */
    public function describe()
    {
        $defaultSchema = $this->driver->getDefaultSchema();
        $description = [
            'schemata' => [],
        ];

        $schemata = $this->getSchemata();

        foreach ($schemata as $schema) {
            if ($schema['name'] == $defaultSchema) {
                $description['tables'] = $this->describeTables($defaultSchema);
                $description['views'] = $this->describeViews($defaultSchema);
            } else {
                $description['schemata'][$schema['name']]['name'] = $schema['name'];
                $description['schemata'][$schema['name']]['tables'] = $this->describeTables($schema['name']);
                $description['schemata'][$schema['name']]['views'] = $this->describeViews($schema['name']);
            }
        }

        return $description;
    }

    public function setCleanDefaults($cleanDefaults)
    {
        $this->cleanDefaults = $cleanDefaults;
    }

    /**
     * Throws exceptions for which are found in the list of requested tables
     * but not found in the list of found tables.
     *
     * @param array $tables
     * @param array $requestedTables
     *
     * @throws exceptions\TableNotFoundException
     */
    private function throwTableExceptions($tables, $requestedTables)
    {
        $foundTables = [];
        foreach ($tables as $table) {
            $foundTables[] = $table['name'];
        }

        foreach ($requestedTables as $requestedTable) {
            if (array_search($requestedTable, $foundTables) === false) {
                throw new exceptions\TableNotFoundException($requestedTable
                    ? "$requestedTable not found on target database."
                    : 'Please specify a table name.'
                );
            }
        }
    }

    public function describeTables($schema, $requestedTables = [], $includeViews = false)
    {
        $description = [];
        $tables = $this->getTables($schema, $requestedTables, $includeViews);

        if (count($requestedTables) > 0 && count($tables) < count($requestedTables)) {
            $this->throwTableExceptions($tables, $requestedTables);
        }

        foreach ($tables as $table) {
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
        $columns = [];
        $columnDetails = $this->getColumns($table);
        foreach ($columnDetails as $column) {
            $columns[$column['name']] = $column;
            $columns[$column['name']]['nulls'] = $columns[$column['name']]['nulls'] == 'YES' ? true : false;

            if ($this->cleanDefaults) {
                $columns[$column['name']]['default'] = $this->cleanDefaultValue($column['default']);
            }
        }

        return $columns;
    }

    private function describeViews($schema)
    {
        $description = [];
        $views = $this->getViews($schema);
        foreach ($views as $view) {
            $description[$view['name']] = [
                'name'       => $view['name'],
                'schema'     => $view['schema'],
                'definition' => $view['definition'],
            ];
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
        $constraints = [];
        foreach ($constraintColumns as $column) {
            $name = $column['name'];
            unset($column['name']);
            foreach ($column as $key => $value) {
                if ($key == 'column' || $key == 'foreign_column') {
                    $constraints[$name]["{$key}s"][] = $value;
                } else {
                    if ($key === 'schema' || $key === 'foreign_schema') {
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
        if ($schema == false || $schema == $defaultSchema) {
            return '';
        } else {
            return $schema;
        }
    }

    protected function cleanDefaultValue($defaultValue)
    {
        return $defaultValue;
    }
}
