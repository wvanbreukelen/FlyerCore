<?php

namespace Flyer\Components\Logging;

use Exceptionizer\Implement\Implementor;
use Flyer\App;
use Monolog\Logger;
use Commandr\Core\Output;

class LoggingImplementor extends Implementor
{

	private $writer, $debugger;

	/**
	 * Register the debugger to Exceptionizer
	 * @return mixed
	 */
	public function register()
	{
		$this->createWriter();

		$this->debugger = App::getInstance()->debugger();

		$this->getExceptionizer()->addExceptionAction(array($this, 'archive'));
	}

	/**
	 * Archive a exception
	 * @param  Exception $exception The exception to be achived
	 * @return mixed
	 */
	public function archive($exception)
	{
		$this->debugger->process($this->writer);
		$this->writer->warning($exception);

		// Is debugging turned on?
		if (App::getInstance()->isRunningDebug())
		{
			// Running in console?
			if (App::getInstance()->isConsole())
			{
				// Creating a new Commandr\Core\Output instance
				$consoleOutput = new Output;

				// Write exception message to console
				$consoleOutput->write($exception);
				$consoleOutput->writeln();
			}
		}
	}

	/**
	 * Create a new monolog writer instance
	 * @return mixed
	 */
	public function createWriter()
	{
		$this->writer = new Writer(
			new Logger('flyer')
		);

		$this->writer->useFiles(App::getInstance()->resolveDebugFile());
	}

}
