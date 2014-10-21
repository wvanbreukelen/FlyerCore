<?php

namespace Flyer;

use Exception;
use Flyer\Foundation\Container;
use Flyer\Foundation\ServiceProvider;
use Flyer\Foundation\Events\Events;
use Flyer\Foundation\Config\Config;
use Flyer\Foundation\AliasLoader;
use Flyer\Components\Security\BcryptHasher;

/**
 * The main application object
 */

class App extends Container
{

	/**
	 * Holds the config object
	 */
	
	public $config;

	/**
	 * Holds all of the view compilers
	 */
	
	protected $viewCompilers = array();

	/**
	 * Holds all of the service providers
	 */

	protected $providers = array();

	/**
	 * Holds all of the booted service providers
	 */

	protected $bootedProviders = array();

	/**
	 * Holds the current booting status of the application
	 */

	protected $booted = false;

	/**
	 * Holds the registry handler that is used the application
	 */

	protected $registryHandler;

	/**
	 * The application instance
	 */

	protected $app;

	/**
	 * Construct
	 *
	 * @param object The config object the application has to use
	 */

	public function __construct(Config $config)
	{
		$this->app = $this;
		$this->config = $config;

		ServiceProvider::setApp($this);
	}
	
	/**
	 * Returns the config instance
	 *
	 * @return object The object instance
	 */

	public function config()
	{
		return $this->config;
	}

	/**
	 * Returns the Registry instance
	 *
	 * @return object The registry instance
	 */
	
	public function registry()
	{
		return $this->registryHandler->registry();
	}

	/**
	 * Returns the database instance
	 *
	 * @return object The database instance
	 */

	public function database()
	{
		return $this['application.db'];
	}

	/**
	 * Returns the view instance
	 *
	 * @return object The view compiler instance
	 */
	
	public function viewCompiler()
	{
		return $this['application.view.compiler'];
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
	 * @return  mixed Container value
	 */
	
	public function access($id)
	{
		if (isset($this[$id]))
		{
			return $this[$id];
		}

		throw new Exception("App: Cannot access " . $id);
	}

	/**
	 * Sets the registry handler that the application has to use
	 *
	 * @var  object The registry handler
	 * @return  void
	 */

	public function setRegistryHandler($handler)
	{
		$this->registryHandler = $handler;
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
					throw new Exception("Cannot load " . $provider . " because the Service Provider does not exists!");
				}
			}

			$this->registerCompilers();
		} else if (is_object($providerCollection)) {
			$provider = new $providerCollection;

			$provider->register();
			$this->providers[] = $provider;

			$this->registerCompilers();
		} else {
			throw new Exception("Unable to register provider(s), variable type has to been a array or object, not " . gettype($providerCollection));
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
				throw new \Exception("Unable to register view compiler " . $viewCompiler . ", because it does not exists");
			}
		}
	}

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
	}
	
	/**
	 * Trigger the final events to shutdown the application
	 */

	public function shutdown()
	{
		if (!$this->booted) throw new \Exception("App: Application cannot been shutdown, it has to be in a booted state!");
		if (Events::exists('application.route'))
		{
			echo Events::trigger('application.route');
		} else {
			echo $this->triggerErrorPage('404');
		}
	}
	
	/**
	 * Triggers the error page, developer has to give the HTTP error code
	 * 
	 * @param $error The HTTP error code
	 */

	public function triggerErrorPage($error)
	{
		return Events::trigger('application.error.' . $error);
	}

	/**
	 * Removes all Service Providers out of the pending boot payload
	 *
	 * @return  void 
	 */

	public function resetProviders()
	{
		unset($this->providers);
	}
	
	/**
	 * Returns the application booting status
	 *
	 * @return bool The booting status
	 */

	public function booted()
	{
		return $this->booted;
	}

	/**
	 * Gets the instance of the app object
	 *
	 * @return  object The app instance
	 */
	
	public function getInstance()
	{
		return $this;
	}
}
