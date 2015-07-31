<?php

namespace ntentan\atiaa\descriptors;

class SqliteDescriptor extends \ntentan\atiaa\Descriptor
{

    protected function getColumns(&$table)
    {
        $pragmaColumns = $this->driver->query("PRAGMA table_info({$table['name']})");
        foreach ($pragmaColumns as $column) {
            preg_match("/(?<type>[a-zA-Z]*)(\((?<length>[0-9]+)\))*/", $column['type'], $matches);
            $columns[] = [
                'name' => $column['name'],
                'type' => $matches['type'],
                'nulls' => $column['notnull'] == '0',
                'default' => $column['dflt_value'],
                'length' => isset($matches['length']) ? $matches['length'] : null
            ];
        }

        return $columns;
    }

    protected function cleanDefaultValue($default)
    {
        if(preg_match("/(')?(?<value>.*)/", $default, $matches)) {
            return substr($matches['value'], 0,  strlen($matches['value']) - 1);
        } else {
            return null;
        }
    }

    protected function getForeignKeys(&$table)
    {
        $foreignKeys = [];
        $pragmaColumns = $this->driver->query("pragma foreign_key_list({$table['name']})");
        foreach ($pragmaColumns as $i => $foreignKey) {
            $foreignKeys[] = [
                'name' => "{$table['name']}_{$foreignKey['table']}_{$i}_fk",
                'schema' => $table['schema'],
                'table' => $table['name'],
                'column' => $foreignKey['from'],
                'foreign_table' => $foreignKey['table'],
                'foreign_schema' => 'main',
                'foreign_column' => $foreignKey['to'],
                'on_update' => $foreignKey['on_update'],
                'on_delete' => $foreignKey['on_delete']
            ];
        }

        return $foreignKeys;
    }

    private function extractIndexDetails($details, $index, &$indexDetails)
    {
        foreach ($details as $detail) {
            if ($detail['name'] != '') {
                $indexDetails[] = [
                    'column' => $detail['name'],
                    'name' => $index['name'],
                    'schema' => $index['schema']
                ];
            }
        }
    }

    private function getIndexDetails($table, $unique)
    {
        $indices = $this->driver->query("pragma index_list({$table['name']})");
        $indexDetails = [];

        foreach ($indices as $index) {
            if ($index['unique'] == $unique) {
                $index['schema'] = $table['schema'];
                $detail = $this->driver->query("pragma index_info({$index['name']})");
                $this->extractIndexDetails($detail, $index, $indexDetails);
            }
        }

        return $indexDetails;
    }

    protected function getIndices(&$table)
    {
        return $this->getIndexDetails($table, '0');
    }

    protected function getPrimaryKey(&$table)
    {
        $keyColumns = [];
        $pragmaColumns = $this->driver->query("PRAGMA table_info({$table['name']})");
        foreach ($pragmaColumns as $column) {
            if ($column['pk'] > 0) {
                $keyColumns[] = [
                    'order' => $column['pk'],
                    'column' => $column['name'],
                    'name' => "{$table['name']}_pk"
                ];
            }
        }

        usort($keyColumns, function ($a, $b) {
            return $a['order'] - $b['order'];
        });
        return $keyColumns;
    }

    protected function getSchemata()
    {
        return [['name' => 'main']];
    }

    protected function getTables($schema, $tables, $includeViews)
    {
        if ($includeViews) {
            $condition = "(type = ? or type = ?)";
            $bind = array('table', 'view');
        } else {
            $condition = "type = ?";
            $bind = array('table');
        }

        if (count($tables) > 0) {
            return $this->driver->quotedQuery(
                            'select name as "name", \'main\' as "schema" from sqlite_master
                where ' . $condition . ' and name not in (\'sqlite_master\', \'sqlite_sequence\') and name in (?' . str_repeat(', ?', count($tables) - 1) . ')
                order by name', array_merge($bind, $tables)
            );
        } else {
            return $this->driver->quotedQuery(
                            'select name as "name", \'main\' as "schema" from sqlite_master
                where name not in (\'sqlite_master\', \'sqlite_sequence\') and ' . $condition, array_merge($bind)
            );
        }
    }

    protected function getUniqueKeys(&$table)
    {
        return $this->getIndexDetails($table, '1');
    }

    protected function getViews(&$schema)
    {
        return $this->driver->query("select 'main' as schema, name, sql as definition from sqlite_master where type = 'view'");
    }

    protected function hasAutoIncrementingKey(&$table)
    {
        $sql = $this->driver->query("select sql from sqlite_master where name = ?", [$table['name']]);
        if (preg_match("/AUTOINCREMENT/", $sql[0]['sql'])) {
            return true;
        } else {
            return false;
        }
    }

}
