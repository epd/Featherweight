<?php
// $Id$

/**
 * Initialization file.
 *
 * This file takes our application definitions and converts them into
 * PHP constants to be used throughout the web app. It also performs any
 * other necessary initialization steps and pre-routing processing.
 */

// Override $debug global if environment variable set (convert string to boolean)
if (isset($_SERVER['DEBUG'])) {
  switch (strtoupper($_SERVER['DEBUG'])) {
    case "TRUE":
      $debug = TRUE;
      break;
    case "FALSE":
      $debug = FALSE;
      break;
    default:
      break;
  }
}

// Our definitions
define("APP_NAME", $application['app name']);
define("BASE_PATH", $application['base path']);
define("APP_SALT", $application['app salt']);
define("DEBUG", isset($debug) ? $debug : FALSE);
define("THEME", isset($theme) ? $theme : "default");
define("TEMPLATE", isset($template) ? $template : "template.php");
define("APP_CSS", "/theme/" . THEME . "/css");
define("APP_JS", "/theme/" . THEME . "/js");

// Cleanup
unset($application, $theme, $debug);
