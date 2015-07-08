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

	public function setConfig(CommandrConfig $config)
	{
		$this->getCommandr()->setConfig($config);
	}

	public function registerCommands(array $commands = array())
	{
		$this->getCommandr()->addCommands($commands);
	}

	public function match()
	{
		$this->getCommandr()->match();
	}

	public function run($output = null)
	{
		if (is_null($output))
		{
			$output = new \Commandr\Core\Output;
		}

		$this->getCommandr()->run($output);
	}

	public function setCommandr(CommandrApplication $commandr)
	{
		$this->commandr = $commandr;
	}

	public function getCommandr()
	{
		return $this->commandr;
	}
}
