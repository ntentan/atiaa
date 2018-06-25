Atiaa PDO Wrapper
=================

[![Build Status](https://travis-ci.org/ntentan/atiaa.svg)](https://travis-ci.org/ntentan/atiaa)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ntentan/atiaa/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ntentan/atiaa/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ntentan/atiaa/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ntentan/atiaa/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/ntentan/atiaa/version.svg)](https://packagist.org/packages/ntentan/atiaa)
[![Total Downloads](https://poser.pugx.org/ntentan/atiaa/downloads.svg)](https://packagist.org/packages/ntentan/atiaa)

Atiaa is a thin wrapper around PHP's PDO database abstraction layer. The main 
purpose of atiaa is to provide utility classes that other packages in the 
ntentan framework need (which are not available in PDO). 

Currently atiaa provides the following features:
 - Wrappers arround the PDO query method which prepare the query and execute in 
   one stretch. These methods then return all the results as a simple 
   PHP associative array.
 - Methods which describe the schema of the database represented by the connection. 
 - A platform independent approach for quoting database literals in queries.

Currently atiaa works only with MySQL, PostgreSQL and SQLite databases. 
Support for other platforms is planned for later releases.

Installation
------------
The best way to install atiaa is to use composer. To install atiaa add 
`ntentan/atiaa` to your composer dependencies.

Example
-------
The following example tries to summarise the entirety of atiaa.

````php
<?php

// Connect to a database
$factory = new \ntentan\atiaa\DriverFactory(
    array(
        'driver' => 'mysql',
        'user' => 'root',
        'password' => 'rootpassy',
        'host' => 'localhost',
        'dbname' => 'somedb'
    )
);
$atiaa = $factory->createDriver();

// Perform some queries
$data = $atiaa->query('SELECT * FROM some_table');
$data2 = $atiaa->query(
    'SELECT * FROM some_other_table WHERE id = ? and an_item = ?', 
    array(2, 'something')
);

// Get the description of the database
$description = $atiaa->describe();
var_dump($description);

// Perform a query while quoting the literals.
$data3 = $atiaa->quoteQuery('SELECT "First Name" from "Users Table" ');
````

License
-------
Copyright (c) 2014 James Ekow Abaka Ainooson

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
