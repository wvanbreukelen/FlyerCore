<?php

/**
 * In this file you can edit the framework configuration to your own wish
 */

return array(

	/**
	 * These environment variables represend the core for your application, they handle general 'framework' stuff
	 */
	'environment' => array(
		'debug' => true,
		'defaultDebugFolder' => 'debug.log',
		'url' => 'localhost/workspace/public/',
	),

	/**
	 * All the service providers for your application
	 */
	'serviceProviders' => array(
		'Flyer\Components\Router\RouterServiceProvider',
		'Flyer\Components\View\ViewServiceProvider',
		'Flyer\Components\Database\DatabaseServiceProvider',
		'Flyer\Components\Security\SecurityServiceProvider',
		'Flyer\Components\Filesystem\FilesystemServiceProvider',
		'Flyer\Components\Random\RandomServiceProvider',
		'Flyer\Components\Server\ServerServiceProvider',
		'Flyer\Components\Config\ConfigServiceProvider',
		'Flyer\Components\Logging\LoggingServiceProvider',
		'Flyer\Components\HTML\HTMLServiceProvider',

	)
);
