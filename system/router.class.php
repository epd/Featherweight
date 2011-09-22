<?php
// $Id$

/**
 * @file
 * Featherweight Router class.
 *
 * This class is designed to handle all application routing of requests to the
 * server. This will leverage the current URL path and the defined routes to
 * see if an available view or script is available for the current request.
 */

class Router {
  /**
   * Boolean value (do we need a DB connection?) or holds the database
   * configuration after initialization.
   */
  protected $DB = FALSE;

  /**
   * Holds the path to the view to be included.
   */
  protected $view;

  /**
   * Holds the application theme to render this route with
   */
  protected $theme = THEME;

  /**
   * Implementation of __construct().
   *
   * Constructs our Router object that holds inforation specific to the
   * current request.
   */
  public function __construct() {
    global $router;
    // Get our route information based on the current path
    $route = $this->getRoute($router);
    $this->DB = $route['DB'];

    // Print debug routing informationg
    if (DEBUG) {
      echo "<pre>";
      echo $_SERVER['REQUEST_URI'] . "\n";
      print_r($route);
      echo "\nTheme: " . $this->theme;
      echo "</pre>\n";
    }
    // Include our view to render to user
    $this->view = "views/" . $route['view'];
    require_once $this->view;

    // Cleanup
    $router = $route = NULL;
  }

  /**
   * Implementation of useDB().
   *
   * Does this request need to use a database connection? Gets the protected
   * variable and returns it.
   */
  public function useDB() {
    return $this->DB;
  }

  /**
   * Implementation of setDB().
   *
   * When a request confirms it needs a database connection, the 
   * DatabaseConnection class uses this method to store the connection
   * object.
   */
   public function setDB(DatabaseConnection $db) {
     $this->DB = $db;
   }

  /**
   * Implementation of getRoute().
   *
   * This utility method gets the current URL path and finds a route for it.
   */
  protected function getRoute($router) {
    if (empty($_SERVER['REQUEST_URI'])) {
      $_SERVER['REQUEST_URI'] = "/";
    }
    $route = $router[$_SERVER['REQUEST_URI']];
    if (isset($route)) {
      // If the file exists, route to this
      if (file_exists("views/" . $route['view'])) {
        // Set the current theme for this route
        if (isset($route['theme'])) {
          $this->theme = $route['theme'];
        }
        return $route;
      }
    }
    // Else, 404
    return array(
      "DB" => FALSE,
      "view" => "errors/404.php",
    );
  }
}
