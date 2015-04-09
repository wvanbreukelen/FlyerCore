<?php

namespace Flyer\Components;

use Flyer\Components\Config\ConfigNotFoundException;
use Exception;

class Config
{

    /**
     * All the saves resources
     * 
     * @param array
     */
    protected static $resources = array();

    /**
     * Import any given config file
     * 
     * @param  string $configFile The path
     * @return mixed             
     */
    public function import($configFile)
    {
        if (is_array($resource = require_once($configFile)))
        {
            self::$resources[] = $resource;
            return;
        }

        throw new ConfigNotFoundException("Config: File " . $configFile . " cannot been imported, because the datatype is not a array!");
                    
    }

    /**
     * Get a specific resource in the resources
     * @param  string $resource The resource you need
     * @return mixed            The (optional) results
     */
    public function get($resource)
    {
        //print_r(self::$resources);
        
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

            return false;
    }

    /**
     * Get mulitple resources
     * @param  array  $resources The resources that you need
     * @return mixed             The (optional) resources
     */
    public function gets(array $resources = array())
    {
        $results = array();

        foreach (self::$resources as $resource)
        {
            if (in_array($resource, $resources))
            {
               $results[] = $this->get($resource); 
            }
        }
        
        return $results;
    }

    /**
     * Add a resource
     */
    public function add(array $resource = array())
    {
        self::$resources = array_merge(self::$resources, $resource);
    }

    /**
     * Check if a specific resource exists
     * @param  string $resource The resource to check
     * @return bool             The result
     */
    public function exists($resource)
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

    /**
     * Get all the resources
     * @return array All the resources
     */
    public function getResources()
    {
        return self::$resources;
    }
}
