<?php $classname = isset($errors) ? " form--invalid" : "" ;?>
<main>
    <nav class="nav">
      <ul class="nav__list container">
      <?php foreach($categories as $category) :?>
        <li class="nav__item <?php if ($category['cat_id'] == $selected_category) :?> nav__item--current <?php endif ?>"> <!-- nav__item--current -->
          <a href="all-lots.php?category=<?= $category['cat_id'];?>"><?= $category['name'] ;?></a>
        </li>
        <?php endforeach ;?>
      </ul>
    </nav>
    <form class="form container" action="/login.php" method="post"> <!-- form--invalid -->
      <h2>Вход</h2>
      <?php @$classname = add_class_name($errors, 'email'); ?>
      <div class="form__item <?= $classname;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $login_data['email'] ?? null ;?>">
        <span class="form__error"><?= $errors['email'] ;?></span>
      </div>
      <?php @$classname = add_class_name($errors, 'password'); ?>
      <div class="form__item form__item--last <?= $classname;?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= $errors['password'] ?? null ;?></span>
      </div>
      <button type="submit" class="button">Войти</button>
    </form>
  </main>

</div>

