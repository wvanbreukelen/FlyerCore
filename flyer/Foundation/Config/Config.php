<?php

namespace Flyer\Foundation\Config;

class Config
{

    protected static $resources = array();

    public function import($configFile)
    {
        if (is_array($resource = require($configFile)))
        {
            self::$resources[] = $resource;
            return;
        }

        throw new \Exception("Config: File " . $configFile . " cannot been imported, because the datatype is not a array!");
                    
    }

    public function getFull()
    {
        return self::$resources;
    }

    public static function exists($resource)
    {
        foreach (self::$resources as $configCollection) 
        {
            foreach ($configCollection as $configItemName => $configItem) {
                if ($resource == $configItemName)
                {
                    return true;
                }
            }
        }

        return false;
    }

    public static function get($resource)
    {
            foreach (self::$resources as $configCollection)
            {
                foreach ($configCollection as $configItemName => $configItem) {
                    if ($resource == $configItemName)
                    {
                            return $configItem;
                    }
                }
		        foreach (self::$resources as $configResource)
		        {
		            if (in_array($resource, $configResource))
		            {
		                    return $configResource[$resource];
		            }
		        }
		    }
            throw new \Exception("Resource " . $resource . " does not exists. Have you imported the file using import()?");
    }

    public static function gets(array $resources = array())
    {

        $result = array();

        foreach (self::$resources as $configResource)
        {
            foreach ($resources as $resource)
            {
                if (in_array($resource, $configResource))
                {
                        $result[$resource] = $configResource;
                }
            }
        }

        if (count($result) > 0) return $result;

        throw new \Exception("Config: Resources cannot been imported!");
    }
}