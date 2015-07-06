<?php

namespace Flyer\Components\System;

use ServiceProvider;

class SystemServiceProvider extends ServiceProvider
{
	public function register()
	{
		// True parameter is passed to the constructor so all other systems are automatically autoloaded
		$this->share('system', new System(true));
	}
}