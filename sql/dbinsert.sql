-- @author: 김서연
USE team02;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- 1. staging tables
DROP TABLE IF EXISTS staging_hosts;
CREATE TABLE staging_hosts (
    slug_game VARCHAR(255), game_end_date VARCHAR(255), game_start_date VARCHAR(255),
    game_location VARCHAR(255), game_name VARCHAR(255), game_season VARCHAR(255), game_year VARCHAR(255)
);

DROP TABLE IF EXISTS staging_medals;
CREATE TABLE staging_medals (
    discipline_title VARCHAR(255), slug_game VARCHAR(255), event_title VARCHAR(255),
    event_gender VARCHAR(255), medal_type VARCHAR(255), participant_type VARCHAR(255),
    participant_title VARCHAR(255), country_name VARCHAR(255), country_code VARCHAR(255)
);

DROP TABLE IF EXISTS staging_results;
CREATE TABLE staging_results (
    discipline_title VARCHAR(255), event_title VARCHAR(255), slug_game VARCHAR(255),
    participant_type VARCHAR(255), medal_type VARCHAR(255), rank_equal VARCHAR(255),
    rank_position VARCHAR(255), country_name VARCHAR(255), country_code VARCHAR(255),
    value_unit VARCHAR(255), value_type VARCHAR(255)
);

-- 2. load data
LOAD DATA INFILE 'C:/xampp/team02/data/olympic_hosts.csv'
INTO TABLE staging_hosts
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE 'C:/xampp/team02/data/olympic_medals.csv'
INTO TABLE staging_medals
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE 'C:/xampp/team02/data/olympic_results.csv'
INTO TABLE staging_results
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

-- 3. transform & insert
INSERT INTO olympics (game_slug, game_name, game_location, game_season, game_year)
SELECT DISTINCT slug_game AS game_slug, game_name, game_location, game_season, CAST(game_year AS INT)
FROM staging_hosts
WHERE slug_game IS NOT NULL AND slug_game != '';

INSERT INTO countries (country_code, country_name)
SELECT DISTINCT country_code, country_name
FROM (
    SELECT country_code, country_name FROM staging_medals
    UNION
    SELECT country_code, country_name FROM staging_results
) AS all_countries
WHERE country_code IS NOT NULL AND country_name IS NOT NULL
ON DUPLICATE KEY UPDATE country_name = VALUES(country_name);

INSERT INTO disciplines (discipline_title)
SELECT DISTINCT discipline_title FROM (
    SELECT discipline_title FROM staging_medals
    UNION
    SELECT discipline_title FROM staging_results
) AS all_disciplines
WHERE discipline_title IS NOT NULL AND discipline_title != '';

INSERT INTO events (discipline_title, event_title, event_gender)
SELECT DISTINCT discipline_title, event_title, event_gender
FROM staging_medals
WHERE discipline_title IS NOT NULL AND event_title IS NOT NULL AND event_gender IS NOT NULL
ON DUPLICATE KEY UPDATE event_title = VALUES(event_title);

INSERT INTO participants (participant_type, participant_title, country_code)
SELECT DISTINCT participant_type, participant_title, country_code
FROM staging_medals
WHERE participant_type IS NOT NULL AND participant_title IS NOT NULL AND country_code IS NOT NULL;

INSERT INTO medals (game_slug, event_id, participant_id, medal_type)
SELECT sm.slug_game, e.event_id, p.participant_id, sm.medal_type
FROM staging_medals AS sm
LEFT JOIN events AS e
  ON sm.discipline_title = e.discipline_title AND sm.event_title = e.event_title AND sm.event_gender = e.event_gender
LEFT JOIN participants AS p
  ON sm.participant_type = p.participant_type AND sm.participant_title = p.participant_title AND sm.country_code = p.country_code
WHERE sm.medal_type IN ('Gold','Silver','Bronze') AND e.event_id IS NOT NULL AND p.participant_id IS NOT NULL;

INSERT INTO results (game_slug, discipline_title, event_title, participant_type, country_code, medal_type, rank_position, value_type, value_unit)
SELECT slug_game, discipline_title, event_title, participant_type, country_code,
       CASE WHEN medal_type IN ('Gold','Silver','Bronze') THEN medal_type ELSE 'None' END,
       rank_position, value_type, value_unit
FROM staging_results
WHERE slug_game IN (SELECT game_slug FROM olympics)
  AND discipline_title IN (SELECT discipline_title FROM disciplines)
  AND country_code IN (SELECT country_code FROM countries);

INSERT INTO medal_summary (country_code, game_slug, gold, silver, bronze, total)
SELECT country_code, game_slug,
    SUM(CASE WHEN medal_type='Gold' THEN 1 ELSE 0 END),
    SUM(CASE WHEN medal_type='Silver' THEN 1 ELSE 0 END),
    SUM(CASE WHEN medal_type='Bronze' THEN 1 ELSE 0 END),
    SUM(CASE WHEN medal_type IN ('Gold','Silver','Bronze') THEN 1 ELSE 0 END)
FROM results
WHERE country_code IS NOT NULL AND game_slug IS NOT NULL
GROUP BY country_code, game_slug;

-- 4. test users
INSERT INTO users (username, password, country_focus) VALUES
('team02', 'team02', 'KOR'),
('admin', 'admin', 'USA');

-- 5. cleanup
DROP TABLE IF EXISTS staging_hosts;
DROP TABLE IF EXISTS staging_medals;
DROP TABLE IF EXISTS staging_results;

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
