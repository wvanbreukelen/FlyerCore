<?php

namespace Flyer\Components\View\Compiler;

use File;

class ViewCompiler
{

	/**
	 * Holds all of the registered view compilers
	 * @param array
	 */
	protected $compilers = array();
	
	/**
	 * Register a new view compiler
	 *
	 * @param  string The id of the compiler
	 * @param  object The instance of the compiler
	 */
	public function addCompiler($id, $compiler)
	{
		$this->compilers[$id] = $compiler;
	}

	/**
	 * Compile a view using a given view compiler
	 *
	 * @param  string The ID of the view compiler
	 * @param  string The view path that will be compiled
	 * @param  mixed The value that will be passed to the view 
	 * @return mixed The compiler results
	 */

	public function compile($id, $path, $view, $values)
	{
		$contents = $this->resolveViewContents($path);

		if (is_null($values))
		{
			$values = array();
		}

		if (isset($this->compilers[$id]))
		{
			return $this->compilers[$id]->compile($contents, $view, $values);
		}

		throw new Exception("Compiler " . $id . " does not exists!");
	}

	/**
	 * Resolve the contents of a given view
	 * @param  string $path The path of the view
	 * @return string       The contents of the view
	 */
	protected function resolveViewContents($path)
	{
		return File::contents($path);
	}
}
