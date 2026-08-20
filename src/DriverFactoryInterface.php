<?php

namespace ntentan\atiaa;

use ntentan\atiaa\Driver;

interface DriverFactoryInterface
{
    public function createDriver(): Driver;
}