<?php

namespace Flyer\Components\Console;

class Application extends \Symfony\Component\Console\Application
{
	/**
	 * The Flyer application instance
	 *
	 * @var  \Flyer\App
	 */
	
	protected $flyer;

	public static function start($app)
	{
		return static::make($apo)
	}
}