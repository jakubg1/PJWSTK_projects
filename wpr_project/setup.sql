-- This is a file you need to run on your database ONCE in order for the server to not throw up any errors and make everything work.
-- WARNING!!! DO NOT RUN THIS SCRIPT ON A RUNNING INSTANCE!!! YOU WILL DESTROY ALL DATA!!!

DROP DATABASE gameserver;
CREATE DATABASE gameserver;
USE gameserver;

-- Użytkownicy (wliczając gości)
-- goście: name generowane, password=null (logowanie tylko po sesji, konta gościa kasowane 24 godziny po ostatniej aktywności)
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(32) UNIQUE NOT NULL,
    type ENUM('user', 'admin', 'guest') NOT NULL,
    password VARCHAR(256),
    created_at TIMESTAMP NOT NULL,
    last_active_at TIMESTAMP
);

-- Lista banów nałożonych na użytkowników
CREATE TABLE bans (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    given_at TIMESTAMP NOT NULL,
    expires_at TIMESTAMP,
    reason VARCHAR(256)
);

-- Lista pokoi
-- Pokoje są usuwane gdy ostatni gracz wyjdzie
-- Przy tworzeniu pokoju od razu jest tworzona gra i wrzucana w game_id
CREATE TABLE rooms (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(32) NOT NULL,
    game_id INTEGER NOT NULL,
    password VARCHAR(256)
);

-- Asocjowanie pokojów z graczami
-- Wpisy usuwane razem z pokojami
CREATE TABLE room_players (
    room_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    is_owner BOOLEAN NOT NULL
);

-- Lista rozegranych gier
-- Gry nie są kasowane po zakończeniu, ale jeżeli pokój zostanie rozwiązany przed rozpoczęciem gry, wpis zostanie skasowany
-- game: "checkers" - warcaby, "uno" - uno
-- started_at: null jeżeli gra nie została jeszcze rozpoczęta
-- finished_at: null jeżeli gra nie została jeszcze zakończona
CREATE TABLE games (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    game_type VARCHAR(16) NOT NULL,
    started_at TIMESTAMP,
    finished_at TIMESTAMP
);

-- Asocjowanie gier z graczami
-- Gracze są dodawani do gry w momencie jej rozpoczęcia. Przydzielanie indeksów.
-- Listy graczy w grze nie można zmienić! Ktoś wyjdzie - gra przerwana.
-- id: indeks gracza, zawsze od 1 do n, używane do wskazywania graczy w grach (np. kogo ruch)
-- status: null jeżeli gra trwa/została przerwana, 1 - wygrana, -1 - przegrana, 0 - remis
CREATE TABLE game_players (
    game_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    id INTEGER NOT NULL,
    status INTEGER
);

-- Zmienne przechowujące stan gier (tylko gry które obecnie trwają)
CREATE TABLE game_states (
    game_id INTEGER NOT NULL,
    state_id VARCHAR(24) NOT NULL,
    value VARBINARY(256)
);

-- Lista wiadomości wysłanych w grach
-- user: null jeżeli wiadomość systemowa
CREATE TABLE messages (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    game_id INTEGER NOT NULL,
    user_id INTEGER,
    message VARCHAR(256),
    sent_at TIMESTAMP NOT NULL
);




-- Test games

INSERT INTO `rooms`(`id`, `name`, `game_id`, `password`) VALUES ('10','Test Jeden!','1',null);
INSERT INTO `rooms`(`id`, `name`, `game_id`, `password`) VALUES ('11','Test Dwa!','2',null);
INSERT INTO `rooms`(`id`, `name`, `game_id`, `password`) VALUES ('12','Test Trzy!','3',null);
INSERT INTO `games`(`id`, `game_type`, `started_at`, `finished_at`) VALUES ('1','uno',null,null);
INSERT INTO `games`(`id`, `game_type`, `started_at`, `finished_at`) VALUES ('2','checkers',null,null);
INSERT INTO `games`(`id`, `game_type`, `started_at`, `finished_at`) VALUES ('3','uno',null,null);