<?php

namespace Flyer\Components\Router;

use Closure;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Flyer\Components\Router\Route;
use App;

/**
 * The router matches the developer own routes and returns a request for that route
 */
class Router
{

	/**
	 * All of the routes that have been registered into this router
	 */
	protected static $routes = array();

	/**
	 * All for the method that can been used
	 */
	protected $methods = array("POST", "GET", "DELETE", "UPDATE");

	/**
	 * The current HTTP request
	 */
	private $request;

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
			throw new RuntimeException("Cannot determine variable type of route, has to be a string or closure");
		}
	}
	
	/**
	 * Sets the request, so the router can compare the routes with the current request
	 *
	 * @param  mixed
	 * @param Request $request
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

		throw new RuntimeException("Cannot set request, because the given request is not a instance of a SymfonyRequest or a array!");
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
	 * @return mixed
	 */
	public static function triggerErrorPage($error)
	{
		return App::make('application.error.' . $error);
	}


	/**
	 * Create an routing event, by a closure
	 *
	 * @param  closure Route
	 * @param Closure $route
	 * 
	 * @return  void
	 */
	protected function handleClosure($route)
	{
		App::bind('application.route', function () use ($route) 
		{
			// Call the closure that corresponds to the correct route

			return call_user_func($route);
		});
	}

	/**
	 * Create an routing event, by a string
	 *
	 * @param  string Route
	 * @param string $route
	 *
	 * @return void
	 */
	protected function handleString($route)
	{
		$route = $this->resolveController($route, App::make('request.get'));

		App::bind('application.route', function () use ($route) 
		{
			// Call the controller with the controller class en method, with the resolved parameters

			return call_user_func_array(array(new $route['controller'], $route['method']), $route['params']);
		});
	}

	/**
	 * Resolve the controller and method of a given route
	 *
	 * @param  array Route
	 * @param string $route
	 *
	 * @return  array Resolved controller
	 */
	protected function resolveController($route, $request)
	{
		$resolver = new ControllerResolver($route, $request);
		
		return array (
			'controller' => $resolver->getResolvedAsset('controller'),
			'method'     => $resolver->getResolvedAsset('method'),
			'params'     => $resolver->generateArgumentList()
		);
	}

	/**
	 * Add a route to the router
	 *
	 * @param  string Request method
	 * @param  string Listener for the route
	 * @param  mixed Route, can be an array or a closure
	 * @param string $method
	 *
	 * @return  void 
	 */
	public static function addRoute($method, $listener, $route)
	{
		$listener = ltrim($listener, '/');

		self::$routes[$listener] = array(
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
}
