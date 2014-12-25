<?php

namespace Flyer\Components\View\Compiler;

/**
 * Compiler have to extend this abstract class
 */
abstract class Compiler
{
	abstract public function compile($contents, $view);
}
