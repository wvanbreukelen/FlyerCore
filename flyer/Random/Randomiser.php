<?php

namespace Flyer\Components\Random;

use Flyer\Components\Random\RandomiserInterface;

class Randomiser implements RandomiserInterface
{

	/**
	 * Create a random string with given length and allowed characters
	 * @param  integer $length       The length of the random string
	 * @param  string $allowedChars  The allowed characters to been used
	 * @return string                The random generated string
	 */
	public function randomString($length, $allowedChars)
	{
		if (is_null($allowedChars))
		{
			return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}

		return substr(str_shuffle($allowedChars), 0, $length);
	}

	/**
	 * Create a random integer
	 * @param  integer $min    The minimum length of a random integer
	 * @param  integer $max    The maximal length of a random integer
	 * @return integer         The random generated integer
	 */
	public function randomInteger($min, $max)
	{
		$rand = null;

		if (is_int($min) && is_int($max))
		{
			$rand = rand($min, $max);
		} else if (is_null($min) && is_null($max)) {
			$rand = rand(1, 999999999);
		}

		return $rand;
	}

	/**
	 * Create a random boolean
	 * @return bool Returns true of false as a boolean
	 */
	public function randomBoolean()
	{
		return (rand(0, 1) == 1) ? true : false;
	}
}
