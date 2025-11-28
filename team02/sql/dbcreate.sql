-- @author: 김서연
DROP DATABASE IF EXISTS team02;
CREATE DATABASE team02 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE team02;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------
-- 1. Dimension Tables
-- ---------------------------
DROP TABLE IF EXISTS leagues;
CREATE TABLE leagues (
    league_id INT AUTO_INCREMENT PRIMARY KEY,
    league_name VARCHAR(100) UNIQUE NOT NULL
);

DROP TABLE IF EXISTS seasons;
CREATE TABLE seasons (
    season_id INT AUTO_INCREMENT PRIMARY KEY,
    season_name VARCHAR(20) UNIQUE NOT NULL
);

DROP TABLE IF EXISTS teams;
CREATE TABLE teams (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(100) UNIQUE NOT NULL
);

DROP TABLE IF EXISTS referees;
CREATE TABLE referees (
    referee_id INT AUTO_INCREMENT PRIMARY KEY,
    referee_name VARCHAR(100) UNIQUE NOT NULL
);

DROP TABLE IF EXISTS players;
CREATE TABLE players (
    player_id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(100) NOT NULL,
    team_id INT NULL,
    UNIQUE KEY (player_name),
    FOREIGN KEY (team_id) REFERENCES teams(team_id)
);

-- ---------------------------
-- 2. Fact Tables
-- ---------------------------
DROP TABLE IF EXISTS matches;
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

DROP TABLE IF EXISTS match_events;
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

-- ---------------------------
-- 3. Users Table
-- ---------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'viewer') DEFAULT 'viewer'
);

-- ---------------------------
-- 4. Staging Tables (CSV 로딩용)
-- ---------------------------
DROP TABLE IF EXISTS staging_summary;
CREATE TABLE staging_summary (
    match_date VARCHAR(20),
    day VARCHAR(10),
    time VARCHAR(20),
    home VARCHAR(100),
    away VARCHAR(100),
    goals_home INT,
    goals_away INT,
    venue VARCHAR(255),
    referee VARCHAR(100),
    attendance VARCHAR(50)
);

DROP TABLE IF EXISTS staging_events;
CREATE TABLE staging_events (
    match_date VARCHAR(20),
    home VARCHAR(100),
    away VARCHAR(100),
    minute INT,
    player VARCHAR(100),
    assist VARCHAR(100),
    event_type VARCHAR(50),
    team VARCHAR(100),
    referee VARCHAR(100),
    venue VARCHAR(255),
    attendance VARCHAR(50)
);

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------
-- 5. Indexes 
-- ---------------------------
CREATE INDEX idx_players_team_id ON players(team_id);
CREATE INDEX idx_events_match_id ON match_events(match_id);

COMMIT;
