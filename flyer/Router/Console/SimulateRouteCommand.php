<?php

namespace Flyer\Components\Router\Console;

use Commandr\Core\Command;

class SimulateRouteCommand extends Command
{
	public $callsign = 'simulate';

	public function prepare()
	{
		$this->setDescription("With this command you can simulate your routes very easy.");
		$this->setSummary("Simulate a http route");
	}

	public function action()
	{
		$this->output->writeln("Hello World!");
	}
}