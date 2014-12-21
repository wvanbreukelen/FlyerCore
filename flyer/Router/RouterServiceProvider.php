<?php

namespace Flyer\Components\Router;

use Flyer\Foundation\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use App;

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