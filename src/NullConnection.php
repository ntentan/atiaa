<?php

namespace ntentan\atiaa;

class NullConnection {

    public function __call($name, $arguments) {
        throw new exceptions\DatabaseDriverException("Please specify a database connection.");
    }

}
