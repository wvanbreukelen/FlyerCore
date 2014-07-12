<?php

use Flyer\Foundation\Registry;

/**
 * Testing the Registry component using PHPUnit
 */

class RegistryTest extends PHPUnit_Framework_TestCase
{
	public function testSetAndDeleteRegistry()
	{
		Registry::set('item', 'Hello World');
		$this->assertEquals(true, Registry::exists('item'));
		Registry::delete('item');
		$this->assertEquals(false, Registry::exists('item'));
	}

	public function testSetAndUpdateRegistry()
	{
		Registry::set('item', 'Hello World');
		Registry::update('item', 'Hello World 2');
		$this->assertEquals('Hello World 2', Registry::get('item'));
		Registry::delete('item');
	}

	public function testSetAndGetFullRegistry()
	{
		Registry::set('fullregistry', 'Hello World');
		$this->assertEquals(true, in_array('fullregistry', Registry::registry()));
		Registry::delete('fullregistry');
	}
}