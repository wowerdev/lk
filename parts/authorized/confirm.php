<?php
@session_start();
require_once  "functions/functions.php";
if (isAut()) { ?>
  <div class="confirm">
    <form action="/" method="POST" class="confirm__form">
      <p class="confirm__form-p">Подтвердите действие:</p>
      <p class="confirm__form-p"><span class="orange"><span class="confirm__service-name"></span> (<span class="confirm__service-char"></span>)</span></p>
      <p class="confirm__form-p">Стоимость: <span class="confirm__form-span--price orange"></span> бонус(ов)</p>
      <div class="confirm__form-btn-block">
        <button class="confirm__form-btn btn btn--min" type="submit">Подтвердить</button>
        <button class="confirm__form-btn confirm__form-btn--close btn btn--min" type="reset">Отказаться</button>
      </div>
      <input type="hidden" name="service_type" id="service_type">
      <input type="hidden" name="char_guid" id="char_guid">
    </form>
  </div>
<?php } else {
  echo "No Aut";
} ?>