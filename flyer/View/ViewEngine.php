<?php

namespace Flyer\Components\View;

use Twig_Environment;
use Flyer\Foundation\Registry;
use Flyer\Foundation\Config\Config;

class ViewEngine
{

	protected $twig;

	public function __construct(Twig_Environment $twig, $compiler)
	{
		$this->twig = $twig;
		$this->compiler = $compiler;
	}

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

	private function renderDefault($view, $values)
	{
		return $this->compiler->compile(Config::get('defaultViewCompiler'), $view, $values);
	}

	private function render($view, $values)
	{
		return $this->twig->render($view);
	}

}	
