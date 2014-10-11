<?php
namespace ntentan\atiaa\descriptors;

class PostgresqlDescriptor extends InformationSchemaDescriptor
{
    /**
     * 
     * @note Query sourced from http://stackoverflow.com/questions/2204058/show-which-columns-an-index-is-on-in-postgresql
     * @param type $table
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
     * @param type $table
     */
    protected function getForeignKeys(&$table)
    {
        return $this->driver->query(
            sprintf("SELECT
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
                          ON tc.constraint_name = kcu.constraint_name and tc.table_schema = kcu.table_schema
                        JOIN information_schema.constraint_column_usage AS ccu
                          ON ccu.constraint_name = tc.constraint_name and tc.table_schema = ccu.table_schema
                        JOIN information_schema.referential_constraints AS rc
                          ON rc.constraint_name = tc.constraint_name and rc.constraint_schema = tc.table_schema
                    WHERE constraint_type = 'FOREIGN KEY' 
                        AND tc.table_name='%s' AND tc.table_schema='%s' order by kcu.constraint_name, kcu.column_name",
                $table['name'], $table['schema']
            )
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
        if(count($primaryKey) == 1 && substr_count($table['columns'][$primaryKey['columns'][0]]['default'], 'nextval'))
        {
            unset($table['columns'][$primaryKey['columns'][0]]['default']);
            $auto = true;
        }
        return $auto;
    }
}

