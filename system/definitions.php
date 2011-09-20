<?php
// $Id$

/**
 * @file
 * System-level Definitions file.
 *
 * This file defines global variables used throughout the site to make
 * integration for a given environment easier. This file defines many
 * defaults that may be overridden in ../../application/definitions.php.
 */

// Turn on/off debug mode
$debug = FALSE;

// Application-based settings
$application = array(
  // Used for visual, becomes SITE_NAME
  'app name' => "",
  // Used when routing, becomes BASE_PATH
  'base path' => "/",
);

// Adds a default route for the root path
$router = array(
  "/" => array(
    // No database connection necessary
    "DB" => false,
  ),
);

// Default database connection settings
// Later, this is used from the database abstraction layer
$database = array(
  'driver' => NULL,
  'hostname' => NULL,
  'username' => NULL,
  'password' => NULL,
  'database' => NULL,
  'port' => NULL,
  'socket' => NULL,
);
