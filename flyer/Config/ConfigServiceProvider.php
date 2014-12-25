<?php

namespace Flyer\Components\Config;

use Flyer\Foundation\ServiceProvider;
use Flyer\Components\Config;

class ConfigServiceProvider extends ServiceProvider
{

	public function register()
	{
		$this->share('config', new Config);
	}
}