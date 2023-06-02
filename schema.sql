CREATE DATABASE my_yeticave;
USE my_yeticave;

CREATE TABLE Categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  character_code VARCHAR(255),
  name VARCHAR(255)
);

CREATE TABLE Users (
                     id INT AUTO_INCREMENT PRIMARY KEY,
                     date_registration DATETIME DEFAULT CURRENT_TIMESTAMP,
                     email VARCHAR(128) NOT NULL UNIQUE,
                     user_name VARCHAR(128),
                     user_password VARCHAR(12),
                     contacts TEXT
);

CREATE TABLE Lots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    title VARCHAR(255),
    lot_description TEXT,
    image VARCHAR(255),
    date_end DATE,
    starting_price INT,
    rate_step INT,
    user_id INT,
    winner_id INT,
    category_id INT,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (winner_id) REFERENCES Users(id),
    FOREIGN KEY (category_id) REFERENCES Categories(id)
);

CREATE TABLE Bets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_bet DATETIME DEFAULT CURRENT_TIMESTAMP,
    price_bet INT,
    user_id INT,
    lot_id INT,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (lot_id) REFERENCES Lots(id)
)