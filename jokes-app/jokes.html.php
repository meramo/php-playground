<?php include_once $_SERVER['DOCUMENT_ROOT'] .
    '/includes/helpers.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>List of Jokes</title>
  </head>
  <body>

    <p><a href="?addjoke">Add your own joke</a></p>

    <p>Here are all the jokes in the database:</p>

    <?php foreach ($jokes as $joke): ?>
      <!-- Each joke displayed in a form -->
      <form action="?deletejoke" method="post">
        <blockquote>
          <p>
            <?php markdownout($joke['text']) ?> <!-- Making use of helper for htmlspecialchars() -->
            <input type="number" name="id" value="<?php echo $joke['id']; ?>">
            <input type="submit" value="Delete">
            (by <a href="mailto:
            <?php htmlout($joke['email']); ?>">
            <?php htmlout($joke['name']) ?></a>)
          </p>
        </blockquote>
      </form>
    <?php endforeach; ?>

  </body>
</html>
