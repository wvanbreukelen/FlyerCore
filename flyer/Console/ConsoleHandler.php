<?php

namespace Flyer\Components\Console;

use wvanbreukelen\Commandr;
use Commandr\Core\Config as CommandrConfig;
use Commandr\Core\Application as CommandrApplication;
use Commandr\Core\Output;

class ConsoleHandler
{
	public function __construct($name, $version)
	{
		$app = new CommandrApplication(
			new \Commandr\Core\Input,
			new \Commandr\Core\Output,
			new \Commandr\Core\Dialog,
			$name,
			$version
		);

		$this->setCommandr($app);
	}

	/**
	 * Set the configuration for the console application
	 * @param CommandrConfig $config Console config file
	 */
	public function setConfig(CommandrConfig $config)
	{
		$this->getCommandr()->setConfig($config);
	}

	/**
	 * Register all the commands to the console application
	 * @param  array  $commands The commands to Register
	 * @return mixed
	 */
	public function registerCommands(array $commands = array())
	{
		$this->getCommandr()->addCommands($commands);
	}

	/**
	 * Match the given argv arguments with a command
	 */
	public function match()
	{
		$this->getCommandr()->match();
	}

	/**
	 * Run the console application
	 * @param mixed  $output An output object
	 */
	public function run($output = null)
	{
		if (is_null($output))
		{
			$output = new \Commandr\Core\Output;
		}

		$this->getCommandr()->run($output);
	}

	/**
	 * Set the Commandr console application instance
	 * @param CommandrApplication $commandr The Commandr instance
	 */
	public function setCommandr(CommandrApplication $commandr)
	{
		$this->commandr = $commandr;
	}

	/**
	 * Get the Commandr console application instance
	 * @return CommandrApplication $commandr The Commandr instance
	 */
	public function getCommandr()
	{
		return $this->commandr;
	}
}
