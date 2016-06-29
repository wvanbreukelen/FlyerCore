<?php

namespace Flyer\Components\Router;

use Flyer\Foundation\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Flyer\Components\Router\Console\RouteListCommand as FlyerRouteListCmd;
use Flyer\Components\Router\Console\SimulateRouteCommand as FlyerSimulateRouteCmd;

class RouterServiceProvider extends ServiceProvider
{

	/**
	 * HTTP router instance
	 * @var object \Flyer\Components\Router\Router
	 */
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
		// Perform actual routing
		$this->router->route();
	}

	/**
	 * Register all the commands for the console application
	 * @return mixed
	 */
	protected function registerCommands()
	{
		// Flyer\Components\Router\Console\RouteListCommand
		$this->command(new FlyerRouteListCmd);

		// Flyer\Components\Router\Console\SimulateRouteCommand
		$this->command(new FlyerSimulateRouteCmd);
	}
}
