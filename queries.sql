INSERT INTO Categories (Categories.character_code, name)
VALUES
    ('boards', 'Доски и лыжи'),
    ('attachment', 'Крепления'),
    ('boots', 'Ботинки'),
    ('clothing', 'Одежда'),
    ('tools', 'Инструменты'),
    ('other', 'Разное');

INSERT INTO Users
    (email, user_name, user_password, contacts)
VALUES
    ('nepoiranov@mail.ru', 'Алексей', 'pass1', '455627851215'),
    ('nepoiranov2@mail.ru', 'Владислав', 'pass2', '455627851455');

INSERT INTO Lots
    (title, lot_description, image, starting_price, date_end, rate_step, user_id, category_id)
VALUES
    ('2014 Rossignol District Snowboard', 'Обалденный сноуборд', 'img/lot-1.jpg', '10999', '2023-07-27', '1000', 1, 1),
    ('DC Ply Mens 2016/2017 Snowboard', 'Обалденнейший сноуборд', 'img/lot-2.jpg', '159999', '2023-07-17', '10000', 1, 1),
    ('Крепления Union Contact Pro 2015 года размер L/XL', 'Надежные крепления', 'img/lot-3.jpg', '8000', '2023-06-28', '500', 2, 2),
    ('Ботинки для сноуборда DC Mutiny Charocal', 'Непромокаемые ботинки', 'img/lot-4.jpg', '10999', '2023-06-29', '600', 2, 3),
    ('Куртка для сноуборда DC Mutiny Charocal', 'Теплая куртка', 'img/lot-5.jpg', '7500', '2023-06-30', '900',1, 4),
    ('Маска Oakley Canopy', 'Прозрачная маска', 'img/lot-6.jpg', '5400', '2023-06-11', '200',1, 6);

INSERT INTO Bets
    (price_bet, user_id, lot_id)
VALUES (11000, 1, 1);
INSERT INTO Bets
    (price_bet, user_id, lot_id)
VALUES (1000, 1, 5);

-- получаем все категории
SELECT name AS 'Категория' FROM Categories;

-- получаем самые новые, открытые лоты, в каждом включено название, стартовую цену, ссылку на изображение, цену, название категории
SELECT Lots.title, Lots.starting_price, Lots.image, Categories.name
FROM Lots JOIN Categories ON Lots.category_id=categories.id;

-- показываем лот по его ID, получаем название категории, к которой принадлежит лот
SELECT Lots.id, Lots.date_creation, Lots.title, Lots.lot_description, Lots.starting_price, Lots.image, Lots.date_end, Lots.rate_step, Categories.name
FROM Lots JOIN Categories ON Lots.category_id=categories.id
WHERE Lots.id=2;

-- обновляем название лота по его идентификатору
UPDATE Lots SET title='Крепления самые лучшие' WHERE id=3;

-- получаем список ставок для лота по его идентификатору с сортировкой по дате
SELECT Bets.date_bet, Bets.price_bet, Lots.title, Users.user_name
FROM Bets
JOIN Lots ON Bets.lot_id=lots.id
JOIN Users ON Bets.user_id=users.id
WHERE Lots.id=5
ORDER BY Bets.date_bet Desc;