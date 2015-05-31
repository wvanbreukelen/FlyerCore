<?php

namespace Flyer\Components\Logging;

use Exceptionizer\Implement\Implementor;
use Flyer\App;
use Monolog\Logger;

class LoggingImplementor extends Implementor
{

	private $writer, $debugger;

	public function register()
	{
		$this->createWriter();

		$this->debugger = App::getInstance()->debugger();

		$this->getExceptionizer()->addExceptionAction(array($this, 'archive'));
	}

	public function archive($exception)
	{
		$this->debugger->process($this->writer);

		$this->writer->warning($exception);
	}

	public function createWriter()
	{
		$this->writer = new Writer(
			new Logger('flyer')
		);

		$this->writer->useFiles(base_path() . App::getInstance()->access('env')['defaultDebugFile']);
	}
}