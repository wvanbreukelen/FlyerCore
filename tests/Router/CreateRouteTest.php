<?php

use Flyer\Components\Router\Router;
use Flyer\Components\Router\Route;
use Flyer\Foundation\Events\Events;
use Symfony\Component\HttpFoundation\Request;

class CreateRouteTest extends PHPUnit_Framework_TestCase
{
	public function testCreateRouteAndMatchesEvent()
	{

		$router = new Router;

		$router->setRequest(array('method' => "GET", 'path' => 'test'));

		$this->assertTrue(is_array($router->getRequest()));

		$router->addRoute("GET", 'test', function() {
			return 'Hello World';
		});

		$router->addRoute("GET", 'test2', function() {
			return 'Hello World2';
		});

		$router->addRoute("POST", 'test', function() {
			return 'Hello World3';
		});

		$router->route();

		$event = Events::trigger('application.route');

		$this->assertEquals('Hello World', $event);
		$this->assertFalse('Hello World2' == $event);
		$this->assertFalse('Hello World3' == $event);
		



		$router->setRequest(Request::createFromGlobals());
		$this->assertTrue(is_array($router->getRequest()));

	}
}