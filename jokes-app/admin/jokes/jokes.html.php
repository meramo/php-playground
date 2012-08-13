<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Manage Jokes: Search Results</title>
  </head>
  <body>
    <h1>Search Results</h1>
    <?php if (isset($jokes)): ?>
      <table>
        <tr><th>Joke Text</th><th>Options</th></tr>
        <?php foreach ($jokes as $joke): ?>
        <tr>
          <td><?php htmlout($joke['text']); ?></td>
          <td>
            <form action="?" method="post"> <!-- We still can edit and delete jokes using the same logic -->
              <div>
                <input type="hidden" name="id" value="<?php htmlout($joke['id']); ?>">
                <input type="submit" name="action" value="Edit">
                <input type="submit" name="action" value="Delete">
              </div>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>

    <?php if (!isset($jokes)): ?>
    Sorry, you search is returned no results. Please try adjusting the search criteria.
    <?php endif; ?>


    <p><a href="?">New search</a></p>
    <p><a href="..">Return to JMS home</a></p>
  </body>
</html>
