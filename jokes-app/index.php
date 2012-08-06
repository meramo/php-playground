<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/magicquotes.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php'; // Establish db connection in include file

// Checks if the query string contains a variable named addjoke
if (isset($_GET['addjoke']))
{
  include 'form.html.php';
  exit();
}

// Adds a joke to the database
if (isset($_POST['joketext'])) // Detects form submission
{
  try
  {
    $sql = 'INSERT INTO joke SET
        joketext = :joketext,
        jokedate = CURDATE()'; // : is a placeholder for the value
    $s = $pdo->prepare($sql); // Prepares the query, no execution yet
    $s->bindValue(':joketext', $_POST['joketext']);
    $s->execute(); // Don't mix with exec() method
  }
  catch (PDOException $e)
  {
    $error = 'Error adding submitted joke: ' . $e->getMessage();
    include 'error.html.php';
    exit();
  }

  header('Location: .');
  exit();
}

// Deletes joke from the database
if (isset($_GET['deletejoke']))
{
  try
  {
    $sql = 'DELETE FROM joke WHERE id = :id'; // Use placeholder
    $s = $pdo->prepare($sql); // Prepares a query
    $s->bindValue(':id', $_POST['id']); // Binds the submitted value of $_POST['id'] to the placeholder
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error deleting joke: ' . $e->getMessage();
    include 'error.html.php';
    exit();
  }

  header('Location: .'); // Asks the browser to send a new request to view the updated list of jokes
  exit();
}

// The first controller load, displays list of jokes
try
{
  $sql = 'SELECT joke.id, joketext, name, email FROM joke INNER JOIN author
      ON authorid = author.id';
  $result = $pdo->query($sql);
}
catch (PDOException $e)
{
  $error = 'Error fetching jokes: ' . $e->getMessage();
  include 'error.html.php';
  exit();
}

// Makes each item in the $jokes array an array in its own right
foreach ($result as $row) // or while ($row = $result->fetch())
{
$jokes[] = array(
'id' => $row['id'],
'text' => $row['joketext'], 
'name' => $row['name'], 
'email' => $row['email'] // So that we can use $joke['email'] in templates
); 

}

include 'jokes.html.php';
