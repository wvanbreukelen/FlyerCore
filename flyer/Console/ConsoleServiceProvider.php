<?php

namespace Flyer\Components\Console;

use Flyer\Foundation\ServiceProvider;
use Commandr\Core\Config as CommandrConfig;

class ConsoleServiceProvider extends ServiceProvider
{
	public function register()
	{
		// Only run the following event when the application is running in a console environment
		if ($this->app()->isConsole())
		{
			$consoleHandler = new ConsoleHandler('Flyer Commandr Console Application', '1.0');
			$consoleHandler->setConfig(
				new CommandrConfig(require_once($this->app()->configPath() . 'commandr.php'))
			);

			$this->app()->setConsoleHandler($consoleHandler);
		}
	}

	public function boot()
	{
		// @wvanbreukelen, maybe write to log?
		if ($this->app()->isConsole())
		{
			$consoleHandler = $this->app()->getConsoleHandler();

			$consoleHandler->registerCommands($this->app()->getCommands());
			$consoleHandler->match();

			$this->app()->setConsoleHandler($consoleHandler);
		}
	}
}
