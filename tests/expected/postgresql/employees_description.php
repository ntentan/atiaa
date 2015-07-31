<?php
$employeesDescription = array (
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
);
