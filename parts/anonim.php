<section class="authorization">
  <form action="/" class="authorization__form" method="POST">
    <div class="authorization__wrap">
      <h1 class="authorization__title">Авторизируйтесь</h1>
      <label for="authorization_name" class="authorization__label">Логин</label>
      <input type="text" class="input authorization__input" id="authorization_name" name="authorization_name" placeholder="Введите логин" required>
      <label for="authorization_pass" class="authorization__label">Пароль</label>
      <input type="password" autocomplete="off" class="input authorization__input authorization__input--last" id="authorization_pass" name="authorization_pass" placeholder="Введите пароль" required>
      <button class="authorization__btn authorization__btn--aut btn">Войти</button>
      <a href="reg.php" class="authorization__btn authorization__btn--reg btn">Регистрация</a>
      <button type="button" class="authorization__btn authorization__btn--back btn" onclick="window.history.back();">Назад</button>
      <p class="authorization__result">
      
      </p>
    </div>
  </form>
</section>
