-- @author: 김서연
DROP DATABASE IF EXISTS team02;
CREATE DATABASE team02 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE team02;

-- 1. countries
CREATE TABLE countries (
    country_code CHAR(3) PRIMARY KEY,
    country_name VARCHAR(100) NOT NULL
);

-- 2. olympics
CREATE TABLE olympics (
    game_slug VARCHAR(50) PRIMARY KEY,
    game_name VARCHAR(100),
    game_location VARCHAR(100),
    game_season ENUM('Summer', 'Winter', 'Youth'),
    game_year INT,
    INDEX idx_year_season (game_year, game_season)
);

-- 3. disciplines
CREATE TABLE disciplines (
    discipline_title VARCHAR(100) PRIMARY KEY
);

-- 4. events
CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    discipline_title VARCHAR(100),
    event_title VARCHAR(255),
    event_gender VARCHAR(20),
    FOREIGN KEY (discipline_title) REFERENCES disciplines(discipline_title),
    UNIQUE KEY uk_event (discipline_title, event_title, event_gender)
);

-- 5. participants
CREATE TABLE participants (
    participant_id INT AUTO_INCREMENT PRIMARY KEY,
    participant_type ENUM('Athlete', 'GameTeam'),
    participant_title VARCHAR(255) NOT NULL,
    country_code CHAR(3),
    FOREIGN KEY (country_code) REFERENCES countries(country_code),
    UNIQUE KEY uk_participant (participant_type, participant_title, country_code)
);

-- 6. medals
CREATE TABLE medals (
    medal_id INT AUTO_INCREMENT PRIMARY KEY,
    game_slug VARCHAR(50),
    event_id INT,
    participant_id INT,
    medal_type ENUM('Gold', 'Silver', 'Bronze') NOT NULL,
    FOREIGN KEY (game_slug) REFERENCES olympics(game_slug),
    FOREIGN KEY (event_id) REFERENCES events(event_id),
    FOREIGN KEY (participant_id) REFERENCES participants(participant_id)
);

-- 7. results
CREATE TABLE results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    game_slug VARCHAR(50),
    discipline_title VARCHAR(100),
    event_title VARCHAR(255),
    participant_type ENUM('Athlete', 'GameTeam'),
    country_code CHAR(3),
    medal_type ENUM('Gold', 'Silver', 'Bronze', 'None') DEFAULT 'None',
    rank_position VARCHAR(10),
    value_type VARCHAR(50) NULL,
    value_unit VARCHAR(50) NULL,
    FOREIGN KEY (game_slug) REFERENCES olympics(game_slug),
    FOREIGN KEY (discipline_title) REFERENCES disciplines(discipline_title),
    FOREIGN KEY (country_code) REFERENCES countries(country_code),
    INDEX idx_game_country (game_slug, country_code)
);

-- 8. medal_summary
CREATE TABLE medal_summary (
    summary_id INT AUTO_INCREMENT PRIMARY KEY,
    country_code CHAR(3),
    game_slug VARCHAR(50),
    gold INT DEFAULT 0,
    silver INT DEFAULT 0,
    bronze INT DEFAULT 0,
    total INT DEFAULT 0,
    FOREIGN KEY (country_code) REFERENCES countries(country_code),
    FOREIGN KEY (game_slug) REFERENCES olympics(game_slug),
    UNIQUE KEY uk_summary (country_code, game_slug)
);

-- 9. users
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    country_focus CHAR(3),
    FOREIGN KEY (country_focus) REFERENCES countries(country_code)
);
