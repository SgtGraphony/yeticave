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
<?php @$classname = isset($errors) ? " form--invalid" : "" ;?>
<form class="form form--add-lot container<?= $classname ;?>" action="add.php" name="add-lot" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <?php @$classname = add_class_name($errors, 'lot-name'); ?>
        <div class="form__item <?= $classname;?>"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= $lot['lot-name'] ?? null ;?>">
          <span class="form__error"><?= $errors['lot-name'];?></span>
        </div>
        <?php @$classname = add_class_name($errors, 'category'); ?>
        <div class="form__item <?= $classname;?>">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
            <option>Выберите категорию</option>
            <?php foreach($categories as $category): ?>
            <option value="<?= $category['id'];?>"><?= $category['name'];?></option>
            <?php endforeach ;?>
          </select>
          <span class="form__error"><?= $errors['category'];?></span>
        </div>
      </div>
      <?php @$classname = add_class_name($errors, 'description'); ?>
      <div class="form__item form__item--wide <?= $classname;?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="description" placeholder="Напишите описание лота" value="<?= $lot['description'] ?? null ;?>"></textarea>
        <span class="form__error"><?= $errors['description'];?></span>
      </div>
      <?php @$classname = add_class_name($errors, 'image'); ?>
      <div class="form__item form__item--file<?= $classname;?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="lot-img" name="image" value="<?= $lot['image'] ?? null ;?>">
          <label for="lot-img">
            Добавить
          </label>
        </div>
      </div>
      <div class="form__container-three">
      <?php @$classname = add_class_name($errors, 'price'); ?>
        <div class="form__item form__item--small <?= $classname;?>">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="price" placeholder="0" value="<?= $lot['price'] ?? null ;?>">
          <span class="form__error"><?= $errors['price'];?></span>
        </div>
        <?php @$classname = add_class_name($errors, 'rate-step'); ?>
        <div class="form__item form__item--small <?= $classname;?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="rate-step" placeholder="0" value="<?= $lot['rate-step'] ?? null ;?>">
          <span class="form__error"><?= $errors['rate-step'];?></span>
        </div>
        <?php @$classname = add_class_name($errors, 'expiration'); ?>
        <div class="form__item <?= $classname;?>">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date" type="text" name="expiration" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<? $lot['lot-date'] ?? null ;?>">
          <span class="form__error"><?= $errors['expiration'];?></span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button" name="submit">Добавить лот</button>
    </form>
    </main>