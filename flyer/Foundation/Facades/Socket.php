<?php

namespace Flyer\Foundation\Facades;

class Socket extends Facade
{
	public static function getFacadeAccessor() { return 'server.socket'; }
}