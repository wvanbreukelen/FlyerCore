<?php

namespace Flyer\Foundation\Facades;

class Ftp extends Facade
{
	public static function getFacadeAccessor() { return 'server.ftp'; }
}