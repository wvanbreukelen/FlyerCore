<?php

namespace Flyer\Components\Router\Console;

use Commandr\Core\Command;
use Flyer\Components\Router\Router;
use Flyer\Foundation\Events\Events;
use Debugger;
use ReflectionClass;

class RouteListCommand extends Command
{
	public $callsign = 'routelist';

	public function prepare()
	{
		$this->setConfig(
			array("arguments" => array()
		));

		$this->setDescription("Lists all registered routes, with their arguments, which are implemented into the application");
		$this->setSummary("Lists all registered routes");
	}

	public function action()
	{
		Debugger::info("Getting routes...");

		$routes = Router::getRoutes();

		foreach ($routes as $callsign => $route)
		{
			$this->output->writeln();
			$routeName = ucfirst(explode('.?.', strtolower($callsign))[0]);

			if ($routeName == "") $routeName = "/";
			$this->output->success($routeName . ":");

			foreach ($route as $id => $element)
			{
				if (is_string($id) && is_string($element))
				{
					$this->output->success("    [" . $id . "] -> " . $element);
				} else {
					if (is_callable($element))
					{
						$this->output->success("    [" . $id . "] -> [closure]");
					} else {
						$this->output->error("   Cannot display " . $id . "!");
					}
				}
			}

			$this->output->writeln();
		}
	}
}
