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

$viewDescription = [
  'users_view' => [
    'schema'  => '',
    'name'    => 'users_view',
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
        'default' => '0',
        'length'  => null,
      ],
      'lastname' => [
        'name'    => 'lastname',
        'type'    => 'varchar',
        'nulls'   => false,
        'default' => null,
        'length'  => '255',
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
      'role' => [
        'name'    => 'role',
        'type'    => 'varchar',
        'nulls'   => false,
        'default' => null,
        'length'  => '255',
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
    ],
    'unique_keys' => [
    ],
    'foreign_keys' => [
    ],
    'indices' => [
    ],
    'auto_increment' => false,
  ],
];
