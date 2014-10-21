<?php

namespace Flyer\Components\HTTP;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{

	public function method()
	{
		return $this->getMethod();
	}

	public function url()
	{
		return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
	}

	public function uri()
	{
		return $this->getUri();
	}

	public function input($key = null, $default = null)
	{
		$input = $this->getInputSource()->all() + $this->query->all();

		return array_get($input, $key, $default);
	}

	public function ip()
	{
		return $this->getClientIp();
	}

	public function ipBag()
	{
		return array(
			'ip' => $this->getClientIp(),
			'host' => $this->getClientHost(),
			'port' => $this->getClientPort()
		);
	}

	public function fullUrl()
	{
		$query = $this->getQueryString();

		return $query ? $this->url() . '?' . $query : $this->url;
	}

	public function fullPath()
	{
		return $request->getPathInfo();
	}

	public function simulate($path, $method = 'GET', array $parameters = array())
	{
		return $this->create($path, $method, $parameters);
	}

	public function header($key = null, $default = null)
	{
		return $this->retrieveItem('headers', $key, $default);
	}

	public function cookies($key = null, $default = null)
	{
		return $this->retrieveItem('cookies', $key, $default);
	}

	protected function retrieveItem($source, $key, $default)
	{
		if (is_null($key))
		{
			return $this->$source->all();
		}

		return $this->$source->get($key, $default, true);
	}

}