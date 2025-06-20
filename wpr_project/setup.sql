-- This is a file you need to run on your database ONCE in order for the server to not throw up any errors and make everything work.
-- WARNING!!! DO NOT RUN THIS SCRIPT ON A RUNNING INSTANCE!!! YOU WILL DESTROY ALL DATA!!!

DROP DATABASE IF EXISTS gameserver;
CREATE DATABASE gameserver;
USE gameserver;

-- Użytkownicy (wyłączając gości!)
-- Dane gości są przechowywane lokalnie w przeglądarce jako ciasteczko.
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(32) UNIQUE NOT NULL,
    type ENUM('user', 'admin', 'guest') NOT NULL,
    email VARCHAR(64) UNIQUE NOT NULL,
    password VARCHAR(256),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    last_active_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP()
);

-- Lista banów nałożonych na użytkowników
CREATE TABLE bans (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    given_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    expires_at TIMESTAMP NULL DEFAULT NULL,
    reason VARCHAR(256)
);

-- Lista pokoi
-- Pokoje są usuwane gdy ostatni gracz wyjdzie
-- Przy tworzeniu pokoju od razu jest tworzona gra i wrzucana w game_id
CREATE TABLE rooms (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(32) NOT NULL,
    owner INTEGER NOT NULL,
    game_id INTEGER NOT NULL,
    password VARCHAR(256)
);

-- Asocjowanie pokojów z graczami
-- Wpisy usuwane razem z pokojami
-- last_heartbeat_at: aktualizowane gdy klient wyśle heartbeat, jeżeli nie będzie heartbeatu przez określony czas, gracz jest usuwany z timeoutem
CREATE TABLE room_players (
    room_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    last_heartbeat_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP()
);

-- Lista rozegranych gier
-- Gry nie są kasowane po zakończeniu, ale jeżeli pokój zostanie rozwiązany przed rozpoczęciem gry, wpis zostanie skasowany
-- game: "checkers" - warcaby, "uno" - uno
-- started_at: null jeżeli gra nie została jeszcze rozpoczęta
-- finished_at: null jeżeli gra nie została jeszcze zakończona
CREATE TABLE games (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    game_type VARCHAR(16) NOT NULL,
    started_at TIMESTAMP NULL DEFAULT NULL,
    finished_at TIMESTAMP NULL DEFAULT NULL
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

-- Lista eventów czekających na wysłanie do graczy
-- Gracz odbiera eventy przez endpoint `endpoints/room/get_events.php`.
-- Odebranie eventów powoduje ich skasowanie z bazy.
-- Jeżeli gracz opuści grę (pokój), kolejka dla niego jest czyszczona.
-- `payload` jest serializowane wbudowaną funkcją PHP, gdyż wartości mogą bardzo mocno się różnić!
-- Typy eventów:
-- - "message" - Nowa wiadomość wysłana na czacie
--   - id: ID wiadomości do odczytania i przekazania graczowi
CREATE TABLE queued_events (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    type VARCHAR(16) NOT NULL,
    payload VARCHAR(256)
);

-- Lista wiadomości wysłanych w grach
-- user: null jeżeli wiadomość systemowa
CREATE TABLE messages (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    game_id INTEGER NOT NULL,
    user_id INTEGER,
    message VARCHAR(256),
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP()
);