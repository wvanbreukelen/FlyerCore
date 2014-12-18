<?php

namespace Flyer\Components\Logging;

use Closure;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Formatter\LineFormatter;
use Psr\Log\LoggerInterface as PsrLoggerInterface;
use Flyer\Components\Logging\Log as LogInterface;
use Exception;

class Writer
{
	protected $monolog;

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
	 * @return void
	 */
	public function log($level, $message, array $context = array())
	{
		return $this->writeLog($level, $message, $context);
	}

	public function write($level, $message, array $context = array())
	{
		return $this->log($level, $message, $context);
	}

	/**
	 * Write a message with any level to a log
	 * @param  string
	 * @param  string
	 * @param  string
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
	 * @return mixed
	 */
	public function useFiles($path, $level = 'debug')
	{
		$this->monolog->pushHandler($handler = new StreamHandler($path, $this->parseLevel($level)));
		$handler->setFormatter($this->getDefaultFormatter());
	}

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
	 * @return [type]
	 */
	public function useSyslog($name = 'laravel', $level = 'debug')
	{
		return $this->monolog->pushHandler(new SyslogHandler('flyer', LOG_USER, $level));
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
	 * Parse a monolog level
	 * @param  [type]
	 * @return [type]
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
	 * @return [type]
	 */
	public function getMonolog()
	{
		return $this->monolog;
	}
}