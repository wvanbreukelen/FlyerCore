<?php

namespace Flyer\Components\Security;

use Flyer\Foundation\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
	public function register()
	{
		if (phpversion() >= "5.50")
		{
			$this->share('hash', new BcryptHasher);
		}
	}
}