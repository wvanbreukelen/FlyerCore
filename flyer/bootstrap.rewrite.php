<?php

use Flyer\App;
use Flyer\Foundation\Registry;

$app = new App();
$app->setRegistryHandler(new Registry);


/**
 * Attach the config files to the application
 */

Registry::set('config', require(APP . 'config' . DS . 'config.php'));

/**
 * Setting up the events handlerer
 */

Registry::set('foundation.events', new Events);


