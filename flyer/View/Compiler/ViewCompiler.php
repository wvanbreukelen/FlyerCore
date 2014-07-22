<?php

namespace Flyer\Components\View\Compiler;

class ViewCompiler
{

	protected $compilers = array();
	
	/**
	 * Add a new compiler
	 *
	 * @param  string The ID of the compiler
	 * @param  object The compiler instance
	 */

	public function addCompiler($id, $compiler)
	{
		$this->compilers[$id] = $compiler;
	}

	/**
	 * Compile a view using a given view compiler
	 *
	 * @param  string The ID of the view compiler
	 * @param  string The view to be compiled
	 * @param  mixed The value that will be passed to the view compiler
	 */

	public function compile($id, $view, $values)
	{
		if (is_null($values))
		{
			$values = array();
		}

		if (isset($this->compilers[$id]))
		{
			return $this->compilers[$id]->compile($view, $values);
		}

		throw new Exception("ViewCompiler: Compiler " . $id . " does not exists!");
	}
}
