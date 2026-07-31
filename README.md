# EPL 24–25 – Football Performance Insight

EPL 2024–2025 경기 데이터를 관계형 데이터베이스로 구성하고, 선수와 팀의
성과를 다양한 SQL 분석 관점에서 탐색하는 웹 애플리케이션입니다. 데이터
적재부터 인증, CRUD, 집계·순위·윈도 함수 기반 분석 화면까지 구현한 데이터베이스
수업 팀 프로젝트입니다.

A web-based sports analytics platform for the EPL 2024–2025 season, built with Apache + PHP + MariaDB (XAMPP).  
Includes CRUD operations, advanced SQL analytics (Group By, Rollup/Drill-Down, Ranking, Windowing), user authentication, and full DB initialization scripts.

---

# 1. Installation Environment

- Windows + XAMPP (Apache + MariaDB)
- All project files must be placed under:

```
htdocs/team02
```

Folder structure:
```
htdocs/
└── team02/
    ├── index.php
    ├── *.php (all pages)
    ├── sql/
    │   ├── dbdrop.sql
    │   ├── dbcreate.sql
    │   └── dbinsert.sql
    └── data/
        ├── summary.csv
        └── events.csv
```

---

# 2. Database Initialization

## ① Connect to MySQL
```bash
cd C:\xampp\mysql\bin
mysql -u root -p
```

---

## ② Create the team02 Database
```sql
DROP DATABASE IF EXISTS team02;
CREATE DATABASE team02 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE team02;
```

---

## ③ Run SQL Scripts (Order Required)
```sql
source C:/xampp/htdocs/team02/sql/dbdrop.sql;
source C:/xampp/htdocs/team02/sql/dbcreate.sql;
source C:/xampp/htdocs/team02/sql/dbinsert.sql;
```

---

# 3. CSV Loading Notes

`dbinsert.sql` loads CSV files using absolute paths:
```
C:/xampp/htdocs/team02/data/summary.csv
C:/xampp/htdocs/team02/data/events.csv
```

→ Therefore, the `data` folder must exist under:
```
htdocs/team02/data/
```

CSV content size:
- summary.csv → 380 rows  
- events.csv → 5960 rows  
- **Total = 6,340 normalized records**

---

# 4. User Account Setup (Required)

```sql
CREATE USER IF NOT EXISTS 'team02'@'localhost' IDENTIFIED BY 'team02';
GRANT ALL PRIVILEGES ON team02.* TO 'team02'@'localhost';
FLUSH PRIVILEGES;
```

---

# 5. Running the Web Application

Open in browser:
```
http://localhost/team02/
```

Admin login:
- ID: team02  
- PW: team02
---
# 6. DB Backup & Restore 

This project includes a full database dump file:

```
team02/sql/dbdump.sql
```

This file contains **all tables + all data after successful insertion**.  
You can use it to quickly restore the same database state without reloading CSVs.

---
