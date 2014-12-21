<?php

namespace Flyer\Components\HTML;

use Flyer\Foundation\ServiceProvider;
use Illuminate\Html\HtmlBuilder;

class HTMLServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->share('html', new HtmlBuilder());
	}
}