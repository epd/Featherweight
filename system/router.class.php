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
  // Our database object (PDO)
  protected $pdo;

  /**
   * Implementation of __construct().
   *
   * Constructs our Router object that holds inforation specific to the
   * current request.
   */
  public function __construct() {
    global $router, $pdo, $database;
    // Get our route information based on the current path
    try {
      $route = $this->getRoute($router);
      // Establish database connection, but do not keep recreating it
      $pdo = NULL;
      if ($route['DB'] && !($this->pdo instanceof PDO)) {
        $dsn = $database['driver'] . ':host=' . $database['hostname'] . ';dbname=' . $database['database'] . (!empty($database['socket']) ? ';unix_socket=' . $database['socket'] : '');
        if (DEBUG) {
          echo '<pre class="debug">Establishing database connection...';
          echo "\n<b>DSN:</b> " . $dsn . "\n";
          echo '</pre>' . "\n";
        } 
        $this->pdo = new PDO($dsn, $database['username'], $database['password']);
      }
      if ($route['DB']) {
        $pdo = $this->pdo;
      }

      // Call our Themer class to output visual to the browser/user
      $display = new Themer($route);
    }
    catch (Exception $e) {
      // An error occured
      die('<pre class="debug error"><i><b>' . __CLASS__ . '</b>: ' . $e->getMessage() . '</i></pre>');
    }

    // Cleanup
    $router = $database = $route = NULL;
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
    $path = "";
    $found = FALSE;
    while (!empty($params)) {
      $path = "/" . implode("/", $params);
      $route = isset($router[$path]) ? $router[$path] : NULL;

      if (DEBUG) {
        echo '<pre class="debug">';
        echo "<b>Trying path:</b> " . $path;
        echo "</pre>";
      }
      // The view to render to user
      if (isset($route) && isset($route['view']['default'])) {
        $route['view'] = is_array($route['view']) ? $route['view'] : array("default" => $route['view']);
        if (file_exists("views/" . $route['view']['default'])) {
          // We've got our view!
          $found = TRUE;
          break;
        }
      }
      array_push($query, array_pop($params));
    }
    krsort($query);
    $query = array_values($query);

    if (DEBUG) {
      echo '<pre class="debug">';
      echo "<b>Query variables:</b>\n";
      print_r($query);
      echo "</pre>";
    }

    // If route not found, check our root route, else 404
    if (empty($params)) {
      $route = $router["/"];
      if (isset($route) && isset($route['view']['default'])) {
        $route['view'] = is_array($route['view']) ? $route['view'] : array("default" => $route['view']);
        if (file_exists("views/" . $route['view']['default'])) {
          // We've got our view!
          $found = TRUE;
        }
      }
      if (!$found) {
        $route = array(
          "DB" => FALSE,
          "view" => array("default" => "errors/404.php"),
        );
      }
    }

    // Add in our query variables to our route
    $route['query'] = $query;

    // Set the current theme for this route
    $route['theme'] = isset($route['theme']) ? $route['theme'] : 'default';

    // Set the current preloaders for this route
    if (isset($route['preload'])) {
      $preloaders = !is_array($route['preload']) ? array($route['preload']) : $route['preload'];
      $route['preload'] = array();
      foreach ($preloaders AS $load) {
        if (file_exists("preload/" . $load)) {
          array_push($route['preload'], $load);
        }
      }
    }
    else {
      $route['preload'] = array();
    }
    // Warn the developer that no preloader is being included
    if (DEBUG && empty($route['preload'])) {
      echo '<pre class="debug error"><i><b>Router:</b> No view preloader files are being included.</i></pre>';
    }
 
    // Set the current template for this route
    $route['template'] = isset($route['template']) ? $route['template'] : 'template.php';

    // Set whether or not we want to use PDO
    $route['DB'] = isset($route['DB']) ? $route['DB'] : FALSE;

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
