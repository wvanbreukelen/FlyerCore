<?php

namespace Flyer\Foundation\Config;

use Flyer\Foundation\Config\ConfigNotFoundException;
use Exception;

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

        throw new ConfigNotFoundException("Config: File " . $configFile . " cannot been imported, because the datatype is not a array!");
                    
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
            throw new Exception("Resource " . $resource . " does not exists. Have you imported the file using import()?");
    }

    public static function gets(array $resources = array())
    {
        $results = array();

        foreach (self::$resources as $configResource)
        {
            foreach ($resources as $resource)
            {
                if (in_array($resource, $configResource))
                {
                    $results[$resource] = $configResource;
                }
            }
        }

        if (count($results) > 0) return $results;

        throw new Exception("Unable to get the specified resources");
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

    public function getAllResources()
    {
        return self::$resources;
    }
}