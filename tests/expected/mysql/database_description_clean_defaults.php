<?php
$databaseDescription = array (
  'schemata' => 
  array (
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
          'type' => 'int',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'name' => 
        array (
          'name' => 'name',
          'type' => 'varchar',
          'nulls' => false,
          'default' => NULL,
          'length' => '255',
        ),
      ),
      'primary_key' => 
      array (
        'PRIMARY' => 
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
          'type' => 'int',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'name' => 
        array (
          'name' => 'name',
          'type' => 'varchar',
          'nulls' => false,
          'default' => NULL,
          'length' => '255',
        ),
      ),
      'primary_key' => 
      array (
        'PRIMARY' => 
        array (
          'columns' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'unique_keys' => 
      array (
        'name' => 
        array (
          'columns' => 
          array (
            0 => 'name',
          ),
        ),
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
          'type' => 'varchar',
          'nulls' => false,
          'default' => NULL,
          'length' => '255',
        ),
        'firstname' => 
        array (
          'name' => 'firstname',
          'type' => 'varchar',
          'nulls' => false,
          'default' => NULL,
          'length' => '255',
        ),
        'id' => 
        array (
          'name' => 'id',
          'type' => 'int',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'is_admin' => 
        array (
          'name' => 'is_admin',
          'type' => 'tinyint',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'lastname' => 
        array (
          'name' => 'lastname',
          'type' => 'varchar',
          'nulls' => false,
          'default' => NULL,
          'length' => '255',
        ),
        'last_login_time' => 
        array (
          'name' => 'last_login_time',
          'type' => 'timestamp',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'office' => 
        array (
          'name' => 'office',
          'type' => 'int',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'othernames' => 
        array (
          'name' => 'othernames',
          'type' => 'varchar',
          'nulls' => true,
          'default' => 'None',
          'length' => 255,
        ),
        'password' => 
        array (
          'name' => 'password',
          'type' => 'varchar',
          'nulls' => false,
          'default' => NULL,
          'length' => '255',
        ),
        'phone' => 
        array (
          'name' => 'phone',
          'type' => 'varchar',
          'nulls' => true,
          'default' => NULL,
          'length' => '64',
        ),
        'role_id' => 
        array (
          'name' => 'role_id',
          'type' => 'int',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'status' => 
        array (
          'name' => 'status',
          'type' => 'int',
          'nulls' => false,
          'default' => '2',
          'length' => NULL,
        ),
        'username' => 
        array (
          'name' => 'username',
          'type' => 'varchar',
          'nulls' => false,
          'default' => NULL,
          'length' => '255',
        ),
      ),
      'primary_key' => 
      array (
        'PRIMARY' => 
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
        'user_role_fk' => 
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
          'on_update' => 'RESTRICT',
          'on_delete' => 'RESTRICT',
        ),
      ),
      'indices' => 
      array (
      ),
      'auto_increment' => true,
    ),
  ),
);
