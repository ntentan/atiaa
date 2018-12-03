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

$databaseDescription = [
  'schemata' => [
  ],
  'tables' => [
    'departments' => [
      'schema'  => '',
      'name'    => 'departments',
      'columns' => [
        'id' => [
          'name'    => 'id',
          'type'    => 'int',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'name' => [
          'name'    => 'name',
          'type'    => 'varchar',
          'nulls'   => false,
          'default' => null,
          'length'  => '255',
        ],
      ],
      'primary_key' => [
        'PRIMARY' => [
          'columns' => [
            0 => 'id',
          ],
        ],
      ],
      'unique_keys' => [
      ],
      'foreign_keys' => [
      ],
      'indices' => [
      ],
      'auto_increment' => true,
    ],
    'roles' => [
      'schema'  => '',
      'name'    => 'roles',
      'columns' => [
        'id' => [
          'name'    => 'id',
          'type'    => 'int',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'name' => [
          'name'    => 'name',
          'type'    => 'varchar',
          'nulls'   => false,
          'default' => null,
          'length'  => '255',
        ],
      ],
      'primary_key' => [
        'PRIMARY' => [
          'columns' => [
            0 => 'id',
          ],
        ],
      ],
      'unique_keys' => [
        'name' => [
          'columns' => [
            0 => 'name',
          ],
        ],
      ],
      'foreign_keys' => [
      ],
      'indices' => [
      ],
      'auto_increment' => true,
    ],
    'users' => [
      'schema'  => '',
      'name'    => 'users',
      'columns' => [
        'email' => [
          'name'    => 'email',
          'type'    => 'varchar',
          'nulls'   => false,
          'default' => null,
          'length'  => '255',
        ],
        'firstname' => [
          'name'    => 'firstname',
          'type'    => 'varchar',
          'nulls'   => false,
          'default' => null,
          'length'  => '255',
        ],
        'id' => [
          'name'    => 'id',
          'type'    => 'int',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'is_admin' => [
          'name'    => 'is_admin',
          'type'    => 'tinyint',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'lastname' => [
          'name'    => 'lastname',
          'type'    => 'varchar',
          'nulls'   => false,
          'default' => null,
          'length'  => '255',
        ],
        'last_login_time' => [
          'name'    => 'last_login_time',
          'type'    => 'timestamp',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'office' => [
          'name'    => 'office',
          'type'    => 'int',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'othernames' => [
          'name'    => 'othernames',
          'type'    => 'varchar',
          'nulls'   => true,
          'default' => 'None',
          'length'  => 255,
        ],
        'password' => [
          'name'    => 'password',
          'type'    => 'varchar',
          'nulls'   => false,
          'default' => null,
          'length'  => '255',
        ],
        'phone' => [
          'name'    => 'phone',
          'type'    => 'varchar',
          'nulls'   => true,
          'default' => null,
          'length'  => '64',
        ],
        'role_id' => [
          'name'    => 'role_id',
          'type'    => 'int',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'status' => [
          'name'    => 'status',
          'type'    => 'int',
          'nulls'   => false,
          'default' => '2',
          'length'  => null,
        ],
        'username' => [
          'name'    => 'username',
          'type'    => 'varchar',
          'nulls'   => false,
          'default' => null,
          'length'  => '255',
        ],
      ],
      'primary_key' => [
        'PRIMARY' => [
          'columns' => [
            0 => 'id',
          ],
        ],
      ],
      'unique_keys' => [
      ],
      'foreign_keys' => [
        'user_role_fk' => [
          'schema'  => '',
          'table'   => 'users',
          'columns' => [
            0 => 'role_id',
          ],
          'foreign_table'   => 'roles',
          'foreign_schema'  => '',
          'foreign_columns' => [
            0 => 'id',
          ],
          'on_update' => 'RESTRICT',
          'on_delete' => 'RESTRICT',
        ],
      ],
      'indices' => [
      ],
      'auto_increment' => true,
    ],
  ],
];
