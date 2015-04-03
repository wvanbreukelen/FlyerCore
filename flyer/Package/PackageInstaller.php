<?php

namespace Flyer\Components\Package;

use File;

/**
 * The package installer handles off easy package installations without composer, so it is easier for the user to simply select a
 * zip file and install that kind of package. Of course, our developer can still the composer application to add their wished packages
 */
class PackageInstaller
{

	/**
	 * The package instance to install
	 * @var object
	 */
	protected $package;

	/**
	 * Construct a new package installer instance
	 * @param Package $package The package instance to use
	 */
	public function __construct(Package $package)
	{
		$this->package = $package;
	}

	/**
	 * Install the package
	 * @return mixed
	 */
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

	/**
	 * Read the package config
	 * @return array The converted package config.
	 */
	protected function readPackageConfig()
	{
		return json_decode(File::contents($this->package->getBasepath() . 'config.json'), true);
	}

	/**
	 * Run the package install script
	 * @param  string $path The path of the install script
	 * @return mixed
	 */
	protected function runPackageInstallScript($path)
	{
		if (File::exists($path))
		{
			include_once($path);
		}
	}

	/**
	 * Write a setup reader for the package
	 * @param  array  $config   The config for the package
	 * @param  string  $basepath The package basepath
	 * @param  boolean $write    Save the page to a file or not
	 * @return string            The setup text itself
	 */
	protected function writeSetupPage($config, $basepath, $write = true)
	{
		$text = "======================= Package install report for " . $config['name'] . " =======================\n\n";

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
