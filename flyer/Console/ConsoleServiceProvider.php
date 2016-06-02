<?php

namespace Flyer\Components\Console;

use Flyer\Foundation\ServiceProvider;
use Commandr\Core\Config as CommandrConfig;
use Debugger;

class ConsoleServiceProvider extends ServiceProvider
{
	public function register()
	{
		// Only run the following event when the application is running in a console environment
		if ($this->app()->isConsole())
		{
			$consoleHandler = new ConsoleHandler('Flyer Commandr Console Application', '1.0');
			//$consoleHandler->setConfig(
			//	new CommandrConfig(require_once($this->app()->configPath() . 'commandr.php'))
			//);

			$this->app()->setConsoleHandler($consoleHandler);
		}
	}

	public function boot()
	{
		// Only run the following event when the application is running in a console environment
		if ($this->app()->isConsole())
		{
			Debugger::info("Application is running in console, creating new console application...");
			$consoleHandler = $this->app()->getConsoleHandler();

			Debugger::info("Registering console commands...");
			$consoleHandler->registerCommands($this->app()->getCommands());

			$this->app()->setConsoleHandler($consoleHandler);
		}
	}
}
