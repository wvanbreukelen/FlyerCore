<?php

namespace Flyer\Components\Package;

use File;

/**
 * The package installer handles off easy package installations without composer, so it is easier for the user to simply select a
 * zip file and install that kind of package. Of course, our developer can still the composer application to add their wished packages
 */
class PackageInstaller
{

	protected $package;

	public function __construct(Package $package)
	{
		$this->package = $package;
	}

	public function install()
	{
		// First, let's extract the files from the zip file into
		// the workbench folder

		if (!File::extract($this->package->getBasepath()))
		{
			throw new Exception("Cannot extract this package!");
		}

		// Second, read out the install file for information
		
		$config = $this->readPackageConfig();

		// Let's use that information to run the package install script
		
		$this->runPackageInstallScript($config['install']['path']);

		// Write a simple setup package for the user
		
		$this->writeSetupPage($config, $this->basepath);
	}

	protected function readPackageConfig()
	{
		return json_decode(File::contents($this->package->getBasepath() . 'config.json'), true);
	}

	protected function runPackageInstallScript($path)
	{
		if (File::exists($path)
		{
			include($path);
		}
	}

	protected function writeSetupPage($config, $basepath, $write = true)
	{
		$text = "Package install report for " . $config['name'] . "\n=======================\n\n";

		$text .= "Succesfully installed " . $config['name'] . "! Please consider reading the information below!\n\n\n";
		$text .= "This information was given by the package itself:\n\n";
		$text .= $config['install']['info'] . "\n";
		$text .= "Version installed: " . $config['version'];

		if ($write)
		{
			File::write(APP . 'debug' . DS . 'packages' . DS . 'installreport_' . $config['name'] . '.txt', $text);
		}

		return $text;
	}
}