<?php
require __DIR__ . '/vendor/autoload.php';

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Backend</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="./favicon.ico">
</head>
<body>
    <form method="post" action="faker/db_seeder.php">
        <div>
            <span>
                Items amount
            </span>
            <input type="number" min="0" name="items_amount">
        </div>
        <button type="submit" class="btn btn-primary">Seed</button>
    </form>
</body>
</html>