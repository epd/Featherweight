<?php
/**
 * @file
 * MySQL Database connection class.
 */
class mysqlDatabase {
  // Holds our connection resource
  protected $connection;

  /**
   * Implementation of __construct().
   *
   * Establish our connection based on the $database information provided.
   */
  public function __construct($database) {
    // Create a new connection resource
 	  $this->connection = mysql_connect($database['hostname'], $database['username'], $database['password']);

    // An error occured if resource is malformed, or database cannot be used.
 	  if (!$this->connection || !mysql_select_db($database['database'], $this->connection)) {
 	  	throw new Exception("Could not connect to database.");
 	  }
  }
}
