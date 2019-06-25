<?php

include("connection.php");
?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Регистрация</title>
  <link rel="stylesheet" href="css/main.min.css">
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>


<body class="body body--reg">
  <main class="main">

    <div class="main__wrap">
      <section class="authorization authorization--reg">
        <form action="/" class="authorization__form">
          <div class="authorization__wrap">
            <h1 class="authorization__title">Зарегистрируйтесь</h1>
            <label for="reg_name" class="authorization__label">Логин</label>
            <input required type="text" class="input authorization__input" id="reg_name" name="reg_name" placeholder="Введите логин">
            <label for="reg_pass" class="authorization__label">Пароль</label>
            <input required type="password" class="input authorization__input" id="reg_pass" name="reg_pass" placeholder="Введите пароль">
            <label for="reg_mail" class="authorization__label">Email</label>
            <input required type="email" class="input authorization__input" id="reg_mail" name="reg_mail" placeholder="Введите Email">
            <label for="reg_captcha" class="authorization__label">Капча</label>
            <input required type="number" min="1" max="31" minlength="1" maxlength="2" class="input authorization__input authorization__input--last" id="reg_captcha" name="reg_captcha" placeholder="Введите сегодняшнее число">
            <input type="text" class="input authorization__input authorization__input--hidden" id="reg_spam" name="reg_spam" placeholder="Заполни если не робот">
            <button class="authorization__btn authorization__btn--req btn">Зарегистрироваться</button>
            <button type="button" onclick="window.history.back();" class="authorization__btn authorization__btn--back btn">Назад</button>
            <p class="authorization__result">
            </p>
          </div>
        </form>
      </section>
    </div>
  </main>
  <script src="js/root.js"></script>

</body>

</html>