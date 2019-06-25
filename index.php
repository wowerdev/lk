<?php

include("connection.php");
?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Личный кабинет</title>
  <link rel="stylesheet" href="css/main.min.css">
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>

<body class="body">
  <?php

  $root =  $_SERVER["DOCUMENT_ROOT"];

  require_once $root . "/start.php";
  ?>
  <script src="js/root.js"></script>

</body>

</html>