<?php

namespace Flyer\Foundation;

use Flyer\App;

abstract class ServiceProvider
{	

	public static $app;

	public function boot() {}

	abstract public function register();

	public function package($package, $namespace = null, $path = null)
	{
		// Processing views
		

		// Processing routes
		

		// Processing models
		
		
		// Processing controllers
	}

	/**
	 * Sets the application instance
	 *
	 * @param  \App $app The application
	 * @return  void
	 */

	public static function setApp(App $app)
	{
		self::$app = $app;
	}

	public function share($id, $value = null)
	{
		$this->app()[$id] = $value;
	}

	/**
	 * Returns the instance of the application
	 *
	 * @return object The app
	 */

	public function app()
	{
		return self::$app;
	}
}