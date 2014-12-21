<?php

namespace Flyer\Components\Logging;

use Flyer\Components\Config;
use Flyer\Components\Logging\Writer;
use DebuggerException;

class Debugger
{

	protected $points = array();

	protected $config;

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
		$this->point($mixed, 'error');
	}

	/**
	 * Process the messages that where attached to the debugger
	 * @param  Writer
	 * @return mixed
	 */
	public function process(Writer $writer)
	{
		foreach ($this->points as $id => $point)
		{
			$writer->{$point['level']}($point['message']);
		}
	}
}