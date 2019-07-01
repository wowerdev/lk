<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";

$connectChar = new mysqli($server, $login, $pass, $bd_char);
$connectAuth = new mysqli($server, $login, $pass, $bd_auth);
$connectChar->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");
$connectAuth->query("SET NAMES `utf8` COLLATE `utf8_general_ci`");

if ($connectChar->connect_errno and $connectAuth->connectAuth) {
  echo @$connectChar->connect_error . " " . @$connectAuth->connect_error;
}
