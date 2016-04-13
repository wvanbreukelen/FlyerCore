<?php

namespace Flyer\Foundation;

use Flyer\App;
use ReflectionClass;

/**
 * The mother of service providers. Every service provider has to extend this abstract class.
 */
abstract class ServiceProvider
{

	/**
	 * Holds the application instance
	 *
	 * @var object Flyer\App The application instance
	 */
	protected static $app;

	/**
	 * Register is for the prework, like setting up database connections
	 *
	 * @return [type] [description]
	 */
	abstract public function register();

	/**
	 * Boot the service provider
	 *
	 * @return mixed
	 */
	public function boot() {}


	/**
	 * "Will be removed in the future"
	 *
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
	 * Share something to the application container
	 *
	 * @param string $id
	 */
	public function share($id, $value = null)
	{
		return $this->app()->attach($id, $value);
	}

	/**
	 * Register any given command into the application
	 *
	 * @param  command $command The command
	 * @return mixed
	 */
	public function command($command)
	{
		$this->app()->registerCommand($command);
	}

	/**
	 * Makes something out of the application container
	 *
	 * @param string $id
	 */
	public function make($id)
	{
		return $this->app()->make($id);
	}

	/**
	 * Returns the current application instance
	 *
	 * @return App The app
	 */

	public function app()
	{
		return static::$app;
	}

	/**
	 * Guess the path of a installed package
	 *
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
		static::$app = $app;
	}
}
