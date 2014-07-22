<?php

namespace Flyer\Components\Router;

use Flyer\Foundation\ServiceProvider;
use Flyer\Foundation\Events\Events;
use Flyer\Foundation\Config;
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

		$this->router->setRequest(explode(Config::get('basePath'), Request::createFromGlobals())[1]);

		$this->share('route', new Route());
	}	
}