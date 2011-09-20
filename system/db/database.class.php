<?php
/**
 * @file
 * DatabaseConnection class.
 *
 * Implementation of our Database class, providing sanitation and connection
 * features.
 */
class DatabaseConnection {
  // Holds our database connection resource
  protected $db;

  /**
   * Implementation of __construct().
   *
   * Establish our database connection and store the connected resource in
   * a member variable, $db.
   */
  public function __construct() {
    global $database;

    // If DEBUG, print out or DSN for connection verification
    if (DEBUG == true) {
      echo "<pre>";
      print_r($database);
      echo "</pre>\n";
    }

    // Connect to our database via the specified driver
    try {
      $this->db = $this->loadDriver($database);
    }
    catch (Exception $e) {
      // An error occured
      die("<i><b>" . __CLASS__ . "</b>: " . $e->getMessage() . "</i>");
    }

    // Cleanup
    $database = NULL;
  }

  /**
   * Implementation of loadDriver().
   *
   * Loads the provided driver class to later initialize a new connection.
   */
  protected function loadDriver($database) {
    // Our driver class file to include
    $driver = strtolower($database['driver']);
    $inc = "system/db/drivers/" . $driver . ".class.php";

    // If this does not exist, throw an exception
    if (!file_exists($inc)) {
      throw new Exception("Could not load database driver file.");
      return;
    }

    // Create a new instance of our database driver class and return it
    require_once $inc;
    $class = $driver . "Database";
    return new $class($database);
  }
}
