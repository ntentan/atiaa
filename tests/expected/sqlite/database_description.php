<?php

$databaseDescription = array (
  'schemata' => 
  array (
  ),
  'tables' => 
  array (
    'departments' => 
    array (
      'name' => 'departments',
      'schema' => '',
      'columns' => 
      array (
        'id' => 
        array (
          'name' => 'id',
          'type' => 'INTEGER',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'name' => 
        array (
          'name' => 'name',
          'type' => 'TEXT',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
      ),
      'primary_key' => 
      array (
        'departments_pk' => 
        array (
          'order' => '1',
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
      'name' => 'roles',
      'schema' => '',
      'columns' => 
      array (
        'id' => 
        array (
          'name' => 'id',
          'type' => 'INTEGER',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'name' => 
        array (
          'name' => 'name',
          'type' => 'TEXT',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
      ),
      'primary_key' => 
      array (
        'roles_pk' => 
        array (
          'order' => '1',
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
      'name' => 'users',
      'schema' => '',
      'columns' => 
      array (
        'id' => 
        array (
          'name' => 'id',
          'type' => 'INTEGER',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'username' => 
        array (
          'name' => 'username',
          'type' => 'TEXT',
          'nulls' => false,
          'default' => NULL,
          'length' => '255',
        ),
        'password' => 
        array (
          'name' => 'password',
          'type' => 'TEXT',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'role_id' => 
        array (
          'name' => 'role_id',
          'type' => 'INTEGER',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'firstname' => 
        array (
          'name' => 'firstname',
          'type' => 'TEXT',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'lastname' => 
        array (
          'name' => 'lastname',
          'type' => 'TEXT',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'othernames' => 
        array (
          'name' => 'othernames',
          'type' => 'TEXT',
          'nulls' => true,
          'default' => '\'None\'',
          'length' => NULL,
        ),
        'status' => 
        array (
          'name' => 'status',
          'type' => 'INTEGER',
          'nulls' => true,
          'default' => '\'2\'',
          'length' => NULL,
        ),
        'email' => 
        array (
          'name' => 'email',
          'type' => 'TEXT',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'phone' => 
        array (
          'name' => 'phone',
          'type' => 'TEXT',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'office' => 
        array (
          'name' => 'office',
          'type' => 'INTEGER',
          'nulls' => false,
          'default' => NULL,
          'length' => NULL,
        ),
        'last_login_time' => 
        array (
          'name' => 'last_login_time',
          'type' => 'TEXT',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
        'is_admin' => 
        array (
          'name' => 'is_admin',
          'type' => 'INTEGER',
          'nulls' => true,
          'default' => NULL,
          'length' => NULL,
        ),
      ),
      'primary_key' => 
      array (
        'users_pk' => 
        array (
          'order' => '1',
          'columns' => 
          array (
            0 => 'id',
          ),
        ),
      ),
      'unique_keys' => 
      array (
        'sqlite_autoindex_users_1' => 
        array (
          'columns' => 
          array (
            0 => 'username',
          ),
          'schema' => '',
        ),
      ),
      'foreign_keys' => 
      array (
        'users_departments_0_fk' => 
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
          'on_update' => 'CASCADE',
          'on_delete' => 'CASCADE',
        ),
        'users_roles_1_fk' => 
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
          'on_update' => 'CASCADE',
          'on_delete' => 'CASCADE',
        ),
      ),
      'indices' => 
      array (
        'user_email_idx' => 
        array (
          'columns' => 
          array (
            0 => 'email',
          ),
          'schema' => '',
        ),
      ),
      'auto_increment' => true,
    ),
  ),
);
