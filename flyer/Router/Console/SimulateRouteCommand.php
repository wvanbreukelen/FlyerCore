<?php

namespace Flyer\Components\Router\Console;

use Flyer\Console\Commands\Command;
use Flyer\Console\Input\InputArgument;
use Flyer\Components\Router\Router;
use Debugger;
use App;
use ReflectionClass;

class SimulateRouteCommand extends Command
{
 	protected $name = 'route:simulate';

	protected $description = 'Simulate a HTTP route request';

	public function prepare()
	{
		$this->addArgument('route', InputArgument::REQUIRED, 'Route name');
		$this->addArgument('method', InputArgument::REQUIRED, 'HTTP method');
	}

	public function handle()
	{
		$route = $this->argument('route');
		$method = $this->argument('method');

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
			$this->error("Unable to simulate " . ucfirst($this->argument("route")) . " route, because the route does not exists!");

			return;
		}

		if (strtolower($route['method']) != strtolower($method))
		{
			$this->error("Unable to simulate " . ucfirst($this->argument('route')) . " route, because the route does not match with the HTTP request method!");

			return;
		}

		$router->generateRouteEvent($route['route']);

		$output = App::make('application.route');

		$this->success("Route simulation for " . ucfirst($this->argument('route')));

		if (is_string($route['route']) && strpos($route['route'], '@') !== false)
		{
			$controller = explode('@', $route['route'])[0];
			$method = explode('@', $route['route'])[1];

			$reflector = new ReflectionClass($controller);
			$controllerLocation = explode(base_path(), $reflector->getFileName())[1];

			Debugger::info("Simulating route with URI " . $this->argument('route') . " HTTP method " . $route['method'] . " and controller " . $controller);

			$this->writeln("ROUTE: ");
			$this->success("    HTTP method -> " . $route['method']);
			$this->success("    Controller -> " . $controller);
			$this->success("    Controller location -> " . $controllerLocation);
			$this->success("    Controller method -> " . $method);
		} else {
			Debugger::info("Simulating route with URI " . $this->getArgument('route') . " HTTP method " . $route['method'] . ", route is a closure");

			$this->writeln("ROUTE: ");
			$this->writeln("    HTTP method -> " . $route['method']);
			$this->writeln("    Closure => true");
		}

		Debugger::info("Displaying route output to console");
		$this->writeln("OUTPUT: ");
		$this->info("    " . $output);
	}
}
