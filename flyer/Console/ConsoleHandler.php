<?php

namespace Flyer\Components\Console;

use wvanbreukelen\Commandr;
use Commandr\Core\Config as CommandrConfig;
use Commandr\Core\Application as CommandrApplication;
use Commandr\Core\Output;
use Flyer\Console\Application as ConsoleApplication;

class ConsoleHandler
{
	/**
	 * Holds the console application instance
	 * @var object Flyer\Console\Application
	 */
	protected $app;

	/**
	 * Construct a new console application handler
	 * @param string $name    The name of your console application
	 * @param string $version The version of your console application
	 */
	public function __construct($name, $version)
	{
		$app = new ConsoleApplication($name, $version);
		$this->setApplication($app);
	}

	/**
	 * Register all the commands to the console application
	 * @param  array  $commands The commands to Register
	 * @return mixed
	 */
	public function registerCommands(array $commands = array())
	{
		foreach ($commands as $command)
		{
			$this->getApplication()->addCommand($command);
		}
	}

	/**
	 * Run the console application
	 * @param mixed  $output An output object
	 */
	public function run()
	{
		$this->getApplication()->run();
	}

	/**
	 * Set the Commandr console application instance
	 * @param CommandrApplication $commandr The Commandr instance
	 */
	public function setApplication(ConsoleApplication $app)
	{
		$this->app = $app;
	}

	/**
	 * Get the Commandr console application instance
	 * @return CommandrApplication $commandr The Commandr instance
	 */
	public function getApplication()
	{
		return $this->app;
	}
}
