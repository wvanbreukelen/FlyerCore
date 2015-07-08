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

	protected function registerCommands()
	{
		$this->command('Flyer\Components\Router\Console\RouteListCommand');
		$this->command('Flyer\Components\Router\Console\SimulateRouteCommand');
	}
}
