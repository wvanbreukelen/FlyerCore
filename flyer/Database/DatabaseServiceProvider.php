<?php

namespace Flyer\Components\Database;

use Flyer\Foundation\ServiceProvider;
use Flyer\Foundation\Config\Config;
use Flyer\Foundation\Registry;
use Illuminate\Database\Capsule\Manager as DatabaseHandler;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class DatabaseServiceProvider extends ServiceProvider
{

	protected $driver;
	protected $database;

	public function register()
	{
		$this->driver = new DatabaseHandler;

		$this->driver->addConnection(Config::get('database'));
		$this->driver->setEventDispatcher(new Dispatcher(new Container));
		$this->driver->setAsGlobal();
		$this->driver->bootEloquent();
	}

	public function boot()
	{
		$this->share('application.db', $this->driver);

		$this->app()->database()->table('users')->get();
	}
}