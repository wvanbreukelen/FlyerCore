<?php

namespace Flyer\Components\Console;

class Application extends \Symfony\Component\Console\Application
{

	protected $flyer;

	public static function start($app)
	{
		return static::make($app)->boot();
	}

	public static function make($app)
	{
		$app->boot();

		$console = new static('Flyer Framework', '1.0');

		$app->share('helpr', $console);

		return $console;
	}

	public function boot()
	{

	}

}