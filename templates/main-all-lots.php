<main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach($categories as $category) :?>
        <li class="nav__item <?php if($_GET['category'] == $category['cat_id']) :?> nav__item--current <?php endif ?>"> <!-- nav__item--current -->
          <a href="all-lots.php?category=<?= $category['cat_id'];?>"><?= $category['name'] ;?></a>
        </li>
        <?php endforeach ;?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Все лоты в категории <span>«<?= $category_name[0];?>»</span></h2>
        <ul class="lots__list">
            <?php foreach($lots as $lot) :?>
            <?php if(strtotime("now") >= strtotime($lot['expiration'])) :?>
            <?php else :?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="/uploads/<?= strip_tags($lot['image']); ?>" width="350" height="260" alt="<?= $lot['name'] ;?>">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?= $lot['category'] ;?></span>
              <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot['id'] ;?>"><?= $lot['name'];?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount">Стартовая цена</span>
                  <span class="lot__cost"><?= price_format($lot['price']) ;?></span>
                </div>
                <?php $expiration_timer = get_time_left($lot['expiration']) ?>
                <div class="lot__timer timer <?php if($expiration_timer[0] < 1) :?> timer--finishing <?php endif ;?>">
                  <?= "$expiration_timer[0] : $expiration_timer[1]" ;?>
                </div>
              </div>
              <?php endif ;?>
              <?php endforeach ;?>
            </div>
        </ul>
      </section>
      <?php if($pages_count >= 2) :?>
        <?php $prev = $current_page - 1; ?>
        <?php $next = $current_page + 1; ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a <?php if($current_page > 1) :?> href="all-lots.php?category=<?= $selected_category;?>&page=<?= $prev ;?>"<?php endif ;?>>Назад</a></li>
        <?php foreach($pages as $page) :?>
        <li class="pagination-item <?php if($current_page == $page) :?>pagination-item-active <?php endif ;?>"><a href="all-lots.php?category=<?= $selected_category;?>&page=<?= $page;?>"><?= $page;?></a></li>
        <?php endforeach ;?>
        <li class="pagination-item pagination-item-next"><a <?php if($current_page < $pages_count) :?> href="all-lots.php?category=<?= $selected_category ;?>&page=<?= $next ;?>"<?php endif ;?>>Вперед</a></li>
      </ul>
      <?php endif ;?>
    </div>
  </main>