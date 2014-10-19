<?php

namespace Flyer\Components\Router;

use Flyer\Foundation\ServiceProvider;
use Flyer\Foundation\Events\Events;
use Flyer\Foundation\Config\Config;
use Symfony\Component\HttpFoundation\Request;

class RouterServiceProvider extends ServiceProvider
{

	protected $router;

	public function register()
	{
		$this->router = new Router();
		
		$this->router->setRequest(Request::createFromGlobals());

		$this->share('route', new Route());
	}	

	public function boot()
	{
		$this->router->route();
	}
}