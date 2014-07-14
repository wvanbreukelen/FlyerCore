<?php

namespace Flyer\Components\Router;

use Closure;
use Flyer\Foundation\Registry;
use Symfony\Component\HttpFoundation\Request;
use Flyer\Components\Http;
use Flyer\Components\Router\Route;
use Flyer\Foundation\Events\Events;

/**
 * The router matches the developer own routes and returns a request for that route
 */

class Router
{

	/**
	 * All of the routes
	 */

	protected static $routes = array();

	/**
	 * All for the method that can been used
	 */

	protected $methods = array("POST", "GET", "DELETE", "UPDATE");

	/**
	 * The request to match the routes
	 */

	private $request;

	/**
	 * Add a route to the router
	 *
	 * @var  string Request method
	 * @var  string Listener for the route
	 * @var  mixed Route, can be an array or a closure
	 *
	 * @return  void 
	 */

	public static function addRoute($method, $listener, $route)
	{
		$salt = rand(0, 999999);
		self::$routes[$listener . '.?.' . $salt] = array(
			'method' => $method,
			'route' => $route
		);
	}

	/**
	 * Resolve the route of the given request
	 *
	 * @return  void
	 */

	public function route()
	{
		if (in_array($this->request, $this->methods))
		{
			foreach (self::$routes as $listener => $route)
			{
				$listener = $this->resolveListener($listener);

				if ($this->request->server->get('REQUEST_METHOD') == $route['method'])
				{
					$uri = explode('/', ltrim($this->request->getPathInfo(), '/'));

					if (strtolower($uri[0]) == $listener)
					{
						$this->generateRouteEvent($route['route']);
						return;
					}
				} else {
					if (strtolower($uri[0]) == $listener)
					{
						$this->generateRouteEvent($route['route']);
						return;
					}
				}
			}
		}
	}

	/**
	 * Determine which type of route is given, and generate a event for that type of variable
	 *
	 * @var  mixed Route
	 *
	 * @return  void
	 */

	public function generateRouteEvent($route)
	{
		if (is_object($route) && $route instanceof Closure)
		{
			$this->handleClosure($route);
		} else if (is_string($route)) {
			$this->handleString($route);
		} else {
			throw new \Exception("Router: Cannot determain variable type of route");
		}
	}
	
	/**
	 * Sets the request, so the router can compare the routes with the current request
	 *
	 * @var  object Symfony\Component\HttpFoundation\Request
	 */

	public function setRequest(Request $request)
	{
		if ($request instanceof Request)
		{
			$this->request = $request->server->get('REQUEST_METHOD');
		}

		$this->request = $request;
	}

	/**
	 * Create a event, by a closure
	 *
	 * @var  closure Route
	 * 
	 * @return  void
	 */

	protected function handleClosure($route)
	{
		Events::create(array(
			'title' => 'application.route',
			'event' => $route
		));
	}

	/**
	 * Create a event, by a string
	 *
	 * @var  string Route
	 *
	 * @return void
	 */

	protected function handleString($route)
	{
		Registry::set('application.controller.path', $this->resolveController($route));

		Events::create(array(
			'title' => 'application.route',
			'event' => function () {
				$action = Registry::get('application.controller.path');

				require(APP . 'controllers' . DS . $action['controller'] . '.php');

				$route = new $action['controller'];
				return $route->$action['method']();
			}
		));
	}

	/**
	 * Resolve the listener out a "salted" listener
	 *
	 * @param  string The listener
	 * @return  string The resolved listener
	 */

	protected function resolveListener($listener)
	{
		return explode('.?.', $listener)[0];
	}

	/**
	 * Resolve the controller of a given route
	 *
	 * @var  array Route
	 *
	 * @return  array Resolved controller
	 */

	protected function resolveController($route)
	{
		$pieces = explode('@', $route);

		return array(
			'controller' => $pieces[0],
			'method' => $pieces[1]
		);
	}
}
