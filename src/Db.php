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
        return self::getDriverClassName(Config::get('ntentan:db.driver'));
    }
    
    private static function getDriverClassName($driver)
    {
        if($driver == null) {
            throw new exceptions\DatabaseDriverException(
                "Please provide a valid driver name in your database config file"
            );
        }
        return '\ntentan\atiaa\drivers\\' . Text::ucamelize($driver) . "Driver";
    }
    
    public static function reset()
    {
        self::getDriver()->disconnect();
    }    
    
    public static function getConnection($parameters)
    {
        Config::set('ntentan:db', $parameters);
        return InjectionContainer::resolve(self::getDriverClassName($parameters['driver']));
    }
    
    public static function query($query, $bindData = false)
    {
        return self::getDriver()->query($query, $bindData);
    }
}
