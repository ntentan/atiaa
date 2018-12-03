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
use PHPUnit\Framework\TestCase;

class DriverTest extends TestCase
{
    private $dbName;

    use DriverLoader;

    private function getDescriptor($driver)
    {
        $descriptorClass = '\\ntentan\\atiaa\\descriptors\\'.ucfirst($this->getDriverName()).'Descriptor';
        $descriptor = new $descriptorClass($driver);

        return $descriptor;
    }

    public function setUp()
    {
        // Preserve the original dbname just in case it changes in any test
        $this->dbName = getenv('ATIAA_DBNAME');
    }

    public function tearDown()
    {
        putenv("ATIAA_DBNAME={$this->dbName}");
    }

    /**
     * @expectedException \ntentan\atiaa\exceptions\ConnectionException
     */
    public function testDbNotFound()
    {
        if (getenv('ATIAA_SKIP_DB') === 'yes') {
            $this->markTestSkipped();

            return;
        }
        putenv('ATIAA_DBNAME=none');
        $this->getDriver();
    }

    public function testFunctions()
    {
        $driverName = $this->getDriverName();
        $driver = $this->getDriver();

        $strings = json_decode(file_get_contents("tests/expected/$driverName/strings.json"), true);
        $this->assertEquals($strings['quoted_string'], $driver->quote('string'));
        $this->assertEquals($strings['quoted_identifier'], $driver->quoteIdentifier('identifier'));
        $this->assertEquals($strings['quoted_query_identifiers'], $driver->quoteQueryIdentifiers('SELECT "some", "identifiers" FROM "some"."table"'));
        $pdo = $driver->getPDO();
        $this->assertInstanceOf('PDO', $pdo);
    }

    public function testFullDescription()
    {
        $driver = $this->getDriver();
        $type = $this->getDriverName();

        $testDbDescription = $driver->describe();
        $views = $testDbDescription['views'];
        unset($testDbDescription['views']);
        require "tests/expected/{$type}/database_description.php";
        $this->assertEquals($databaseDescription, $testDbDescription);
        $this->assertArrayHasKey('users_view', $views);
        $this->assertArrayHasKey('definition', $views['users_view']);
        $this->assertEquals('users_view', $views['users_view']['name']);
    }

    public function testCleanDefaultDescription()
    {
        $driver = $this->getDriver();
        $type = $this->getDriverName();
        $driver->setCleanDefaults(true);

        $testDbDescription = $driver->describe();
        $views = $testDbDescription['views'];
        unset($testDbDescription['views']);
        require "tests/expected/{$type}/database_description_clean_defaults.php";
        $this->assertEquals($databaseDescription, $testDbDescription);
        $this->assertArrayHasKey('users_view', $views);
        $this->assertArrayHasKey('definition', $views['users_view']);
        $this->assertEquals('users_view', $views['users_view']['name']);
    }

    public function testViewDescriptionAsTable()
    {
        $driver = $this->getDriver();
        $type = $this->getDriverName();

        $viewDbDescription = $driver->describeTable('users_view');
        require "tests/expected/{$type}/view_description.php";
        $this->assertEquals($viewDescription, $viewDbDescription);
    }

    public function testStringSchema()
    {
        if (!$this->hasSchemata()) {
            $this->markTestSkipped();

            return;
        }

        $driver = $this->getDriver();
        $type = $this->getDriverName();

        $employeesDbDescription = $driver->describeTable('hr.employees');
        require "tests/expected/{$type}/employees_description.php";
        $this->assertEquals($employeesDescription, $employeesDbDescription);
    }

    /**
     * @expectedException \ntentan\atiaa\exceptions\TableNotFoundException
     */
    public function testTableNotFoundException()
    {
        $driver = $this->getDriver();
        $driver->describeTable('unknown_table');
    }

    /**
     * @expectedException \ntentan\atiaa\exceptions\TableNotFoundException
     */
    public function testTableNotFoundExceptionAgain()
    {
        $driver = $this->getDriver($this);
        $this->getDescriptor($driver)->describeTables($driver->getDefaultSchema(), ['users', 'unknown_table']);
    }

    /**
     * @expectedException \ntentan\atiaa\exceptions\DatabaseDriverException
     */
    public function testFaultyQueryException()
    {
        $driver = $this->getDriver($this);
        $driver->query('SPELECT * FROM dummy');
    }

    /**
     * @expectedException \ntentan\atiaa\exceptions\DatabaseDriverException
     */
    public function testDisconnect()
    {
        $driver = $this->getDriver($this);
        $driver->disconnect();
        $driver->query('SELECT * FROM users');
    }

    private function hasSchemata()
    {
        return strtolower(getenv('ATIAA_HAS_SCHEMAS')) === 'yes';
    }
}
