Atiaa PDO Wrapper
=================

[![Build Status](https://github.com/ntentan/atiaa/actions/workflows/tests.yml/badge.svg)](https://github.com/ntentan/atiaa/actions/workflows/tests.yml)
[![Code Coverage](https://scrutinizer-ci.com/g/ntentan/atiaa/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/ntentan/atiaa/?branch=main)
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
$factory = new \ntentan\atiaa\DefaultDriverFactory(
    [
        'driver' => 'mysql',
        'user' => 'root',
        'password' => 'rootpassy',
        'host' => 'localhost',
        'dbname' => 'somedb'
    ]
);
$atiaa = $factory->createDriver();

// Perform some queries
$data = $atiaa->query('SELECT * FROM some_table');
$data2 = $atiaa->query(
    'SELECT * FROM some_other_table WHERE id = ? and an_item = ?', 
    [2, 'something']
);

// Get the description of the database
$description = $atiaa->describe();
var_dump($description);

// Perform a query while quoting the literals.
$data3 = $atiaa->quoteQuery('SELECT "First Name" from "Users Table" ');
````
