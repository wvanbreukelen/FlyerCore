<?php

namespace Flyer\Components\Logging;

use Flyer\Components\Logging\Writer;

use Flyer\Foundation\ServiceProvider;
use Monolog\Logger;

class LoggingServiceProvider extends ServiceProvider
{

	protected $logger;

	public function register()
	{
		$this->logger = new Writer(
			new Logger('flyer')
		);

		$this->logger->useFiles(ROOT . $this->app()->access('env')['defaultDebugFolder']);

		$this->share('log', $this->logger);
	}

	public function boot()
	{
		if ($this->app()->access('env')['debug'] == true)
		{
			$this->make('application.debugger')->process($this->logger);
		}
	}
}