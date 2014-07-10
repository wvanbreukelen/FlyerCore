<?php

use Flyer\Foundation\Events\Events;
use Flyer\Foundation\Facades\Facade;
use Flyer\Components\ClassLoader;
use Flyer\Foundation\Registry;
use Flyer\Foundation\Config\Config;
use Flyer\App;

/**
 * Create a new application
 */

$app = new App(new Config);

/**
 * Set the application registry handler
 */

$app->setRegistryHandler(new Registry);

/**
 * Set up the Exception handler for the application
 */

$whoops = new Whoops\Run();
$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());
$whoops->register();

/**
 * Setting up the current request method
 */

Registry::set('application.request.method', $_SERVER['REQUEST_METHOD']);

/**
 * Require the config files and add those results to the Registry
 */

$app->config()->import(APP . 'config' . DS . 'config.php');

Registry::set('config', require(APP . 'config' . DS . 'config.php'));

/**
 * Setting up the events manager
 */

Registry::set('foundation.events', new Events);

/**
 * Setting the current HTTP request to the events manager
 */

Events::create(array(
	'title' => 'request.get',
	'event' => function () {
		return Request::createFromGlobals();
	}
));

/**
 * Setting up the default error page
 */

Events::create(array(
	'title' => 'application.error.404',
	'event' => function () {
		return View::render('404.blade', array('page' => Request::createFromGlobals()->getPathInfo()));
	}
));

/**
 * Creating all aliases for the original classes, they are specified in the config array
 */


/**
 * Initialize the Database component
 */

$app->createAliases(array('Eloquent' => 'Illuminate\Database\Eloquent\Model'), false);

/**
 * Register all of the developed created compilers
 */

$app->setViewCompilers(Registry::get('config')['viewCompilers']);

/**
 * Attach all of the service providers (specified the config file) to the application
 */

$app->register(Registry::get('config')['serviceProviders']);

$app->createAliases(Registry::get('config')['classAliases']);


/**
 * Initialize the facade and setting some things up
 */

Facade::setFacadeApplication($app);
$app->attach('app', $app);

/**
 * Require the route file
 */

require(APP . 'routes.php');

/**
 * Boot the application
 */

$app->boot();

/**
 * Shutdown the application
 */

$app->shutdown();
