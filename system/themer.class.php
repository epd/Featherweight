<?php
/**
 * Themer class file.
 *
 * This class provides functionality to take a view (by a specific route) and
 * render the theme (template, css, js, view file, etc.) to the browser/user.
 */

class Themer {
  /**
   * Holds our route to access views, themes, and template
   */
  protected $route;

  /**
   * Construct our theme!
   */
  public function __construct($route) {
    $this->route = $route;
    unset($route);

    // Debug print of information
    if (DEBUG) {
      echo '<pre class="debug">';
      echo "<b>Request:</b> " . $_SERVER['REQUEST_URI'] . "\n";
      echo "<b>View:</b>\n";
      print_r($this->route['view']);
      echo "<b>Preload:</b>\n";
      print_r($this->route['preload']);
      echo "<b>Theme:</b> ";
      print_r($this->route['theme']);
      echo "\n<b>Template:</b> ";
      print_r($this->route['template']);
      echo "</pre>\n";
    }

    // Get our template HTML and return it
    $this->getHTML();
  }

  /**
   * Implementation of getHTML().
   *
   * This will take our template and themes and construct our HTML to be
   * pushed to the browser.
   */
  public function getHTML() {
    // Start output buffering
    ob_start();

    // Get our template file
    include_once "theme/" . $this->route['theme'] . "/" . $this->route['template'];
    $template = ob_get_contents();
    ob_clean();

    // Make database accessible (if set in route)
    global $pdo;

    // Make our query string accessible
    global $query;
    $query = $this->route['query'];

    // Include our view preloader files
    if (!empty($this->route['preload'])) {
      foreach ($this->route['preload'] AS $preload) {
        include_once "preload/" . $preload;
      }
    }

    // Clean our buffer and get our views
    foreach ($this->route['view'] AS $region => $view_inc) {
      include_once "views/" . $view_inc;
      $view = ob_get_contents();
      ob_clean();
      $template = str_replace("{{" . $region . "}}", $view, $template);
    }

    // End output buffering and replace regions with HTML
    ob_end_clean();
    print preg_replace("/{{([a-zA-Z0-9])+}}/i", "", $template);
  }
}

