<?php

use Flyer\Components\Filesystem\File;
use Flyer\Components\Filesystem\Folder;

/**
 * Testing the Filesystem component
 */

class FilesystemTest extends PHPUnit_Framework_TestCase
{
	public function testGetRetrievesFiles()
	{
		file_put_contents(__DIR__ . '/file.txt', 'Hello World');
		$file = new File;
		$this->assertEquals('Hello World', $file->contents(__DIR__ . '/file.txt'));
		unlink(__DIR__ . '/file.txt');
	}

	public function testWriteFileAndExists()
	{
		$file = new File;
		$file->write(__DIR__ . '/file.txt', 'Hello World');
		$this->assertEquals('Hello World', file_get_contents(__DIR__ . '/file.txt'));
		unlink(__DIR__ . '/file.txt');
	}

	public function testWriteToFileAndAppendToFile()
	{
		$file = new File;
		$file->write(__DIR__ . '/file.txt', 'Hello');
		$file->append(__DIR__ . '/file.txt', ' World');
		$this->assertEquals('Hello World', file_get_contents(__DIR__ . '/file.txt'));
		unlink(__DIR__ . '/file.txt');
	}

	public function testWriteToFileAndDelete()
	{
		$file = new File;
		$file->write(__DIR__ . '/file.txt', 'Hello World');
		$this->assertEquals(true, file_exists(__DIR__ . '/file.txt'));
		unlink(__DIR__ . '/file.txt');
	}
}