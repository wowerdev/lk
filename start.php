<main class="main">
  <div class="main__wrap">
    <?php
    @session_start();
    require_once $root . "/functions/functions.php";

    if (isAut()) {
      require_once $root . "/parts/authorized.php";
      return;
    } else {
      if (isset($_COOKIE["login"]) and isset($_COOKIE["hash_pass"])) {
        require_once  $_SERVER['DOCUMENT_ROOT'] . "/connection.php";
        $login = getSafePost($_COOKIE["login"], $connectAuth);
        $sha_password = getSafePost($_COOKIE["hash_pass"], $connectAuth);
        $sql = "SELECT * FROM `account` WHERE `username` = '$login' AND `sha_pass_hash` = '$sha_password' LIMIT 1";
        $res = $connectAuth->query($sql);
        if ($res and $res->num_rows == 1) {
          $data = $res->fetch_assoc();
          @$_SESSION["username"] = $login;
          @$_SESSION["state"] = "true";
          @$_SESSION["acc_id"] = $data["id"];
          require_once $root . "/parts/authorized.php";
        } else {
          setcookie("login", "", time() - 3600);
          setcookie("hash_pass", "", time() - 3600);
          require_once $root . "/parts/anonim.php";
        }
      } else {
        require_once $root . "/parts/anonim.php";
      }
    }
    ?>
  </div>
</main>