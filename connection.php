<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";

$connectChar = new mysqli($server, $login, $pass, $bd_char);
$connectAuth = new mysqli($server, $login, $pass, $bd_auth);

if ($connectChar->connect_errno and $connectAuth->connectAuth) {
  echo @$connectChar->connect_error . " " . @$connectAuth->connect_error;
}
