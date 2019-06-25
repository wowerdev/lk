<?php
@session_start();
if (isset($_POST["authorization_name"]) and isset($_POST["authorization_pass"])) {
  require_once  $_SERVER['DOCUMENT_ROOT'] . "/connection.php";
  require_once $_SERVER['DOCUMENT_ROOT'] . "/functions/functions.php";
  $login = getSafePost($_POST["authorization_name"], $connectAuth);
  $password = getSafePost($_POST["authorization_pass"], $connectAuth);
  $sha_password = strtoupper(sha1(strtoupper($login) . ":" . strtoupper($password)));
  $sql = "SELECT * FROM `account` WHERE `username` = '$login' AND `sha_pass_hash` = '$sha_password' LIMIT 1";
  $res = $connectAuth->query($sql);
  if ($res and $res->num_rows == 1) {
    $data = $res->fetch_assoc();
    @$_SESSION["username"] = $login;
    @$_SESSION["state"] = "true";
    @$_SESSION["acc_id"] = $data["id"];
    setcookie("login", $login, time() + 999999, "/", "", 0);
    setcookie("hash_pass",  $sha_password, time() + 999999, "/", "", 0);
    echo true;
  } else {
    echo "<span class=\"fail\">Не верный логин\пароль</span>";
  }
} else {
  echo "<span class='red'>Вы не ввели данные</span>";
}
