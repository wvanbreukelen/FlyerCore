<?php

namespace Flyer\Components\Package;

use Commandr\Core\Command;
use Flyer\Components\Package\Package;
use Flyer\Components\Package\PackageInstaller;

class PackageInstallCommand extends Command
{
	public $callsign = 'package install';

	public function prepare()
	{
		$this->setConfig(
			array("arguments" => array("path")
		));

		$this->setDescription("Install a package");
		$this->setSummary("Install a package by any given .zip file");
	}

	public function action()
	{
		$package = new Package();

		$package->setPath($this->getArgument("path"));

		$installer = new PackageInstaller($package);

		$installer->install();
	}
}