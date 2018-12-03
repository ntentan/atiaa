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
    'crm' => [
      'name'   => 'crm',
      'tables' => [
        'customers' => [
          'schema'  => 'crm',
          'name'    => 'customers',
          'columns' => [
            'employee_id' => [
              'name'    => 'employee_id',
              'type'    => 'integer',
              'nulls'   => false,
              'default' => null,
              'length'  => null,
            ],
            'id' => [
              'name'    => 'id',
              'type'    => 'integer',
              'nulls'   => false,
              'default' => null,
              'length'  => null,
            ],
            'name' => [
              'name'    => 'name',
              'type'    => 'character varying',
              'nulls'   => false,
              'default' => null,
              'length'  => 255,
            ],
          ],
          'primary_key' => [
            'customers_pkey' => [
              'columns' => [
                0 => 'id',
              ],
            ],
          ],
          'unique_keys' => [
          ],
          'foreign_keys' => [
            'customers_employee_id_fkey' => [
              'schema'  => 'crm',
              'table'   => 'customers',
              'columns' => [
                0 => 'employee_id',
              ],
              'foreign_table'   => 'employees',
              'foreign_schema'  => 'hr',
              'foreign_columns' => [
                0 => 'id',
              ],
              'on_update' => 'NO ACTION',
              'on_delete' => 'NO ACTION',
            ],
          ],
          'indices' => [
          ],
          'auto_increment' => true,
        ],
      ],
      'views' => [
      ],
    ],
    'hr' => [
      'name'   => 'hr',
      'tables' => [
        'categories' => [
          'schema'  => 'hr',
          'name'    => 'categories',
          'columns' => [
            'id' => [
              'name'    => 'id',
              'type'    => 'integer',
              'nulls'   => false,
              'default' => null,
              'length'  => null,
            ],
            'name' => [
              'name'    => 'name',
              'type'    => 'character varying',
              'nulls'   => false,
              'default' => null,
              'length'  => 255,
            ],
          ],
          'primary_key' => [
            'departments_pkey' => [
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
        'employees' => [
          'schema'  => 'hr',
          'name'    => 'employees',
          'columns' => [
            'date_of_birth' => [
              'name'    => 'date_of_birth',
              'type'    => 'date',
              'nulls'   => true,
              'default' => null,
              'length'  => null,
            ],
            'firstname' => [
              'name'    => 'firstname',
              'type'    => 'character varying',
              'nulls'   => false,
              'default' => null,
              'length'  => 255,
            ],
            'id' => [
              'name'    => 'id',
              'type'    => 'integer',
              'nulls'   => false,
              'default' => null,
              'length'  => null,
            ],
            'lastname' => [
              'name'    => 'lastname',
              'type'    => 'character varying',
              'nulls'   => true,
              'default' => null,
              'length'  => 255,
            ],
          ],
          'primary_key' => [
            'employees_pkey' => [
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
      ],
      'views' => [
      ],
    ],
  ],
  'tables' => [
    'departments' => [
      'schema'  => '',
      'name'    => 'departments',
      'columns' => [
        'id' => [
          'name'    => 'id',
          'type'    => 'integer',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'name' => [
          'name'    => 'name',
          'type'    => 'character varying',
          'nulls'   => false,
          'default' => null,
          'length'  => 255,
        ],
      ],
      'primary_key' => [
        'departments_pkey' => [
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
          'type'    => 'integer',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'name' => [
          'name'    => 'name',
          'type'    => 'character varying',
          'nulls'   => false,
          'default' => null,
          'length'  => 255,
        ],
      ],
      'primary_key' => [
        'roles_pkey' => [
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
      'schema'  => '',
      'name'    => 'users',
      'columns' => [
        'email' => [
          'name'    => 'email',
          'type'    => 'character varying',
          'nulls'   => false,
          'default' => null,
          'length'  => 255,
        ],
        'firstname' => [
          'name'    => 'firstname',
          'type'    => 'character varying',
          'nulls'   => false,
          'default' => null,
          'length'  => 255,
        ],
        'id' => [
          'name'    => 'id',
          'type'    => 'integer',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'is_admin' => [
          'name'    => 'is_admin',
          'type'    => 'boolean',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'last_login_time' => [
          'name'    => 'last_login_time',
          'type'    => 'timestamp without time zone',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'lastname' => [
          'name'    => 'lastname',
          'type'    => 'character varying',
          'nulls'   => false,
          'default' => null,
          'length'  => 255,
        ],
        'office' => [
          'name'    => 'office',
          'type'    => 'integer',
          'nulls'   => true,
          'default' => null,
          'length'  => null,
        ],
        'othernames' => [
          'name'    => 'othernames',
          'type'    => 'character varying',
          'nulls'   => true,
          'default' => 'None',
          'length'  => 255,
        ],
        'password' => [
          'name'    => 'password',
          'type'    => 'character varying',
          'nulls'   => false,
          'default' => null,
          'length'  => 255,
        ],
        'phone' => [
          'name'    => 'phone',
          'type'    => 'character varying',
          'nulls'   => true,
          'default' => null,
          'length'  => 64,
        ],
        'role_id' => [
          'name'    => 'role_id',
          'type'    => 'integer',
          'nulls'   => false,
          'default' => null,
          'length'  => null,
        ],
        'status' => [
          'name'    => 'status',
          'type'    => 'integer',
          'nulls'   => false,
          'default' => '2',
          'length'  => null,
        ],
        'username' => [
          'name'    => 'username',
          'type'    => 'character varying',
          'nulls'   => false,
          'default' => null,
          'length'  => 255,
        ],
      ],
      'primary_key' => [
        'users_pkey' => [
          'columns' => [
            0 => 'id',
          ],
        ],
      ],
      'unique_keys' => [
      ],
      'foreign_keys' => [
        'users_office_fkey' => [
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
          'on_update' => 'NO ACTION',
          'on_delete' => 'NO ACTION',
        ],
        'users_role_id_fkey' => [
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
          'on_update' => 'NO ACTION',
          'on_delete' => 'NO ACTION',
        ],
      ],
      'indices' => [
      ],
      'auto_increment' => true,
    ],
  ],
];
