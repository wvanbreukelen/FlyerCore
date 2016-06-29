<?php

use Flyer\Components\Router\Router;
use Flyer\Components\Router\Route;
use Flyer\Foundation\Events\Events;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpRequest;

class CreateRouteTest extends PHPUnit_Framework_TestCase
{

	protected $router;

	public function setUp()
	{
		$this->router = new Router;
	}

	public function testRouteCreation()
	{
		$this->assertEmpty($this->router->getRoutes());

		$this->createTestRoutes();

		$this->assertEquals(3, count($this->router->getRoutes()));

		$this->router->removeRoute('test_1');
		$this->router->removeRoute('test_3');

		$this->assertEquals(1, count($this->router->getRoutes()));

		$this->assertTrue(isset($this->router->getRoutes()['test_2']));
	}

	/**
	 * @depends testRouteCreation
	 */
	public function testHandleOfClosure()
	{
		$this->router->generateRouteEvent('test_1');
	}

	public function testSetRequestInRouter()
	{
		$this->router->setRequest(new SymfonyHttpRequest);

		$this->assertArrayHasKey('method', $this->router->getRequest());
		$this->assertArrayHasKey('path', $this->router->getRequest());

		$this->assertEquals(2, count($this->router->getRequest()));

		if (is_array($this->router->getRequest()['method']))
		{
			$this->assertContains($this->router->getRequest()['method'], ['GET', 'POST', 'UPDATE', 'DELETE']);
		}

		$this->assertNull($this->router->getRequest()['method']);

		$this->assertTrue(is_string($this->router->getRequest()['path']));
	}

	/**
	 * @depends testRouteCreation
	 */
	public function testCreateRouteAndMatchesEvent()
	{
		$this->router->setRequest(array('method' => "GET", 'path' => 'test'));

		$this->assertTrue(is_array($this->router->getRequest()));

		$this->createTestRoutes();

		//$this->assertEquals();

		/**$router->route();

		$event = Events::trigger('application.route');

		$this->assertEquals('Hello World', $event);
		$this->assertFalse('Hello World2' == $event);
		$this->assertFalse('Hello World3' == $event);




		$router->setRequest(Request::createFromGlobals());
		$this->assertTrue(is_array($router->getRequest()));**/

	}

	private function createTestRoutes()
	{
		$this->router->addRoute("GET", 'test_1', function() {
			return 'Hello Bob';
		});

		$this->router->addRoute("GET", 'test_2', function() {
			return 'Hello Anne';
		});

		$this->router->addRoute("POST", 'test_3', function() {
			return 'Hello Taylor';
		});
	}
}
