<?php

namespace Flyer\Components\Router\Console;

use Commandr\Core\Command;
use Flyer\Components\Router\Router;
use Flyer\Foundation\Events\Events;
use ReflectionClass;

class RouteListCommand extends Command
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
		$routes = Router::getRoutes();

		foreach ($routes as $callsign => $route)
		{
			$this->output->writeln();
			$this->output->success(ucfirst(explode('.?.', $callsign)[0]) . ":");

			foreach ($route as $id => $element)
			{
				if (is_string($id) && is_string($element))
				{
					$this->output->success("    [" . $id . "] => " . $element);
				} else {
					$this->output->error("   Sorry, cannot display " . ucfirst(explode('.?.', $callsign)[0]) . "!");
				}
				
				$this->output->writeln();
			}	
		}
	}
}