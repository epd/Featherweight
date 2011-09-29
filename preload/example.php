<?php
/**
 * @file
 * Example view pre-processor.
 *
 * This file will contain code to execute before the view is rendered.
 */
$examples = $pdo->query("SELECT title FROM examples");

