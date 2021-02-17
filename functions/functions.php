<?php
function setBonusCount($connect, $count_bonus, $acc_id)
{
  $sql = "UPDATE `lk_bonus` SET `count` = (count + $count_bonus) WHERE `acc_id` = $acc_id";
  $res = $connect->query($sql);
}

function initBonus($connect, $acc_id, $start_bonus)
{
  // Проверяем есть ли в таблице с бонусами аккаунт, если нет, то создаём
  $sql = "SELECT * FROM `lk_bonus` WHERE `acc_id` = $acc_id";
  $res = $connect->query($sql);

  if (!$res) {  // Если произошла ошибка. Например нет такой таблицы
    $bonus_count = $connect->error;
  } else {
    if ($data = $res->fetch_assoc()) { // Если всё успешно и запись найдена
      $bonus_count = $data["count"];
    } else { // Если нет аккаунта в таблице, создаём
      $sql = "INSERT INTO `lk_bonus` VALUES ($acc_id, $start_bonus)";
      $res = $connect->query($sql);
      if (!$res) { // Если создание прошло с ошибкой
        $bonus_count = $connect->error;
      } else if ($connect->affected_rows == 1) { // Если запись успешно создана
        $bonus_count = $start_bonus;
      }
    }
  }
  return $bonus_count;
}

function getSafePost($str, $link)
{
  $str = trim($str);
  $str = strip_tags($str);
  $str = addslashes($str);
  $str = htmlspecialchars($str);
  $str = mysqli_escape_string($link, $str);
  return $str;
}

function getSafePostWithoutLink($str)
{
  $str = trim($str);
  $str = strip_tags($str);
  $str = addslashes($str);
  $str = htmlspecialchars($str);
  return $str;
}

function getValidPass($str)
{
  $str  = preg_replace('/[^A-Za-z0-9]/', '', $str);
  return $str;
}


function isValidPass($old, $new)
{
  if ($old == $new) {
    echo "<span class='fail'>Новый и старый пароль совпадают</span>";
    die();
  }
  if (!ctype_alnum($new)) {
    echo "<span class='fail'>Пароль может содержать только англ. буквы и цифры</span>";
    die();
  }
  if (strlen($new) > 12 or strlen($new) < 6) {
    echo "<span class='fail'>Пароль должен быть от 6 до 12 символов</span>";
    die();
  }
  return true;
}

function getNumber($str)
{
  return $str = preg_replace("/[^0-9]/", '', $str);
}

function isAut()
{
  if (isset($_SESSION["state"]) and $_SESSION["state"] == "true") {
    return true;
  } else {
    return false;
  }
}

function isValidLoginReg($str)
{
  if (strlen($str) > 32 or strlen($str) < 4) {
    echo "<span class=\"fail\">Логин должен быть от 4 до 32 символов</span>";
    return false;
  } else if (!ctype_alnum($str)) {
    echo "<span class=\"fail\">Логин должен состоять из англ букв и цифр</span>";
    return false;
  } else {
    return true;
  }
}

function isValidPassReg($str)
{
  if (strlen($str) > 32 or strlen($str) < 4) {
    echo "<span class=\"fail\">Пароль должен быть от 4 до 32 символов</span>";
    return false;
  } else if (!ctype_alnum($str)) {
    echo "<span class=\"fail\">Пароль должен состоять из англ букв и цифр</span>";
    return false;
  } else {
    return true;
  }
}



function GetSRP6RegistrationData($username, $password)
{
  // generate a random salt
  $salt = random_bytes(32);

  // calculate verifier using this salt
  $verifier = CalculateSRP6Verifier($username, $password, $salt);

  // done - this is what you put in the account table!
  return array($salt, $verifier);
}

function CalculateSRP6Verifier($username, $password, $salt)
{
  // algorithm constants
  $g = gmp_init(7);
  $N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);

  // calculate first hash
  $h1 = sha1(strtoupper($username . ':' . $password), TRUE);

  // calculate second hash
  $h2 = sha1($salt . $h1, TRUE);

  // convert to integer (little-endian)
  $h2 = gmp_import($h2, 1, GMP_LSW_FIRST);

  // g^h2 mod N
  $verifier = gmp_powm($g, $h2, $N);

  // convert back to a byte array (little-endian)
  $verifier = gmp_export($verifier, 1, GMP_LSW_FIRST);

  // pad to 32 bytes, remember that zeros go on the end in little-endian!
  $verifier = str_pad($verifier, 32, chr(0), STR_PAD_RIGHT);

  // done!
  return $verifier;
}

function VerifySRP6Login($username, $password, $salt, $verifier)
{
  // re-calculate the verifier using the provided username + password and the stored salt
  $checkVerifier = CalculateSRP6Verifier($username, $password, $salt);

  // compare it against the stored verifier
  return ($verifier === $checkVerifier);
}
