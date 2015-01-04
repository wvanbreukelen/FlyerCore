<?php

namespace Flyer\Components\HTTP;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{

	/**
	 * Get the request method
	 * @return string HTTP request method
	 */
	public function method()
	{
		return $this->getMethod();
	}

	/**
	 * Get the URL
	 * @return string The URL
	 */
	public function url()
	{
		return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
	}

	/**
	 * Get the full URL
	 * @return string The URL
	 */
	public function fullUrl()
	{
		$query = $this->getQueryString();

		return $query ? $this->url() . '?' . $query : $this->url;
	}

	/**
	 * Get the full path
	 * @return string The full path
	 */
	public function fullPath()
	{
		return $request->getPathInfo();
	}

	/**
	 * Get the URI
	 * @return string The URI
	 */
	public function uri()
	{
		return $this->getUri();
	}

	/**
	 * Get the HTTP input parameters
	 * @param  string $key     Optional key to look for
	 * @param  string $default The default
	 * @return string          Input
	 */
	public function input($key = null, $default = null)
	{
		$input = $this->getInputSource()->all() + $this->query->all();

		return array_get($input, $key, $default);
	}

	/**
	 * Get the client IP address
	 * @return string The IP address
	 */
	public function ip()
	{
		return $this->getClientIp();
	}

	/**
	 * Get a IP bag, which contains all needed information about the client
	 * @return array The IP bag
	 */
	public function ipBag()
	{
		return array(
			'ip' => $this->getClientIp(),
			'host' => $this->getClientHost(),
			'port' => $this->getClientPort()
		);
	}

	/**
	 * Simulate a HTTP request
	 * @param  string $path       The request path
	 * @param  string $method     The request method, default is 'GET'
	 * @param  array  $parameters The optional parameters
	 * @return string             The response
	 */
	public function simulate($path, $method = 'GET', array $parameters = array())
	{
		return $this->create($path, $method, $parameters);
	}

	/**
	 * Get all headers or a specific one
	 * @param  string $key     Optional header to look for
	 * @param  string $default The default
	 * @return string          The header or headers you requested
	 */
	public function header($key = null, $default = null)
	{
		return $this->retrieveItem('headers', $key, $default);
	}

	/**
	 * Get a cookie or all the cookies
	 * @param  string $key     Optional cookie to look for
	 * @param  string $default The default
	 * @return string          The cookie or cookies values you requested
	 */
	public function cookies($key = null, $default = null)
	{
		return $this->retrieveItem('cookies', $key, $default);
	}

	/**
	 * Receive any item
	 * @param  string $source  The item source
	 * @param  string $key     The item key
	 * @param  string $default The default
	 * @return string          T
	 */
	protected function retrieveItem($source, $key, $default)
	{
		if (is_null($key))
		{
			return $this->$source->all();
		}

		return $this->$source->get($key, $default, true);
	}

}