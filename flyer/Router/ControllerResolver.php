<?php

namespace Flyer\Components\Router;

use ReflectionMethod;
use Exception;

class ControllerResolver
{

	protected $route;

	protected $request;

	protected $resolved = array();

	public function __construct($route, $request)
	{
		$this->route = $route;
		$this->request = $request;

		$this->resolveClassController();
	}

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

	public function getMethodParameters()
	{
		$reflection = new ReflectionMethod($this->getResolvedAsset('controller'), $this->getResolvedAsset('method'));

		$params = array();

		foreach ($reflection->getParameters() as $param)
		{
			$params[] = $param->name;
		}

		return $params;
	}

	public function getResolvedAsset($key)
	{
		return $this->resolved[$key];
	}

	public function addResolvedAsset($key, $value)
	{
		$this->resolved[$key] = $value;
	}

	protected function resolveClassController()
	{
		$pieces = explode('@', $this->route);

		$this->addResolvedAsset('controller', $pieces[0]);
		$this->addResolvedAsset('method', $pieces[1]);
	}
}