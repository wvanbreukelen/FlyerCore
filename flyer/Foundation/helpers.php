<?php


use Flyer\App;

/**
 * The helper functions are available in the whole application, just call them like functions
 *
 * You may overwrite these functions by creating them before this file is included.
 * Helpers.php is by default required once in the application bootstrap
 */

if (!function_exists('app'))
{
	/**
	 * Using application instance to make given container item
	 */

	function app($parameter)
	{
		return App::getInstance()->make($parameter);
	}
}


if (!function_exists('base_path'))
{
	/**
	 * Return the application base path
	 */

	function base_path()
	{
		return app('path.base');
	}
}

if (!function_exists('app_path'))
{
	/**
	 * Return the application path
	 */

	function app_path()
	{
		return app('path.app');
	}
}

if (!function_exists('bindings_path'))
{
	/**
	 * Return the bindings path
	 */

	function bindings_path()
	{
		return app('path.bindings');
	}
}

if (!function_exists('config_path'))
{
	/**
	 * Return the config path
	 */

	function config_path()
	{
		return app('path.config');
	}
}

if (!function_exists('debug_path'))
{
	/**
	 * Return the debug path
	 */

	function debug_path()
	{
		return app('path.debug');
	}
}

if (!function_exists('storage_path'))
{
	/**
	 * Return the storage path
	 */

	function storage_path()
	{
		return app('path.storage');
	}
}

if (!function_exists('views_path'))
{
	/**
	 * Return the views path
	 */

	function views_path()
	{
		return app('path.views');
	}
}
