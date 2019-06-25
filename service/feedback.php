<?php

if (isset($_POST["feedback_theme"]) and isset($_POST["feedback_email"]) and isset($_POST["feedback_msg"])) {
  require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
  require_once $_SERVER["DOCUMENT_ROOT"] . "/functions/functions.php";
  $theme = getsafePostWithoutLink($_POST["feedback_theme"]);
  $emailToAnswer = getsafePostWithoutLink($_POST["feedback_email"]);
  $msg = getsafePostWithoutLink($_POST["feedback_msg"]) . " \n Эмейл для ответа: $emailToAnswer";

  if (mail($config_mail, $theme, $msg)) {
    echo "<span class=\"success\">Письмо \"$theme\" отправлено.<br>\nОтвет придёт на: $emailToAnswer</span>";
  } else {
    echo "<span class=\"red\">Ошибка! Сообщение не отправлено</span>";
  }
} else {
  echo "<span class=\"red\">Не хватает данных</span>";
}
