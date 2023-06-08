USE my_yeticave;

INSERT INTO categories (symbol_code, name_category)
VALUES ('boards', 'Доски и лыжи'),
       ('attachment', 'Крепления'),
       ('boots', 'Ботинки'),
       ('clothing', 'Одежда'),
       ('tools', 'Инструменты'),
       ('other', 'Разное');

INSERT INTO user (user_name, email, user_password, user_contacts)
VALUES ('Алексей Непойранов', 'Nepoiranov@mail.ru', 'password1', 123123123),
       ('Ярослав Непойранов', 'Yarik@gmail.ru', 'password12', 12312312345);

INSERT INTO lots (lot_name, lot_image, start_price, date_finish, user_id, category_id)
VALUES ('2014 Rossignol District Snowboard', 'img/lot-1.jpg', 10999, '2023-06-15', 1, 1),
       ('DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', 159999, '2023-06-16', 1, 1),
       ('Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 8000, '2023-06-17', 2, 2),
       ('Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 10999, '2023-06-18', 2, 3),
       ('Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 7500, '2023-06-19', 1, 4),
       ('Маска Oakley Canopy', 'img/lot-6.jpg', 5400, '2023-06-20', 2, 6);

INSERT INTO bets (sum_bet, user_id, lot_id)
VALUES (5000, 1, 2),
       (2000, 2, 5);

# получаем все категории
SELECT *
FROM categories;

# получить самые новые, открытые лоты, каждый лот включает название, стартовую цену, ссылку на изображение, цену, название категории
SELECT lots.lot_name, lots.start_price, lots.lot_image, categories.name_category
FROM lots
         JOIN categories ON categories.id = lots.category_id;

# показываем лот по его ID, получаем название категории, к которой принадлежит лот
SELECT lots.*, categories.name_category
FROM lots
         JOIN categories ON categories.id = lots.category_id
WHERE lots.id = 3;

# обновляем название лота по его идентификатору
UPDATE lots
SET lot_name = 'Маска прикольная'
WHERE id = 6;

# получаем список ставок для лота по его идентификатору с сортировкой по дате
SELECT bets.bet_date, bets.sum_bet, lots.lot_name, user.user_name
FROM bets
         JOIN lots ON lots.id = bets.lot_id
         JOIN user ON user.id = bets.user_id
WHERE lots.id = 2
ORDER BY bets.bet_date DESC;
