<?php

namespace Flyer\Components\Filesystem\Console;

use Commandr\Core\Command;
use Flyer\App;
use Flyer\Components\Filesystem\File;
use Flyer\Components\Filesystem\Folder;
use ReflectionClass;
use Exception;

class CleanCommand extends Command
{
		public $callsign = 'clean';

		private $file, $folder;

		public function prepare()
		{
			$this->setConfig(
				array("arguments" => array()
			));

			$this->setDescription("Cleans the application");
			$this->setSummary("Cleans debug files, cache and other cool stuff");

			$this->file = new File();
			$this->folder = new Folder();
		}

		public function action()
		{
			// Remove all the debug files
			if ($this->file->exists(App::getInstance()->resolveDebugFile()))
			{
				$this->output->success("Removing debug files...");
				$this->file->delete(App::getInstance()->resolveDebugFile());
			}

			// Remove the debug folder itself
			if ($this->file->exists(App::getInstance()->appPath() . 'debug'))
			{
				$this->output->success("Removing debug folder...");
				$this->folder->delete(App::getInstance()->appPath() . 'debug');
			}

			// Create a new debug folder for future debugging
			$this->output->success("Creating debug folder...");
			$this->folder->create(App::getInstance()->appPath() . 'debug');
			$this->output->success("Done!");
			$this->output->writeln();
		}
}
