<?php

namespace Flyer\Components\View;

use Flyer\Foundation\ServiceProvider;
use Flyer\Components\View\ViewEngine;
use Flyer\Components\View\ViewFinder;
use Flyer\Components\View\Compiler\ViewCompiler;
use Flyer\Components\View;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ViewServiceProvider extends ServiceProvider
{

	protected $twig;
	protected $engine;
	protected $viewFinder;

	public function register()
	{
		$this->twig = new Twig_Environment(new Twig_Loader_Filesystem(APP . 'views' . DS), array(
			'cache' => APP . 'storage' . DS . 'cache' . DS,
			'auto_reload' => true
		));
		
		$this->share('application.view.compiler', new ViewCompiler());
		$this->share('application.view.engine', new ViewEngine($this->twig, $this->app(), $this->app()['application.view.compiler']));
		$this->share('application.view.finder', new ViewFinder());
		
		$this->share('view', new View($this->app()['application.view.engine']));
	}

	public function boot()
	{
		$this->app()->access('application.view.finder')->addViewsPath(APP . 'views' . DS);
	}
}