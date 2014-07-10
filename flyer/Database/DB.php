<?php

namespace Flyer\Components\Database;

use Illuminate\Database\Capsule\Manager as DatabaseHandler;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

/**
 * Handles the database drivers
 */

class DB
{

	/**
	 * The selected driver
	 */

	private static $driver;

	/**
	 * Construct a driver
	 *
	 * @param object The driver
	 */

	public function __construct($driver)
	{
		$this->setDriver($driver);
	}

	/**
	 * Sets the driver
	 *
	 * @param object The driver
	 */
	
	public function setDriver($driver)
	{
		self::$driver = $driver;
	}

	/**
	 * Gets the driver
	 *
	 * @return object The driver
	 */

	public static function getDriver()
	{
		return self::$driver;
	}
}
