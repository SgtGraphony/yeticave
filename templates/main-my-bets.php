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
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php if(!empty($bets)) : ?>
        <?php foreach($bets as $bet) :?>
          <?php if($user_id == $bet['winner_id']) :?>
            <tr class="rates__item rates__item--win">
              <?php else :?>
          <tr class="rates__item <?php if(strtotime("now") > strtotime($bet['expiration'])):?> rates__item--end <?php endif ;?>">
          <?php endif ;?>
          <td class="rates__info">
            <div class="rates__img">
              <img src="/uploads/<?= $bet['image'];?>" width="54" height="40" alt="<?= $bet['name'];?>">
            </div>
            <h3 class="rates__title"><a href="lot.php?id=<?= $bet['id'];?>"><?= $bet['name'];?></a></h3>
            <?php if($user_id == $bet['winner_id']) :?><p><?= $bet['contact'] ;?></p><?php endif;?>
          </td>
          <td class="rates__category">
            <?= $bet['category'];?>
          </td>
          <?php $result = get_time_left($bet['expiration']) ?>
          <td class="rates__timer">
            <?php if($user_id == $bet['winner_id']) :?>
            <div class="timer timer--win">Ставка выиграла</div>
            <?php else :?>
            <?php $expiration_timer = get_time_left($bet['expiration']) ?>
            <?php if($expiration_timer[0] > 0 && strtotime("now") < strtotime($bet['expiration'])) :?>
            <div class="timer"><?= "$expiration_timer[0] : $expiration_timer[1]" ;?></div> <!--  timer--end  timer--finishing -->
            <?php elseif($expiration_timer[0] == 0 && $expiration_timer[1] > 0 && strtotime("now") < strtotime($bet['expiration'])) :?>
            <div class="timer timer--finishing"><?= "$expiration_timer[1] минут осталось" ;?></div>
            <?php elseif(strtotime("now") > strtotime($bet['expiration'])) :?>
            <div class="timer timer--end">Торги закончились</div>
            <?php endif ;?>
            <?php endif ;?>
          </td>
          <td class="rates__price">
            <?= price_format($bet['price']) ;?>
          </td>
          <td class="rates__time">
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
          </td>
        </tr>
         <?php endforeach ;?>
         <?php else :?>
            <h2>Ставок не найдено</h2>
            <?php endif ;?>
      </table>
    </section>
  </main>

