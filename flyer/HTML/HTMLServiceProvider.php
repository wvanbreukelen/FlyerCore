<?php

namespace Flyer\Components\HTML;

use Flyer\Foundation\ServiceProvider;
use Illuminate\Html\HtmlBuilder;

/**
 * Note! The HTML component is currently not active and will be worked on in the future
 */
class HTMLServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->share('html', new HtmlBuilder());
	}
}