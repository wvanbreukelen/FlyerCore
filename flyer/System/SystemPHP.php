<?php

namespace Flyer\Components\System;

/**
 * The system information class for PHP
 */
class SystemPHP
{
	/**
	 * Returns the current PHP version
	 * @return string PHP version
	 */
	public function version()
	{
			return phpversion();
	}

	/**
	 * Get PHP developer credits
	 * @return string The credits
	 */
	public function credits()
	{
			return phpcredits();
	}

	/**
	 * Get the current system's PHP interface
	 * @return array The system interface
	 */
	public function interface()
	{
			return php_sapi_name();
	}

	/**
	 * Returns the running OS
	 * @return string The OS
	 */
	public function os()
	{
			return php_uname('s');
	}

	/**
	 * Get the running OS release
	 * @return string OS release
	 */
	public function osRelease()
	{
			return php_uname('r');
	}

	/**
	 * Get the running OS version
	 * @return string OS version
	 */
	public function osVersion()
	{
			return php_uname('v');
	}

	/**
	 * Get the PHP system's running host
	 * @return string The host
	 */
	public function host()
	{
			return php_uname('n');
	}

	/**
	 * Get the PHP system's running machine name
	 * @return string The machine name
	 */
	public function machine()
	{
			return php_uname('m');
	}

	/**
	 * Get the location of the loaded php.ini configuration file
	 * @return string The file location of php.ini
	 */
	public function php_ini()
	{
			return php_ini_loaded_file();
	}

	/**
	 * Returns phpinfo information
	 * @return string phpinfo
	 */
	public function info()
	{
			return phpinfo();
	}
}
