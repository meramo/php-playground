<?php

function userIsLoggedIn()
{
  if (isset($_POST['action']) and $_POST['action'] == 'login') // if the form is submitted
  {
    if (!isset($_POST['email']) or $_POST['email'] == '' or
      !isset($_POST['password']) or $_POST['password'] == '') // if no email or pass, or empty
    {
      $GLOBALS['loginError'] = 'Please fill in both fields'; // sets $loginError
      return FALSE; // function ends
    }

    $password = md5($_POST['password'] . 'ijdb'); // scrambles submitted password

    // If the db contains a matching author, we log him in
    if (databaseContainsAuthor($_POST['email'], $password))
    {
      session_start();
      $_SESSION['loggedIn'] = TRUE;
      $_SESSION['email'] = $_POST['email'];
      $_SESSION['password'] = $password;
      return TRUE;
    }
    else
    {
      session_start();
      unset($_SESSION['loggedIn']);
      unset($_SESSION['email']);
      unset($_SESSION['password']);
      $GLOBALS['loginError'] =
          'The specified email address or password was incorrect.';
      return FALSE;
    }
  }

  // Handles the logout form
  if (isset($_POST['action']) and $_POST['action'] == 'logout')
  {
    session_start();
    unset($_SESSION['loggedIn']);
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    header('Location: ' . $_POST['goto']);
    exit();
  }

  // If neither of the two special cases are matched, we check IF he's logged in
  session_start();
  if (isset($_SESSION['loggedIn']))
  {
    return databaseContainsAuthor($_SESSION['email'], $_SESSION['password']);
  }
}

// Custom function for querying the db
function databaseContainsAuthor($email, $password)
{
  include 'db.inc.php';
  /**
   * Counts the number of records in the author table that have a matching
   *  email address and password
   */
  try
  {
    $sql = 'SELECT COUNT(*) FROM author
        WHERE email = :email AND password = :password';
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $email);
    $s->bindValue(':password', $password);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error searching for author.';
    include 'error.html.php';
    exit();
  }

  $row = $s->fetch();

  if ($row[0] > 0)
  {
    return TRUE;
  }
  else
  {
    return FALSE;
  }
}

// Checks if logged-in user has a role
function userHasRole($role)
{
  include 'db.inc.php';

  try
  {
    $sql = "SELECT COUNT(*) FROM author
        INNER JOIN authorrole ON author.id = authorid
        INNER JOIN role ON roleid = role.id
        WHERE email = :email AND role.id = :roleId";
    $s = $pdo->prepare($sql);
    $s->bindValue(':email', $_SESSION['email']);
    $s->bindValue(':roleId', $role);
    $s->execute();
  }
  catch (PDOException $e)
  {
    $error = 'Error searching for author roles.';
    include 'error.html.php';
    exit();
  }

  $row = $s->fetch();

  if ($row[0] > 0)
  {
    return TRUE;
  }
  else
  {
    return FALSE;
  }
}
