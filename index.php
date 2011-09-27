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

// Load our constants and configuration used through the app
require_once "system/definitions.php";

// Load custom user definitions
if (file_exists("definitions.php")) {
  require_once "definitions.php";
}

// Parse our definitions into constants and globals to use throughout web app
require_once "system/init.php";

// Load our core theming system
require_once "system/themer.class.php";

// Load our core database connection system
require_once "system/db/database.class.php";

// Load our core routing system
require_once "system/router.class.php";

// Global variable that loads defaults from definitions
$_ROUTER = new Router;

