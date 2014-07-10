<?php

namespace Flyer\Components\Router;

class RouteBuilder
{

	private static $listener;

	private static $route;

	private static $method;

	private static $options = array();
	
	/**
	 * Sets the route listener
	 * 
	 * @param The listener
	 */

	public static function setListener($listener) 
	{
		$this->listener = $listener;
	}
	
	/**
	 * Sets the route
	 * 
	 * @param The route
	 */

	public static function setRoute($route)
	{
		$this->route = $route;
	}
	
	/**
	 * Sets the route HTTP method
	 * 
	 * @param The HTTP method
	 */

	public static function setMethod($method)
	{
		$this->method = $method;
	}
	
	/**
	 * Sets the route options
	 * 
	 * @param The options for the route
	 */

	public static function setOptions(array $options = array()) 
	{
		$this->options = $options;
	}
	
	/**
	 * Sets the route listener
	 * 
	 * @param The listener
	 */

	public static function getListener()
	{
		return $this->listener;
	}
	
	/**
	 * Get the route
	 * 
	 * @return string
	 */

	public static function getRoute()
	{
		return $this->route;
	}
	
	/**
	 * Get the HTTP method
	 * 
	 * @return string
	 */

	public static function getMethod()
	{
		return $this->method;
	}
	
	/**
	 * Get the route options
	 * 
	 * @return array
	 */

	public static function getOptions()
	{
		return $this->options;
	}
	
}
