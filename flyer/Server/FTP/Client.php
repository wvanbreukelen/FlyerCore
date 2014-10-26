<?php

namespace Flyer\Components\Server\FTP;

use Flyer\Components\Server\FTP\Exceptions\FtpTransportException;
use Exception;
use Folder;
use File;

class Client
{

	protected $connector;

	/**
	 * Download a file from the FTP server
	 * @param  string $fromPath The path on the FTP server
	 * @param  string $toPath   The local path where the downloaded file must be placed
	 * @param  global $mode     The tranfer mode
	 * @return string           Returns $toPath
	 */
	public function download($fromPath, $toPath, $mode = FTP_ASCII)
	{
		if (File::is($toPath))
		{
			File::delete($toPath);
		}

		$handle = fopen($toPath, 'w');

		if (!ftp_fget($this->getConnector(), $handle, $fromPath, $mode))
		{
			throw new FtpTransportException("Cannot download " . $fromPath . " from FTP server!");
		}

		fclose($handle);
		return $toPath;
	}

	/**
	 * Upload a file to the FTP server
	 * @param  string $fromPath The local path for the file
	 * @param  string $toPath   The path on the FTP server
	 * @param  global $mode     The transfer mode
	 * @return string           Returns $toPath
	 */
	public function upload($fromPath, $toPath, $mode = FTP_ASCII)
	{
		if (!File::is($fromPath)) throw new FtpTransportException("Cannot upload " . $fromPath . ", the file does not exists");

		$handle = fopen($fromPath, 'r');

		if (!ftp_fput($this->getConnector(), $toPath, $handle, $mode))
		{
			throw new FtpTransportException("Cannot upload " . $fromPath . " to " . $toPath . " on FTP server!");
		}

		fclose($handle);
		return $toPath;
	}

	/**
	 * Get permission of a file
	 * @param  string  $file  The file path on the FTP server
	 * @param  integer $chmod Chmod permission to be set
	 * @return mixed
	 */
	public function permission($file, $chmod = 0755)
	{
		if (!ftp_chmod($this->getConnector(), $chmod, $file))
		{
			throw new FtpTransportException("Cannot change permission of " . $file);
		}
	}

	/**
	 * Get the last modified date for a specified file
	 * @param  string $file The file path on the FTP server
	 * @return mixed
	 */
	public function modified($file)
	{
		if (!ftp_mdtm($this->getConnector(), $file))
		{
			throw new FtpTransportException("Cannot get last modified date for " . $file . " file");
		}
	}

	/**
	 * Change directory on the FTP server
	 * @param  string $path The FTP path
	 * @return mixed
	 */
	public function cd($path)
	{
		if (!ftp_chdir($this->getConnector(), $path))
		{
			throw new FtpTransportException("Cannot change directory to " . $path . ", make sure that this path does exists");
		}
	}

	/**
	 * Returns the current path
	 * @param  string $path Optimal: Set the path to check if it exists
	 * @return mixed
	 */
	public function path($path = null)
	{
		if (is_null($path))
		{
			if (!$dir = $this->getWorkingDirectory())
			{
				throw new FtpTransportException("Cannot get current FTP directory");
			}

			return $dir;
		} else {
			if (ftp_chdir($this->getConnector(), $path))
			{
				return true;
			}

			return false;
		}
	}

	/**
	 * Get the size of a file
	 * @param  string $file The file path on the FTP server
	 * @return mixed
	 */
	public function size($file)
	{
		$size = ftp_size($this->getConnector(), $file);

		if ($size == -1)
		{
			throw new Exception("Cannot get filesize of " . $file . " on FTP server");
		}

		return ($size != -1 ) ? $size : false;
	}

	/**
	 * Rename a file
	 * @param  string $oldPath Old path
	 * @param  string $newPath New path
	 * @return mixed
	 */
	public function rename($oldPath, $newPath)
	{
		if (!ftp_rename($this->getConnector(), $oldPath, $newPath))
		{
			throw new FtpTransportException("Cannot rename " . $oldPath . " to " . $newPath . " on FTP server");
		}
	}

	/**
	 * Create a directory
	 * @param  string $dir Directory name
	 * @return mixed
	 */
	public function createDir($dir)
	{
		if (!ftp_mkdir($this->getConnector(), $dir))
		{
			throw new FtpTransportException("Cannot change FTP directory to " . $dir);
		}
	}

	/**
	 * Remove a directory
	 * @param  string $dir Directory name
	 * @return mixed
	 */
	public function removeDir($dir)
	{
		if (!ftp_rmdir($this->getConnector(), $dir))
		{
			throw new FtpTransportException("Cannot remove directory on FTP server, with path/dir" . $dir);
		}
	}

	/**
	 * List file in a folder on the FTP server
	 * @param  string $path Optinal: Give a path to list
	 * @return array The formatted list
	 */
	public function listFiles($path = null)
	{
		if (is_null($path))
		{
			if (!$buffer = ftp_rawlist($this->getConnector(), $this->getWorkingDirectory()))
			{
				throw new FtpTransportException("Cannot list files for path " . $path . ", does this path exists?");
			}	
		} else {
			if (!$buffer = ftp_rawlist($this->getConnector(), $path))
			{
				throw new FtpTransportException("Cannot list files for path " . $path . ", does this path exists?");
			}	
		}


		return $this->formatList($buffer);
	}

	/**
	 * Run a raw command on the FTP server
	 * @param  string $command The command to run
	 * @return string          Output result
	 */
	public function raw($command)
	{
		return ftp_raw($this->getConnector(), $command);
	}

	/**
	 * Set the FTP connector
	 * @param Connector $connector The FTP connector
	 */
	public function setConnector(Connector $connector)
	{
		$this->connector = $connector;
	}

	/**
	 * Get the FTP connector
	 * @return object The FTP connector
	 */
	public function getConnector()
	{
		return $this->connector->connector;
	}

	/**
	 * Get the current working directory on the FTP server
	 * @return mixed
	 */
	protected function getWorkingDirectory()
	{
		return ftp_pwd($this->getConnector());
	}

	/**
	 * Resolves the directory of a path
	 * @param  string $path The path to resolve
	 * @return string       The resolved directory
	 */
	protected function resolveDirectory($path)
	{
		if (count(explode('/', $path)) == 0 || count(explode('\\', $path)) == 0)
		{
			return $path;
		}

		return ltrim($path, '/') . ltrim($path . '\\') . $this->getWorkingDirectory();
	}

	/**
	 * Format a list provied by the ftp_rawlist function
	 * @param  array $buffer The buffer
	 * @return array         The formatted list
	 */
	protected function formatList($buffer)
	{
		$items = array();

		foreach ($buffer as $child)
		{
			$chunks = preg_split("/\s+/", $child);

			list($item['rights'],
				$item['number'],
				$item['user'],
				$item['group'],
				$item['size'],
				$item['month'],
				$item['day'],
				$item['time']
			) = $chunks;

			$item['type'] = $chunks[0]{0} === 'd' ? 'dir' : 'file';

			array_splice($chunks, 0, 8);
			$items[implode(" ", $chunks)] = $item;
		}

		return $items;
	}
}