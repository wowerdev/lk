<?php 
@session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/functions/functions.php";
if (isAut()) {
  require_once $root . "/parts/authorized/aside.php";
  require_once $root . "/parts/authorized/main.php";
}

