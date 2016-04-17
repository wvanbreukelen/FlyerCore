<?php

namespace Flyer\Components\Logging;

use Flyer\Components\Config;
use Flyer\Components\Logging\Writer;
use Flyer\Components\Logging\DebuggerException;
use Flyer\Components\Performance\Timer;

class Debugger
{

	/**
	 * The points that have been created by the application
	 * @var array
	 */
	protected $points = array();

	/**
	 * The config instance used by the application
	 * @var object
	 */
	protected $config;

	protected $prevIdentifier = null;

	/**
	 * Create a new debugger instance
	 * @param Config $config The config instance that is used in the application
	 */
	public function __construct(Config $config, Timer $timer)
	{
		$this->config = $config->get('debugMessages');
		$this->timer = $timer;
	}

	/**
	 * Add a point to the debugger
	 * @param  string
	 * @param  string
	 * @return mixed
	 */
	public function point($identifier, $level = 'debug')
	{
		if (!isset($this->config[$identifier]))
		{
			throw new DebuggerException("Cannot load debug message for [" . $identifier . "] identifier");
		}

		// @wvanbreukelen May need to use another approach to access application instance
		if (app()->isLoggingPerformance())
		{
			// Get the performance of the previous point
			$pf = $this->getPerformance($this->prevIdentifier);

			// Set the previous identifier equal to the current
			$this->prevIdentifier = $identifier;

			// Start the timer!
			$this->timer->start($identifier);

			// Add point, append timespan and memory usage
			$this->points[$identifier] = array(
				'message' => $this->config[$identifier] . " TIME: " . $pf['elapsed'] . " MEM: " . $pf['mem'],
				'level' => $level,
			);
		} else {
			$this->points[$identifier] = array(
				'message' => $this->config[$identifier],
				'level' => $level,
			);
		}
	}

	public function exception($exception)
	{
		$this->points[] = array(
			'message' => $exception,
			'level' => 'debug'
		);
	}

	public function info($message)
	{
		$this->points[] = array(
			'message' => $message,
			'level' => 'info'
		);
	}

	public function warning($message)
	{
		$this->flag($message);
	}

	/**
	 * Add a flag message to the debugger
	 * @param  mixed
	 * @return mixed
	 */
	public function flag($mixed)
	{
		$this->point($mixed, 'warning');
	}

	/**
	 * Add a error message to the debugger
	 * @param  mixed
	 * @return mixed
	 */
	public function error($mixed)
	{
		if (is_object($mixed))
		{
			if (method_exists($mixed, 'getMessage'))
			{
				$this->point($mixed, 'error');
			}
		} else if (is_string($mixed)) {
			$this->point($mixed, 'error');
		}
	}

	/**
	 * Process the messages that where attached to the debugger
	 * @param  Writer
	 * @return mixed
	 */
	public function process(Writer $writer)
	{
		if (isset($this->points) && count($this->points) > 0)
		{
			foreach ($this->points as $point)
			{
				if (method_exists($writer, $point['level']))
				{
					$writer->{$point['level']}($point['message']);
				} else {
					Debugger::error("Could not process logging message with level " . $point['level'] . " and message: " . $point['message']);
				}
			}

			// Clean the cache so the points are not processed twice
			$this->clean();
		}
	}

	/**
	 * Clean the points cache of the debugger
	 * @return mixed
	 */
	public function clean()
	{
		unset($this->points);
	}

	protected function getPerformance($prevIdentifier)
	{
		$memory = null;
		$elapsedTime = null;

		// Stop the previous point, so we can receive all kind of information about the period between two points
		if (!is_null($prevIdentifier))
		{
			$event = $this->timer->stop($prevIdentifier);
			$memory = $event->getMemory();
			$elapsedTime = ($event->getEndTime()) - ($event->getStartTime());
		}

		return ['elapsed' => $elapsedTime, 'mem' => $memory];
	}
}
