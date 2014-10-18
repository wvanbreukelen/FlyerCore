<?php

namespace Flyer\Components\Random;

class Random
{

	protected $randomiser;

	public function __construct($randomister)
	{
		$this->randomiser = $randomister;
	}

	public function string($length = 30, $allowedChars = null)
	{
		if (is_null($allowedChars))
		{
			return $this->randomiser->randomString($length, null);
		}

		return $this->randomiser->randomString($length, $allowedChars);
	}

	public function integer($min = null, $max = null, $length = null)
	{
		return $this->randomiser->randomInteger($min, $max, $length);
	}

	public function boolean()
	{
		return $this->randomiser->randomBoolean();
	}
}