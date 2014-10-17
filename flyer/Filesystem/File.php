<?php

namespace Flyer\Components\Filesystem;

use Exception;

class File
{
	
	/**
	 * Checks if a file exists
	 * 
	 * @var  $path The path
	 * @return   bool
	 */

	public function exists($path)
	{
		return is_readable($path);
	}
	
	/**
	 * Get the contents of a file
	 * 
	 * @var  $path The path of the file 
	 * @return    bool
	 */

	public function contents($path)
	{
		if ($this->exists($path)) return file_get_contents($path);
	}
	
	/**
	 * Write some content to a specified file
	 * 
	 * @var  $path  The path of the file 
	 */

	public function write($path, $data = null)
	{
		file_put_contents($path, $data);
	}
	
	/**
	 * Append some content to a specified file
	 *
	 * @var  $path  The path of the file
	 * @var  $data  Data to append to the file
	 */

	public function append($path, $data)
	{
		if ($this->exists($path))
		{
			file_put_contents($path, $data, FILE_APPEND);
		} else {
			throw new \Exception();
		}
	}
	
	/**
	 * Deletes a file
	 *
	 * @var  $path  The path of the file 
	 */

	public function delete($path)
	{
		if ($this->exists($path)) unlink($path);
	}
	
	/**
	 * Move a file from a specified directory to another directory
	 *
	 * @var  $path  The path of the file
	 * @var  $target The new target of the file
	 */

	public function move($path, $target)
	{
		if ($this->exists($path)) rename($path, $target);
	}
	
	/**
	 * Check the extension of a file
	 *
	 * @var  $path  The path of the file
	 */

	public function extension($path)
	{
		if ($this->exists($path))
		{
			$split = explode('.', $path);
			return $split[0];	
		}
	}
	
	/**
	 * Get filesize of a file
	 *
	 * @var  $path  The path of the file
	 */

	public function size($path)
	{
		if ($this->exists($path)) return filesize($path);
	}
	
	/**
	 * Get the file last modified date
	 *
	 * @var  $path  The path of the file
	 * @var  $path  The time format
	 */

	public function lastModified($path, $format = 'timestamp')
	{
		if ($this->exists($path))
		{
			if ($format == 'timestamp')
			{
				return filemtime($path);
			} else if ($format == 'date') {
				return date(filemtime($path));
			} else {
				throw new Exception("Cannot get last modified date, because this format does not exists");

				return filemtime($path);
			}
		}
	}
	
	/**
	 * Check if a path is a file
	 *
	 * @var  $path  The path of the file
	 */

	public function is($path)
	{
		if ($this->exists($path)) return is_file($path);
	}
}
