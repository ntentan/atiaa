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

namespace ntentan\atiaa\descriptors;

class PostgresqlDescriptor extends InformationSchemaDescriptor
{
    protected function cleanDefaultValue($defaultValue)
    {
        // Deal with typecasts
        if (preg_match("/(?<value>.*)(::)(?<type>[a-zA-Z0-9\s]*)$/", $defaultValue, $matches)) {
            $value = $matches['value'];

            // If numeric
            if (is_numeric($value)) {
                return $value;

            // If its a string
            } elseif (preg_match("/'(?<string>.*)'/", $value, $matches)) {
                return $matches['string'];

            // null for anything else
            } else {
                return;
            }

            // Return the nextval as atiaa uses them to detect auto keys
        } elseif (preg_match("/nextval\(.*/", $defaultValue)) {
            return $defaultValue;

        // Return numeric default values
        } elseif (is_numeric($defaultValue)) {
            return $defaultValue;

        // Return null for anything else
        } else {
            return;
        }
    }

    /**
     * @note Query sourced from http://stackoverflow.com/questions/2204058/show-which-columns-an-index-is-on-in-postgresql
     *
     * @param type $table
     *
     * @return type
     */
    protected function getIndices(&$table)
    {
        return $this->driver->query(
            sprintf("select
                        t.relname as table_name,
                        i.relname as name,
                        a.attname as column
                    from
                        pg_class t,
                        pg_class i,
                        pg_index ix,
                        pg_attribute a,
                        pg_namespace n
                    where
                        t.oid = ix.indrelid
                        and i.oid = ix.indexrelid
                        and a.attrelid = t.oid
                        and a.attnum = ANY(ix.indkey)
                        and t.relkind = 'r'
                        and t.relname = '%s'
                        and n.nspname = '%s'
                        and i.relnamespace = n.oid
                            AND indisunique != 't'
                            AND indisprimary != 't'
                        order by i.relname, a.attname",
            $table['name'], $table['schema'])
        );
    }

    /**
     * @note Query sourced from http://stackoverflow.com/questions/1152260/postgres-sql-to-list-table-foreign-keys
     *
     * @param type $table
     */
    protected function getForeignKeys(&$table)
    {
        return $this->driver->query(
            "SELECT distinct
                kcu.constraint_name as name,
                kcu.table_schema as schema,
                kcu.table_name as table, 
                kcu.column_name as column, 
                ccu.table_name AS foreign_table,
                ccu.table_schema AS foreign_schema,
                ccu.column_name AS foreign_column,
                rc.update_rule as on_update,
                rc.delete_rule as on_delete

            FROM 
                information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name and tc.table_schema = kcu.table_schema and tc.table_name = kcu.table_name 
                JOIN information_schema.constraint_column_usage AS ccu
                  ON ccu.constraint_name = tc.constraint_name   and ccu.constraint_schema = tc.table_schema
                JOIN information_schema.referential_constraints AS rc
                  ON rc.constraint_name = tc.constraint_name and rc.constraint_schema = tc.table_schema
            WHERE constraint_type = 'FOREIGN KEY' 
                AND tc.table_name=:name AND tc.table_schema=:schema
                AND kcu.table_name=:name AND kcu.table_schema=:schema
                order by kcu.constraint_name, kcu.column_name",
                //$table['name'], $table['schema']
                ['name'=>$table['name'], 'schema'=>$table['schema']]
        );
    }

    public function getSchemata()
    {
        return $this->driver->query(
            "select schema_name as name from information_schema.schemata 
            where schema_name not like 'pg_temp%' and 
            schema_name not like 'pg_toast%' and 
            schema_name not in ('pg_catalog', 'information_schema')
            order by schema_name"
        );
    }

    protected function hasAutoIncrementingKey(&$table)
    {
        $auto = false;
        $primaryKey = reset($table['primary_key']);
        if (is_array($primaryKey)) {
            if (count($primaryKey) == 1 && substr_count($table['columns'][$primaryKey['columns'][0]]['default'], 'nextval')) {
                $table['columns'][$primaryKey['columns'][0]]['default'] = null;
                $auto = true;
            }
        }

        return $auto;
    }
}
