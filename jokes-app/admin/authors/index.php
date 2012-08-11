<?php
include_once $_SERVER['DOCUMENT_ROOT'] .'/includes/magicquotes.inc.php';

// Displays the add form after getting the ?add from authors.html.php
if (isset($_GET['add']))
{
  $pageTitle = 'New Author';
  $action = 'addform';
  $name = '';
  $email = '';
  $id = '';
  $button = 'Add author';

  include 'form.html.php';
  exit();
}

// Submits the new author from the previously displayed form
if (isset($_GET['addform']))
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

  try
  {
    $sql = 'INSERT INTO author SET
        name = :name,
        email = :email';
    $s = $pdo->prepare($sql);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':email', $_POST['email']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error adding submitted author.';
    include 'error.html.php';
    exit();
  }
  // After form submission, displays the list of authors from the controller
  header('Location: .');
  exit();
}

// Edits the author in authors.html.php
if (isset($_POST['action']) and $_POST['action'] == 'Edit')
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

  try
  {
    $sql = 'SELECT id, name, email FROM author WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error fetching author details.';
    include 'error.html.php';
    exit();
  }

  // Holds the SELECT result
  $row = $s->fetch();

  $pageTitle = 'Edit Author';
  $action = 'editform';
  $name = $row['name'];
  $email = $row['email'];
  $id = $row['id'];
  $button = 'Update author';

  include 'form.html.php';
  exit();
}

// Submits the edited author to the database
if (isset($_GET['editform']))
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

  try
  {
    $sql = 'UPDATE author SET
        name = :name,
        email = :email
        WHERE id = :id';
    $s = $pdo->prepare($sql);
    $s->bindValue(':id', $_POST['id']);
    $s->bindValue(':name', $_POST['name']);
    $s->bindValue(':email', $_POST['email']);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error updating submitted author.';
    include 'error.html.php';
    exit();
  }

  header('Location: .');
  exit();
}

// Deletes the author and all associated data
if (isset($_POST['action']) and $_POST['action'] == 'Delete')
{
  include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

  // Gets jokes belonging to author
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

  // Retrieves the entire set of results for the query and store them in a PHP array ($result)
  $result = $s->fetchAll();

  // Deletes joke category entries
  try
  {
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

  // Delete the author
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

// Displays author list. Default behaviour
include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.inc.php';

try
{
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
