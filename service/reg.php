<?php
if (!isset($_POST["reg_spam"]) or $_POST["reg_spam"] != "") {
  echo "<span class=\"fail\">Обнаружен робот</span>";
} else if (isset($_POST["reg_name"]) and isset($_POST["reg_pass"]) and isset($_POST["reg_mail"]) and isset($_POST["reg_captcha"])) {

  require_once  "../functions/functions.php";
  require_once  "../connection.php";
  require_once  "../config.php";
  /* Основной поток */

  $reg_captcha = getNumber($_POST["reg_captcha"]);
  $current_day = date("j");
  if ($reg_captcha != $current_day) {
    echo "<span class=\"fail\">Неверная капча.<br>Введите сегодняшнее число без 0.(если не получается попробуйте +-1 день)<br><span class=\"green\">Например: $current_day</span></span>";
    die();
  }
  $login = strtoupper(getsafePost($_POST["reg_name"], $connectAuth));
  $pass = getsafePost($_POST["reg_pass"], $connectAuth);
  $mail = getsafePost($_POST["reg_mail"], $connectAuth);

  if (isValidLoginReg($login) and isValidPassReg($pass)) {
    $sql = "SELECT * FROM `account` WHERE `username` = '$login' LIMIT 1";
    $res = $connectAuth->query($sql);
    if ($res and $res->num_rows > 0) {
      echo "<span class=\"fail\">Логин занят</span>";
    } else {
      list($salt, $verifier) = GetSRP6RegistrationData($login, $pass);
      $sql = "INSERT INTO `account` (`username`, `salt`, `verifier`, `email`) VALUES('$login', '$salt', '$verifier', '$mail')";
      $res = $connectAuth->query($sql);
      // TODO из-за перехода на SRP6 иногда падает sql запрос из-за крокозябров соли. Временно отключаю показ ошибок
      // echo $connectAuth->error ? $connectAuth->error : "<span class=\"success\">Аккаунт $login успешно создан!<br>$current_realmlist</span>";
      echo $connectAuth->error ? "<span class='fail'>Ошибка сервера!<br>Нажмите 'Зарегистрироваться' еще раз</span>" : "<span class=\"success\">Аккаунт $login успешно создан!<br>$current_realmlist</span>";
    }
  }


  /* Конец Основной поток */
} else {
  echo "<span class=\"fail\">Не хватает данных</span>";
}
