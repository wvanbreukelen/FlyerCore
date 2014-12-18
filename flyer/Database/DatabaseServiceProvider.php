<?php

namespace Flyer\Components\Database;

use Flyer\Foundation\ServiceProvider;
use Illuminate\Database\Capsule\Manager as DatabaseHandler;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Config;

class DatabaseServiceProvider extends ServiceProvider
{

	protected $driver;
	protected $database;

	public function register()
	{
		// Create a new Illuminate ORM instance

		$this->driver = new DatabaseHandler;
	}

	public function boot()
	{
		// Prepare the database driver

		$this->driver->addConnection(Config::get('database'));
		$this->driver->setEventDispatcher(new Dispatcher(new Container));
		$this->driver->setAsGlobal();
		$this->driver->bootEloquent();
		
		// Bind the ORM to the application container

		$this->share('application.db', $this->driver);
	}
}