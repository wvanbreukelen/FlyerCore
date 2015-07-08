<?php

namespace Flyer\Components\Database;

use Flyer\Foundation\ServiceProvider;
use Illuminate\Database\Capsule\Manager as IlluminateDatabaseHandler;
use Illuminate\Events\Dispatcher as IlluminateDispatcher;
use Illuminate\Container\Container as IlluminateContainer;
use Config;

class DatabaseServiceProvider extends ServiceProvider
{

	protected $driver;
	protected $database;

	public function register()
	{
		// Create a new Illuminate ORM instance
		$this->driver = new IlluminateDatabaseHandler;

		// Bind the ORM to the application container
		$this->share('application.db', $this->driver);
	}

	public function boot()
	{
		// Prepare the database driver
		$this->driver->addConnection(Config::get('database'));
		$this->driver->setEventDispatcher(new IlluminateDispatcher(new IlluminateContainer));
		$this->driver->setAsGlobal();
		$this->driver->bootEloquent();
	}
}
