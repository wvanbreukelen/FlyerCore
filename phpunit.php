<?php

define('AUTOLOADER_LOC', '../vendor/autoload.php');
define('BOOTSTRAP_LOC', '../bootstrap/bootstrap.php');

/**
 * Initialize application (fatal) error handling
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);



/**
 * Check if the composer autoloader file exists
 */

if (!file_exists(AUTOLOADER_LOC))
{
    throw new RuntimeException("Composer autoloader was not found, path: " . AUTOLOADER_LOC);
}

/**
 * Require the composer autoloader
 */

require(AUTOLOADER_LOC);
