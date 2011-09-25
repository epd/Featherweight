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
      // Save existing password and mask it for debug information
      $password = $database['password'];
      $database['password'] = "******** <i>(hidden)</i>";

      echo '<pre class="debug">';
      echo "<b>Database:</b>\n";
      print_r($database);
      echo "</pre>\n";

      // Reset password to original value
      $database['password'] = $password;
      unset($password);
    }

    // Connect to our database via the specified driver
    try {
      $this->db = $this->loadDriver($database);
    }
    catch (Exception $e) {
      // An error occured
      die('<pre class="debug error"><i><b>' . __CLASS__ . '</b>: ' . $e->getMessage() . '</i></pre>');
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

  // Required methods for each driver
  public function query($tables) {}
  public function fields($fields) {}
  public function conditions($conditions) {}
  public function execute() {}
}
