<?php

namespace Flyer\Components\View;

use Flyer\Foundation\ServiceProvider;
use Flyer\Components\View\ViewEngine;
use Flyer\Components\View\ViewFinder;
use Flyer\Components\View\Compiler\ViewCompiler;
use Flyer\Components\View;
use Twig_Environment as TwigEnvironment;
use Twig_Loader_Filesystem as TwigFilesystem;

class ViewServiceProvider extends ServiceProvider
{

	protected $twig;
	protected $engine;
	protected $viewFinder;

	public function register()
	{
		$this->twig = new TwigEnvironment(new TwigFilesystem(APP . 'views' . DS), array(
			'cache' => APP . 'storage' . DS . 'cache' . DS,
			'auto_reload' => true
		));
		
		$this->share('application.view.compiler', new ViewCompiler());
		$this->share('application.view.engine', new ViewEngine($this->twig, $this->app(), $this->make('application.view.compiler')));
		$this->share('application.view.finder', new ViewFinder());
		
		$this->share('view', new View($this->make('application.view.engine')));
	}

	public function boot()
	{
		$this->make('application.view.finder')->addViewsPath(APP . 'views' . DS);
	}
}