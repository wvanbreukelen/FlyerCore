<?php

namespace Flyer\Foundation\Facades;

class Ssh extends Facade
{
	public static function getFacadeAccessor() { return 'server.ssh'; }
}