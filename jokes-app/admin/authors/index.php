<?php
// Displays author list
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
try {
  $result = $pdo->query('SELECT id, name FROM author');
}
catch (PDOException $e)
{
  $error = 'Error fetching authors from the database!';
  include 'error.html.php';
  exit();
}
foreach ($result as $row)
{
  $authors[] = array('id' => $row['id'], 'name' => $row['name']);
}
include 'authors.html.php';