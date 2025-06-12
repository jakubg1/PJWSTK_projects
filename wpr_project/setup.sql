-- This is a file you need to run on your database ONCE in order for the server to not throw up any errors and make everything work.
-- WARNING!!! DO NOT RUN THIS SCRIPT ON A RUNNING INSTANCE!!! YOU WILL DESTROY ALL DATA!!!

DROP DATABASE gameserver;
CREATE DATABASE gameserver;
USE gameserver;

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(32) NOT NULL,
    type ENUM('user', 'admin', 'guest') NOT NULL,
    password VARCHAR(256),
    created_at TIMESTAMP,
    last_active_at TIMESTAMP
)