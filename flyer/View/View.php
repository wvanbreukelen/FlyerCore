<?php

namespace Flyer\Components;

use Flyer\Components\View\ViewEngine;

/**
 * Handles everything with views
 */

class View
{

	/**
	 * The view engine instance
	 *
	 * @param  object \Flyer\Components\View\ViewEngine
	 */
	protected $engine;

	/**
	 * Construct a new View class, expects a engine instance for compiling views
	 *
	 * @param object The view engine instance
	 */
	public function __construct(ViewEngine $viewEngine)
	{
		$this->engine = $viewEngine;
	}

	/**
	 * Render any specified view
	 *
	 * @param  string The view to compile
	 * @param  mixed The value which will be passed to the view
	 * @param  string The ID of the view compiler
	 *
	 * @return  string The rendered view
	 */

	public function render($view, $values = null, $id = null)
	{
		return $this->engine->compile($view, $values, $id);
	}
}