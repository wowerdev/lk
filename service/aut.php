<?php
@session_start();
if (isset($_POST["authorization_name"]) and isset($_POST["authorization_pass"])) {
  require_once "../connection.php";
  require_once  "../functions/functions.php";
  $login = strtoupper(getSafePost($_POST["authorization_name"], $connectAuth));
  $password = getSafePost($_POST["authorization_pass"], $connectAuth);

  $sql = "SELECT * FROM `account` WHERE `username` = '$login' LIMIT 1";
  $res = $connectAuth->query($sql);
  if ($res and $res->num_rows == 1) {
    $data = $res->fetch_assoc();

    $salt = $data["salt"];
    $verifier = $data["verifier"];
    $isAuthUser = VerifySRP6Login($login, $password, $salt, $verifier);

    if ($isAuthUser) {
      @$_SESSION["username"] = $login;
      @$_SESSION["state"] = "true";
      @$_SESSION["acc_id"] = $data["id"];
      setcookie("login", $login, time() + 999999, "/", "", 0);
      setcookie("salt",  $salt, time() + 999999, "/", "", 0);
      setcookie("verifier",  $verifier, time() + 999999, "/", "", 0);
      echo true;
    } else {
      echo "<span class=\"fail\">Не верный логин\пароль</span>";
    }
  } else {
    echo "<span class=\"fail\">Не верный логин\пароль</span>";
  }
} else {
  echo "<span class='red'>Вы не ввели данные</span>";
}
