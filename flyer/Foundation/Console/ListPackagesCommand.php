<?php

namespace Flyer\Foundation\Console;

use Commandr\Core\Command;
use Flyer\Components\Router\Router;
use Flyer\Foundation\Events\Events;
use Flyer\App;
use ReflectionClass;
use Exception;

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
			$providers = App::getInstance()->getProviders();
			$packageNames = array();

			foreach ($providers as $provider)
			{
				$reflector = new ReflectionClass($provider);

				$filename = $reflector->getFileName();
				$classname = get_class($provider);

				$packageName = $this->guessPackageName($filename);

				$packageNames[] = $packageName;

				$this->output->success($packageName);
				$this->output->writeln();
				$this->output->success("   [Class Name] -> " . $classname);
				$this->output->success("   [Package Location] -> " . $filename);
				$this->output->success("   [Service Provider Location] -> " . $filename);
			}

			$this->output->write("Shortly: " . implode(", ", $packageNames));

			$this->output->writeln();
			$this->output->writeln();
		}

		protected function guessPackageName($filename)
		{
			$parts = explode('\\', $filename);

			if (count($parts) == 1)
			{
				$parts = explode('/', $filename);
			}

			if (count($parts) == 1) throw new Exception("Cannot guess package name for filename: " . $filename);

			array_pop($parts);
			return end($parts);
		}
}
