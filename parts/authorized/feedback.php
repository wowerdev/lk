<?php
@session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/functions/functions.php";
if (isAut()) { ?>
  <div class="feedback">
    <div class="feedback__wrap">
      <form action="/" method="POST" class="feedback__form">
        <div class="feedback__row">
          <label class="feedback__label for=" feedback_theme">Тема письма</label>
          <input type="text" required name="feedback_theme" id="feedback_theme" class="feedback__input input" placeholder="Введите тему">
        </div>
        <div class="feedback__row">
          <label class="feedback__label for=" feedback_email">Email для ответа:</label>
          <input type="email" required name="feedback_email" id="feedback_email" class="feedback__input input" placeholder="Введите email">
        </div>
        <div class="feedback__row">
          <label class="feedback__label for=" feedback_msg">Ваше сообщение:</label>
          <textarea name="feedback_msg" id="feedback_msg" class="feedback__textarea textarea" required placeholder="Введите ваше сообщение"></textarea>
        </div>
        <div class="feedback__row">
          <button class="feedback__send btn">Отправить</button>
        </div>
      </form>
    </div>
  </div>

<?php } else {
  echo "<div class=\"account\">Вы не авторизированы</div>";
}
?>