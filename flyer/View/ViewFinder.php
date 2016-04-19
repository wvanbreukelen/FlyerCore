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
	/**
	 * Contains all the views paths
	 * @var array
	 */
	protected $views = array();

	/**
	 * Adds a view path and registers them
	 * @param string $path      The path where the views have been located
	 * @param string $extension Search for this type of extension, default is 'php'
	 */
	public function addViewsPath($path, $extension = 'php', $overwrite = true)
	{
		// Does views folder exists?
		if (!Folder::is($path))
		{
			throw new Exception("Cannot find views for path: " . $path . ", folder doesn't exists");
		}

		// List all views paths
		$views = Folder::listFiles($path);

		// Loop through every view and add them when possible
		foreach ($views as $path)
		{
			$explode = explode('.', File::filename($path));

			if (end($explode) == $extension)
			{
				// Resolve view name
				$viewName = $explode[0];

				// Make sure the view does not exists beforehand, only allow with overwrite enabled
				if ($this->viewExists($viewName) && !$overwrite)
				{
					throw new Exception("View " . $viewName . ", with path " . $path . " does already exists! Remove dublicate or enable overwrite.");
				}

				// Make overwrite possible
				unset($this->views[$viewName]);
				// Add the view
				$this->views[$viewName] = $path;
			}
		}
	}

	/**
	 * Does a given view exists
	 * @param  string $viewName Name of the view
	 * @return bool
	 */
	public function viewExists($viewName)
	{
		return isset($this->views[$viewName]);
	}

	/**
	 * Get any view path
	 * @return mixed        The results
	 */
	public function getViewPath($viewName)
	{
		if ($this->viewExists($viewName))
		{
			return $this->views[$viewName];
		}

		throw new Exception("Cannot find path for " . $viewName . " view, make sure that you added the right paths!");
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
