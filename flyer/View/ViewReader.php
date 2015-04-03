<?php

namespace Flyer\Components\View;

use Flyer\Components\View\ViewFinder;
use Exception;

/**
 * The view reader reads the views
 */
class ViewReader
{

	/**
	 * The view finder instance
	 * @var object The view to read
	 */
	protected $viewFinder;

	/**
	 * The view you want to read
	 * @var string
	 */
	protected $view;

	/**
	 * Construct a new view reader instance using a view finder instance and a given view
	 * @param ViewFinder $finder The view finder instance
	 * @param string $view   The name of the view
	 */
	public function __construct(ViewFinder $finder, $view)
	{
		$this->viewFinder = $finder;
		$this->view = $view;
	}

	/**
	 * Reads out the view
	 * @return mixed The results
	 */
	public function read()
	{
		if ($viewPath = $this->viewFinder->getViewPath($this->view))
		{
			if (!$contents = File::contents($viewPath))
			{
				throw new Exception("Cannot find view named " . $this->view);

				return null;
			}

			return $contents;
		}

		$appViewPath = APP . 'views' . DS . $this->view . '.php';

		if (File::exists($appViewPath))
		{
			if (!$contents = File::contents($appViewPath))
			{
				throw new Exception("Cannot find view named " . $this->view);
			}

			return $contents;
		}

		throw new Exception("Cannot find view named " . $this->view);
	}
}