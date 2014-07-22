<?php

namespace Flyer\Components\Console;

use Flyer\Components\Console\Command;

class Helpr
{

	protected $app;

	protected $commands = array();

	public function attachCommand(Command $command)
	{
		$this->commands[] = $this->optimizeCommand($command);
	}

	public function setApp($app)
	{
		$this->app = $app;
	}

	protected function optimizeCommand(Command $command)
	{
		
	}
}