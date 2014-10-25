<?php

namespace Flyer\Components\Router\Console;

use Commandr\Core\Command;
use Flyer\Components\Router\Router;
use Flyer\Foundation\Events\Events;
use ReflectionClass;

class ListPackagesCommand extends Command
{
	public $callsign = 'routelist';

	public function prepare()
	{
		$this->setConfig(
			array("arguments" => array()
		));

		$this->setDescription("The general router command");
		$this->setSummary("The general router command");
	}

	public function action()
	{

	}
}