<?php

namespace Flyer\Components\View;

use Twig_Environment;
use Flyer\Foundation\Registry;
use Flyer\Foundation\Config\Config;

class ViewEngine
{

	protected $twig;

	/**
	 * Construct a new ViewEngine instance, expects a twig instance and a compiler
	 *
	 * @param object The Twig compiler instance
	 * @param object Our own created compiler
	 */

	public function __construct(Twig_Environment $twig, $compiler)
	{
		$this->twig = $twig;
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
			return $this->renderDefault($view, $values);
		}

		if (is_null($id))
		{
			return $this->render($view . '.php', $values);
		}

		return $this->compiler->compile($id, $view, $values);
	}

	/**
	 * Render using the default view compiler
	 *
	 * @param  string The view to be compiled
	 * @param  mixed The values that have to be passed to the view compiler
	 *
	 * @return  string 
	 */

	private function renderDefault($view, $values)
	{
		return $this->compiler->compile(Config::get('defaultViewCompiler'), $view, $values);
	}

	/**
	 * Render a view using fallback Twig
	 *
	 * @param  string The view to be compiled
	 * @param  mix [varname] [description]
	 */

	private function render($view, $values)
	{
		return $this->twig->render($view);
	}

}	
