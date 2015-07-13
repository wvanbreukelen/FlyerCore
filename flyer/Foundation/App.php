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
	 * Construct
	 *
	 * @param object The config object the application has to use
	 */
	public function __construct($basePath = null)
	{
		$this->setBasePath($basePath);
		self::$app = $this;

		ServiceProvider::setApp($this);
	}

	/**
	 * Returns the config instance
	 *
	 * @return Config The object instance
	 */
	public function config()
	{
		return $this->config;
	}

	/**
	 * Returns the database instance
	 *
	 * @return object The database instance
	 */
	public function database()
	{
		return $this->make('application.db');
	}

	/**
	 * Returns the debugger instance
	 *
	 * @return object The database instance
	 */
	public function debugger()
	{
		return $this->make('application.debugger');
	}

	/**
	 * Returns the application view compiler instance
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
	 * Remove a value that is attached to the application container
	 *
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

		throw new Exception("Cannot remove [" . $id . "] from the container, because this asset does not exists!");
	}

	/**
	 * Sets the View compilers of the application that can be used
	 *
	 * @var  array The View compilers
	 */
	public function setViewCompilers(array $viewCompilers = array())
	{
		$this->viewCompilers = $viewCompilers;
	}

	/**
	 * Creates aliases for specified classes
	 *
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
	 * Import a ServiceProvider into the application, and run the register method in the Service Provider
	 *
	 * @var mixed The Service Provider(s), a array or an object
	 */
	public function register($providerCollection)
	{
		if (is_array($providerCollection))
		{
			foreach ($providerCollection as $provider)
			{
				if (class_exists($provider))
				{
					$provider = new $provider;
					$provider->register();

					$this->providers[] = $provider;
				} else {
					throw new Exception("Cannot load " . $provider . " service provider, because the provider does not exists!");
				}
			}

			$this->registerCompilers();
			$this->registerCommand(new Foundation\Console\ListPackagesCommand);
		} else if (is_object($providerCollection)) {
			$provider = new $providerCollection;

			$provider->register();
			$this->providers[] = $provider;

			$this->registerCompilers();
			$this->registerCommand(new Foundation\Console\ListPackagesCommand);
		} else {
			throw new Exception("Unable to register provider, given input variable has to be an array or object, not a " . gettype($providerCollection));
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
				$this->commands[] = $command;
			} else if (is_object($command)) {
				$this->commands[] = get_class($command);
			} else if (is_array($command)) {
				$this->commands = array_merge($command, $this->commands);
			} else {
				throw new Exception("Not able to register this kind of command");
			}
		}
	}

	/**
	 * Register all the compilers that where registered into the application
	 *
	 * @var  $config Out of the config
	 */
	protected function registerCompilers()
	{
		foreach ($this->viewCompilers as $viewCompilerID => $viewCompiler)
		{
			if (class_exists($viewCompiler))
			{
				$this->viewCompiler()->addCompiler($viewCompilerID, new $viewCompiler);
			} else {
				throw new Exception("Unable to register " . $viewCompiler . " view compiler, because the compiler is not registered to this application");
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

		throw new Exception("Cannot return console handler, not running in console!");
	}

	/**
	 * Resolves the debug file location for logging purposes
	 * @return string The debug file location path
	 */
	public function resolveDebugFile()
	{
		$configDebugFile = $this->access('env')['defaultDebugFile'];

		if (strlen($configDebugFile) > 0)
		{
			return $this->debugPath() . $configDebugFile;
		} else {
			return $this->debugPath() . $this->defaultDebugFile;
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
	 * @param String $basePath The basepath
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

		foreach (['app', 'base', 'bindings', 'config', 'debug', 'storage', 'views'] as $path)
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
		return $this->basePath . 'app' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the application path
	 * @return string The app path
	 */
	public function appPath()
	{
		return $this->path();
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
	 * Get the bindings path
	 * @return string Bindings path
	 */
	public function bindingsPath()
	{
		return $this->path() . 'bindings' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the config path
	 * @return string Config path
	 */
	public function configPath()
	{
		return $this->path() . 'config' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the debug path
	 * @return string Debug path
	 */
	public function debugPath()
	{
		return $this->path() . 'debug' . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the storage path
	 * @return string Storage path
	 */
	public function storagePath()
	{
		return $this->path() . 'storage' . DIRECTORY_SEPARATOR;
	}

	/**
	 * The views path
	 * @return string Views path
	 */
	public function viewsPath()
	{
		return $this->path() . 'views' . DIRECTORY_SEPARATOR;
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
	 * Boot the application, boots all of the imported Service Providers
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
			throw new Exception("Application cannot been shutdown, it has not been booted!");
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
				throw new RuntimeException("Unable to return a response, please check app code");
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
	 * @return boolean The status
	 */
	public function isRunningDebug()
	{
		return $this->config()->get('environment')['debug'];
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
