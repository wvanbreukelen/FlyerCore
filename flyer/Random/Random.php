<?php

namespace Flyer\Components\Random;

class Random
{

	protected $randomister;

	public function __construct($randomister)
	{
		if ($randomister instanceof RandomisterInterface)
		{
			$this->randomister = $randomister;
		}
	}

	public function string($length = 30, array $allowedChars = array())
	{
		if (count($allowedChars) == 0)
		{
			return $this->randomiser->randomString($length, null);
		}

		return $this->randomister->randomString($length, $allowedChars);
	}

	public function integer($min = null, $max = null)
	{
		return $this->randomister->randomInteger($min, $max);
	}

	public function boolean()
	{
		return $this->randomister->randomBoolean;
	}
}