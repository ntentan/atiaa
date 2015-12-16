<?php

namespace ntentan\atiaa;

class Db
{
    private static $db;
    private static $defaultSettings;
    
    /**
     * 
     * @return Driver
     */
    public static function getDriver()
    {
        if(self::$db == null) {
            self::$db = \ntentan\atiaa\Driver::getConnection(self::$defaultSettings);
            self::$db->setCleanDefaults(true);

            try {
                self::$db->getPDO()->setAttribute(\PDO::ATTR_AUTOCOMMIT, false);
            } catch (\PDOException $e) {
                // Just do nothing for drivers which do not allow turning off autocommit
            }
        }
        return self::$db;
    }    
    
    /**
     * Set the settings used for creating default datastores.
     * @param array $settings
     */
    public static function setDefaultSettings($settings)
    {
        self::$defaultSettings = $settings;
    }    
    
    public static function getDefaultSettings()
    {
        return self::$defaultSettings;
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
