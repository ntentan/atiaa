<?php
$databaseDescription = array (
  'schemata' => 
  array (
    'crm' => 
    array (
      'name' => 'crm',
      'tables' => 
      array (
        'customers' => 
        array (
          'schema' => 'crm',
          'name' => 'customers',
          'columns' => 
          array (
            'employee_id' => 
            array (
              'name' => 'employee_id',
              'type' => 'integer',
              'nulls' => false,
              'default' => NULL,
              'length' => NULL,
            ),
            'id' => 
            array (
              'name' => 'id',
              'type' => 'integer',
              'nulls' => false,
              'default' => NULL,
              'length' => NULL,
            ),
            'name' => 
            array (
              'name' => 'name',
              'type' => 'character varying',
              'nulls' => false,
              'default' => NULL,
              'length' => 255,
            ),
          ),
          'primary_key' => 
          array (
            'customers_pkey' => 
            array (
              'columns' => 
              array (
                0 => 'id',
              ),
            ),
          ),
          'unique_keys' => 
          array (
          ),
          'foreign_keys' => 
          array (
            'customers_employee_id_fkey' => 
            array (
              'schema' => 'crm',
              'table' => 'customers',
              'columns' => 
              array (
                0 => 'employee_id',
              ),
              'foreign_table' => 'employees',
              'foreign_schema' => 'hr',
              'foreign_columns' => 
              array (
                0 => 'id',
              ),
              'on_update' => 'NO ACTION',
              'on_delete' => 'NO ACTION',
            ),
          ),
          'indices' => 
          array (
          ),
          'auto_increment' => true,
        ),
      ),
      'views' => 
      array (
      ),
    ),
    'hr' => 
    array (
      'name' => 'hr',
      'tables' => 
      array (
        'categories' => 
        array (
          'schema' => 'hr',
          'name' => 'categories',
          'columns' => 
          array (
            'id' => 
            array (
              'name' => 'id',
              'type' => 'integer',
              'nulls' => false,
              'default' => NULL,
              'length' => NULL,
            ),
            'name' => 
            array (
              'name' => 'name',
              'type' => 'character varying',
              'nulls' => false,
              'default' => NULL,
              'length' => 255,
            ),
          ),
          'primary_key' => 
          array (
            'departments_pkey' => 
            array (
              'columns' => 
              array (
                0 => 'id',
              ),
            ),
          ),
          'unique_keys' => 
          array (
          ),
          'foreign_keys' => 
          array (
          ),
          'indices' => 
          array (
          ),
          'auto_increment' => true,
        ),
        'employees' => 
        array (
          'schema' => 'hr',
          'name' => 'employees',
          'columns' => 
          array (
            'date_of_birth' => 
            array (
              'name' => 'date_of_birth',
              'type' => 'date',
              'nulls' => true,
              'default' => NULL,
              'length' => NULL,
            ),
            'firstname' => 
            array (
              'name' => 'firstname',
              'type' => 'character varying',
              'nulls' => false,
              'default' => NULL,
              'length' => 255,
            ),
            'id' => 
            array (
              'name' => 'id',
              'type' => 'integer',
              'nulls' => false,
              'default' => NULL,
              'length' => NULL,
            ),
            'lastname' => 
            array (
              'name' => 'lastname',
              'type' => 'character varying',
              'nulls' => true,
              'default' => NULL,
              'length' => 255,
            ),
          ),
          'primary_key' => 
          array (
            'employees_pkey' => 
            array (
              'columns' => 
              array (
                0 => 'id',
              ),
            ),
          ),
          'unique_keys' => 
          array (
          ),
          'foreign_keys' => 
          array (
          ),
          'indices' => 
          array (
          ),
          'auto_increment' => true,
        ),
      ),
      'views' => 
      array (
      ),
    ),
  ),
  'tables' => 
  array (
    'departments' => 
    array (
      'schema' => '',
      'name' => 'departments',
      'columns' => 
      array (
        'id' => 
        array (
          'name' => 'id',
          'type' => 'integer',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'name' => 
        array (
          'name' => 'name',
          'type' => 'character varying',
          'nulls' => false,
          'default' => NULL,
          'length' => 255,
        ),
      ),
      'primary_key' => 
      array (
        'departments_pkey' => 
        array (
          'columns' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'unique_keys' => 
      array (
      ),
      'foreign_keys' => 
      array (
      ),
      'indices' => 
      array (
      ),
      'auto_increment' => true,
    ),
    'roles' => 
    array (
      'schema' => '',
      'name' => 'roles',
      'columns' => 
      array (
        'id' => 
        array (
          'name' => 'id',
          'type' => 'integer',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'name' => 
        array (
          'name' => 'name',
          'type' => 'character varying',
          'nulls' => false,
          'default' => NULL,
          'length' => 255,
        ),
      ),
      'primary_key' => 
      array (
        'roles_pkey' => 
        array (
          'columns' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'unique_keys' => 
      array (
      ),
      'foreign_keys' => 
      array (
      ),
      'indices' => 
      array (
      ),
      'auto_increment' => true,
    ),
    'users' => 
    array (
      'schema' => '',
      'name' => 'users',
      'columns' => 
      array (
        'email' => 
        array (
          'name' => 'email',
          'type' => 'character varying',
          'nulls' => false,
          'default' => NULL,
          'length' => 255,
        ),
        'firstname' => 
        array (
          'name' => 'firstname',
          'type' => 'character varying',
          'nulls' => false,
          'default' => NULL,
          'length' => 255,
        ),
        'id' => 
        array (
          'name' => 'id',
          'type' => 'integer',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'is_admin' => 
        array (
          'name' => 'is_admin',
          'type' => 'boolean',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'last_login_time' => 
        array (
          'name' => 'last_login_time',
          'type' => 'timestamp without time zone',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'lastname' => 
        array (
          'name' => 'lastname',
          'type' => 'character varying',
          'nulls' => false,
          'default' => NULL,
          'length' => 255,
        ),
        'office' => 
        array (
          'name' => 'office',
          'type' => 'integer',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'othernames' => 
        array (
          'name' => 'othernames',
          'type' => 'character varying',
          'nulls' => true,
          'default' => 'None',
          'length' => 255,
        ),
        'password' => 
        array (
          'name' => 'password',
          'type' => 'character varying',
          'nulls' => false,
          'default' => NULL,
          'length' => 255,
        ),
        'phone' => 
        array (
          'name' => 'phone',
          'type' => 'character varying',
          'nulls' => true,
          'default' => null,
          'length' => 64,
        ),
        'role_id' => 
        array (
          'name' => 'role_id',
          'type' => 'integer',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'status' => 
        array (
          'name' => 'status',
          'type' => 'integer',
          'nulls' => false,
          'default' => '2',
          'length' => NULL,
        ),
        'username' => 
        array (
          'name' => 'username',
          'type' => 'character varying',
          'nulls' => false,
          'default' => NULL,
          'length' => 255,
        ),
      ),
      'primary_key' => 
      array (
        'users_pkey' => 
        array (
          'columns' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'unique_keys' => 
      array (
      ),
      'foreign_keys' => 
      array (
        'users_office_fkey' => 
        array (
          'schema' => '',
          'table' => 'users',
          'columns' => 
          array (
            0 => 'office',
          ),
          'foreign_table' => 'departments',
          'foreign_schema' => '',
          'foreign_columns' => 
          array (
            0 => 'id',
          ),
          'on_update' => 'NO ACTION',
          'on_delete' => 'NO ACTION',
        ),
        'users_role_id_fkey' => 
        array (
          'schema' => '',
          'table' => 'users',
          'columns' => 
          array (
            0 => 'role_id',
          ),
          'foreign_table' => 'roles',
          'foreign_schema' => '',
          'foreign_columns' => 
          array (
            0 => 'id',
          ),
          'on_update' => 'NO ACTION',
          'on_delete' => 'NO ACTION',
        ),
      ),
      'indices' => 
      array (
      ),
      'auto_increment' => true,
    ),
  )
);
