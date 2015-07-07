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

				$this->output->success("Writing new debug file");
				$this->file->create(App::getInstance()->resolveDebugFile());
			}

			if ($this->folder->exists(debug_path()))
			{
				$this->output->success("Removing debug folders...");
				$this->folder->delete(debug_path());
			}

			// Cleans storage
			if ($this->folder->exists(storage_path()))
			{
				$this->output->success("Removing storage...");
				$this->folder->delete(storage_path());
			}

			$this->output->success("Done!");

			$this->output->writeln();
			$this->output->writeln();
		}
}
