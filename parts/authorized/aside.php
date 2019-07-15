<?php
@session_start();
require_once  "functions/functions.php";
if (isAut()) { ?>
  <aside class="aside">
    <div class="aside__wrap">
      <h1 class="aside__title">Личный кабинет</h1>
      <ul class="aside__list">
        <li class="aside__item">
          <button class="aside__btn btn" data-type="account" data-title="Информация о аккаунте">Аккаунт</button>
        </li>
        <li class="aside__item">
          <button class="aside__btn btn" data-type="char" data-title="Информация о персонажах">Персонажи</button>
        </li>
        <li class="aside__item">
          <button class="aside__btn btn" data-type="feedback" data-title="Обратная связь">Обратная связь</button>
        </li>
        <li class="aside__item">
          <button class="aside__btn btn" data-type="vote" data-title="Голосование">Голосование</button>
        </li>
        <li class="aside__item">
          <button class="aside__btn aside__btn--back btn" onclick="window.history.back();">Вернуться назад</button>
        </li>
        <li class="aside__item">
          <button class="aside__btn aside__btn--back btn" id="exit_btn">Выйти</button>
        </li>
      </ul>
      </nav>
      <img src="img/race.png" alt="Личный кабинет сервера" title="Личный кабинет" class="aside__img">
    </div>
  </aside>
<?php } else {
  echo "<div class=\"account\">Вы не авторизированы</div>";
}
?>