<?php

use Flyer\Components\Package\PackageInstaller;
use Flyer\Components\Package;

class PackageInstallerTest extends PHPUnit_Framework_TestCase
{
	public function testPackageCreation()
	{
		$this->package = new Package();

		$this->package->setPath(getcwd() . '\\tests\\Package\\' . 'SamplePackage.zip');
	}
}