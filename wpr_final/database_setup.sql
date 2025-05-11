-- mysql -u root

CREATE DATABASE mojaBaza;
USE mojaBaza;
CREATE TABLE samochody (id INTEGER PRIMARY KEY AUTO_INCREMENT, marka VARCHAR(32), model VARCHAR(32), cena INTEGER, rok INTEGER, opis VARCHAR(1024));