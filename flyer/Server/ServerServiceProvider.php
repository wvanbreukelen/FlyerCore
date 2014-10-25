<?php

namespace Flyer\Components\Server;

use Flyer\Foundation\ServiceProvider;
use Flyer\Components\Server\FTP\Ftp;

class ServerServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->share('server.ftp', new Ftp());
	}
}