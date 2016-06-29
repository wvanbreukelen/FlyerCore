<?php

use Flyer\Components\Filesystem\File;
use Flyer\Components\Filesystem\Folder;

/**
 * Testing the Filesystem component using PHPUnit
 */

class FilesystemTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->file = new File;
		$this->folder = new Folder;
	}

	public function testGetRetrievesFiles()
	{
		file_put_contents(__DIR__ . '/file.txt', 'Hello World');
		$this->assertEquals('Hello World', $this->file->contents(__DIR__ . '/file.txt'));
		unlink(__DIR__ . '/file.txt');
	}

	public function testWriteFileAndExists()
	{
		$this->file->write(__DIR__ . '/file.txt', 'Hello World');
		$this->assertEquals('Hello World', file_get_contents(__DIR__ . '/file.txt'));
		unlink(__DIR__ . '/file.txt');
	}

	public function testWriteToFileAndAppendToFile()
	{
		$this->file->write(__DIR__ . '/file.txt', 'Hello');
		$this->file->append(__DIR__ . '/file.txt', ' World');
		$this->assertEquals('Hello World', file_get_contents(__DIR__ . '/file.txt'));
		unlink(__DIR__ . '/file.txt');
	}

	public function testWriteToFileAndDelete()
	{
		$this->file->write(__DIR__ . '/file.txt', 'Hello World');
		$this->assertTrue(file_exists(__DIR__ . '/file.txt'));
		unlink(__DIR__ . '/file.txt');
	}

	public function testMoveFileAndDelete()
	{
		$this->file->write(__DIR__ . '/file.txt');
		$this->file->move(__DIR__ . '/file.txt', __DIR__ . '/file2.txt');
		$this->assertTrue(file_exists(__DIR__ . '/file2.txt'));
		unlink(__DIR__ . '/file2.txt');
	}

	public function testCreateFolderExistsAndDelete()
	{
		$this->folder->create(__DIR__ . '/TestFolder');
		$this->assertTrue(file_exists(__DIR__ . '/TestFolder'));
		$this->folder->delete(__DIR__ . '/TestFolder');
		$this->assertFalse(file_exists(__DIR__ . '/TestFolder'));
	}

	public function testFileExists()
	{
		$this->assertFalse($this->file->exists(__DIR__ . '/ThisFileDoesNotExists.php'));
	}

	public function testFileRename()
	{
		file_put_contents(__DIR__ . '/file.txt', null);

		$this->file->rename(__DIR__ . '/file.txt', __DIR__ . '/fileRename.txt');
		$this->assertTrue(file_exists(__DIR__ . '/fileRename.txt'));
		$this->assertFalse(file_exists(__DIR__ . '/file.txt'));

		$this->file->delete(__DIR__ . '/fileRename.txt');
	}

	public function testCountFilesInCurrentFolder()
	{
		$this->assertEquals(count($this->folder->listFiles(__DIR__)), 1);
	}
}
