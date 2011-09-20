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
  'driver' => "mysql",
  'hostname' => "localhost",
  'username' => "username",
  'password' => "password",
  'database' => 'my_db',
);

// Define your application routes
$router["/"] = array(
  "DB" => TRUE,
  "view" => "home.php",
);
