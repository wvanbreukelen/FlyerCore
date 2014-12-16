<?php

namespace Flyer\Components\Random;

use Flyer\Components\Random\RandomiserInterface;
use Flyer\Components\Random\RandomiserException;

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
		if (is_int($min) && is_int($max))
		{
			$rand = rand($min, $max);

			return (is_null($length)) ? $rand : substr($rand, 0, 0 - $length);
		}

		if (is_null($min) && is_null($max))
		{
			$rand = rand(1, 999999999);

			return (is_null($length)) ? $rand : substr($rand, 0, 0 - $length);
		} 
		
		throw new RandomiserException("Cannot create a random integer with minimum of " . $min . " and maximum of " . $max);
		return null;
	}

	public function randomBoolean()
	{
		return (rand(0, 1) == 1) ? true : false;
	}
}