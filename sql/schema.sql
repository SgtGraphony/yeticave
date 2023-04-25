/* Дропаем текущее соединение с БД */
DROP DATABASE IF EXISTS yeticave;

/* Создаем Базу Данных и задаем кодировку */
CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

/* Делаем БД Активной */
USE yeticave;

/* Создаем таблицы: пользователи, категории, лоты, ставки */
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY ,
    email VARCHAR(30) NOT NULL UNIQUE ,
    password VARCHAR(64) NOT NULL ,
    name CHAR(30) NOT NULL ,
    contact TEXT(30) NOT NULL ,
    registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY ,
    cat_id CHAR(30) NOT NULL , 
    name CHAR(30) NOT NULL UNIQUE
);

CREATE TABLE lot (
    id INT AUTO_INCREMENT PRIMARY KEY ,
    category_id INT ,
    author_id INT ,
    winner_id INT DEFAULT NULL ,
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    name VARCHAR(128) NOT NULL ,
    description TEXT NOT NULL ,
    image VARCHAR(128) NOT NULL , 
    price INT NOT NULL ,
    expiration TIMESTAMP ,
    rate_step INT ,
    FOREIGN KEY (category_id) REFERENCES category(id),
    FOREIGN KEY (author_id)  REFERENCES user(id),
    FOREIGN KEY (winner_id) REFERENCES user(id)


);

CREATE TABLE bet (
    id INT AUTO_INCREMENT PRIMARY KEY ,
    date_published TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    user_id INT,
    lot_id INT,
    price INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (lot_id) REFERENCES lot(id)
);

CREATE FULLTEXT INDEX lot_ft_search ON lot(name, description);