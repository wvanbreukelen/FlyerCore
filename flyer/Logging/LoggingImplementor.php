<?php

namespace Flyer\Components\Logging;

use Exceptionizer\Implement\Implementor;
use Flyer\App;
use Monolog\Logger;

class LoggingImplementor extends Implementor
{

	private $logger;

	public function register()
	{
		$this->createWriter();

		$debugger = App::getInstance()->debugger();

		$this->getExceptionizer()->addExceptionAction(array($this->logger, 'alert'));
	}

	public function createWriter()
	{
		$this->logger = new Writer(
			new Logger('flyer')
		);

		$this->logger->useFiles(base_path() . App::getInstance()->access('env')['defaultDebugFile']);
	}
}