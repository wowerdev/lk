<?php
@session_start();
require_once  "../config.php";
require_once  "../functions/functions.php";

if (isAut()) {

  if (isset($_POST["service_type"]) and $_POST["service_type"] != "" and isset($_POST["char_guid"]) and $_POST["char_guid"] != "") {

    require_once  "../connection.php";

    $type = getSafePost($_POST["service_type"], $connectChar);

    $isActiveService = [
      "change_nick" => $config_change_nick,
      "change_race" => $config_change_race,
      "change_fraction" => $config_change_fraction,
      "teleport" => $config_teleport
    ];

    $priceService = [
      "change_nick" => $config_change_nick_price,
      "change_race" => $config_change_race_price,
      "change_fraction" => $config_change_fraction_price,
      "teleport" => $config_teleport_price
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


      // Проверяем хватает ли бонусов
      $sql_check = "SELECT `count` FROM `lk_bonus` WHERE `acc_id` = $acc_id LIMIT 1";
      $res_check = $connectAuth->query($sql_check);

      if ($res_check) {

        $data_check = $res_check->fetch_assoc();
        $bonus_count = $data_check["count"];
        $service_price = $priceService[$type];
        if ($bonus_count >= $service_price) {
          // Если хватает бонусов
          $res = $connectChar->query($sql);
          if ($res) {
            $num = $connectChar->affected_rows;
            if ($num == 1) {
              $sql = "UPDATE `lk_bonus` SET `count` = (count -  $service_price) WHERE `acc_id` = $acc_id";
              $res = $connectAuth->query($sql);

              if ($res) {
                $current_bonus = $bonus_count - $service_price;
                echo "<span class=\"green\">Успешно выполнено!<br>Cнято бонусов: $service_price<br>Ваш баланс: $current_bonus</span>";
                return;
              } else {
                echo "<span class=\"green\">Успешно выполнено, но что то случилось с бонусами. Обновите страницу</span>";
                return;
              }
            } else {
              echo "<span class=\"red\">Ошибка! Персонаж в игре или услуга уже выполнена.</span>";
              return;
            }
          } else {
            echo $connectChar->error;
          }
        } else {
          // Если не хватает бонусов
          echo "<span class=\"red\">У вас не хватает бонусов!<br>Цена услуги: $service_price<br>Ваш баланс: $bonus_count</span>";
        }
      } else {
        echo "<span class=\"red\">Ошибка! $connectAuth->error</span>";
      }
      // Конец проверки
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
