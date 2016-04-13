<?php

namespace Flyer\Components\Security;

use Flyer\Foundation\ServiceProvider;
use Exception;

class SecurityServiceProvider extends ServiceProvider
{
	public function register()
	{
		// Receive PHP version
		$phpv = (float) phpversion();

		// Check if the PHP version is equal or above version 5.50, otherwise the security component will not work!
		if ($phpv <= 5.50)
		{
			// Share new BcryptHasher to application container
			$this->share('hash', new BcryptHasher);

			debugger()->info("Attached BcryptHasher to container");
		} else {
			// PHP version is incorrect, set hash share to null. Notify the developer
			$this->share('hash', null);

			debugger()->info("Cannot use BcryptHasher! PHP version (" . $phpv . ") needs to be >= 5.50!");
		}
	}
}
