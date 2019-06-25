<?php
@session_start();
require_once $_SERVER["DOCUMENT_ROOT"] . "/functions/functions.php";
if (isAut()) { ?>
  <section class="section">
    <?php require_once $_SERVER["DOCUMENT_ROOT"] . "/parts/authorized/confirm.php"; ?>
    <div class="section__wrap">
      <h2 class="section__title">Информация об аккаунте</h2>
      <div class="section__block">
      </div>
    </div>
  </section>
<?php } ?>