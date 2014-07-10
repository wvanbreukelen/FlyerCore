<?php

namespace Flyer\Foundation;

class Registry
{

	/**
	 * Holds all of the registry items
	 */

	protected static $items = array();

	/**
	 * Sets a registry item
	 *
	 * @var  string Registry item's key
	 * @var  mixed Registry item's value
	 *
	 * @return  void
	 */

	public static function set($key, $value)
	{
		self::$items[$key] = $value;
	}

	/**
	 * Gets a registry item
	 *
	 * 
	 */

	public static function get($key)
	{
		if (self::exists($key))
		{
			return self::$items[$key];
		}
		throw new \RuntimeException("Registry: Key " . $key . " is not found in the registry, please initialize it by the set() function!");
		return;
	}

	public static function update($key, $toValue)
	{
		if (self::exists($key))
		{
			self::delete($key);
			self::set($key, $toValue);
			return;
		}

		throw new \Exception("Registry: key " . $key . " does not exists, so the key cannot been updated!");
	}

	public static function exists($key)
	{
		if (isset(self::$items[$key]))
		{
			return true;
		}
		return false;
	}

	public static function delete($key)
	{
		if (self::exists($key)) unset(self::$items[$key]);
	}

	public function registry()
	{
		return self::$items;
	}
	
}