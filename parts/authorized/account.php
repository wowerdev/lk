<?php
@session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/functions/functions.php";
if (isAut()) {
  require_once $_SERVER["DOCUMENT_ROOT"] . "/connection.php";
  require_once $_SERVER["DOCUMENT_ROOT"] . "/config.php";
  $acc_id =  $_SESSION["acc_id"];
  $sql = "SELECT * FROM `account` WHERE `id` = '$acc_id'";
  $res = $connectAuth->query($sql);
  $data = $res->fetch_assoc();

  $username = ucfirst(strtolower($data["username"]));
  $reg_date = date("d.m.Y", strtotime($data["joindate"]));
  $log_date = $data["last_login"] ? date("d.m.Y", strtotime($data["last_login"])) : "Новый аккаунт";
  $last_ip = $data["last_ip"];
  $email = $data["email"] ? $data["email"] : $data["reg_mail"];
  $hideClass = $config_change_pass ? null : "none";
  if ($data["online"] == 1) {
    $state = "<span class='green'>Онлайн</span>";
  } else {
    $sql = "SELECT * FROM `account_banned` WHERE `id` = $acc_id AND `active` = 1";
    $res = $connectAuth->query($sql);
    if ($res->fetch_assoc()) {
      $state = "<span class='red'>Забанен</span>";
    } else {
      $state = "<span class='orange'>Оффлайн</span>";
    }
  }

  $bonus_count = initBonus($connectAuth, $acc_id);



  ?>

  <div class="account">
    <ul class="account__list">
      <li class="account__item">
        <p class="account__p">Имя аккаунта: <span class="orange"><?php echo $username; ?></span></p>
      </li>
      <li class="account__item">
        <p class="account__p">Email: <span class="orange"><?php echo $email; ?></span></p>
      </li>
      <li class="account__item">
        <p class="account__p">Регистрация: <span class="orange"><?php echo $reg_date; ?></span></p>
      </li>
      <li class="account__item">
        <p class="account__p">Состояние: <?php echo $state; ?></p>
      </li>
      <li class="account__item">
        <p class="account__p">Заходил: <span class="orange"><?php echo $log_date; ?></span></p>
      </li>
      <li class="account__item">
        <p class="account__p">Последний IP: <span class="orange"><?php echo $last_ip; ?></span></p>
      </li>
      <li class="account__item">
        <p class="account__p">Ваш баланс: <span class="orange"><?php echo $bonus_count; ?></span> бонусов</p>
      </li>
      <li class="account__item <?php echo $hideClass; ?>">
        <button class="account__btn btn" id="сhangePass">Сменить пароль</button>
        <div class="account__pass-block">
          <form action="service/change_pass.php" method="POST" class="account__form">
            <div class="account__form-wrap">
              <input type="password" placeholder="Введите старый пароль" required name="account_old-pass" class="account__form-input input">
              <input type="password" placeholder="Введите новый пароль" required name="account_new-pass" class="account__form-input input">
              <button class="account__form-btn btn btn--inv">Изменить пароль</button>
            </div>
            <div class="account__form-result">

            </div>
          </form>
        </div>
      </li>
    </ul>
  </div> <?php
        } else {
          echo "<div class=\"account\">Вы не авторизированы</div>";
        } ?>