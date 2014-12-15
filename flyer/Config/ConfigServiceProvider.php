<?php

namespace Flyer\Components\Config;

use Flyer\Foundation\ServiceProvider;
use Flyer\Components\Config;

class ConfigServiceProvider extends ServiceProvider
{

	protected $config;

	public function register()
	{
		$this->config = new Config;

		$this->share('config', $this->config);
	}
}