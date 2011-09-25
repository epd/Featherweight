<?php
// $Id$

/**
 * @file
 * Applications definitions file.
 *
 * Define application settings and routing information. Also define a
 * database configuration to connect to.
 */

// Turn on/off debug mode
$debug = TRUE;

// Your applications name, used almost exclusively for visual
$application['app name'] = "My Featherweight Web App";

// Your database connection information
$database = array(
  'driver' => $_SERVER['db_driver'],
  'hostname' => $_SERVER['db_hostname'],
  'username' => $_SERVER['db_username'],
  'password' => $_SERVER['db_password'],
  'database' => $_SERVER['db_database'],
);

// Define your application's main theme
$theme = "default";

// Define your application routes
$router["/"] = array(
  "DB" => TRUE,
  "view" => "index.php",
  "preload" => "example.php",
);

