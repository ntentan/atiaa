<?php
namespace ntentan\atiaa\descriptors;

/**
 * An abstract descriptor for DBs which use the information_schema tables.
 * Database systems which implement the ANSI information_schema standard can
 * extend this descriptor.
 */
abstract class InformationSchemaDescriptor extends \ntentan\atiaa\Descriptor
{
    #[\Override]
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
            [
                $table['name'],
                $table['schema'],
            ]
        );
    }

    #[\Override]
    protected function getTables($schema, $tables, $includeViews)
    {
        if ($includeViews) {
            $condition = '(table_type = ? or table_type = ?)';
            $bind = ['BASE TABLE', 'VIEW'];
        } else {
            $condition = 'table_type = ?';
            $bind = ['BASE TABLE'];
        }

        if (count($tables) > 0) {
            return $this->driver->quotedQuery(
                'select "table_schema" as "schema", "table_name" as "name"
                from "information_schema"."tables"
                where '.$condition.' and table_schema = ?
                    and table_name in (?'.str_repeat(', ?', count($tables) - 1).')
                order by "table_name"',
                array_merge($bind, [$schema], $tables)
            );
        } else {
            return $this->driver->quotedQuery(
                'select "table_schema" as "schema", "table_name" as "name"
                from "information_schema"."tables"
                where '.$condition.' and table_schema = ? order by "table_name"',
                array_merge($bind, [$schema])
            );
        }
    }

    #[\Override]
    protected function getPrimaryKey(&$table)
    {
        return $this->getConstraints($table, 'PRIMARY KEY');
    }

    #[\Override]
    protected function getUniqueKeys(&$table)
    {
        return $this->getConstraints($table, 'UNIQUE');
    }

    /**
     * @param string $type
     */
    private function getConstraints($table, $type)
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
            [$table['name'], $table['schema'], $type]
        );
    }

    #[\Override]
    protected function getViews(&$schema)
    {
        return $this->driver->quotedQuery(
            'select "table_schema" as "schema", "table_name" as "name", "view_definition" as "definition"
            from "information_schema"."views"
            where "table_schema" = ? order by "table_name"',
            [$schema]
        );
    }
}
