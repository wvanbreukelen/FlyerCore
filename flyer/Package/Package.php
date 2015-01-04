<?php

namespace Flyer\Components;

class Package
{

	protected $path;

	protected $basepath;

	public function setPath($path)
	{
		$this->path = $path;

		$this->guessBasePath();
	}

	public function guessPackageName()
	{
		$explod = explode('.zip', $this->path);

		return $explod[count($explod) - 1];
	}

	public function guessBasePath()
	{
		$this->basepath = $this->path . ROOT . 'workbench' . DS;
	}
}