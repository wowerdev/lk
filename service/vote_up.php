<?php
@session_start();
require_once  "../functions/functions.php";
require_once  "../connection.php";

$countVote = [0, 1, 10, 50, 100];
$current_day = date('j');
$asnwer = "Новых голосов нет. Повторите попытку через час.";
// Выбираем поледний id голоса и проверяем существует ли таблица голосований в бд
$sql = "SELECT MAX(`vote_id`) AS 'max_id' FROM `lk_vote`";
$res = $connectAuth->query($sql);
if ($res) {
  $data = $res->fetch_assoc();
  $max_id = $data["max_id"] ? $data["max_id"] : 0;  // Если есть хоть 1 запись то устанавливаем максимальный id
  if ((@$fileConnect = fopen($file_path_mmotop, 'r')) !== false) {
    while (($parseFile = fgetcsv($fileConnect, 0, "\t")) !== false) { // Идём циклом по файлу голосования и проверяем на правильность
      if (count($parseFile) != 5 or $max_id >= $parseFile[0]) {
        continue;
      } else {
        $vote_id = $parseFile[0];
        $vote_date = date('j', strtotime($parseFile[1]));
        $vote_ip = $parseFile[2];
        $vote_name = $parseFile[3];
        $vote_count = $countVote[$parseFile[4]];
        // Ищем id акка по имени голосовавшего
        $sql = "SELECT `account` FROM `characters` WHERE `name` = '$vote_name'";
        $res = $connectChar->query($sql);
        if ($res and $data = $res->fetch_assoc()) { // Если акк найден, записываем его id
          $vote_acc_id = $data["account"];
        } else {  // Если акк не найден, записываем в id -1 (такому акку не будет бонусов)
          $vote_acc_id = -1;
        }
        // Заполняем таблицу голосовавшими
        $sql = "INSERT INTO `lk_vote`(`vote_id`, `vote_date`, `vote_ip`, `vote_name`, `vote_count`, `acc_id`) 
        VALUES($vote_id, $vote_date, '$vote_ip', '$vote_name', $vote_count, $vote_acc_id)";
        $res = $connectAuth->query($sql);
        if (!$res) {
          echo $connectAuth->error;
        }
      }
    } // Конец цикла заполнения таблицы
    // выбираем все голооса за сегодня с найдеными акками и не начисленными бонусами
    $sql = "SELECT * FROM `lk_vote` WHERE `vote_today` = 0 AND `vote_date` = $current_day AND `acc_id` != -1";
    $res = $connectAuth->query($sql);
    if ($res) {
      while ($data = $res->fetch_assoc()) { // Если такие найдены, то начинаем цикл с проверкой
        // Защита от нескольких голосов с разных чаров для одного аккаунта
        // Ищем есть ли хоть один голос с начисленными бонусами от аккаунта за сегодня и если есть, не начисляем ему
        $current_acc_id = $data["acc_id"];
        $sql_inner = "SELECT * FROM `lk_vote` WHERE `vote_today` != 0 AND `acc_id` =  $current_acc_id AND `vote_date` = $current_day";
        $res_inner = $connectAuth->query($sql_inner);
        if ($res_inner and $data_inner = $res_inner->fetch_assoc()) { 
          continue; // Выходим из иттерации цика, если найден хоть 1 голос с зачисленными бонусами от аккаунта за сегодня
        } else {  // Если у аккаунта есть нет не 1ого зачисленного бонуса, зачисляем ему и ставим отметку, что голоса засчитаны
          $count_bonus = $data["vote_count"] * $mmotop_vote_count;
          $sql_add_bonus = "UPDATE `lk_bonus` SET `count` = (count + $count_bonus) WHERE `acc_id` = $current_acc_id";
          $res_add_bonus = $connectAuth->query($sql_add_bonus);
          if ($current_acc_id == @$_SESSION["acc_id"]) { // Если id аккаунта совпадает с вашим id, вылезает оповещение
            $asnwer = "<span class='green'>Начислено бонусов: $count_bonus. Обновите страницу</span> <br> \n";
          }
          $sql_add_vote = "UPDATE `lk_vote` SET `vote_today` = 1 WHERE `acc_id` = $current_acc_id";
          $res_add_vote = $connectAuth->query($sql_add_vote);
        }
      }
      echo $asnwer;
    } else {
      echo $connectAuth->error;
    }
  } else {
    echo "Файл с статистикой голосов не найден";
  }
} else {
  echo $connectAuth->error;
}
