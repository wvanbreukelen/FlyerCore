<?php

namespace Flyer\Components\Logging;

use Flyer\Components\Config;
use Flyer\Components\Logging\Writer;
use Flyer\Components\Logging\DebuggerException;

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

	/**
	 * Create a new debugger instance
	 * @param Config $config The config instance that is used in the application
	 */
	public function __construct(Config $config)
	{
		$this->config = $config->get('debugMessages');
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
			throw new DebuggerException("Cannot load debug for [" . $identifier . "] identifier!");
		}

		$this->points[$identifier] = array(
			'message' => $this->config[$identifier],
			'level' => $level
		);
	}

	public function exception($exception)
	{
		$this->points[] = array(
			'message' => $exception,
			'level' => 'debug'
		);
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
			foreach ($this->points as $id => $point)
			{
				$writer->{$point['level']}($point['message']);
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
}
