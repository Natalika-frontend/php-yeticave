CREATE DATABASE my_yeticave;
USE my_yeticave;

CREATE TABLE user
(
    id                INT AUTO_INCREMENT PRIMARY KEY,
    date_registration DATE,
    email             VARCHAR(128) NOT NULL UNIQUE,
    user_name         VARCHAR(128),
    user_password     CHAR(15),
    user_contacts     TEXT
);

CREATE TABLE categories
(
    id            INT AUTO_INCREMENT PRIMARY KEY,
    symbol_code   VARCHAR(128),
    name_category VARCHAR(128)
);

CREATE TABLE lots
(
    id              INT AUTO_INCREMENT PRIMARY KEY,
    date_create     DATETIME DEFAULT CURRENT_TIMESTAMP,
    lot_name        VARCHAR(128),
    lot_description VARCHAR(128),
    lot_image       VARCHAR(128),
    start_price     INT,
    date_finish     DATE,
    bet_step        INT,
    user_id         INT,
    winner_id       INT,
    category_id     INT,
    FOREIGN KEY (user_id) REFERENCES user (id),
    FOREIGN KEY (winner_id) REFERENCES user (id),
    FOREIGN KEY (category_id) REFERENCES categories (id)
);

CREATE TABLE bets
(
    id       INT AUTO_INCREMENT PRIMARY KEY,
    bet_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    sum_bet  INT,
    user_id  INT,
    lot_id   INT,
    FOREIGN KEY (user_id) REFERENCES user (id),
    FOREIGN KEY (lot_id) REFERENCES lots (id)
)