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
   * Holds our current theme name
   */
  protected $theme = THEME;

  /**
   * Holds our current template file
   */
  protected $template = TEMPLATE;

  /**
   * Construct our theme!
   */
  public function __construct($views, $theme, $template) {
    // Make arguments available as member variables
    $this->views = is_array($views) ? $views : array("content" => $views);
    $this->theme = $theme;
    $this->template = $template;

    // Debug print of information
    if (DEBUG) {
      echo "<pre>";
      print_r($this->views);
      echo "\n\n";
      print_r($this->theme);
      echo "\n\n";
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
 
    // Clean our buffer and get our views
    foreach ($this->views AS $rk => $view) {
      include_once $view;
      $view = ob_get_contents();
      ob_clean();
      $template = str_replace("{{" . $rk . "}}", $view, $template);
    }

    // End output buffering and replace regions with HTML
    ob_end_clean();
    print $template;
  }
}

