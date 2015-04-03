<?php

namespace Flyer\Components;

class Package
{

	protected $path;

	protected $basepath = false;

	/**
	 * Set the current zip package path
	 * @param string $path Package zip path
	 */
	public function setPath($path)
	{
		$this->path = $path;

		$this->guessBasePath($this->guessPackageName());
	}

	/**
	 * Guess the name of the package
	 * @return string The name of the package
	 */
	public function guessPackageName()
	{
		$pieces = explode('.zip', $this->path);

		return $pieces[count($pieces) - 1];
	}

	/**
	 * Guess the package base path
	 * @param string $name
	 * @return string The basepath used in the package
	 */
	public function guessBasePath($name)
	{
		$this->basepath = getcwd() . '..\\workbench' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;

		return $this->basepath;
	}
}