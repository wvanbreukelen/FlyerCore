<?php

use Flyer\Components\Router\Router;
use Flyer\Components\Router\Route;
use Flyer\Foundation\Events\Events;

class CreateRouteTest extends PHPUnit_Framework_TestCase
{
	public function testCreateRouteAndMatchesEvent()
	{

		$router = new Router;

		$router->setRequest(array('method' => "GET", 'path' => 'test'));

		$router->addRoute("GET", 'test', function() {
			return 'Hello World';
		});

		$router->route();

		$this->assertEquals('Hello World', Events::trigger('application.route'));


	}
}