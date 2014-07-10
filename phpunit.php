<?php

// Set error handling

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// Define the path constances

define('DS', DIRECTORY_SEPARATOR);
$stack = explode(DS, getcwd());
$array = array_pop($stack);
define('ROOT', implode(DS, $stack) . DS);
define('APP', ROOT . 'app' . DS);

// Require the composer autoloader

require('../vendor/autoload.php');

// Require the application's bootstrap

require('../flyer/bootstrap.php');

exit();