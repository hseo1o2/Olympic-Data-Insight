-- @author: 김서연

DROP DATABASE IF EXISTS team02;
CREATE DATABASE team02 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE team02;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Dimension 테이블
CREATE TABLE leagues (
    league_id INT AUTO_INCREMENT PRIMARY KEY,
    league_name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE seasons (
    season_id INT AUTO_INCREMENT PRIMARY KEY,
    season_name VARCHAR(20) UNIQUE NOT NULL
);

CREATE TABLE teams (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE referees (
    referee_id INT AUTO_INCREMENT PRIMARY KEY,
    referee_name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE players (
    player_id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(100) UNIQUE NOT NULL
);

-- 2. Fact 테이블
CREATE TABLE matches (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    league_id INT,
    season_id INT,
    match_date DATE,
    home_team_id INT,
    away_team_id INT,
    home_goals INT,
    away_goals INT,
    referee_id INT,
    venue VARCHAR(255),
    attendance VARCHAR(50),
    FOREIGN KEY (league_id) REFERENCES leagues(league_id),
    FOREIGN KEY (season_id) REFERENCES seasons(season_id),
    FOREIGN KEY (home_team_id) REFERENCES teams(team_id),
    FOREIGN KEY (away_team_id) REFERENCES teams(team_id),
    FOREIGN KEY (referee_id) REFERENCES referees(referee_id)
);

CREATE TABLE match_events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT,
    team_id INT,
    player_id INT,
    assist_player_id INT,
    minute INT,
    event_type VARCHAR(50),
    FOREIGN KEY (match_id) REFERENCES matches(match_id),
    FOREIGN KEY (team_id) REFERENCES teams(team_id),
    FOREIGN KEY (player_id) REFERENCES players(player_id),
    FOREIGN KEY (assist_player_id) REFERENCES players(player_id)
);

-- 3. App 테이블 
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role VARCHAR(20) NOT NULL DEFAULT 'user' 
);

SET FOREIGN_KEY_CHECKS = 1;