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
		// Create a new file Logger

		$this->logger = new Writer(
			new Logger('flyer')
		);

		// Set the files that have to been used in the logger

		$this->logger->useFiles(ROOT . $this->app()->access('env')['defaultDebugFolder']);

		// Share the log instance to the application container

		$this->share('log', $this->logger);
	}

	public function boot()
	{
		// Check if the application is in debug mode, if so, make the current debugger instance and process the log inputs

		if ($this->app()->access('env')['debug'] == true)
		{
			$this->make('application.debugger')->process($this->logger);
		}
	}
}