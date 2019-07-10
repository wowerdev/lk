 <?php
  @session_start();
  require_once $_SERVER["DOCUMENT_ROOT"] . "/functions/functions.php";
  if (isAut() and isset($_SESSION["acc_id"])) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/connection.php";
    require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
    $acc_id =  $_SESSION["acc_id"];
    $sql = "SELECT * FROM `characters` WHERE `account` = $acc_id";
    $res = $connectChar->query($sql);

    $arrColorsClass = [
      ["Нет класса", "red"],
      ["Воин", "warrior"],
      ["Паладин", "paladin"],
      ["Охотник", "hunter"],
      ["Разбойник", "rogue"],
      ["Жрец", "priest"],
      ["Рыцарь смерти", "dk"],
      ["Шаман", "shaman"],
      ["Маг", "mage"],
      ["Чернокнижник", "warlock"],
      ["Друид", "druid"],
      ["Друид", "druid"]
    ];
    

    $isActiveService = [
      "change_nick" => $config_change_nick,
      "change_race" => $config_change_race,
      "change_fraction" => $config_change_fraction,
      "teleport" => $config_teleport
    ];

    ?>
   <div class="char">
     <ul class="char__list">
       <?php while ($data = $res->fetch_assoc()) {
          $lvl = $data["level"];
          $class = $data["class"];
          $name = $data["name"];
          $honor = $data["totalHonorPoints"];
          $arena = $data["arenaPoints"];
          $classArr = $arrColorsClass[$class];
          $guid = $data["guid"];

          $sql_guildmember = "SELECT * FROM `guild_member` WHERE `guid` = '$guid'";
          $res_guildmember = $connectChar->query($sql_guildmember);
          $data_guildmember = $res_guildmember->fetch_assoc();
          $guildid = $data_guildmember["guildid"];

          $sql_guild = "SELECT * FROM `guild` WHERE `guildid` = '$guildid'";
          $res_guild = $connectChar->query($sql_guild);
          $data_guild = $res_guild->fetch_assoc();
          $guildname = $data_guild["name"];


          ?>
         <li class="char__item" data-id="<?php echo $guid; ?>">
           <div class="char__col char__col--lvl"><?php echo  $lvl; ?></div>
           <div class="char__col char__col--icon"><img src="img/class/<?php echo $classArr[1];  ?>.jpg" alt="<?php echo $classArr[0];  ?>" title="<?php echo $classArr[0];  ?>"></div>
           <div class="char__col char__col--name">
             <p class="char__name"><span class="<?php echo $classArr[1]; ?>"><?php echo $name; ?></span></p>
             <p class="char__guild"><?php echo $guildname; ?></p>
           </div>
           <div class="char__col char__col--honor">
             <p class="char__honor-p">Очки арены: <span class="orange"><?php echo $arena; ?></span></p>
             <p class="char__honor-p">Хонор: <span class="orange"><?php echo $honor; ?></span></p>
           </div>
           <div class="char__col char__col--service">
             <ul class="char__service-list">
               <li class="char__service-item <?php echo $config_teleport ? null : "none"  ?>">
                 <button class="char__service-btn btn btn--min" data-price="<?php echo $config_teleport_price ?>" data-type="teleport">Телепорт в таверну</button>
               </li>
               <li class="char__service-item <?php echo $config_change_race ? null : "none"  ?>">
                 <button class="char__service-btn btn btn--min" data-price="<?php echo $config_change_race_price ?>" data-type="change_race">Сменить рассу</button>
               </li>
               <li class="char__service-item <?php echo $config_change_fraction ? null : "none"  ?>">
                 <button class="char__service-btn btn btn--min" data-price="<?php echo $config_change_fraction_price ?>" data-type="change_fraction">Сменить фракцию</button>
               </li>
               <li class="char__service-item <?php echo $config_change_nick ? null : "none"  ?>">
                 <button class="char__service-btn btn btn--min" data-price="<?php echo $config_change_nick_price ?>" data-type="change_nick">Сменить ник</button>
               </li>
             </ul>
           </div>
         </li>

       <?php } ?>
     </ul>
   </div>
 <?php } else {
  echo "<div class=\"account\">Вы не авторизированы</div>";
}
