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

// Security salt (Please change this!)
$application['app salt'] = "FEATHERWEIGHT";

// Your database connection information
$database = array(
  'driver' => $_SERVER['db_driver'],
  'hostname' => $_SERVER['db_hostname'],
  'username' => $_SERVER['db_username'],
  'password' => $_SERVER['db_password'],
  'database' => $_SERVER['db_database'],
  'socket' => $_SERVER['db_socket'],
);

// Define your application's main theme
$theme = "default";

// Define your application routes
$router["/"] = array(
  "DB" => TRUE,
  "view" => "index.php",
  "preload" => "example.php",
);
$router["/example"] = array(
  "view" => "example.php",
);

// Define data sets
$data['KEY'] = array(
  "table1" => array(
    "field1",
    "field2",
    "field3",
  ),
  "conditions" => array(
    "OR" => array(
      "field1 = field2",
      "field1 = 1",
    ),
  ),
);
