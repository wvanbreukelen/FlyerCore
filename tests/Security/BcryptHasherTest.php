<?php

use Flyer\Components\Security\BcryptHasher;

class BcryptHasherTest extends PHPUnit_Framework_TestCase
{
	public function testBasicHashing()
	{
		$hasher = new BcryptHasher;
		$value = $hasher->make('password');
		$this->assertTrue($value !== 'password');
		$this->assertTrue($hasher->check('password', $value));
		$this->assertTrue(!$hasher->needsRehash($value));
		$this->assertTrue($hasher->needsRehash($value, array('rounds' => 1)));
	}
}