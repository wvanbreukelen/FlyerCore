<?php

namespace Flyer\Components\Router\Console;

use Flyer\Console\Commands\Command;
use Flyer\Components\Router\Router;
use Flyer\Foundation\Events\Events;
use Debugger;
use ReflectionClass;

class RouteListCommand extends Command
{
	protected $name = 'route:list';

	protected $description = 'Lists all available application routes';

	public function handle()
	{
		Debugger::info("Getting routes...");

		$routes = Router::getRoutes();

		foreach ($routes as $callsign => $route)
		{
			$routeName = ucfirst(explode('.?.', strtolower($callsign))[0]);

			if ($routeName == "") $routeName = "/";
			$this->success($routeName . ":");

			foreach ($route as $id => $element)
			{
				if (is_string($id) && is_string($element))
				{
					$this->success("    [" . $id . "] -> " . $element);
				} else {
					if (is_callable($element))
					{
						$this->success("    [" . $id . "] -> [closure]");
					} else {
						$this->error("   Cannot display " . $id . "!");
					}
				}
			}
		}
	}
}
