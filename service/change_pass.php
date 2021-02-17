<?php
require_once  "../config.php";
if ($config_change_pass) {
  @session_start();
  require_once  "../functions/functions.php";
  if (isAut()) {

    if (isset($_POST["account_old-pass"]) and isset($_POST["account_new-pass"])) {
      require_once  "../connection.php";

      $login = strtoupper($_SESSION["username"]);

      $oldPass = getSafePost($_POST["account_old-pass"], $connectAuth);
      $newPass = getSafePost($_POST["account_new-pass"], $connectAuth);
      $newPass = getValidPass($newPass);

      // Else нет, т.к isValidPass сам выкинет ошибку и умрёт
      if (isValidPass($oldPass, $newPass)) {
        $sql = "SELECT * FROM `account` WHERE `username` = '$login' LIMIT 1";
        $res = $connectAuth->query($sql);
        if ($res and $res->num_rows == 1) {
          $data = $res->fetch_assoc();
          $oldSalt = $data["salt"];
          $oldVerifier = $data["verifier"];
          $isAuthUser = VerifySRP6Login($login, $oldPass, $oldSalt, $oldVerifier);

          if ($isAuthUser) {
            list($salt, $verifier) = GetSRP6RegistrationData($login, $newPass);
            $sql = "UPDATE `account` SET `salt` = '$salt', `verifier` = '$verifier' WHERE `username` = '$login'";
            $res = $connectAuth->query($sql);
            if ($res) {
              echo "<span class='success'>Пароль успешно изменён</span>";
            } else {
              echo "<span class='fail'>Произошла оишбка</span>" . $connectAuth->error;
            }
          } else {
            echo "<span class='fail'>Не совпадает старый пароль</span>";
          }
        } else {
          echo "<span class='fail'>Аккаунт не найден</span>";
        }
      }
    } else {
      echo "<span class='fail'>Введите данные</span>";
    }
  } else {
    echo "No access";
  }
} else {
  echo "<span class='fail'>Услуга отключена</span>";
  return false;
}
