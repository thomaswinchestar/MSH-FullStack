// Relational Database Management System (RDBMS)
// MySQL, MSSQL, Oracle, PostgreSQL, SQLite(Standalone Database) - SQL Query Language

// NoSQL Database
// Redis, MongoDB

//MySQL Database - MySQL, MariaDB
// MySQL AB - Sun Microsystems - Oracle
// Drop-in Replacement

//phpMyAdmin - Web-based MySQL Database Management Tool
// localhost/phpmyadmin

//utf8mb4_general_ci
UTF-8 = Character Encoding -> ABC, ကခဂ
ASCII, Latin1 - > English, Spain, Portugal, France, Germany, Italy, Netherlands, Denmark, Norway, Sweden, Finland
UTF-8 -> All Languages

mb4 -> Multi-Byte(4-Byte), ABC-> 1-byte, ကခဂ -> 3 byte, Emoji-> 4-byte

general_ci, unicode_ci -> Case Insensitive, a = A, က = က

Unicode-> Unicode Consortium

general-> Database

Convention Over Configuration

Database Name, Table Name, Column Name -> Snake Case -> php_my_admin, Camel Case -> phpMyAdmin, Capital Case/Pascal Case -> PhpMyAdmin

Table Name -> Plural -> users, roles, categories

Double Context -> users table, user_id -> id

Type-> INT, VARCHAR, TEXT, DATETIME, TIMESTAMP
INT -> Integer, VARCHAR -> Variable Character, TEXT -> Large Text, DATETIME -> Date and Time, TIMESTAMP -> Timestamp
VARCHAR-> Name, Email, Phone No, Password
TEXT-> Article, Comment, Post, Personal Information, Address
DATETIME-> Created At, Updated At-> Year-Month-Day Hour:Minute:Second format (2026-05-21 14:30:00)
TIMESTAMP-> Created At, Updated At -> Automatically Updated -> 1970-01-01 00:00:00 UTC Timezone

Length/Values -> VARCHAR(255), INT(11)
Default Value -> DEFAULT 'Default Value', DEFAULT CURRENT_TIMESTAMP
NULL -> NULL, NOT NULL -> NOT NULL
Auto Increment -> AUTO_INCREMENT -> Primary Key -> Unique Identifier -> id, user_id, role_id, category_id
Collation->
Attributes -> Column Type -> INT(or)FLOAT -> UNSIGNED -> cannot store minus(-) values
Index -> Indexing -> Primary Key, Unique Key, Foreign Key, Composite Key
Index-> Students->Row Number(1, 2, 3, 4, 5) -> Search Index -> Faster Search, Name(Index),
Fulltext -> Search Index
Spatial -> Geolocation Data-> Latitude, Longitude Index
Primary Key -> Unique Identifier -> id, user_id, role_id, category_id
Unique Key -> Unique Value -> email, username
Unique Index->Row Number(Unique Index),
Foreign Key -> Relationship between Tables -> user_id in posts table references id in users table
AI -> Auto Increment -> Automatically Incremented Value -> id, user_id, role_id, category_id

Storage Engine -> Inno DB -> Transaction, Foreign Key, ACID Compliance
MyISAM -> Default


roles
id - INT, Primary, AI
name - VARCHAR(255)
value - INT

users
id - INT, Primary, AI
name - VARCHAR(255)
email - VARCHAR(255)
phone - VARCHAR(255)
address - TEXT
password - VARCHAR(255)
role_id - INT, Default (1)
photo - VARCHAR(255), Null
suspended - INT, Default (0)
created_at - DATETIME
updated_at - DATETIME, null

SQL(Structure Query Language)
SQL Query Language

CRUD -> Create, Read, Update, Delete

Create
INSERT INTO table (column, column, ...) VALUES (value, value, ...); // '', "", `role_id`

Read
SELECT column1, column2, .... FROM table; *
SELECT * FROM users;

SELECT id, name, email FROM users ORDER BY name

SELECT id, name, email FROM users ORDER BY name DESC

SELECT id, name, email FROM users ORDER BY role_id DESC, name

SELECT * FROM users WHERE role_id = 2  // !=, >, >=, <, <=, Logical Operator -> AND, OR

SELECT * FROM users WHERE role_id > 1 AND suspended = 0 //EXISTS, ANY, BETWEEN, LIKE

SELECT * FROM users LIMIT 10

SELECT * FROM users LIMIT 5, 10

SELECT * FROM users WHERE role_id = 1 ORDER BY name LIMIT 10