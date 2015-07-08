<?php

namespace Flyer\Components\Logging;

use Exceptionizer\Implement\Implementor;
use Flyer\App;
use Monolog\Logger;
use Commandr\Core\Output;

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

		// If the application is running in console mode and debugging is turned on, return the warning in the console itself
		if (App::getInstance()->isConsole() && App::getInstance()->isRunningDebug())
		{
			// Creating a new Commandr\Core\Output instance
			$consoleOutput = new Output;

			// Writing to the console
			$consoleOutput->write($exception);
			$consoleOutput->writeln();
		}
	}

	public function createWriter()
	{
		$this->writer = new Writer(
			new Logger('flyer')
		);

		$this->writer->useFiles(App::getInstance()->resolveDebugFile());
	}

}
