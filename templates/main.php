<main class="container">
<section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach ($categories as $key => $categorie) :?>
            <li class="promo__item promo__item--<?=$categorie['cat_id']; ?>">
                <a class="promo__link" href="all-lots.php?category=<?= $categorie['cat_id'];?>"><?=$categorie['name']; ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->
            <?php foreach($lots as $lot) :?>
                <?php if(strtotime("now") > strtotime($lot['expiration'])) :?>
                <?php else :?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="../uploads/<?= strip_tags($lot['image']); ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=$lot['category']; ?></span>
                    <h3 class="lot__title"><a class="text-link" href=<?= ("lot.php?id=" . $lot['id']);?>><?= strip_tags($lot['name']); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=price_format(strip_tags($lot['price'])); ?></span>
                        </div>
                        <?php $expiration_timer = get_time_left($lot['expiration']) ?>
            
            <div class="lot__timer timer <?php if($expiration_timer[0] < 1) :?> timer--finishing <?php endif ;?>"><?= "$expiration_timer[0] : $expiration_timer[1]" ;?></div> <!--  timer--end  timer--finishing -->
            
                    </div>
                </div>
            </li>
            <?php endif ;?>
            <?php endforeach ;?>
        </ul>
    </section>
    </main>