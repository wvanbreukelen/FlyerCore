<?php

namespace Flyer\Components\Random;

use Flyer\Foundation\ServiceProvider;
use Flyer\Components\Random\Random;
use Flyer\Components\Random\Randomiser;

class RandomServiceProvider extends ServiceProvider
{
	public function register()
	{
		$random = new Random(new Randomiser);

		$this->share('random', $random);

		$this->package('flyer/random');
	}
}