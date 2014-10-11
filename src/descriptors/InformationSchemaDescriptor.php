<?php

/* 
 * The MIT License
 *
 * Copyright 2014 ekow.
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

abstract class InformationSchemaDescriptor extends \ntentan\atiaa\Descriptor
{
    protected function getColumns(&$table)
    {
        return $this->driver->quotedQuery(
            'select 
                "column_name" as "name", 
                "data_type" as "type", 
                "is_nullable" as "nulls", 
                "column_default" as "default", 
                "character_maximum_length" as "length"
            from "information_schema"."columns"
            where "table_name" = ? and "table_schema"=?
            order by "column_name"',
            array(
                $table['name'],
                $table['schema']
            )
        );
    }
    
    protected function getTables($schema)
    {
        return $this->driver->quotedQuery(
            'select "table_schema" as "schema", "table_name" as "name"
            from "information_schema"."tables"
            where table_schema = ? and table_type = ? order by "table_name"',
            array($schema, 'BASE TABLE')
        );
    }  
    
    protected function getPrimaryKey(&$table)
    {
        return $this->getConstraint($table, 'PRIMARY KEY');
    }
    
    protected function getUniqueKeys(&$table)
    {
        return $this->getConstraint($table, 'UNIQUE');
    }

    private function getConstraint($table, $type)
    {
        return $this->driver->quotedQuery(
            'select "column_name" as "column", "pk"."constraint_name" as "name" 
            from "information_schema"."table_constraints" "pk" 
            join "information_schema"."key_column_usage" "c" on 
               "c"."table_name" = "pk"."table_name" and 
               "c"."constraint_name" = "pk"."constraint_name" and
               "c"."constraint_schema" = "pk"."table_schema"
            where "pk"."table_name" = ? and pk.table_schema= ?
            and constraint_type = ? order by "pk"."constraint_name", "column_name"',
            array($table['name'], $table['schema'], $type)
        );
    }  
    
    protected function getViews(&$schema)
    {
        return $this->driver->quotedQuery(
            'select "table_schema" as "schema", "table_name" as "name", "view_definition" as "definition"
            from "information_schema"."views"
            where "table_schema" = ? order by "table_name"',
            array($schema)
        );
    }    
}
