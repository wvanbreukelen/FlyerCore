<?php

namespace Flyer\Foundation;

use Flyer\App;

use ReflectionClass;

abstract class ServiceProvider
{	

	protected static $app;

	public function boot() {}

	abstract public function register();

	public function package($package, $namespace = null, $path = null)
	{
		//$namespace = $this->resolvePackageNamespace($package, $namespace);

		$path = $path ?: $this->guessPackagePath();

		// Processing views

		$views = $path . '/views';
		
		if ($this->app()['folder']->is($views))
		{
			
		}

		// Processing routes
		

		// Processing models
		
		
		// Processing controllers
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

	protected function guessPackagePath()
	{
		$path = (new ReflectionClass($this))->getFileName();

		return realpath(dirname($path . '/../../'));
	}

	protected function resolvePackageNamespace($package, $namespace)
	{
		if (is_null($namespace))
		{
			list($vendor, $namespace) = explode('/', $package);
		}

		return $vendor;
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
}