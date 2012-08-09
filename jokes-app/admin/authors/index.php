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

// Logic to delete authors and all related entries (jokes, cats)
if (isset($_POST['action']) and $_POST['action'] == 'Delete')
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';
  // Geting jokes belonging to author
  try
  {
    $sql = 'SELECT id FROM joke WHERE authorid = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
}
catch (PDOException $e)
{
  $error = 'Error getting list of jokes to delete.';
  include 'error.html.php';
  exit();
}

// Retrieves the entire set of results for the query and stores them in a PHP array ($result)
$result = $s->fetchAll();

// Deletes joke category entries
try
{
  // Creates a prepared statement from the SQL code with a placeholder in it
  $sql = 'DELETE FROM jokecategory WHERE jokeid = :id';
  $s = $pdo->prepare($sql);
  // For each joke
  foreach ($result as $row)
  {
    $jokeId = $row['id'];
    $s->bindValue(':id', $jokeId);
    $s->execute();
  } 
}
catch (PDOException $e)
{
  $error = 'Error deleting category entries for joke.';
  include 'error.html.php';
  exit();
}

// Deletes jokes belonging to author
try
{
  $sql = 'DELETE FROM joke WHERE authorid = :id';
  $s = $pdo->prepare($sql);
  $s->bindValue(':id', $_POST['id']);
  $s->execute();
}
catch (PDOException $e)
{
  $error = 'Error deleting jokes for author.';
  include 'error.html.php';
  exit();
}

 // Deletes the author
try
{
  $sql = 'DELETE FROM author WHERE id = :id';
  $s = $pdo->prepare($sql);
  $s->bindValue(':id', $_POST['id']);
  $s->execute();
}
catch (PDOException $e)
{
  $error = 'Error deleting author.';
  include 'error.html.php';
  exit();
}
 
header('Location: .');

exit(); 

}
