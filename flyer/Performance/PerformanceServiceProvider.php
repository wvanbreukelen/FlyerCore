<?php

namespace Flyer\Components\Performance;

use Flyer\Foundation\ServiceProvider;
use Symfony\Component\Stopwatch\Stopwatch as SymfonyStopwatch;

class PerformanceServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->share('performance.timer', new Timer(new SymfonyStopwatch));

		$this->make('performance.timer')->openSection();
	}
}
