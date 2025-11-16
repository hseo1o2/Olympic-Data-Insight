-- @author: 김서연

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------
-- 1. 기본 Dimension 값 삽입
-- ---------------------------
INSERT IGNORE INTO leagues (league_name) VALUES ('Premier League');
INSERT IGNORE INTO seasons (season_name) VALUES ('24-25');

-- ---------------------------
-- 2. CSV 파일 로딩
-- ---------------------------
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

-- ---------------------------
-- 3. Dimension 테이블 채우기
-- ---------------------------
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

-- ---------------------------
-- 4. matches 삽입
-- ---------------------------
INSERT INTO matches (
    league_id, season_id, match_date,
    home_team_id, away_team_id,
    home_goals, away_goals,
    referee_id, venue, attendance
)
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

-- ---------------------------
-- 5. match_events 삽입
-- ---------------------------
INSERT INTO match_events (
    match_id, team_id, player_id, assist_player_id, minute, event_type
)
SELECT
    m.match_id,
    t.team_id,
    p.player_id,
    ap.player_id,
    e.minute,
    e.event_type
FROM staging_events e
JOIN teams ht ON e.home = ht.team_name
JOIN teams at ON e.away = at.team_name
JOIN matches m ON m.match_date = STR_TO_DATE(e.match_date, '%Y-%m-%d')
               AND m.home_team_id = ht.team_id
               AND m.away_team_id = at.team_id
LEFT JOIN teams t ON e.team = t.team_name
LEFT JOIN players p ON e.player = p.player_name
LEFT JOIN players ap ON e.assist = ap.player_name;

-- ---------------------------
-- 6. players.team_id 자동 업데이트
-- ---------------------------
UPDATE players p
JOIN match_events me ON p.player_id = me.player_id
SET p.team_id = me.team_id
WHERE p.team_id IS NULL;

-- ---------------------------
-- 7. 기본 관리자 계정 추가
-- ---------------------------
INSERT IGNORE INTO users (username, password, role)
VALUES ('team02', 'team02', 'admin');

-- ---------------------------
-- 8. staging 테이블 삭제
-- ---------------------------
DROP TABLE IF EXISTS staging_summary;
DROP TABLE IF EXISTS staging_events;

SET FOREIGN_KEY_CHECKS = 1;

COMMIT;
