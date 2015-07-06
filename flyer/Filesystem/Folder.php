<?php

namespace Flyer\Components\Filesystem;

use Exception;

class Folder
{

	/**
	 * Checks if a given directory exists
	 */
	public function exists($path)
	{
		return is_readable($path);
	}

	/**
	 * Create a folder
	 */
	public function create($path, $permission = 0777)
	{
		if ($this->exists($path))
		{
			throw new Exception("Cannot create directory, because the directory " . $path . " already exists!");
			return;
		}

		mkdir($path, $permission);
	}

	/**
	 * Deletes a given directory
	 */
	public function delete($path)
	{
		if (!$this->exists($path))
		{
			throw new Exception("Cannot delete directory, because the directory " . $path . " already exists!");
			return;
		}

		if (count(scandir($path)) == 2)
		{
			rmdir($path);
		} else {
			throw new Exception("Cannot delete directory, because the directory " . $path . " contains files!");
			return;
		}
	}

	/**
	 * Rename a given directory
	 */
	public function rename($original, $path)
	{
		if (!$this->exists($original))
		{
			throw new Exception("Cannot rename directory, because the directory " . $original . " does not exists!");
			return;
		}

		rename($original, $path);
	}

	/**
	 * Give a directory a certain permission
	 */
	public function permission($path, $permission)
	{
		if (!$this->exists($path))
		{
			throw new Exception("Cannot give directory permissions, because the directory " . $path . " does not exists!");
		}

		chmod($path, $permission);
	}
	public function lastModified($path, $format = 'timestamp')
	{
		if ($this->exists($path))
		{
			if ($format == 'timestamp')
			{
				return filemtime($path);
			} else {
				return date(filemtime($path));
			}
		}
	}
	public function listFiles($path)
	{
		$list = array();

		if ($this->exists($path))
		{
			$scan = array_diff(scandir($path), array('..', '.'));

			foreach ($scan as $key => $filePos)
			{
				$optPath = $path . $filePos;

				if (\File::is($optPath))
				{
					$list[] = $optPath;
				}
			}
		}

		return $list;
	}

	public function is($path)
	{
		if ($this->exists($path)) return is_dir($path);
	}

}
