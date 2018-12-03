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

namespace ntentan\atiaa\tests\lib;

use ntentan\atiaa\DriverFactory;

trait DriverLoader
{
    /**
     * @return \ntentan\atiaa\Driver
     */
    public function getDriver()
    {
        $factory = new DriverFactory([
            'driver'   => getenv('ATIAA_DRIVER'),
            'host'     => getenv('ATIAA_HOST'),
            'user'     => getenv('ATIAA_USER'),
            'password' => getenv('ATIAA_PASSWORD'),
            'file'     => getenv('ATIAA_FILE'),
            'dbname'   => getenv('ATIAA_DBNAME'),
        ]);
        $driver = $factory->createDriver();
        $driver->connect();

        return $driver;
    }

    public function getDriverName()
    {
        return getenv('ATIAA_DRIVER');
    }
}
