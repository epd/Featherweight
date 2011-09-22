<?php
// $Id$

/**
 * Initialization file.
 *
 * This file takes our application definitions and converts them into
 * PHP constants to be used throughout the web app. It also performs any
 * other necessary initialization steps and pre-routing processing.
 */
define("APP_NAME", $application['app name']);
define("BASE_PATH", $application['base path']);
define("DEBUG", isset($debug) ? $debug : FALSE);
define("THEME", isset($theme) ? $theme : "default");

// Cleanup
unset($application, $theme, $debug);
