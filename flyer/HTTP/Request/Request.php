<?php

namespace Flyer\Components\HTTP\Request;

class Request
{

	protected static $payload;
	
	/**
	 * Sets the payload for a request
	 * 
	 * @param mixed The payload
	 */

	public function setPayload($payload)
	{
		self::$payload = $payload;
	}
	
	/**
	 * Gets the 'distance'-path of the payload
	 * 
	 * @return mixed The path
	 */

	public static function getPath()
	{
		return ltrim(self::$payload->getPathInfo(), '/');
	}
	
	/**
	 * Get the current request method
	 * 
	 * @return string The request method
	 */

	public static function getRequestMethod()
	{
		return Registry::get('application.request.method');
	}
}
