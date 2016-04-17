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
	 * Guess the paths of a installed package
	 *
	 * @return String The package class
	 */
	protected function guessPackagePaths($path)
	{
		// Path storage in array
		$paths = array();

		// Resolve package path & realpath
		if (is_null($path)) $path = (new ReflectionClass($this))->getFileName();
		$realPath = realpath(dirname($path)) . DIRECTORY_SEPARATOR;

		// Getting Flyer\Components\Filesystem\Folder instance
		$folder = $this->app()->make('folder');

		// Make sure the path and service provider are actually on the place we want
		if (!$folder->is($realPath) && !$folder->is($path))
		{
			throw new Exception("Package path " . $realPath . " is invalid");
		}

		// @wvanbreukelen Might use views_paths helper in the future.
		// @wvanbreukelen Change function from views_paths() to views_path($basepath)

		if ($folder->is($viewsPath = $realPath . 'views')) $paths['views'] = $viewsPath;
		if ($folder->is($ctrlPath = $realPath . 'controllers')) $paths['controllers'] = $ctrlPath;
		if ($folder->is($modelsPath = $realPath . 'models')) $paths['models'] = $modelsPath;
		// Maybe process config files too in the future

		return $paths;
	}

	/**
	 * Sets the application instance
	 * @wvanbreukelen May be removed in the near future
	 *
	 * @param  App $app The application
	 * @return  void
	 */

	public static function setApp(App $app)
	{
		static::$app = $app;
	}
}
