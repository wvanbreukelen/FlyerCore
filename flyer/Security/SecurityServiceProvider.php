<?php

namespace Flyer\Components\Security;

use Flyer\Foundation\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
	public function register()
	{
		// Check if the PHP version is equal or above version 5.50, otherwise the security component will not work!
		if (phpversion() >= "5.50")
		{
			$this->share('hash', new BcryptHasher);
		} else {
			$this->share('hash', false);
		}
	}
}