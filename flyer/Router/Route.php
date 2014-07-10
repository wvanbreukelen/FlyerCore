<?php

namespace Flyer\Components\Router;

use Flyer\Components\Router\RouteBuilder;

/**
 * The Route class responds to the RouteBuilder which builds a instance for the Router.
 */

class Route
{
	/**
	 * Add a route by the HTTP GET method
	 * 
	 * @param string The URI where the Router can respond to
	 * @param string The action if the Router matches with the given URI
	 */
	
	public function get($uri, $action)
	{
		Router::addRoute("GET", $uri, $action);
	}
	
	/**
	 * Add a route by the HTTP POST method
	 * 
	 * @param string The URI where the Router can respond to
	 * @param string The action if the Router matches with the given URI
	 */

	public function post($uri, $action)
	{
		Router::addRoute("POST", $uri, $action);
	}
	
	/**
	 * Add a route by the HTTP UPDATE method
	 * 
	 * @param string The URI where the Router can respond to
	 * @param string The action if the Router matches with the given URI
	 */

	public function update($uri, $action)
	{
		Router::addRoute("UPDATE", $uri, $action);
	}
	
	/**
	 * Add a route by the HTTP DELETE method
	 * 
	 * @param string The URI where the Router can respond to
	 * @param string The action if the Router matches with the given URI
	 */

	public function delete($uri, $action)
	{
		Router::addRoute("DELETE", $uri, $action);
	}
	
	/**
	 * Add a route by ANY HTTP method
	 * 
	 * @param string The URI where the Router can respond to
	 * @param string The action if the Router matches with the given URI
	 */

	public function any($uri, $action)
	{
		Router::addRoute("ANY", $uri, $action);
	}
}
