<main class="main">
  <div class="main__wrap">
    <?php
    @session_start();
    require_once "functions/functions.php";

    if (isAut()) {
      require_once "parts/authorized.php";
      return;
    } else {
      if (isset($_COOKIE["login"]) and isset($_COOKIE["salt"]) and isset($_COOKIE["verifier"])) {
        require_once "connection.php";

        $login = getSafePost($_COOKIE["login"], $connectAuth);

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
            require_once "parts/authorized.php";
          } else {
            setcookie("login", "", time() - 3600);
            setcookie("salt", "", time() - 3600);
            setcookie("verifier", "", time() - 3600);
            require_once "parts/anonim.php";
          }
        } else {
          setcookie("login", "", time() - 3600);
          setcookie("salt", "", time() - 3600);
          setcookie("verifier", "", time() - 3600);
          require_once "parts/anonim.php";
        }
      } else {
        require_once "parts/anonim.php";
      }
    }
    ?>
  </div>
</main>