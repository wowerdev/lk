<?php 
@session_start();
require_once  "functions/functions.php";
if (isAut()) {
  require_once "parts/authorized/aside.php";
  require_once "parts/authorized/main.php";
}

