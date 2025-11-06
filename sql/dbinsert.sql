-- @author: 김서연

USE team02;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- 1. Dimension 테이블 로드
LOAD DATA INFILE 'C:/xampp/team02/data/countries.csv'
INTO TABLE countries
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS
(country_id, continent_name, name); 

LOAD DATA INFILE 'C:/xampp/team02/data/olympics.csv'
INTO TABLE olympics
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS;

LOAD DATA INFILE 'C:/xampp/team02/data/sports.csv'
INTO TABLE sports
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS; 


-- 2. Dependent 테이블 로드
LOAD DATA INFILE 'C:/xampp/team02/data/events.csv'
INTO TABLE events
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS; 

LOAD DATA INFILE 'C:/xampp/team02/data/athletes.csv'
INTO TABLE athletes
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS; 


-- 3. Fact 테이블 로드
LOAD DATA INFILE 'C:/xampp/team02/data/results.csv'
INTO TABLE results
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS; 


-- 4. Pre-Aggregated 테이블 로드 (요약 데이터)
LOAD DATA INFILE 'C:/xampp/team02/data/medal_summary.csv'
INTO TABLE medal_summary
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\r\n'
IGNORE 1 ROWS
(country_id, olympic_id, bronze, gold, silver, total);


-- 5. 테스트 사용자
INSERT INTO users (username, password, country_focus) VALUES
('team02', 'team02', 11);


-- 마무리
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;