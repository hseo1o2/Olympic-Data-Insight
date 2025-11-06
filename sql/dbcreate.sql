-- @author: 김서연 

DROP DATABASE IF EXISTS team02;
CREATE DATABASE team02 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE team02;

-- 1. countries (from countries.csv)
CREATE TABLE countries (
    country_id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    continent_name VARCHAR(50)
);

-- 2. olympics (from olympics.csv)
CREATE TABLE olympics (
    olympic_id INT PRIMARY KEY,
    year INT,
    city VARCHAR(100),
    slug VARCHAR(50)
);

-- 3. sports (from sports.csv)
CREATE TABLE sports (
    sport_id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(100)
);

-- 4. events (from events.csv)
CREATE TABLE events (
    event_id INT PRIMARY KEY,
    sport_id INT,
    name VARCHAR(255), 
    FOREIGN KEY (sport_id) REFERENCES sports(sport_id)
);

-- 5. athletes (from athletes.csv)
CREATE TABLE athletes (
    athlete_id INT PRIMARY KEY,
    name VARCHAR(255),
    country_id INT,
    FOREIGN KEY (country_id) REFERENCES countries(country_id)
);

-- 6. results (from results.csv)
CREATE TABLE results (
    result_id INT PRIMARY KEY,
    athlete_id INT,
    event_id INT,
    olympic_id INT,
    medal ENUM('Gold', 'Silver', 'Bronze', 'None') DEFAULT 'None',
    FOREIGN KEY (athlete_id) REFERENCES athletes(athlete_id),
    FOREIGN KEY (event_id) REFERENCES events(event_id),
    FOREIGN KEY (olympic_id) REFERENCES olympics(olympic_id)
);

-- 7. medal_summary (from medal_summary.csv)
CREATE TABLE medal_summary (
    summary_id INT AUTO_INCREMENT PRIMARY KEY, 
    country_id INT,
    olympic_id INT,
    bronze INT DEFAULT 0,
    gold INT DEFAULT 0,
    silver INT DEFAULT 0,
    total INT DEFAULT 0,
    FOREIGN KEY (country_id) REFERENCES countries(country_id),
    FOREIGN KEY (olympic_id) REFERENCES olympics(olympic_id),
    UNIQUE KEY uk_summary (country_id, olympic_id)
);

-- 8. users (App Table)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    country_focus INT, 
    FOREIGN KEY (country_focus) REFERENCES countries(country_id) 
);