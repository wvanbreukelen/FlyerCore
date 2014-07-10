<?php

namespace Flyer\Components\Security;

use Flyer\Foundation\ServiceProvider;

class SecurityServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->share('hash', new BcryptHasher);
	}
}