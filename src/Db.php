<?php

namespace ntentan\atiaa;

use ntentan\config\Config;
use ntentan\panie\InjectionContainer;
use ntentan\utils\Text;

class Db
{
    //private static $db;
    
    /**
     * 
     * @return Driver
     */
    public static function getDriver()
    {
        return InjectionContainer::singleton(Driver::class);
    }
    
    public static function getDefaultDriverClassName()
    {
        $driver = Config::get('ntentan:db.driver');
        if($driver == null) {
            throw new exceptions\DatabaseDriverException(
                "Please provide a valid driver name in your database config file"
            );
        }
        return '\ntentan\atiaa\drivers\\' . Text::ucamelize(Config::get('ntentan:db.driver')) . "Driver";
    }
    
    public static function reset()
    {
        if(self::$db !== null) {
            self::$db->disconnect();
            self::$db = null;
        }
    }    
    
    public static function query($query, $bindData = false)
    {
        return self::getDriver()->query($query, $bindData);
    }
}
