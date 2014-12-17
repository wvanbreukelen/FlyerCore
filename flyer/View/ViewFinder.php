<?php

namespace Flyer\Components\View;

use Exception;
use Folder;
use File;

class ViewFinder
{
	protected $views = array();

	public function addViewsPath($path, $extension = 'php')
	{
		if (Folder::is($path))
		{
			$views = Folder::listFiles($path);

			foreach ($views as $path)
			{
				$explode = explode('.', File::filename($path));

				if (end($explode) == $extension)
				{
					$this->views[$explode[0]] = $path;
				}
			}

			return;
		}

		throw new Exception("Cannot find views in path: " . $path . ", because the folder does not exists");
	}

	public function addView($view)
	{
		if (File::is($view))
		{
			$this->views[] = $view; 
		}
	}

	public function getViewPath($view)
	{
		if (isset($this->views[$view]))
		{
			return $this->views[$view];
		}

		throw new Exception("Cannot find path for " . $view . " view, make sure that you added the right paths!");
	}

	public function getViews()
	{
		return $this->views;
	}
}