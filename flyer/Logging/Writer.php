<?php

namespace Flyer\Components\Logging;

use Monolog\Handler\SyslogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Formatter\LineFormatter;
use Exception;
// @wvanbreukelen Are this namespaces even needed??
use Jsonable;
use Arrayable;

class Writer
{
	/**
	 * Contains the Monolog instance
	 * @var object
	 */
	protected $monolog;

	/**
	 * All the debug levels, with their Monolog logger static
	 * @var array
	 */
	protected $levels = array(
		'debug'     => MonologLogger::DEBUG,
		'info'      => MonologLogger::INFO,
		'notice'    => MonologLogger::NOTICE,
		'warning'   => MonologLogger::WARNING,
		'error'     => MonologLogger::ERROR,
		'critical'  => MonologLogger::CRITICAL,
		'alert'     => MonologLogger::ALERT,
		'emergency' => MonologLogger::EMERGENCY,
	);

	/**
	 * Construct a new writer instance, with a Monolog instance
	 * @param MonologLogger $monolog The Monolog instance to use
	 */
	public function __construct(MonologLogger $monolog)
	{
		$this->monolog = $monolog;
	}

	/**
	 * Log an emergency message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return void
	 */
	public function emergency($message, array $context = array())
	{
		return $this->writeLog(__FUNCTION__, $message, $context);
	}
	/**
	 * Log an alert message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return void
	 */
	public function alert($message, array $context = array())
	{
		return $this->writeLog(__FUNCTION__, $message, $context);
	}
	/**
	 * Log a critical message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return void
	 */
	public function critical($message, array $context = array())
	{
		return $this->writeLog(__FUNCTION__, $message, $context);
	}
	/**
	 * Log an error message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return void
	 */
	public function error($message, array $context = array())
	{
		return $this->writeLog(__FUNCTION__, $message, $context);
	}
	/**
	 * Log a warning message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return void
	 */
	public function warning($message, array $context = array())
	{
		return $this->writeLog(__FUNCTION__, $message, $context);
	}
	/**
	 * Log a notice to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return void
	 */
	public function notice($message, array $context = array())
	{
		return $this->writeLog(__FUNCTION__, $message, $context);
	}
	/**
	 * Log an informational message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return void
	 */
	public function info($message, array $context = array())
	{
		return $this->writeLog(__FUNCTION__, $message, $context);
	}
	/**
	 * Log a debug message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @return void
	 */
	public function debug($message, array $context = array())
	{
		return $this->writeLog(__FUNCTION__, $message, $context);
	}
	/**
	 * Log a message to the logs.
	 *
	 * @param  string  $message
	 * @param  array  $context
	 * @param string $level
	 * @return void
	 */
	public function log($level, $message, array $context = array())
	{
		return $this->writeLog($level, $message, $context);
	}

	/**
	 * Simply write to the log
	 * @param  string $level   The level
	 * @param  string $message The message
	 * @param  array  $context The context
	 * @return string          The added item
	 */
	public function write($level, $message, array $context = array())
	{
		return $this->log($level, $message, $context);
	}

	/**
	 * Write a message with any level to a log
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param string $message
	 * @param string $level
	 * @return mixed
	 */
	public function writeLog($level, $message, $context)
	{
		$message = $this->formatMessage($message);

		$this->monolog->{$level}($message, $context);
	}

	/**
	 * Set the files that have to be used by Monolog
	 * @param  string
	 * @param  string
	 * @param string $path
	 * @return mixed
	 */
	public function useFiles($path, $level = 'debug')
	{
		$this->monolog->pushHandler($handler = new StreamHandler($path, $this->parseLevel($level)));
		$handler->setFormatter($this->getDefaultFormatter());
	}

	/**
	 * Use daily files to process a log
	 * @param  string  $path  The path
	 * @param  integer $days  Amount of days
	 * @param  string  $level The log level, default is debug
	 * @return mixed
	 */
	public function useDailyFiles($path, $days = 0, $level = 'debug')
	{
		$this->monolog->pushHandler(
			$handler = new RotatingFileHandler($path, $days, $this->parseLevel($level))
		);
		$handler->setFormatter($this->getDefaultFormatter());
	}

	/**
	 * Use the System log handler
	 * @param  string
	 * @param  string
	 * @return mixed
	 */
	public function useSyslog($name = 'flyer', $level = 'debug')
	{
		return $this->monolog->pushHandler(new SyslogHandler($name, LOG_USER, $level));
	}

	/**
	 * Use the error log handler
	 * @param  string
	 * @param  mixed
	 * @return mixed
	 */
	public function useErrorLog($level = 'debug', $messageType = ErrorLogHandler::OPERATING_SYSTEM)
	{
		$this->monolog->pushHandler(
			$handler = new ErrorLogHandler($messageType, $this->parseLevel($level))
		);
		$handler->setFormatter($this->getDefaultFormatter());
	}

	/**
	 * Format a logging message
	 * @param  mixed
	 * @param string $message
	 * @return mixed
	 */
	protected function formatMessage($message)
	{
		if (is_array($message))
		{
			return var_export($message, true);
		}
		elseif ($message instanceof Jsonable)
		{
			return $message->toJson();
		}
		elseif ($message instanceof Arrayable)
		{
			return var_export($message->toArray(), true);
		}
		return $message;
	}

	/**
	 * Parse a Monolog level
	 * @param  string The level
	 * @param string $level
	 * @return integer
	 */
	protected function parseLevel($level)
	{
		if (isset($this->levels[$level]))
		{
			return $this->levels[$level];
		}
		throw new Exception("Invalid log level.");
	}

	/**
	 * Get a default Monolog formatter instance.
	 *
	 * @return \Monolog\Formatter\LineFormatter
	 */
	protected function getDefaultFormatter()
	{
		return new LineFormatter(null, null, true, true);
	}

	/**
	 * Get the current Monolog logging instance
	 * @return Monolog\Logger The current Monolog instance
	 */
	public function getMonolog()
	{
		return $this->monolog;
	}
}
