<?php
// $Id$

/**
 * @file
 * Applications definitions file.
 *
 * Define application settings and routing information. Also define a
 * database configuration to connect to.
 */

// Your applications name, used almost exclusively for visual
$application['app name'] = "My Featherweight Web App";

// Define your application routes
$router["/"] = array(
  "DB" => FALSE,
  "view" => "home.php",
);
