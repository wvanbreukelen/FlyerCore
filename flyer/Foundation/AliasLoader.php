<?php

namespace Flyer\Foundation;

class AliasLoader
{
	public static function create($original, $alias)
	{
		class_alias($original, $alias);
	}
}