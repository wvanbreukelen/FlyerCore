<?php

namespace Flyer\Components\View;

use Exception;
use Folder;
use File;

/**
 * The view finder searches for views in paths and add those
 */
class ViewFinder
{
	protected $views = array();

	/**
	 * Adds a view path and registers them
	 * @param string $path      The path where the views have been located
	 * @param string $extension Search for this type of extension, default is '.php'
	 */
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

	/**
	 * Add a view to the view finder
	 * @param  $path The path of the view
	 */
	public function addView($path)
	{
		if (File::is($path))
		{
			$this->views[] = $path; 
		}
	}

	/**
	 * Get any view path
	 * @param  string $view The view
	 * @return mixed        The results
	 */
	public function getViewPath($view)
	{
		if (isset($this->views[$view]))
		{
			return $this->views[$view];
		}

		throw new Exception("Cannot find path for " . $view . " view, make sure that you added the right paths!");
	}

	/**
	 * Get all the views
	 * @return array The views
	 */
	public function getViews()
	{
		return $this->views;
	}
}