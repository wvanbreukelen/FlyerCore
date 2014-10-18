<?php

use Flyer\Components\Random\Random;
use Flyer\Components\Random\Randomiser;

class RandomiserTest extends PHPUnit_Framework_TestCase
{

	public $random;
	public $randomiser;

	public function __construct()
	{
		$this->randomiser = new Randomiser();
		$this->random = new Random($this->randomiser);
	}

	public function testStringRandomiser()
	{
		$defaultStringRandomiser = $this->random->string();

		$this->assertEquals(strlen($defaultStringRandomiser), 30);
		$this->assertTrue(is_string($defaultStringRandomiser));

		$customStringRandomiser = $this->random->string(15);

		$this->assertEquals(strlen($customStringRandomiser), 15);
		$this->assertTrue(is_string($customStringRandomiser));

		$customStringRandomiserWithChars = $this->random->string(15, 'zbcdEFGH1234@#');

		foreach (array('a', 'b', 'c', 'd', 'E', 'F', 'G', 'H', '1', '2', '3', '4', '@', '#') as $char)
		{
			$this->assertTrue(strpos('abcdEFGH1234@#', $char) !== false);
		}
	}

	public function testIntegerRandomiser()
	{
		$defaultIntegerRandomiser = $this->random->integer();

		$this->assertTrue(is_integer($defaultIntegerRandomiser));

		$customIntegerRandomiser = $this->random->integer(64, 3200);

		$this->assertTrue($customIntegerRandomiser >= 64 && $customIntegerRandomiser <= 3200);

		$customIntegerRandomiserWithLength = $this->random->integer(4000, 9800, 2);

		$this->assertTrue(strlen($customIntegerRandomiserWithLength) == 2);
	}

	public function testBooleanRandomiser()
	{
		$randomBoolean = $this->random->boolean();

		$this->assertTrue(is_bool($randomBoolean));
	}
}