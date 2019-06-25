<?php
@session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/functions/functions.php";
if (isAut()) {
  if (isset($_POST["service_type"]) and $_POST["service_type"] != "" and isset($_POST["char_guid"]) and $_POST["char_guid"] != "") {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/connection.php";
    $type = getSafePost($_POST["service_type"], $connectChar);
    $isActiveService = [
      "change_nick" => $config_change_nick,
      "change_race" => $config_change_race,
      "change_fraction" => $config_change_fraction,
      "teleport" => $config_teleport
    ];
    if ($isActiveService[$type]) {
      $guid = getNumber(getSafePost($_POST["char_guid"], $connectChar));
      $legalServices = [
        "change_nick" => 1,
        "change_race" => 128,
        "change_fraction" => 64,
        "teleport" => 0
      ];

      $acc_id = $_SESSION["acc_id"];
      if ($type == "teleport" and $config_teleport) {
        // Делаем доп запрос в базу, что бы убедиться что персонаж принадлежит аккаунту
        $sql = "SELECT * FROM `characters` WHERE `account` = '$acc_id' AND `guid` = '$guid' AND `online` = '0' LIMIT 1";
        $res = $connectChar->query($sql);
        if (!$res) {
          echo "<span class=\"red\">Ошибка! $connectChar->error</span>";
          return;
        }
        $data = $res->fetch_assoc();
        $validGuid = $data["guid"];
        $sql = "SELECT * FROM `character_homebind` WHERE `guid` = '$validGuid' LIMIT 1";
        $res = $connectChar->query($sql);
        if (!$res) {
          echo "<span class=\"red\">Ошибка! $connectChar->error</span>";
          return;
        }
        $data = $res->fetch_assoc();
        $coord_x = $data["posX"];
        $coord_y = $data["posY"];
        $coord_z = $data["posZ"];
        $map_id = $data["mapId"];
        $sql = "UPDATE `characters` SET `position_x` = '$coord_x', `position_y` = '$coord_y',  `position_z` = '$coord_z',  `map` = '$map_id' WHERE `account` = '$acc_id' AND `guid` = '$validGuid' AND `online` = '0'";
      } else {
        $flag = getNumber($legalServices[$type]);
        $sql = "UPDATE `characters` SET `at_login` = '$flag' WHERE `account` = '$acc_id' AND `guid` = '$guid' AND `online` = '0'";
      }

      $res = $connectChar->query($sql);
      if ($res) {
        $num = $connectChar->affected_rows;
        if ($num == 1) {
          echo "<span class=\"green\">Успешно выполнено!</span>";
          return;
        } else {
          echo "<span class=\"red\">Ошибка! Персонаж в игре или услуга уже выполнена.</span>";
          return;
        }
      } else {
        echo $connectChar->error;
      }
    } else {
      echo "<span class='red'>Услуга отключена</span>";
      return false;
    }
  } else {
    echo "<span class=\"red\">Нет данных</span>";
    return;
  }
} else {
  echo "No aut";
  return;
}
