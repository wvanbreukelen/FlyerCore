<?php

namespace Flyer\Components\Filesystem;

use Flyer\Foundation\ServiceProvider;

class FilesystemServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->share('file', new File);
		$this->share('folder', new Folder);
		$this->command('Flyer\Components\Filesystem\Console\CleanCommand');
	}
}
