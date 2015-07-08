<?php

namespace Flyer\Components\Router\Console;

use Commandr\Core\Command;
use Flyer\Components\Router\Router;
use Debugger;
use App;
use ReflectionClass;

class SimulateRouteCommand extends Command
{
	public $callsign = 'simulate';

	public function prepare()
	{
		$this->setConfig(
			array("arguments" => array("route", "method")
		));

		$this->setDescription("With this command you can simulate your own routes very easy");
		$this->setSummary("Simulate a http route request");
	}

	public function action()
	{
		$route = $this->getArgument("route");
		$method = $this->getArgument("method");

		Debugger::info("Getting routes...");

		$routes = Router::getRoutes();
		$router = new Router;

		// Resolves the listener
		foreach ($routes as $id => $action)
		{
			if (explode('.?.', $id)[0] == $route)
			{
				$route = $routes[$id];
			}
		}

		if (!is_array($route))
		{
			$this->output->error("Unable to simulate " . ucfirst($this->getArgument("route")) . " route, because the route does not exists!");

			return;
		}

		if (strtolower($route['method']) != strtolower($method))
		{
			$this->output->error("Unable to simulate " . ucfirst($this->getArgument('route')) . " route, because the route does not match with the HTTP request method!");

			return;
		}

		$router->generateRouteEvent($route['route']);

		$output = App::make('application.route');

		$this->output->writeln();
		$this->output->success("Route simulation for " . ucfirst($this->getArgument('route')));
		$this->output->writeln();
		$this->output->writeln();

		if (is_string($route['route']) && strpos($route['route'], '@') !== false)
		{
			$controller = explode('@', $route['route'])[0];
			$method = explode('@', $route['route'])[1];

			$reflector = new ReflectionClass($controller);
			$controllerLocation = explode(base_path(), $reflector->getFileName())[1];

			Debugger::info("Simulating route with URI " . $this->getArgument('route') . " HTTP method " . $route['method'] . " and controller " . $controller);

			$this->output->writeln("ROUTE: ");
			$this->output->success("    HTTP method -> " . $route['method']);
			$this->output->success("    Controller -> " . $controller);
			$this->output->success("    Controller location -> " . $controllerLocation);
			$this->output->success("    Method -> " . $method);
			$this->output->writeln();
		} else {
			Debugger::info("Simulating route with URI " . $this->getArgument('route') . " HTTP method " . $route['method'] . ", route is a closure");

			$this->output->writeln("ROUTE: ");
			$this->output->writeln("    HTTP method -> " . $route['method']);
			$this->output->writeln("    Closure => true");
			$this->output->writeln();
		}

		Debugger::info("Displaying route output to console");
		$this->output->writeln("OUTPUT: ");
		$this->output->info("    " . $output);
	}
}
