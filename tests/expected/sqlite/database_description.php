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
      'name'    => 'departments',
      'schema'  => '',
      'columns' => [
        'id' => [
          'name'    => 'id',
          'type'    => 'INTEGER',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'name' => [
          'name'    => 'name',
          'type'    => 'TEXT',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
      ],
      'primary_key' => [
        'departments_pk' => [
          'order'   => '1',
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
      'name'    => 'roles',
      'schema'  => '',
      'columns' => [
        'id' => [
          'name'    => 'id',
          'type'    => 'INTEGER',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'name' => [
          'name'    => 'name',
          'type'    => 'TEXT',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
      ],
      'primary_key' => [
        'roles_pk' => [
          'order'   => '1',
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
    'users' => [
      'name'    => 'users',
      'schema'  => '',
      'columns' => [
        'id' => [
          'name'    => 'id',
          'type'    => 'INTEGER',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'username' => [
          'name'    => 'username',
          'type'    => 'TEXT',
          'nulls'   => false,
          'default' => null,
          'length'  => '255',
        ],
        'password' => [
          'name'    => 'password',
          'type'    => 'TEXT',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'role_id' => [
          'name'    => 'role_id',
          'type'    => 'INTEGER',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'firstname' => [
          'name'    => 'firstname',
          'type'    => 'TEXT',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'lastname' => [
          'name'    => 'lastname',
          'type'    => 'TEXT',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'othernames' => [
          'name'    => 'othernames',
          'type'    => 'TEXT',
          'nulls'   => true,
          'default' => '\'None\'',
          'length'  => null,
        ],
        'status' => [
          'name'    => 'status',
          'type'    => 'INTEGER',
          'nulls'   => true,
          'default' => '\'2\'',
          'length'  => null,
        ],
        'email' => [
          'name'    => 'email',
          'type'    => 'TEXT',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'phone' => [
          'name'    => 'phone',
          'type'    => 'TEXT',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'office' => [
          'name'    => 'office',
          'type'    => 'INTEGER',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'last_login_time' => [
          'name'    => 'last_login_time',
          'type'    => 'TEXT',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'is_admin' => [
          'name'    => 'is_admin',
          'type'    => 'INTEGER',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
      ],
      'primary_key' => [
        'users_pk' => [
          'order'   => '1',
          'columns' => [
            0 => 'id',
          ],
        ],
      ],
      'unique_keys' => [
        'sqlite_autoindex_users_1' => [
          'columns' => [
            0 => 'username',
          ],
          'schema' => '',
        ],
      ],
      'foreign_keys' => [
        'users_departments_0_fk' => [
          'schema'  => '',
          'table'   => 'users',
          'columns' => [
            0 => 'office',
          ],
          'foreign_table'   => 'departments',
          'foreign_schema'  => '',
          'foreign_columns' => [
            0 => 'id',
          ],
          'on_update' => 'CASCADE',
          'on_delete' => 'CASCADE',
        ],
        'users_roles_1_fk' => [
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
          'on_update' => 'CASCADE',
          'on_delete' => 'CASCADE',
        ],
      ],
      'indices' => [
        'user_email_idx' => [
          'columns' => [
            0 => 'email',
          ],
          'schema' => '',
        ],
      ],
      'auto_increment' => true,
    ],
  ],
];
