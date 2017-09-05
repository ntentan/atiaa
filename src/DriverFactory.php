<?php
/**
 * Created by PhpStorm.
 * User: ekow
 * Date: 9/4/17
 * Time: 10:50 AM
 */

namespace ntentan\atiaa;

use ntentan\utils\Text;


class DriverFactory
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function createDriver()
    {
        $classname = '\ntentan\atiaa\drivers\\' . Text::ucamelize($this->config['driver']) . "Driver";
        return new $classname($this->config);
    }
}