<?php

namespace Flyer\Components\Filesystem\Console;

use Flyer\Console\Commands\Command;
use Flyer\App;
use Flyer\Components\Filesystem\File;
use Flyer\Components\Filesystem\Folder;
use ReflectionClass;
use Exception;

class CleanCommand extends Command
{
		protected $name = 'clean';

		protected $description = 'Clean up your application';

		private $file, $folder;

		public function prepare()
		{
			$this->file = new File();
			$this->folder = new Folder();
		}

		public function handle()
		{
			// Remove all the debug files
			if ($this->file->exists(App::getInstance()->resolveDebugFile()))
			{
				$this->success("Removing debug files...");
				$this->file->delete(App::getInstance()->resolveDebugFile());
			}

			// Remove the debug folder itself
			if ($this->file->exists(App::getInstance()->appPath() . 'debug'))
			{
				$this->success("Removing debug folder...");
				$this->folder->delete(App::getInstance()->appPath() . 'debug');
			}

			// Create a new debug folder for future debugging
			$this->success("Creating debug folder...");
			$this->folder->create(App::getInstance()->appPath() . 'debug');
			$this->success("Done!");
		}
}
