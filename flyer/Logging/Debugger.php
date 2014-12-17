<?php

namespace Flyer\Components\Logging;

use Flyer\Components\Logging;
use Flyer\Components\Config;
use Flyer\Components\Logging\Writer;
use Exception;

class Debugger
{

	protected $points = array();

	protected $config;

	public function __construct(Config $config)
	{
		$this->config = $config->get('debugMessages');
	}

	public function point($identifier, $level = 'debug')
	{
		if (!isset($this->config[$identifier]))
		{
			throw new Exception("Cannot load debug for [" . $identifier . "] identifier!");
		}

		$this->points[$identifier] = array(
			'message' => $this->config[$identifier],
			'level' => $level
		);
	}

	public function process(Writer $writer)
	{
		foreach ($this->points as $id => $point)
		{
			$writer->{$point['level']}($point['message']);
		}
	}
}