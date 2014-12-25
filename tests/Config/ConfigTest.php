<?php

use Flyer\Components\Config;

class ConfigTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->prepare();
	}

	public function testConfigAddResourceFile()
	{
		$this->config->import(__DIR__ . '/RandomConfigFile.php');

		//$this->assertEquals($this->config->getResources(), $this->config->import(__DIR__ . '/RandomConfigFile.php'));
	}

	public function testConfigResourceExists()
	{
		$this->assertTrue($this->config->exists('environment'));
		$this->assertTrue($this->config->exists('serviceProviders'));

		$this->assertFalse($this->config->exists('foobar'));
	}

	public function testGetConfigResource()
	{
		$resource = $this->config->get('environment');
		$config = array('debug' => true,
		'defaultDebugFolder' => 'debug.log',
		'url' => 'localhost/workspace/public/');

		$this->assertEquals($resource, $config);

		$resource = $this->config->get('foobar');

		$this->assertFalse($resource);
	}

	public function testGetMultipleResources()
	{
		$resources = array('environment', 'serviceProviders');

		$expects = array(
			'environment' => array(
				'debug' => true,
				'defaultDebugFolder' => 'debug.log',
				'url' => 'localhost/workspace/public/',
			),
			'serviceProviders' => array(
				'Flyer\Components\Router\RouterServiceProvider',
				'Flyer\Components\View\ViewServiceProvider',
				'Flyer\Components\Database\DatabaseServiceProvider',
				'Flyer\Components\Security\SecurityServiceProvider',
				'Flyer\Components\Filesystem\FilesystemServiceProvider',
				'Flyer\Components\Random\RandomServiceProvider',
				'Flyer\Components\Server\ServerServiceProvider',
				'Flyer\Components\Config\ConfigServiceProvider',
				'Flyer\Components\Logging\LoggingServiceProvider',
				'Flyer\Components\HTML\HTMLServiceProvider',
			)
		);

		$this->assertEquals($this->config->gets($resources), $expects);
	}

	public function testAddConfigResource()
	{
		$resource = array(
			'simpleconfig' => array('foo' => 'test', 'bar' => 'test')
		);

		$this->config->add($resource);

		$expects = array(
			'foo' => 'test', 'bar' => 'test'
		);

		$this->assertEquals($this->config->get('simpleconfig'), $expects);
	}

	protected function prepare()
	{
		$this->config = new Config;
	}
}