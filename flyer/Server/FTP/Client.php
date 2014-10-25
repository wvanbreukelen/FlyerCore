<?php

namespace Flyer\Components\Server\FTP;

use Flyer\Components\Server\FTP\Exceptions\FtpTransportException;
use Exception;
use Folder;
use File;

class Client
{

	protected $connector;

	public function __construct(Connector $connector)
	{
		$this->connector = $connector;
	}

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

	public function permission($file, $chmod = 0755)
	{
		if (!ftp_chmod($this->getConnector(), $chmod, $file))
		{
			throw new FtpTransportException("Cannot change permission of " . $file);
		}
	}

	public function modified($file)
	{
		if (!ftp_mdtm($this->getConnector(), $file))
		{
			throw new FtpTransportException("Cannot get last modified date for " . $file . " file");
		}
	}

	public function cd($path)
	{
		if (!ftp_chdir($this->getConnector(), $path))
		{
			throw new FtpTransportException("Cannot change directory to " . $path . ", make sure that this path does exists");
		}
	}

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

	public function size($file)
	{
		$size = ftp_size($this->getConnector(), $file);

		if ($size == -1)
		{
			throw new Exception("Cannot get filesize of " . $file . " on FTP server");
		}

		return ($size != -1 ) ? $size : false;
	}

	public function rename($oldPath, $newPath)
	{
		if (!ftp_rename($this->getConnector(), $oldPath, $newPath))
		{
			throw new FtpTransportException("Cannot rename " . $oldPath . " to " . $newPath . " on FTP server");
		}
	}

	public function createDir($dir)
	{
		if (!ftp_mkdir($this->getConnector(), $dir))
		{
			throw new FtpTransportException("Cannot change FTP directory to " . $dir);
		}
	}

	public function removeDir($dir)
	{
		if (!ftp_rmdir($this->getConnector(), $dir))
		{
			throw new FtpTransportException("Cannot remove directory on FTP server, with path/dir" . $dir);
		}
	}

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

	public function raw($command)
	{
		return ftp_raw($this->getConnector(), $command);
	}

	public function getConnector()
	{
		return $this->connector->connector;
	}

	protected function getWorkingDirectory()
	{
		return ftp_pwd($this->getConnector());
	}

	protected function resolveDirectory($path)
	{
		if (count(explode('/', $path)) == 0 || count(explode('\\', $path)) == 0)
		{
			return $path;
		}

		return ltrim($path, '/') . ltrim($path . '\\') . $this->getWorkingDirectory();
	}

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