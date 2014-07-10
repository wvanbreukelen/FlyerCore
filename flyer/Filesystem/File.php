<?php

namespace Flyer\Components\Filesystem;

use Exception;

class File
{

	public function exists($path)
	{
		return is_readable($path);
	}

	public function contents($path)
	{
		if ($this->exists($path)) return file_get_contents($path);
	}

	public function write($path, $data = null)
	{
		file_put_contents($path, $data);
	}

	public function append($path, $data)
	{
		if ($this->exists($path))
		{
			file_put_contents($path, $data, FILE_APPEND);
		} else {
			throw new \Exception();
		}
	}

	public function delete($path)
	{
		if ($this->exists($path)) unlink($path);
	}

	public function move($path, $target)
	{
		if ($this->exists($path)) rename($path, $target);
	}

	public function extension($path)
	{
		if ($this->exists($path))
		{
			$split = explode('.', $path);
			return $split[0];	
		}
	}

	public function size($path)
	{
		if ($this->exists($path)) return filesize($path);
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

	public function is($path)
	{
		if ($this->exists($path)) return is_file($path);
	}
}