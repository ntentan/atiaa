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

namespace ntentan\atiaa;

use ntentan\utils\Text;

class DriverFactory
{
    private $config;

    /**
     * Create an instance of the driver factory with the connection configurations.
     *
     * @param array $config
     */
    public function __construct(array $config = null)
    {
        $this->config = $config;
    }

    /**
     * Set or replace the configuration found in the factory.
     *
     * @param $config
     */
    public function setConfig($config) : void
    {
        $this->config = $config;
    }

    /**
     * Return the configuration currently stored in the factory.
     *
     * @return array
     */
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * Create a new driver based on the configuration in the factory.
     *
     * @return \ntentan\atiaa\Driver
     */
    public function createDriver() : Driver
    {
        $classname = '\ntentan\atiaa\drivers\\'.Text::ucamelize($this->config['driver']).'Driver';

        return new $classname($this->config);
    }
}
