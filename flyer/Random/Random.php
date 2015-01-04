<?php

namespace Flyer\Components\Random;

class Random
{

	/**
	 * Contains the randomiser instance
	 * @var object
	 */
	protected $randomiser;

	/**
	 * Construct a new random instance with a randomiser instance
	 * @param objecr $randomiser The randomiser instance
	 */
	public function __construct($randomister)
	{
		$this->randomiser = $randomister;
	}

	/**
	 * Generate a random string
	 * @param  integer $length       The length of the random string
	 * @param  array  $allowedChars  The characters that have to be used for generating the random string
	 * @return string                The random string
	 */
	public function string($length = 30, $allowedChars = null)
	{
		if (is_null($allowedChars))
		{
			return $this->randomiser->randomString($length, null);
		}

		return $this->randomiser->randomString($length, $allowedChars);
	}

	/**
	 * Generate a random integer
	 * @param  integer $min    Minimal length
	 * @param  integer $max    Maximal length
	 * @param  integer $length The length of the random integer
	 * @return integer         The random integer
	 */
	public function integer($min = null, $max = null, $length = null)
	{
		return $this->randomiser->randomInteger($min, $max, $length);
	}

	/**
	 * Generate a random boolean
	 * @return bool The random boolean
	 */
	public function boolean()
	{
		return $this->randomiser->randomBoolean();
	}
}