

<?php
//// Relational Database Management System (RDBMS)
//// MySQL, MSSQL, Oracle, PostgreSQL, SQLite(Standalone Database) - SQL Query Language
//
//// NoSQL Database
//// Redis, MongoDB
//
////MySQL Database - MySQL, MariaDB
//// MySQL AB - Sun Microsystems - Oracle
//// Drop-in Replacement
//
////phpMyAdmin - Web-based MySQL Database Management Tool
//// localhost/phpmyadmin
//
////utf8mb4_general_ci
//UTF-8 = Character Encoding -> ABC, ကခဂ
//ASCII, Latin1 - > English, Spain, Portugal, France, Germany, Italy, Netherlands, Denmark, Norway, Sweden, Finland
//UTF-8 -> All Languages
//
//mb4 -> Multi-Byte(4-Byte), ABC-> 1-byte, ကခဂ -> 3 byte, Emoji-> 4-byte
//
//general_ci, unicode_ci -> Case Insensitive, a = A, က = က
//
//Unicode-> Unicode Consortium
//
//general-> Database
//
//Convention Over Configuration
//
//Database Name, Table Name, Column Name -> Snake Case -> php_my_admin, Camel Case -> phpMyAdmin, Capital Case/Pascal Case -> PhpMyAdmin
//
//Table Name -> Plural -> users, roles, categories
//
//Double Context -> users table, user_id -> id
//
//Type-> INT, VARCHAR, TEXT, DATETIME, TIMESTAMP
//INT -> Integer, VARCHAR -> Variable Character, TEXT -> Large Text, DATETIME -> Date and Time, TIMESTAMP -> Timestamp
//VARCHAR-> Name, Email, Phone No, Password
//TEXT-> Article, Comment, Post, Personal Information, Address
//DATETIME-> Created At, Updated At-> Year-Month-Day Hour:Minute:Second format (2026-05-21 14:30:00)
//TIMESTAMP-> Created At, Updated At -> Automatically Updated -> 1970-01-01 00:00:00 UTC Timezone
//
//Length/Values -> VARCHAR(255), INT(11)
//Default Value -> DEFAULT 'Default Value', DEFAULT CURRENT_TIMESTAMP
//NULL -> NULL, NOT NULL -> NOT NULL
//Auto Increment -> AUTO_INCREMENT -> Primary Key -> Unique Identifier -> id, user_id, role_id, category_id
//Collation->
//Attributes -> Column Type -> INT(or)FLOAT -> UNSIGNED -> cannot store minus(-) values
//Index -> Indexing -> Primary Key, Unique Key, Foreign Key, Composite Key
//Index-> Students->Row Number(1, 2, 3, 4, 5) -> Search Index -> Faster Search, Name(Index),
//Fulltext -> Search Index
//Spatial -> Geolocation Data-> Latitude, Longitude Index
//Primary Key -> Unique Identifier -> id, user_id, role_id, category_id
//Unique Key -> Unique Value -> email, username
//Unique Index->Row Number(Unique Index),
//Foreign Key -> Relationship between Tables -> user_id in posts table references id in users table
//AI -> Auto Increment -> Automatically Incremented Value -> id, user_id, role_id, category_id
//
//Storage Engine -> Inno DB -> Transaction, Foreign Key, ACID Compliance
//MyISAM -> Default
//
//
//roles
//id - INT, Primary, AI
//name - VARCHAR(255)
//value - INT
//
//users
//id - INT, Primary, AI
//name - VARCHAR(255)
//email - VARCHAR(255)
//phone - VARCHAR(255)
//address - TEXT
//password - VARCHAR(255)
//role_id - INT, Default (1)
//photo - VARCHAR(255), Null
//suspended - INT, Default (0)
//created_at - DATETIME
//updated_at - DATETIME, null
//
//SQL(Structure Query Language)
//SQL Query Language
//
//CRUD -> Create, Read, Update, Delete
//
//Create
//INSERT INTO table (column, column, ...) VALUES (value, value, ...); // '', "", `role_id`
//
//Read
//SELECT column1, column2, .... FROM table; *
//SELECT * FROM users;
//
//SELECT id, name, email FROM users ORDER BY name
//
//SELECT id, name, email FROM users ORDER BY name DESC
//
//SELECT id, name, email FROM users ORDER BY role_id DESC, name
//
//SELECT * FROM users WHERE role_id = 2  // !=, >, >=, <, <=, Logical Operator -> AND, OR
//
//SELECT * FROM users WHERE role_id > 1 AND suspended = 0 //EXISTS, ANY, BETWEEN, LIKE
//
//SELECT * FROM users LIMIT 10
//
//SELECT * FROM users LIMIT 5, 10
//
//SELECT * FROM users WHERE role_id = 1 ORDER BY name LIMIT 10
//
//SELECT users.name, users.role_id, roles.name AS role FROM users LEFT JOIN roles ON users.role_id = roles.id;
//
//SELECT users.name, users.role_id, roles.name AS role FROM users RIGHT JOIN roles ON users.role_id = roles.id;
//
//INNER JOIN - Both Table have full information records,
//
//CROSS JOIN - Both Table have full information records
//
//Update
//
//UPDATE table SET column1 = value1, column2 = value2, ... WHERE condition;
//
//UPDATE users SET role_id = 2, updated_at = NOW() WHERE id = 5;
//
//Delete
//
//DELETE FROM table WHERE condition;
//
//DELETE FROM users WHERE id = 5;
//
//PHP -> MySQL, MSSQL, Oracle, PostgreSQL, SQLite
//
//PDO -> PHP Data Objects
//
//mysqli_connect(), mysqli_query(), mysqli_fetch_assoc(), mysqli_fetch_array(), mysqli_fetch_row(), mysqli_num_rows(), mysqli_close()

//Connecting Database
// DSN -> Data Source Name

$db = new PDO('mysql:dbhost=localhost;dbname=project', 'root', '');
//$db = new PDO('sqlite:project.db'), new PDO('mongodb:dsn');
// mysql default user name -> root, password -> ''
$db = new PDO('mysql:dbhost=localhost;dbname=project', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);

//$statement = $db->query('SELECT * FROM roles');
//
//$row1 = $statement->fetch();
//$row2 = $statement->fetch();
//$row3 = $statement->fetch();
//
//print_r($row1);
//print_r($row2);
//print_r($row3);

//$result = $statement->fetchAll();
//
//print_r($result);

// PDO -> 3 Fetch Methods
// fetch(), fetchAll(), fetchObject()
//
//$sql = "INSERT INTO roles(name, value) VALUES ('Supervisor', 4)";
//$db->query($sql);
//echo $db->lastInsertId();

//$sql = "INSERT INTO roles(name, value) VALUES (:name, :value)";
//
//$statement = $db->prepare($sql);
//$statement->execute([
//    'name' => 'God',
//    'value' => 999,
//]);
//
//echo $db->lastInsertId();

// SQL Injection
//
//$sql = "UPDATE roles SET name=:name WHERE value = 999";
//
//$statement = $db->prepare($sql);
//$statement->execute([
//    'name' => 'Superman',
//]);
//
//echo $statement->rowCount();

$sql = "DELETE FROM roles WHERE id > 3";

$statement = $db->prepare($sql);
$statement->execute();

echo $statement->rowCount();