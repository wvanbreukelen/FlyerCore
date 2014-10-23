<?php

namespace Flyer\Components\View;

use Flyer\Components\View\ViewFinder;
use Exception;

class ViewReader
{

	protected $viewFinder;
	protected $view;

	public function __construct(ViewFinder $finder, $view)
	{
		$this->viewFinder = $finder;
		$this->view = $view;
	}

	public function read()
	{
		if ($viewPath = $this->viewFinder->getViewPath($this->view))
		{
			if (!$contents = File::contents($viewPath))
			{
				throw new Exception("Cannot find view named " . $this->view);
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