<?php

namespace ntentan\atiaa;

class NullConnection
{
    public function __call($name, $arguments) 
    {
        throw new DatabaseDriverException("Your database is currently not connected");
    }
}
