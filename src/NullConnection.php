<?php

namespace ntentan\atiaa;

class NullConnection
{
    public function __call($name, $arguments) 
    {
        throw new exceptions\DatabaseDriverException("Your database is currently not connected");
    }
}
