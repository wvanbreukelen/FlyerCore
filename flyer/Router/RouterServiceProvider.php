<?php

namespace Flyer\Components\Router;

use Flyer\Foundation\ServiceProvider;
use Flyer\Foundation\Events\Events;
use Flyer\Foundation\Config\Config;
use Symfony\Component\HttpFoundation\Request;

class RouterServiceProvider extends ServiceProvider
{

	private $router;

	public function boot()
	{
		$this->router->route();
	}

	public function register()
	{
		$this->router = new Router();
		
		$this->router->setRequest(Request::createFromGlobals());

		$this->share('route', new Route());
	}	
}