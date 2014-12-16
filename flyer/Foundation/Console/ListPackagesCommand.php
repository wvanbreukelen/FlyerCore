<?php

namespace Flyer\Components\Router\Console;

use Commandr\Core\Command;
use Flyer\Components\Router\Router;
use Flyer\Foundation\Events\Events;
use ReflectionClass;

class ListPackagesCommand extends Command
{
	public $callsign = 'packagelist';

	public function prepare()
	{
		$this->setConfig(
			array("arguments" => array()
		));

		$this->setDescription("List the registered packages");
		$this->setSummary("Listes the application registered packages");
	}

	public function action()
	{
		
	}
}