<?php

namespace Flyer\Foundation;

use Flyer\App;

use ReflectionClass;

abstract class ServiceProvider
{

	/**
	 * Holds the application instance
	 * @var object Flyer\App The application instance
	 */
	protected static $app;

	/**
	 * Boot's the ServiceProvider provider
	 * @return [type] [description]
	 */
	public function boot() {}

	abstract public function register();

	/**
	 * @wvanbreukelen Is this needed?
	 * @param string $package
	 */
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

	/**
	 * @param string $id
	 */
	public function share($id, $value = null)
	{
		return $this->app()->attach($id, $value);
	}

	public function command($command)
	{
		$this->app()->registerCommand($command);
	}

	/**
	 * @param string $id
	 */
	public function make($id)
	{
		return $this->app()->make($id);
	}

	/**
	 * Returns the instance of the application
	 *
	 * @return App The app
	 */

	public function app()
	{
		return self::$app;
	}

	/**
	 * Guess the path of a installed package
	 * @return String The package class
	 */
	protected function guessPackagePath()
	{
		$path = (new ReflectionClass($this))->getFileName();

		return realpath(dirname($path . '/../../'));
	}

	/**
	 * Sets the application instance
	 *
	 * @param  App $app The application
	 * @return  void
	 */

	public static function setApp(App $app)
	{
		self::$app = $app;
	}
}
