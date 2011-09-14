<?php
// $Id$

/**
 * @file
 * Site controller.
 *
 * This file acts as a traditional controller and routes all requests given to
 * the web application.
 */
session_start();

// Load our constants used through the app
require_once "library/system/definitions.php";

// Load custom user definitions
if (file_exists("application/definitions.php")) {
  require_once "application/definitions.php";
}

// Parse our definitions into constants to use throughout web app
require_once "library/system/init.php";

// Load our core routing system
require_once "library/system/router.class.php";

// Global variable that loads defaults from definitions
$_ROUTER = new Router;

// Load our database classes and methods (only if necessary)
if ($_ROUTER->useDB()) {
  require_once "library/db/database.class.php";
  $_ROUTER->setDB(new DatabaseConnection());
}
