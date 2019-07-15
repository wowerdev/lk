<?php
require_once  "../config.php";
if ($config_change_pass) {
  @session_start();
  require_once  "../functions/functions.php";
  if (isAut()) {

    if (isset($_POST["account_old-pass"]) and isset($_POST["account_new-pass"])) {
      require_once  "../connection.php";

      $acc_name = $_SESSION["username"];


      $old = getSafePost($_POST["account_old-pass"], $connectAuth);
      $new = getSafePost($_POST["account_new-pass"], $connectAuth);
      $new = getValidPass($new);
      isValidPass($old, $new);
      $sha_old = strtoupper(sha1(strtoupper($acc_name) . ":" . strtoupper($old)));
      $sha_new = strtoupper(sha1(strtoupper($acc_name) . ":" . strtoupper($new)));
      $sql = "SELECT * FROM `account` WHERE `username` = '$acc_name' AND `sha_pass_hash` = '$sha_old'";
      $res = $connectAuth->query($sql);
      $isVerPass = $res->fetch_assoc() ? true : false;
      if ($isVerPass) {
        $sql = "UPDATE `account` SET `sha_pass_hash` = '$sha_new', `v` = '' WHERE `sha_pass_hash` = '$sha_old'";
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
      echo "<span class='fail'>Введите данные</span>";
    }
  } else {
    echo "No access";
  }
} else {
  echo "<span class='fail'>Услуга отключена</span>";
  return false;
}
