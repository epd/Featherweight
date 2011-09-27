<?php
/**
 * @file
 * MySQL Database connection class.
 */
class mysqlDatabase {
  // Holds our connection resource
  protected $connection;

  // Holds the tables were are querying
  protected $tables = array();

  // Holds the fields we are querying
  protected $fields = array();

  /**
   * Implementation of __construct().
   *
   * Establish our connection based on the $database information provided.
   */
  public function __construct($database) {
    // Create a new connection resource
 	  $this->connection = @mysql_connect($database['hostname'], $database['username'], $database['password']);

    // An error occured if resource is malformed, or database cannot be used.
 	  if (!$this->connection || !@mysql_select_db($database['database'], $this->connection)) {
 	  	throw new Exception("Could not connect to database.");
 	  }
  }

  /**
   * Implementation of query().
   *
   * Builds an SQL query, first starting with the table name(s).
   */
  public function query($tables = array()) {
    $this->tables = !is_array($tables) ? array($tables) : $tables;
    return $this;
  }

  /**
   * Implementation of fields().
   *
   * Adds our fields that we are going to query.
   */
  public function fields($fields = array()) {
    $this->fields = !is_array($fields) ? array($fields) : $fields;
    return $this;
  }

  /**
   * Implementation of execute().
   *
   * Executes the SQL query in memory and performs cleanup.
   */
  public function execute() {
    $query = "SELECT " . implode($this->fields, ",") . " FROM " . implode($this->tables, ",");
    if (DEBUG) {
      echo '<pre class="debug"><b>Executing query:</b> ' . $query . "</pre>\n";
    }
    $this->tables = $this->fields = array();
    $result = @mysql_query($query);
    if (!$result) {
      echo '<pre class="debug error"><i><b>' . __CLASS__ . ':</b> Could not query database.</i></pre>' . "\n";
    }
    $results = array();
    while ($row = mysql_fetch_assoc($result)) {
      $results[] = $row;
    }
    if (DEBUG) {
      echo '<pre class="debug"><b>Query returned:</b>' . "\n";
      print_r($results);
      echo "\n</pre>\n";
    }
    return $results;
  }
}
