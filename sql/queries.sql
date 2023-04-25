/* Вносим существующий список категорий */
INSERT INTO category (cat_id , name) VALUES
('boards' , 'Доски и лыжи'),
('attachment' , 'Крепления'),
('boots' , 'Ботинки'),
('clothing' , 'Одежда'),
('tools' , 'Инструменты'),
('other' , 'Разное');

/* Вносим несколько пользователей */
INSERT INTO user (email , password , name , contact) VALUES
('user1@mail.com' , '123' , 'Alex' , 'Contact'),
('user2@mail.com' , '123' , 'Tom' , 'Contact');

/* Вносим существующий список объявлений */
INSERT INTO lot (category_id, author_id, name, description, image, price, expiration, rate_step) VALUES
(1, 1, '2014 Rossignol District Snowboard', 'Description', "lot-1.jpg", 10999, '2023-04-19', 500),
(1, 2, 'DC Ply Mens 2016/2017 Snowboard', 'Description', 'lot-2.jpg', 15999, '2023-04-20', 1000),
(2, 1, 'Крепления Union Contact Pro 2015 года размер L/XL', 'Description', 'lot-3.jpg', 8000, '2023-04-19', 800),
(3, 2, 'Ботинки для сноуборда DC Mutiny Charocal', 'Description', 'lot-4.jpg', 10999, '2023-04-17', 1500),
(4, 1, 'Куртка для сноуборда DC Mutiny Charocal', 'Description', 'lot-5.jpg', 7500, '2023-04-18', 400),
(6, 2, 'Маска Oakley Canopy', 'Description', 'lot-6.jpg', 5400, '2023-04-17', 200);

/* Добавляем ставки */
INSERT INTO bet (user_id, lot_id, price) VALUES
(1, 2, 17000),
(2, 1, 12000);

/* Запросы к БД */
/* -получить все категории; */
SELECT * FROM category;

/* -получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории; */
SELECT l.name, c.name AS category, price, image, expiration FROM lot l JOIN category c ON category_id = c.id LIMIT 6;

/* -показать лот по его ID. Получите также название категории, к которой принадлежит лот; */
SELECT lot.id, category.name, author_id, winner_id, start_date, lot.name, DESCRIPTION, image, price, expiration, rate_step FROM lot JOIN category WHERE lot.id=1 LIMIT 1;

/* -обновить название лота по его идентификатору; */
UPDATE lot SET name='Новое имя Лота для проверки' WHERE id=1;

/* -получить список ставок для лота по его идентификатору с сортировкой по дате. */
SELECT * FROM bet WHERE lot_id=1 ORDER BY date_published DESC;