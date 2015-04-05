<?php

use Flyer\App;
use Flyer\Components\Config;

class AppTest extends PHPUnit_Framework_TestCase
{
	public function testAppFabrication()
	{
		$app = new App();

		$app->setConfig(new Config());

		$this->assertTrue(is_object($app));

		$app->boot();
	}
}