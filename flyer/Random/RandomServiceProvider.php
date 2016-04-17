<?php

namespace Flyer\Components\Random;

use Flyer\Foundation\ServiceProvider;
use Flyer\Components\Random\Random;
use Flyer\Components\Random\Randomiser;

class RandomServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->share('random', new Random(new Randomiser));
	}

	public function boot()
	{
		$this->package('flyer/random');
	}
}
