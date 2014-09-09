<?php

namespace Flyer\Components\Router\Console;

use Commandr\Core\Command;
use Flyer\Components\Router\Router;
use Flyer\Foundation\Events\Events;
use ReflectionClass;

class SimulateRouteCommand extends Command
{
	public $callsign = 'simulate';

	public function prepare()
	{

		$this->setConfig(
			array("arguments" => array("route", "method")
		));

		$this->setDescription("With this command you can simulate your routes very easy.");
		$this->setSummary("Simulate a http route");
	}

	public function action()
	{
		$route = $this->getArgument("route");
		$method = $this->getArgument("method");

		$routes = Router::getRoutes();
		$router = new Router;

		// Resolve the listener
		foreach ($routes as $id => $action)
		{
			if (explode('.?.', $id)[0] == $route)
			{
				$route = $routes[$id];
			}
		}

		if (!is_array($route))
		{
			$this->output->error("Cannot simulate " . $this->getArgument("route") . ", because it does not exists!");

			return;
		}

		if (strtolower($route['method']) != strtolower($method))
		{
			//$this->output->error("Cannot simulate " . $this->getArgument("route") . ", because it does not matches with the request method");

			//return;
		}

		$router->generateRouteEvent($route['route']);

		$output = Events::trigger('application.route');

		$this->output->writeln();
		$this->output->success("Route Simulation...");
		$this->output->writeln();
		$this->output->writeln();

		$this->output->writeln("Simulated route: " . $this->getArgument("route"));
		$this->output->writeln();

		if (strpos($route['route'], '@') !== false)
		{
			$controller = explode('@', $route['route'])[0];
			$method = explode('@', $route['route'])[1];

			$reflector = new ReflectionClass($controller);
			$controllerLoc = explode(ROOT, $reflector->getFileName())[1];

			$this->output->writeln("Route: ");
			$this->output->success("    [http] => " . $route['method']);
			$this->output->success("    [controller] => " . $controller);
			$this->output->success("    [controllerLocation] => " . $controllerLoc);
			$this->output->success("    [method] => " . $method);
			$this->output->writeln();
		} else {
			$this->output->writeln("Route: ");
			$this->output->writeln("    [http] => " . $route['method']);
			$this->output->writeln();
		}

		$this->output->writeln("Output: ");
		$this->output->writeln();
		$this->output->info($output);

	}
}