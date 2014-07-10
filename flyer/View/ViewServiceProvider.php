<?php

namespace Flyer\Components\View;

use Flyer\Foundation\Registry;
use Flyer\Foundation\ServiceProvider;
use Flyer\Components\View\ViewEngine;
use Flyer\Components\View\Compiler\ViewCompiler;
use Flyer\Components\View;

class ViewServiceProvider extends ServiceProvider
{

	protected $twig;
	protected $engine;

	public function register()
	{
		$this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(APP . 'views' . DS), array(
			'cache' => APP . 'storage' . DS . 'cache' . DS,
			'auto_reload' => true
		));
		//Registry::set('application.view.engine', new ViewEngine($this->twig));
		//Registry::set('application.view.compiler', new ViewCompiler());
		
		$this->share('application.view.compiler', new ViewCompiler());
		$this->share('application.view.engine', new ViewEngine($this->twig, $this->app()['application.view.compiler']));
		
		$this->share('view', new View($this->app()['application.view.engine']));
	}
}