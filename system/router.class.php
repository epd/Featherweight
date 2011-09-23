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
   * Holds the path to the views to be included.
   */
  protected $views;

  /**
   * Holds the application theme to render this route with
   */
  protected $theme = THEME;

  /**
   * Holds the application's current template file
   */
  protected $template = TEMPLATE;

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

    // Call our Themer class to output visual to the browser/user
    $display = new Themer($this->view, $this->theme, $this->template);

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

    // Our parameters, also cleaned for empty array values
    $params = $this->getRequestURI();

    // Our query variables, similar to query string: ?property=value&propery2=value2
    $query = array();

    // Grab our view, or the "next-best-thing"
    while (!empty($params)) {
      $path = "/" . implode("/", $params);
      $route = $router[$path];

      if (DEBUG) {
        echo '<pre class="debug">';
        echo "<b>Trying path:</b> " . $path;
        echo "</pre>";
      }
      // The view to render to user
      if (isset($route) && isset($route['view']['default'])) {
        $route['view'] = is_array($route['view']) ? $route['view'] : array("default" => $route['view']);
        if (file_exists("views/" . $this->view['default'])) {
          break;
        }
      }
      array_push($query, array_pop($params));
    }
    krsort($query);

    if (DEBUG) {
      echo '<pre class="debug">';
      echo "<b>Query variables:</b>\n";
      print_r($query);
      echo "</pre>";
    }

    // 404 on empty path
    if (empty($params)) {
      $route = array(
        "DB" => FALSE,
        "view" => array("default" => "errors/404.php"),
      );
    }

    // Set the current theme for this route
    if (isset($route['theme'])) {
      $this->theme = $route['theme'];
    }
    // Set the current template for this route
    if (isset($route['template'])) {
      $this->template = $route['template'];
    }
    // Set the current view for this route
    $this->view = $route['view'];

    return $route;
  }

  /**
   * Implementation of getRequestURI().
   *
   * This method takes the server's REQUEST_URI global and splits it up into
   * segments. It then removes all null/invalid chunks and returns the
   * parameters for additional routing.
   */
  protected function getRequestURI() {
    $request_params = explode("/", $_SERVER['REQUEST_URI']);
    $params = array();
    foreach ($request_params AS $rp) {
      if (preg_match("/^([a-zA-Z0-9-_]+)$/", $rp)) {
        array_push($params, $rp);
      }
    }
    return $params;
  }
}
