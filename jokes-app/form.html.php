<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Add Joke</title>
    <style type="text/css">
    textarea {
      display: block;
      width: 100%;
    }
    </style>
  </head>
  <body>
    <form action="?" method="post">
      <div>
        <label for="joketext">Type your joke here:</label>
        <textarea id="joketext" name="joketext" rows="3" cols="40"></textarea>
       <!--  <label for="jokeauthorid">Specify the author ID:</label>
        <input type="text" id="jokeauthorid" name="jokeauthorid"/> -->
      </div>
      <div><input type="submit" value="Add"></div>
    </form>
  </body>
</html>
