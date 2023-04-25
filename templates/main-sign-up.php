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
    <?php $classname = isset($errors) ? " form--invalid" : "" ;?>
<form class="form container" action="/sign-up.php" method="post" autocomplete="off"> <!-- form
    --invalid -->
      <h2>Регистрация нового аккаунта</h2>
      <?php @$classname = add_class_name($errors, 'email') ;?>
      <div class="form__item <?= $classname;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $user['email'] ?? null;?>">
        <span class="form__error"><?= $errors['email'];?></span>
      </div>
      <?php @$classname = add_class_name($errors, 'password'); ?>
      <div class="form__item <?= $classname;?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?= $errors['password'];?></span>
      </div>
      <?php @$classname = add_class_name($errors, 'name'); ?>
      <div class="form__item <?= $classname;?>">
        <label for="name">Имя<sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= $user['name'] ?? null ;?>">
        <span class="form__error"><?= $errors['name'];?></span>
      </div>
      <?php @$classname = add_class_name($errors, 'contact'); ?>
      <div class="form__item <?= $classname;?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="contact" placeholder="Напишите как с вами связаться" value="<?= $user['contact'];?>"></textarea>
        <span class="form__error"><?= $errors['contact'];?></span>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
  </main>