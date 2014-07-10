<?php

namespace Flyer\Components\HTTP;

use Flyer\Components\HTTP\Request\Request;
use Flyer\Foundation\ServiceProvider;
use Flyer\Foundation\Events\Events;

class HTTPServiceProvider extends ServiceProvider
{

	private $request;

	public function boot()
	{
		$request = new Request($this->request);
	}

	public function register()
	{
		$this->request = Events::trigger('request.get');
	}
}
