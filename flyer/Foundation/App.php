<?php

namespace Flyer;

use Exception;
use RuntimeException;
use ReflectionClass;
use Flyer\Foundation\Container;
use Flyer\Foundation\ServiceProvider;
use Flyer\Foundation\Events\Events;
use Flyer\Foundation\AliasLoader;
use Flyer\Components\Security\BcryptHasher;
use Flyer\Components\Config;
use Flyer\Components\Logging\Debugger;
use Flyer\Components\Router\Router;
use Flyer\Components\Console\ConsoleHandler;
use Flyer\Components\Performance\Timer;
use Flyer\Foundation\Console\ListPackagesCommand as FlyerListPackagesCmd;

/**
 * The main application object, simply the core of your application. Extends the Illuminate container, for binding instances and stuff
*/
class App extends Container
{

	/**
	 * The application base path
	 */
	public $basePath;

	/**
	 * Holds the config object
	 */
	public $config;

	/**
	 * Holds the default backup file for logging purposes
	 * @var string
	 */
	public $defaultDebugFile = "debug.log";

	/**
	 * Holds all of the view compilers
	 */
	protected $viewCompilers = array();

	/**
	 * Holds all of the service providers
	 */
	protected $providers = array();

	/**
	 * Holds all of the registered commands
	 * @var array
	 */
	protected $commands = array();

	/**
	 * Holds all of the booted service providers
	 */
	protected $bootedProviders = array();

	/**
	 * Holds the current booting status of the application
	 */
	protected $booted = false;

	/**
	 * If the application is running in a console environment or not
	 * @var bool
	 */
	protected $console = false;

	/**
	 * The application instance
	 */
	protected static $app;

	/**
	 * Construct the application, provide an optional basepath
	 *
	 * @param object The config object the application has to use
	 */
	public function __construct($basePath = null)
	{
		$this->setBasePath($basePath);
		self::$app = $this;
	}

	/**
	 * Return the config instance
	 *
	 * @return Config The object instance
	 */
	public function config()
	{
		return $this->config;
	}

	/**
	 * Return the database instance
	 *
	 * @return object The database instance
	 */
	public function database()
	{
		return $this->make('application.db');
	}

	/**
	 * Return the debugger instance
	 *
	 * @return object The database instance
	 */
	public function debugger()
	{
		return $this->make('application.debugger');
	}

	public function performance()
	{
		if (!$this->exists('performance.timer'))
		{
			throw new Exception("Unable to resolve Performance instance, check service providers");
		}

		return $this->make('performance.timer');
	}

	/**
	 * Return the application view compiler instance
	 *
	 * @return object The view compiler instance
	 */
	public function viewCompiler()
	{
		return $this->make('application.view.compiler');
	}

	/**
	 * Bind a value to the application container
	 *
	 * @var mixed
	 */
	public function attach($id, $value = null)
	{
		if (is_callable($value))
		{
			$this[$id] = call_user_func($value);
		} else {
			$this[$id] = $value;
		}
	}

	/**
	 * Access a value that is attacted to the application container
	 *
	 * @var id Container id
	 * @param string $id
	 * @return  mixed Container value
	 */
	public function access($id)
	{
		if (isset($this[$id]))
		{
			return $this[$id];
		}

		throw new Exception("Cannot access [" . $id . "] in application container!");
	}

	/**
	 * Check if a asset exists in the container
	 * @param  string $id The id
	 * @return bool       Asset exists
	 */
	public function exists($id)
	{
		return (isset($this[$id]));
	}

	/**
	 * Removes a value that is attached to the application container
	 * @var  string
	 * @return  bool
	 */
	public function remove($id)
	{
		if (isset($this[$id]))
		{
			unset($this[$id]);
			return true;
		}

		return false;
	}

	/**
	 * Sets the View compilers of the application that can be used
	 * @var  array The View compilers
	 */
	public function setViewCompilers(array $viewCompilers = array())
	{
		$this->viewCompilers = $viewCompilers;
	}

	/**
	 * Creates aliases for specified classes
	 * @param array The classes
	 */
	public function createAliases(array $options = array())
	{
		foreach ($options as $alias => $class)
		{
			class_alias($class, $alias);
		}
	}

	/**
	 * Register an provider collection indivially
	 * @var mixed The provider collection
	 */
	public function register($providerCollection)
	{
		if (is_array($providerCollection))
		{
			foreach ($providerCollection as $provider)
			{
				// Does the given service provider exists?
				if (class_exists($provider))
				{
					// Create service provider instance
					$provider = new $provider;

					// Run service provider register method
					$provider->register();

					// Add service provider to providers array
					$this->providers[] = $provider;
				} else {
					// Might use a ProviderException in the future
					throw new Exception("Cannot load " . $provider . " service provider, because the provider does not exists!");
				}
			}

			// Register view compilers
			$this->registerViewCompilers();

			// Register list packages command
			$this->registerCommand(new FlyerListPackagesCmd);
		} else if (is_object($providerCollection)) {
			if (class_exists($providerCollection))
			{
				$provider = new $providerCollection;

				// Run service provider register method
				$provider->register();

				// Add service provider to providers array
				$this->providers[] = $provider;
			} else {
				throw new Exception("Cannot load " . $provider . " service provider, because the provider does not exists!");
			}

			// Register all view compilers
			$this->registerViewCompilers();

			// Register list packages command
			$this->registerCommand(new FlyerListPackagesCmd);
		} else {
			throw new Exception("Unable to register provider, given input variable has to be an array or object, not an " . gettype($providerCollection));
		}
	}

	/**
	 * Register a command to the application
	 * @param  object $command The command object
	 * @return mixed
	 */
	public function registerCommand($command)
	{
		if ($this->isConsole())
		{
			if (is_string($command))
			{
				$this->commands[] = new $command;
			} else if (is_object($command)) {
				$this->commands[] = $command;
			} else {
				throw new Exception("Únable to register command with the type of " . gettype($command));
			}
		}
	}

	/**
	 * Register all the compilers that where registered into the application
	 * @var  $config Out of the config
	 */
	protected function registerViewCompilers()
	{
		foreach ($this->viewCompilers as $viewCompilerID => $viewCompiler)
		{
			if (class_exists($viewCompiler))
			{
				$this->viewCompiler()->addCompiler($viewCompilerID, new $viewCompiler);
			} else {
				throw new Exception("Unable to register " . $viewCompiler . " view compiler. May include view compiler in config");
			}
		}
	}

	/**
	 * Set the application debugger handler
	 * @param Debugger $debugger The application debugger
	 */
	public function setDebuggerHandler(Debugger $debugger)
	{
		$this->attach('application.debugger', $debugger);
	}

	/**
	 * Get the applicatiuon debugger handler
	 * @return Debugger $debugger The application debugger
	 */
	public function getDebuggerHandler()
	{
		return $this->make('application.debugger');
	}

	public function setPerformanceMonitor(Timer $timer)
	{
		// Creating new section
		//$timer->openSection();

		$this->attach('performance.timer', $timer);
	}

	 /**
	 * Set the application console handler
	 * @param Console $console The console handler that is the paste between comminucation with the framework and the console application
	 */
	public function setConsoleHandler(ConsoleHandler $console)
	{
		if ($this->isConsole())
		{
			$this->attach('application.console', $console);
		}
	}

	/**
	 * Get the application console handler
	 * @return Console The application console handler
	 */
	public function getConsoleHandler()
	{
		if ($this->isConsole())
		{
			return $this->make('application.console');
		}

		throw new Exception("Cannot return console handler, not running in console modus");
	}

	/**
	 * Resolves the debug file location for logging purposes
	 * @return string The debug file location path
	 */
	public function resolveDebugFile()
	{
		try
		{
			$configDebugFile = $this->access('env')['defaultDebugFile'];

			if (strlen($configDebugFile) > 0)
			{
				return $this->debugPath() . $configDebugFile;
			} else {
				return $this->debugPath() . $this->defaultDebugFile;
			}
		} catch (Exception $e) {
			throw new Exception("Environmental error: " . $e->getMessage());
		}
	}

	/**
	 * Set up the application environment
	 */
	public function setEnvironment()
	{
		if (!isset($this['env']))
		{
			$this->attach('env', $this->config->get('environment'));
		}

		if (defined('ENV_CONSOLE'))
		{
		    if (ENV_CONSOLE)
		    {
		        $this->runAsConsole();
		    }
		}
	}

	/**
	 * Get the application environment
	 * @return mixed
	 */
	public function getEnvironment()
	{
		if (isset($this['env']))
		{
			return $this->access('env');
		} else {
			throw new Exception("Cannot access environment, is has not been set!");
		}
	}

	/**
	 * Set the application base path
	 * @param String The base path
	 */
	public function setBasePath($basePath)
	{
		if (is_null($basePath))
		{
			$this->basePath = realpath(getcwd() . '/../') . DIRECTORY_SEPARATOR;
		} else {
			$this->basePath = rtrim($basePath, '/') . DIRECTORY_SEPARATOR;
		}

		$this->bindPathsInContainer();
	}

	/**
	 * Bind all needed paths into the container
	 * @return mixed
	 */
	protected function bindPathsInContainer()
	{
		$this->instance('path', $this->path());

		$paths = array('app', 'base', 'config', 'cache', 'controllers', 'debug', 'models', 'storage', 'views');

		foreach ($paths as $path)
		{
			$this->instance('path.' . $path, $this->{$path . 'Path'}());
		}
	}

	/**
	 * Get the application base path
	 * @return string The app base path
	 */
	public function path()
	{
		return $this->appPath();
	}

	/**
	 * Get the application path
	 * @return string The app path
	 */
	public function appPath()
	{
		return $this->basePath() . 'app' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the base path
	 * @return string Base path
	 */
	public function basePath()
	{
		return $this->basePath;
	}

	/**
	 * Get the config path
	 * @return string Config containing path
	 */
	public function configPath()
	{
		return $this->appPath() . 'config' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the cache path
	 * @return string Cache containing path
	 */
	public function cachePath()
	{
		return $this->appPath() . 'cache' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the path where the controllers are located
	 * @return string Controller containing path
	 */
	public function controllersPath()
	{
		return $this->appPath() . 'controllers' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the debug path
	 * @return string Debug containing path
	 */
	public function debugPath()
	{
		return $this->appPath() . 'debug' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the models path
	 * @return string Models containing path
	 */
	public function modelsPath()
	{
		return $this->appPath() . 'models' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the storage path
	 * @return string Storage containing path
	 */
	public function storagePath()
	{
		return $this->appPath() . 'storage' . DIRECTORY_SEPARATOR;
	}

	/**
	 * The views path
	 * @return string Views containing path
	 */
	public function viewsPath()
	{
		return $this->appPath() . 'views' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Register any given facade
	 * @param  string $alias The alias for this facade
	 * @param  string $class The class ifself
	 * @return mixed
	 */
	protected function registerFacade($alias, $class)
	{
		if (class_exists($class))
		{
			$this[$alias] = new $class;
		}
	}

	/**
	 * Boot the application, boots all of the registered service providers
	 *
	 * @return  void
	 */
	public function boot()
	{
		foreach ($this->providers as $provider)
		{
			$provider->boot();
		}

		$this->booted = true;
		return $this;
	}

	/**
	 * Abort the currect application request
	 * @param  integer  $error     HTTP type of error
	 * @return mixed
	 */
	public function abort($error)
	{
		exit(Router::triggerErrorPage($error));
	}

	/**
	 * Trigger the final events to shutdown the application, and display it's output to the user
	 * @return mixed The application response output
	 */
	public function shutdown()
	{
		if (!$this->booted)
		{
			throw new Exception("Application has not been booted");
		}

		// Check if the application is running in a console
		if ($this->isConsole())
		{
			// Is the application running in a console, run the console application
			$response = $this->getConsoleHandler()->run();
		} else {
			// Make the application.route container and set the response
			if ($this->exists('application.route'))
			{
				$response = $this->make('application.route');
			} else if ($this->exists('application.error.404')) {
				$response = Router::triggerErrorPage(404);
			} else {
				throw new RuntimeException("No route/404 route found, unable to return response");
			}
		}

		// Handle off some final debugging
		if ($this->isRunningDebug())
		{
			$this->getDebuggerHandler()->process($this->make('log'));
		}

		// Finally return the response
		return $response;
	}

	/**
	 * Run the application as a console
	 * @return mixed
	 */
	public function runAsConsole()
	{
		$this->console = true;

		$this->setBasePath(getcwd());
	}

	/**
	 * Removes all service providers out of the pending boot payload
	 * @return  void
	 */
	public function resetProviders()
	{
		unset($this->providers);
	}

	/**
	 * Returns all for the registered service providers
	 * @return array The service providers
	 */
	public function getProviders()
	{
		return $this->providers;
	}

	/**
	 * Returns the array containing all of the registered commands
	 * @return array The registered commands
	 */
	public function getCommands()
	{
		return $this->commands;
	}

	/**
	 * Returns the application booting status
	 * @return bool The booting status
	 */
	public function isBooted()
	{
		return $this->booted;
	}

	/**
	 * Is the application running in a console
	 * @return boolean The status
	 */
	public function isConsole()
	{
		return $this->console;
	}

	/**
	 * Is the application running in debug mode
	 * @return boolean
	 */
	public function isRunningDebug()
	{
		return $this->config()->get('environment')['debug'];
	}

	/**
	 * Is the application logging performance
	 * @return boolean
	 */
	public function isLoggingPerformance()
	{
		return $this->config()->get('environment')['logperformance'];
	}

	/**
	 * Set the application config handler
	 * @param Config $config Config handler
	 */
	public function setConfig(Config $config)
	{
		$this->config = $config;
	}

	/**
	 * Get the current application instance
	 *
	 * @return  App The application instance
	 */
	public static function getInstance()
	{
		return self::$app;
	}
}
