-- idk if this working :p
SET time_zone = "+09:00";

-- FOR USER CREATION, MAYBE YOU CAN CHECK SOME TUTORIALS.
-- I WILL RECOMMEND "Sequel Pro" TO MANAGE MYSQL DATABASES. It useful!
-- HOW TO INSTALL MYSQL(MARIADB)
-- sudo apt install mariadb-server -y
-- mysql_secure_installation
-- mysql to login to database...
-- Then create your account on database :
-- 
-- CREATE USER 'username'@'%' IDENTIFIED BY 'password';
-- CREATE DATABASE database_name;
-- GRANT ALL PRIVILEGES ON database_name.* TO 'username'@'%';
-- FLUSH PRIVILEGES;
--
-- IF YOU WANNA ALLOW ACCESS FROM OUTSIDE OF SERVER, LET BIND TO 0.0.0.0!
-- sudo nano /etc/mysql/mariadb.conf.d/50-server.cnf
-- maybe the file dir difference by OS... idk!
-- Find line "bind-address" and change value to "0.0.0.0"



-- "Drop table" means delete the table itself and the datas inside of it.
-- So please remove it if you are not intent to it.
DROP TABLE IF EXISTS verifys;
DROP TABLE IF EXISTS boards;
DROP TABLE IF EXISTS threads;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS admins;

--  BELOWS ARE CREATION OF TABLES!

CREATE TABLE IF NOT EXISTS verifys (
    id INT PRIMARY KEY AUTO_INCREMENT,
    useruuid VARCHAR(255) NOT NULL,
    userkey VARCHAR(255) NOT NULL,
    userexpiry TIMESTAMP NOT NULL,
    isadmin TINYINT(1) NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS boards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    boarduuid VARCHAR(255) NOT NULL,
    boardname VARCHAR(255) NOT NULL,
    boarddescription VARCHAR(255) NOT NULL,
    boardpassword VARCHAR(255) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    browser TEXT NOT NULL DEFAULT "UNKNOWN-UNKNOWN",
    isdeleted TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS threads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    boarduuid VARCHAR(255) NOT NULL,
    threaduuid VARCHAR(255) NOT NULL,
    threadname VARCHAR(255) NOT NULL,
    readonly TINYINT(1) NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    browser TEXT NOT NULL DEFAULT "UNKNOWN-UNKNOWN",
    isdeleted TINYINT(1) NOT NULL DEFAULT 0,
    last_comment TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    threaduuid VARCHAR(255) NOT NULL,
    commentuuid VARCHAR(255) NOT NULL,
    useruuid VARCHAR(255) NOT NULL,
    nickname VARCHAR(255) NOT NULL,
    context VARCHAR(255) NOT NULL,
    isadmin TINYINT(1) NOT NULL DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    browser TEXT NOT NULL DEFAULT "UNKNOWN-UNKNOWN",
    isdeleted TINYINT(1) NOT NULL DEFAULT 0
);
