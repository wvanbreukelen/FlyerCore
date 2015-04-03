<?php

namespace Flyer\Components;

class Package
{

	protected $path;

	protected $basepath;

	/**
	 * Set a package path
	 * @param string $path Package path
	 */
	public function setPath($path)
	{
		$this->path = $path;

		$this->guessBasePath();
	}

	/**
	 * Guess the name of the package
	 * @return string The name of the package
	 */
	public function guessPackageName()
	{
		$explod = explode('.zip', $this->path);

		return $explod[count($explod) - 1];
	}

	/**
	 * Guess the package base path
	 * @return string The basepath used in the package
	 */
	public function guessBasePath()
	{
		$this->basepath = $this->path . ROOT . 'workbench' . DS;

		return $this->basepath;
	}
}