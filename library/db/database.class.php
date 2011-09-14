<?php
/**
 * @file
 * DatabaseConnection class.
 *
 * Implementation of our Database class, providing sanitation and connection
 * features.
 */
class DatabaseConnection {
  // Our connection resource string
  protected $dsn = NULL;

  // Establish our connection, set up our DSN
  public function __construct($dsn = NULL) {
    $dsn = parse_url($dsn);
  }
}
