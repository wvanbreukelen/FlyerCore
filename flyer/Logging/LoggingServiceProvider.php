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
		// Create a new file logger
		$this->logger = new Writer(new Logger('flyer'));

		// Resolve the logging file location and pass that to Monolog
		$this->logger->useFiles($this->app()->resolveDebugFile());

		// Share the log instance to the application container
		$this->share('log', $this->logger);
	}
}
