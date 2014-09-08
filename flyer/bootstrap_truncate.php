<?php
/**
use Flyer\Foundation\Events\Events;
use Flyer\Foundation\Facades\Facade;
use Flyer\Components\ClassLoader;
use Flyer\Foundation\Registry;
use Flyer\Foundation\Config\Config;
use Flyer\App;


Create a new application


$app = new App(new Config);


Set the application registry handler


$app->setRegistryHandler(new Registry);


 Set up the Exception handler for the application


$whoops = new Whoops\Run();
$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());
$whoops->register();




Registry::set('application.request.method', $_SERVER['REQUEST_METHOD']);


$app->config()->import(APP . 'config' . DS . 'config.php');

Registry::set('config', require(APP . 'config' . DS . 'config.php'));



Registry::set('foundation.events', new Events);



Events::create(array(
	'title' => 'request.get',
	'event' => function () {
		return Request::createFromGlobals();
	}
));



Events::create(array(
	'title' => 'application.error.404',
	'event' => function () {
		return View::render('404.blade', array('page' => Request::createFromGlobals()->getPathInfo()));
	}
));






$app->createAliases(array('Eloquent' => 'Illuminate\Database\Eloquent\Model'), false);



$app->setViewCompilers(Registry::get('config')['viewCompilers']);



$app->register(Registry::get('config')['serviceProviders']);

$app->createAliases(Registry::get('config')['classAliases']);




Facade::setFacadeApplication($app);
$app->attach('app', $app);



require(APP . 'routes.php');



$app->boot();



$app->shutdown();
