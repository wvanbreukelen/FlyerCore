<?php
namespace Flyer\Components;

class ClassLoader
{
	
	/**
	 * Register the given autoload function to the SPL autoloader
	 * 
	 * @param object The autoload function
	 */
	
	public function register($function)
	{
		spl_autoload_register($function);
	}
	
	/**
	 * Unregister a registered autoload function in the SPL autoloader
	 * 
	 * @param object The autoload function who have to been deregistered
	 */

	public function unregister($function) 
	{
		spl_autoload_unregister($function);
	}
	
	/**
	 * Restore the default SPL autoloader
	 */

	public function restore()
	{
		spl_autoload_register('__autoload');
	}
}
