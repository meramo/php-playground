<?php include_once $_SERVER['DOCUMENT_ROOT'] .
    '/includes/helpers.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manage Authors</title>
  </head>
  <body>
    <h1>Manage Authors</h1>
    <p><a href="?add">Add new author</a></p>
    <ul>
      <?php foreach ($authors as $author): ?>
      <li>
          <form action="" method="post"> <!-- Because we don't know user's decision yet -->
            <div>
              <?php htmlout($author['name']); ?>
              <input type="hidden" name="id" value="<?php echo $author['id']; ?>">
              <input type="submit" name="action" value="Edit">
              <input type="submit" name="action" value="Delete" onclick="return confirm('Are you sure you want to delete?')"/>
            </div>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
    <p><a href="..">Return to JMS home</a></p>
  </body>
</html>