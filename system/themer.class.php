<?php
/**
 * Themer class file.
 *
 * This class provides functionality to take a view (by a specific route) and
 * render the theme (template, css, js, view file, etc.) to the browser/user.
 */

class Themer {
  /**
   * Holds our views file names and regions
   */
  protected $views;

  /**
   * Holds our preloader file names
   */
  protected $preload;

  /**
   * Holds our current theme name
   */
  protected $theme = THEME;

  /**
   * Holds our current template file
   */
  protected $template = TEMPLATE;

  /**
   * Holds our database connection
   */
  protected $db;

  /**
   * Construct our theme!
   */
  public function __construct($views, $preload, $theme, $template, $db) {
    // Make arguments available as member variables
    $this->views = $views;
    $this->preload = $preload;
    $this->theme = $theme;
    $this->template = $template;
    $this->db = $db->getDriver();

    // Debug print of information
    if (DEBUG) {
      echo '<pre class="debug">';
      echo "<b>Request:</b> " . $_SERVER['REQUEST_URI'] . "\n";
      echo "<b>View:</b>\n";
      print_r($this->views);
      echo "<b>Preload:</b>\n";
      print_r($this->preload);
      echo "<b>Theme:</b> ";
      print_r($this->theme);
      echo "\n<b>Template:</b> ";
      print_r($this->template);
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
    include_once "theme/" . $this->theme . "/" . $this->template;
    $template = ob_get_contents();
    ob_clean();

    // Include our view preloader files
    global $db;
    $db = $this->db;
    if (!empty($this->preload)) {
      foreach ($this->preload AS $load) {
        include_once "preload/" . $load;
      }
    }

    // Clean our buffer and get our views
    foreach ($this->views AS $region => $view_inc) {
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

