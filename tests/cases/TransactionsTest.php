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

namespace ntentan\atiaa\tests\cases;

use ntentan\atiaa\tests\lib\DriverLoader;
use PHPUnit\DbUnit\TestCase;

class TransactionsTest extends TestCase
{
    use DriverLoader;

    public function getDataSet()
    {
        return $this->createFlatXMLDataSet('tests/expected/xml/transactions.xml');
    }

    public function testTransactions()
    {
        $driver = $this->getDriver();
        $this->assertEquals(0, $this->getConnection()->getRowCount('roles'));
        $driver->beginTransaction();
        $driver->query('INSERT INTO roles(name) VALUES(?)', ['hello']);
        $driver->commit();
        $this->assertEquals(1, $this->getConnection()->getRowCount('roles'));
        $driver->disconnect();
    }

    public function testTransactionsRollback()
    {
        $driver = $this->getDriver();
        $this->assertEquals(0, $this->getConnection()->getRowCount('roles'));
        $driver->beginTransaction();
        $driver->query('INSERT INTO roles(name) VALUES(?)', ['hello']);
        $driver->rollback();
        $this->assertEquals(0, $this->getConnection()->getRowCount('roles'));
        $driver->disconnect();
    }

    public function getConnection()
    {
        $pdo = new \PDO(getenv('ATIAA_PDO_DSN'), getenv('ATIAA_USER'), getenv('ATIAA_PASSWORD'));

        return $this->createDefaultDBConnection($pdo);
    }

    protected function getSetUpOperation()
    {
        return \PHPUnit\DbUnit\Operation\Factory::DELETE_ALL();
    }
}
