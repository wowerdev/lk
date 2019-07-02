<?php
function setBonusCount($connect, $count_bonus, $acc_id)
{
  $sql = "UPDATE `lk_bonus` SET `count` = (count + $count_bonus) WHERE `acc_id` = $acc_id";
  $res = $connect->query($sql);
}

function initBonus($connect, $acc_id)
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
      $sql = "INSERT INTO `lk_bonus` VALUES ($acc_id, 0)";
      $res = $connect->query($sql);
      if (!$res) { // Если создание прошло с ошибкой
        $bonus_count = $connect->error;
      } else if ($connect->affected_rows == 1) { // Если запись успешно создана
        $bonus_count = "Ваш аккаунт зарегистрирован. У вас пока 0";
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
