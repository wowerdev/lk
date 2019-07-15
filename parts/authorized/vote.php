<?php
@session_start();
require_once  "../../functions/functions.php";
if (isAut()) { 
  require_once  "../../config.php";
  ?>

  <div class="vote">
    <ul class="vote__list">
      <li class="vote__item">
        <div class="vote__item-wrap">
          <p class="vote__name orange">Mmotop</p>
          <p class="vote__count">1 голос = <span class="orange"><?php echo $mmotop_vote_count; ?></span> бонусов</p>
          <div class="vote__btn-block">
            <a href="<?php echo $mmotop_link; ?>" target="_blank" class="vote__btn vote__btn--vote btn btn--min">Проголосовать</a>
            <button class="vote__btn vote__btn--add btn btn--min">Начислить голоса</button>
          </div>
        </div>
      </li>
    </ul>
  </div>
<?php } else {
  echo "<div class=\"account\">Вы не авторизированы</div>";
}
?>