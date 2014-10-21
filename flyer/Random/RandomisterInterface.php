<?php

namespace Flyer\Components\Random;

interface RandomiserInterface
{
	public function randomString($length, $allowedChars);
	public function randomInteger($min, $max, $length);
	public function randomBoolean();
}