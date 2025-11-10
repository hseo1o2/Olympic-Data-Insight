-- @author: 김서연

USE team02;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
ALTER DATABASE team02 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- 1. 임시 스테이징 테이블 생성
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

-- 2. 새 CSV 2개 로드 
LOAD DATA INFILE 'C:\\xampp\\team02\\data\\summary.csv'
INTO TABLE staging_summary
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA INFILE 'C:\\xampp\\team02\\data\\events.csv'
INTO TABLE staging_events
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;


-- 3. Dimension 테이블 삽입 
INSERT IGNORE INTO leagues (league_name) VALUES ('Premier League');
INSERT IGNORE INTO seasons (season_name) VALUES ('24-25');

INSERT IGNORE INTO teams (team_name)
SELECT DISTINCT home FROM staging_summary WHERE home IS NOT NULL AND home != ''
UNION
SELECT DISTINCT away FROM staging_summary WHERE away IS NOT NULL AND away != '';

INSERT IGNORE INTO referees (referee_name)
SELECT DISTINCT referee FROM staging_summary WHERE referee IS NOT NULL AND referee != '';

INSERT IGNORE INTO players (player_name)
SELECT DISTINCT player FROM staging_events WHERE player IS NOT NULL AND player != ''
UNION
SELECT DISTINCT assist FROM staging_events WHERE assist IS NOT NULL AND assist != '';


-- 4. Fact 테이블 삽입
INSERT INTO matches (league_id, season_id, match_date, home_team_id, away_team_id, home_goals, away_goals, referee_id, venue, attendance)
SELECT 
    (SELECT league_id FROM leagues WHERE league_name = 'Premier League'),
    (SELECT season_id FROM seasons WHERE season_name = '24-25'),
    STR_TO_DATE(s.match_date, '%Y-%m-%d'),
    ht.team_id,
    at.team_id,
    s.goals_home,
    s.goals_away,
    r.referee_id,
    s.venue,
    s.attendance
FROM staging_summary s 
LEFT JOIN teams ht ON s.home = ht.team_name
LEFT JOIN teams at ON s.away = at.team_name
LEFT JOIN referees r ON s.referee = r.referee_name;


-- 5. match_events 테이블 삽입 
INSERT INTO match_events (match_id, team_id, player_id, assist_player_id, minute, event_type)
SELECT
    m.match_id,
    t.team_id,
    p.player_id,
    ap.player_id,
    s.minute,
    s.event_type
FROM staging_events s

JOIN teams ht ON s.home = ht.team_name
JOIN teams at ON s.away = at.team_name
JOIN matches m ON m.match_date = STR_TO_DATE(s.match_date, '%Y-%m-%d')
                AND m.home_team_id = ht.team_id
                AND m.away_team_id = at.team_id
LEFT JOIN teams t ON s.team = t.team_name
LEFT JOIN players p ON s.player = p.player_name
LEFT JOIN players ap ON s.assist = ap.player_name;


-- 6. 임시 테이블 삭제
DROP TABLE IF EXISTS staging_summary;
DROP TABLE IF EXISTS staging_events;

-- 7. 테스트 사용자 삽입 (admin 권한)
INSERT INTO users (username, password, role) VALUES
('team02', 'team02', 'admin');

-- 마무리
COMMIT;
SET FOREIGN_KEY_CHECKS = 1;