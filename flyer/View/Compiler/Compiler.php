<?php

namespace Flyer\Components\View\Compiler;

/**
 * ViewCompiler instances have to extend this class
 */

abstract class Compiler
{
	abstract public function compile($contents, $view);
}
