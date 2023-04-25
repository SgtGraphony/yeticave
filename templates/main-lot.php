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
    <section class="lot-item container">
      <h2><?= strip_tags($lot['name']); ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="/uploads/<?= strip_tags($lot['image']); ?>" width="730" height="548" alt="<?= strip_tags($lot['name']);?>">
          </div>
          <p class="lot-item__category">Категория: <span><?= strip_tags($lot['category']); ?></span></p>
          <p class="lot-item__description"><?= strip_tags($lot['description']);?></p>
        </div>
        <div class="lot-item__right">
          <?php if (@$_SESSION['username'] && @$lot['author_id'] !== @$_SESSION['id'] && @$bets[0]['user_id'] !== @$_SESSION['id']): ?>
          <div class="lot-item__state">
          <?php $expiration_timer = get_time_left($lot['expiration']) ?>
            <?php if($expiration_timer[0] > 0 && strtotime("now") < strtotime($lot['expiration'])) :?>
          <div class="timer"><?= "$expiration_timer[0] : $expiration_timer[1]" ;?></div> <!--  timer--end  timer--finishing -->
          <?php elseif($expiration_timer[0] == 0 && $expiration_timer[1] > 0 && strtotime("now") < strtotime($lot['expiration'])) :?>
            <div class="timer timer--finishing"><?= "$expiration_timer[1] минут осталось" ;?></div>
            <?php elseif(strtotime("now") > strtotime($lot['expiration'])) :?>
              <div class="timer timer--end">Торги закончились</div>
              <?php endif ;?>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?= price_format(strip_tags($current_price)) ;?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?= price_format($min_bet);?></span>
              </div>
            </div>
            <?php if(strtotime("now") < strtotime($lot['expiration'])) :?>
            <form class="lot-item__form" action="/lot.php?id=<?= $lot_id;?>" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item form__item--invalid">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="<?= $min_bet ?? null ;?>" value="<?= $bet ?? null ;?>">
                <span class="form__error"><?= $error ?? null ;?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
            <?php endif ;?>
          </div>
          <?php endif ;?>
          <div class="history">
            <h3>История ставок (<span><?= $bets_count;?></span>)</h3>
            <table class="history__list">
              <?php foreach($bets as $bet) :?>
              <tr class="history__item">
                <td class="history__name"><?= $bet['user_name'] ;?></td>
                <td class="history__price"><?= price_format($bet['price']) ;?></td>
                <?php $time_left = get_time_left($bet['date_published']) ;?>
                <?php $expiration_timestamp = date_create($bet['date_published']) ;?>
                <?php $remaining_hours = $time_left[0] ;?>
                <?php $remaining_minutes = $time_left[1] ;?>
                <?php if($remaining_hours < 1) :?>
                <td class="history__time"><?= "{$remaining_minutes}" . get_noun_plural_form($remaining_minutes, ' минута ', ' минуты ', ' минут ') . "назад" ;?></td>
                <?php elseif($remaining_hours > 1 && $remaining_hours < 24) :?>
                <td class="history__time"><?= "{$remaining_hours}" . get_noun_plural_form($remaining_hours, ' час ', ' часа ', ' часов ') . "назад" ;?></td>
                <?php elseif($remaining_hours > 24) :?>
                <td class="history__time"><?= date_format($expiration_timestamp, "d.m.Y в H:m") ;?></td>
                <?php endif ;?>  
              <?php endforeach ;?>
            </table>
          </div>
        </div>
      </div>
    </section>
  </main>