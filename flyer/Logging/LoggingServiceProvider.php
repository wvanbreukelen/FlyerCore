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
			new Logger('debug')
		);

		$this->logger->useFiles(ROOT . $this->app()->config()->get('defaultDebugFolder'));

		$this->app()->bind('log', $this->logger);
	}

	public function boot()
	{
		$this->app()->access('application.debugger')->process($this->logger);
	}
}