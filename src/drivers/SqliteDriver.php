<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\atiaa\drivers;

/**
 * 
 * 
 * @author ekow
 */
class SqliteDriver extends \ntentan\atiaa\Driver
{
    public function __construct($config) 
    {
        $this->defaultSchema = 'main';
        parent::__construct($config);
    }
    
    protected function getDriverName() 
    {
        return 'sqlite';
    }

    public function quoteIdentifier($identifier)
    {
        return "\"$identifier\"";
    }
}
