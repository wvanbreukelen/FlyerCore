<?php

/**
 * The helper functions all available in the whole application, when requiring them
 */

use Flyer\App;

if (!function_exists('app'))
{
	function app($parameter)
	{
		return App::getInstance()->make($parameter);
	}
}

if (!function_exists('base_path'))
{
	function base_path()
	{
		return app('path.base');
	}	
}

if (!function_exists('app_path'))
{
	function app_path()
	{
		return app('path.app');
	}	
}

if (!function_exists('bindings_path'))
{
	function bindings_path()
	{
		return app('path.bindings');
	}	
}

if (!function_exists('config_path'))
{
	function config_path()
	{
		return app('path.config');
	}	
}

if (!function_exists('debug_path'))
{
	function debug_path()
	{
		return app('path.debug');
	}	
}

if (!function_exists('storage_path'))
{
	function storage_path()
	{
		return app('path.storage');
	}	
}

if (!function_exists('views_path'))
{
	function views_path()
	{
		return app('path.views');
	}	
}




