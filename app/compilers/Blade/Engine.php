<?php

namespace wvanbreukelen\Blade;

use wvanbreukelen\Blade\BladeCompiler;
use Flyer\Components\View\Compiler\Compiler;

class BladeEngine extends Compiler
{

	protected $blade;

	protected $values;

	public function compile($view, array $values = array())
	{
		$contents = file_get_contents(APP . 'views' . DS . $view . '.php');

		$this->blade = new BladeCompiler();
		$this->contents = $this->blade->compile($contents);

		if (!file_exists(APP . 'storage/cache' . DS . $view . '.php'))
		{
			file_put_contents(APP . 'storage/cache' . DS . $view . '.php', $this->contents);
		} else if ($this->contents != file_get_contents(APP . 'storage/cache' . DS . $view . '.php')) {
			file_put_contents(APP . 'storage/cache' . DS . $view . '.php', $this->contents);
		}

		return $this->build(APP . 'storage/cache' . DS . $view . '.php', $values);
	}

	protected function build($path, $values)
	{
		ob_start();

		extract($values);
		include($path);

		return ltrim(ob_get_clean());
	}
}