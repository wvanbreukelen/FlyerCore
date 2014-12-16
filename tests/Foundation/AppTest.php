<?php

use Flyer\App;
use Flyer\Components\Config;
use Flyer\Foundation\Registry;

class AppTest extends PHPUnit_Framework_TestCase
{
	public function testAppFabrication()
	{
		$app = new App(new Config);

		$app->setRegistryHandler(new Registry);

		$this->assertTrue(is_object($app));
	}
}