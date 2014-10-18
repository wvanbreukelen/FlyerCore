<?php

namespace Flyer\Components\Random;

use Flyer\Components\Random\RandomiserInterface;

class Randomiser implements RandomiserInterface
{
	public function randomString($length, $allowedChars)
	{
		if (is_null($allowedChars))
		{
			return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}
		return substr(str_shuffle($allowedChars), 0, $length);
	}

	public function randomInteger($min, $max, $length)
	{
		if (is_null($min) && is_null($max))
		{
			return rand();
		}

		if (is_int($min) && is_int($max))
		{
			$rand = rand($min, $max);

			return (is_null($length)) ? $rand : substr($rand, 0, 0 - $length);
		}
		
		throw new Exception("Cannot create a random integer with minimum of " . $min . " and maximum of " . $max);
		return null;
	}

	public function randomBoolean()
	{
		return (rand(0, 1) == 1) ? true : false;
	}
}