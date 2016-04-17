<?php

namespace Flyer\Foundation\Facades;

class Timer extends Facade
{
	public static function getFacadeAccessor() { return 'performance.timer'; }
}
