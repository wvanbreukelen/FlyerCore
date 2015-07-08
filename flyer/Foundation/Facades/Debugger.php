<?php

namespace Flyer\Foundation\Facades;

class Debugger extends Facade
{
	public static function getFacadeAccessor() { return 'application.debugger'; }
}
