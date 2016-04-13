<?php

namespace Flyer\Components\Router;

use ReflectionMethod;
use Exception;

/**
 * Resolves controller stuff
 */
class ControllerResolver
{

	/**
	 * The route
	 * @param string
	 */
	protected $route;

	/**
	 * The request
	 * @param string
	 */
	protected $request;

	/**
	 * The resolved arguments
	 * @param array
	 */
	protected $resolved = array();

	/**
	 * Construct a new controller resolver
	 * @param string $route   The route
	 * @param strinbf $request The request
	 */
	public function __construct($route, $request)
	{
		$this->route = $route;
		$this->request = $request;

		$this->resolveClassController();
	}

	/**
	 * Generate an argument list of passing thought request parameters
	 * @return mixed The argument list
	 */
	public function generateArgumentList()
	{
		$parameters = $this->getMethodParameters();

		if (count($parameters) === 0)
		{
			return array();
		}

		$data = array();

		foreach ($parameters as $key => $value)
		{
			$data[$value] = $this->request->get($value, null);
		}

		return $data;
	}

	/**
	 * Get the parameters of a method
	 * @wvanbreukelen Maybe create a separate package called Reflector of this kind of actions?
	 * @return array The method parameters
	 */
	public function getMethodParameters()
	{
		try {
			$reflection = new ReflectionMethod($this->getResolvedAsset('controller'), $this->getResolvedAsset('method'));
		} catch (Exception $e) {
			throw new Exception("Unable to get method parameters for " . $this->getResolvedAsset('controller'));
		}

		$params = array();

		foreach ($reflection->getParameters() as $param)
		{
			$params[] = $param->name;
		}

		return $params;
	}

	/**
	 * Add a resolved asset
	 * @param string $key   The key
	 * @param string $value The value
	 */
	public function addResolvedAsset($key, $value)
	{
		$this->resolved[$key] = $value;
	}

	/**
	 * Get a resolved asset
	 * @param string $key The key of the value to resolve
	 */
	public function getResolvedAsset($key)
	{
		return $this->resolved[$key];
	}

	/**
	 * Resolve the class controller of a method
	 * @return mixed
	 */
	protected function resolveClassController()
	{
		$pieces = explode('@', $this->route);

		$this->addResolvedAsset('controller', $pieces[0]);
		$this->addResolvedAsset('method', $pieces[1]);
	}
}
