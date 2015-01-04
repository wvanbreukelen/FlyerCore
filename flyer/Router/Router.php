<?php

namespace Flyer\Components\Router;

use Closure;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Flyer\Components\Http;
use Flyer\Components\Router\Route;
use Flyer\Foundation\Events\Events;
use App;

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
	 * @param  string Request method
	 * @param  string Listener for the route
	 * @param  mixed Route, can be an array or a closure
	 *
	 * @return  void 
	 */

	public static function addRoute($method, $listener, $route)
	{
		$salt = rand(0, 999999);
		
		$listener = ltrim($listener, '/');

		self::$routes[$listener . '.?.' . $salt] = array(
			'method' => $method,
			'route' => $route
		);
	}

	/**
	 * Returns all routes that where binded to the router
	 *
	 * @return array
	 */

	public static function getRoutes()
	{
		return self::$routes;
	}

	/**
	 * Resolve the route of the given request
	 *
	 * @return  void
	 */

	public function route()
	{
		if (in_array($this->request['method'], $this->methods))
		{
			foreach (self::$routes as $listener => $route)
			{
				$listener = $this->resolveListener($listener);
				$uri = explode('/', ltrim($this->request['path'], '/'));
				
				if ($this->request['method'] == $route['method'])
				{
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
	 * @param  mixed Route
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
			throw new Exception("Cannot determine variable type of route");
		}
	}
	
	/**
	 * Sets the request, so the router can compare the routes with the current request
	 *
	 * @param  mixed
	 */

	public function setRequest($request)
	{
		if ($request instanceof Request)
		{
			$this->request = array('method' => $request->server->get('REQUEST_METHOD'), 'path' => $request->getPathInfo());
			return;
		}

		if (is_array($request))
		{
			$this->request = $request;
			return;
		}

		throw new Exception("Cannot set request, because the given request is not a instance of a SymfonyRequest or a array!");
		return false;
	}

	/**
	 * Returns the given request
	 *
	 * @return  mixed
	 */
	
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * Triggers the error page, developer has to give the HTTP error code
	 * 
	 * @param $error The HTTP error code
	 */

	public static function triggerErrorPage($error)
	{
		$asset = 'application.error.' . $error;

		if (App::offsetExists($asset))
		{
			return App::make('application.error.' . $error);
		}

		throw new Exception("Cannot trigger error page for error " . $error . "! Did you create a error page for this specified error?");
	}

	/**
	 * Create an routing event, by a closure
	 *
	 * @param  closure Route
	 * 
	 * @return  void
	 */

	protected function handleClosure($route)
	{
		App::bind('application.route', function () use ($route) {
			return $route;
		});
	}

	/**
	 * Create an routing event, by a string
	 *
	 * @param  string Route
	 *
	 * @return void
	 */

	protected function handleString($route)
	{
		$route = $this->resolveController($route, App::access('request.get'));

		App::bind('application.route', function () use ($route) {
			return call_user_func_array(array(new $route['controller'], $route['method']), $route['params']);
		});
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
	 * Resolve the controller and method of a given route
	 *
	 * @param  array Route
	 *
	 * @return  array Resolved controller
	 */

	protected function resolveController($route, $request)
	{
		$resolver = new ControllerResolver($route, $request);

		//App::attach('route.parameters', $resolver->generateArgumentList());
		//App::attach('route.controller', $resolver->getResolvedAsset('controller'));
		//App::attach('route.method', $resolver->getResolvedAsset('method'));
		
		return array(
			'controller' => $resolver->getResolvedAsset('controller'),
			'method'     => $resolver->getResolvedAsset('method'),
			'params'     => $resolver->generateArgumentList()
		);
	}
}
