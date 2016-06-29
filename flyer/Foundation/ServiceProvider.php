<?php

namespace Flyer\Foundation;

use Flyer\App;
use Exception;
use ReflectionClass;
use ViewFinder;

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
	 * @return mixed
	 */
	abstract public function register();

	/**
	 * Boot the service provider
	 *
	 * @return mixed
	 */
	public function boot() {}


	/**
	 * Add package to Flyer PHP framework
	 *
	 * @param string $package
	 */
	public function package($package, $packagePath = null)
	{
		//$namespace = $this->resolvePackageNamespace($package, $namespace);

		// Resolving some paths
		// @wvanbreukelen Might change helper views_path() to views_path($basepath)
		$paths = $this->guessPackagePaths($packagePath);

		// Processing views
		if (isset($paths['views']))
		{
			// Add the view path to the ViewFinder
			$vFinder = $this->app()->make('application.view.finder');
			$vFinder->addViewsPath($paths['views']);
		}

		// Processing routes
		if (isset($paths['routes'])) {}

		// Processing models
		if (isset($paths['models'])) {}

		// Processing controllers
		if (isset($paths['controllers'])) {}
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
		return app();
	}

	/**
	 * Guess the paths of an installed package
	 *
	 * @return array All of the resolved package paths
	 */
	protected function guessPackagePaths($path)
	{
		// Path storage in array
		$paths = array();

		// Resolve package path & realpath
		if (is_null($path))
		{
			try {
				$path = (new ReflectionClass($this))->getFileName();
			} catch (Exception $e) {
				throw new Exception("Unable to reflect " . $path);
			}
		}

		// Resolve the realpath of the package
		$realPath = realpath(dirname($path)) . DIRECTORY_SEPARATOR;

		// Receiving instance of Flyer Folder component
		$folder = $this->app()->make('folder');

		// Make sure the path and service provider are actually on the place we want
		if ($folder->is($realPath) && $folder->is($path))
		{
			throw new Exception("Unexpected exception. Reflected package path " . $path . " was not found");
		}

		// @wvanbreukelen Might use views_paths helper in the future.
		// Original: $viewsPath = $realPath . 'views'
		// $ctrlPath = $realPath . 'controllers'
		// $modelsPath = $realPath . 'models'
		// @wvanbreukelen Change function from views_paths() to views_path($basepath)

		if ($folder->is(views_path($realPath))) $paths['views'] = $viewsPath;
		if ($folder->is($ctrlPath = $realPath . 'controllers')) $paths['controllers'] = $ctrlPath;
		if ($folder->is($modelsPath = $realPath . 'models')) $paths['models'] = $modelsPath;
		// @wvanbreukelen Maybe process config files too in the future

		return $paths;
	}
}
