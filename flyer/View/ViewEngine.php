<?php

namespace Flyer\Components\View;

use Twig_Environment;
use Flyer\Foundation\Registry;
use Flyer\Foundation\Config\Config;
use Flyer\App;

class ViewEngine
{

	protected $twig;

	protected $app;

	protected $compiler;

	/**
	 * Construct a new ViewEngine instance, expects a twig instance and a compiler
	 *
	 * @param object The Twig compiler instance
	 * @param object Our own created compiler
	 */

	public function __construct(Twig_Environment $twig, App $app, $compiler)
	{
		$this->twig = $twig;
		$this->app = $app;
		$this->compiler = $compiler;
	}

	/**
	 * Compile a given view and return the result
	 *
	 * @param  string The view to be compiled
	 * @param  mixed The values that have to be passed to the view compiler
	 * @param  string The ID of the view compiler, which is going to be the selected compiler
	 *
	 * @return  string The compiled view
	 */

	public function compile($view, $values, $id)
	{
		if (Config::exists('defaultViewCompiler') && is_null($id))
		{	
			return $this->renderDefault($this->resolveViewPath($view), $view, $values);
		}

		if (is_null($id))
		{
			return $this->renderTwig($this->resolveViewPath($view));
		}

		return $this->compiler->compile($id, $view, $values);
	}

	protected function resolveViewPath($view)
	{
		return $this->app->access('application.view.finder')->getViewPath($view);
	}

	/**
	 * Render using the default view compiler
	 *
	 * @param  string The view path that will be compiled
	 * @param  mixed The values that have to be passed to the view compiler
	 *
	 * @return  string 
	 */

	private function renderDefault($path, $view, $values)
	{
		return $this->compiler->compile(Config::get('defaultViewCompiler'), $path, $view, $values);
	}

	/**
	 * Render a view using fallback Twig
	 *
	 * @param  string The view to be compiled
	 * @param  mix [varname] [description]
	 */

	private function renderTwig($path)
	{
		return $this->twig->render($path);
	}

}	
