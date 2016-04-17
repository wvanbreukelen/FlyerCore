<?php

namespace Flyer\Foundation\Console;

use Commandr\Core\Command;
use Flyer\App;
use Debugger;
use ReflectionClass;
use Exception;

/**
 * List all the packages that where registered into the application
 */
class ListPackagesCommand extends Command
{
	// Command callsign
	public $callsign = 'packagelist';

	// Prepare the command, not running it!
	public function prepare()
	{
		$this->setConfig(
			array("arguments" => array()
		));

		$this->setDescription("List the registered packages");
		$this->setSummary("Listes the application registered packages");
	}

	// Run a command
	public function action()
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

			$this->output->success($packageName);
			$this->output->writeln();
			$this->output->success("   [Class Name] -> " . $classname);
			$this->output->success("   [Package Location] -> " . $filename);
			$this->output->success("   [Service Provider Location] -> " . $filename);
			$this->output->writeln();
		}

		sort($packageNames);

		$this->output->write("Shortly: " . implode(", ", $packageNames));

		$this->output->writeln();
		$this->output->writeln();
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
