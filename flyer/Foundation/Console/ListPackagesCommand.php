<?php

namespace Flyer\Foundation\Console;

use Flyer\Console\Commands\Command;
use Flyer\App;
use Debugger;
use ReflectionClass;
use Exception;

/**
 * List all the packages that where registered into the application
 */
class ListPackagesCommand extends Command
{
	protected $name = 'package:list';

	protected $description = 'List all the packages of Flyer';

	public function handle()
	{
		$providers = App::getInstance()->getProviders();
		$packageNames = array();

		Debugger::info("Receiving application providers...");

		foreach ($providers as $provider)
		{
			$reflector = new ReflectionClass($provider);
			$filename = $reflector->getFileName();

			$classname = get_class($provider);
			$packageName = $this->guessPackageName($filename);
			$packageNames[] = $packageName;

			Debugger::info("Guessed package name of " . $classname . ", " . $packageName . "?");

			$this->writeln($packageName);
			$this->success("   [Class Name] -> " . $classname);
			$this->success("   [Package Location] -> " . $filename);
			$this->success("   [Service Provider Location] -> " . $filename);
		}

		sort($packageNames);

		$this->write("Shortly: " . implode(", ", $packageNames));
	}

	// Guess the name of any given package filename
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
