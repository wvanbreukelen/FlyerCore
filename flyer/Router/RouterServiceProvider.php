<?php

namespace Flyer\Components\Router;

use Flyer\Foundation\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class RouterServiceProvider extends ServiceProvider
{

	protected $router;

	public function register()
	{
		$this->router = new Router();
		$this->router->setRequest(Request::createFromGlobals());

		$this->share('route', new Route());
		$this->registerCommands();
	}

	public function boot()
	{
		$this->router->route();
	}

	/**
	 * Register all the commands for the console application
	 * @return mixed
	 */
	protected function registerCommands()
	{
		$this->command(new \Flyer\Components\Router\Console\RouteListCommand);
		$this->command(new \Flyer\Components\Router\Console\SimulateRouteCommand);
	}
}
