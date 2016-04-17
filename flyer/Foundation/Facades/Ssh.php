<?php

namespace Flyer\Foundation\Facades;

class SSH extends Facade
{
	public static function getFacadeAccessor() { return 'server.ssh'; }
}
