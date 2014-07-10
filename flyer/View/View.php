<?php

namespace Flyer\Components;

class View
{

	protected $engine;

	public function __construct($engine)
	{
		$this->engine = $engine;
	}

	public function render($view, $values = null, $id = null)
	{
		return $this->engine->compile($view, $values, $id);
	}
}