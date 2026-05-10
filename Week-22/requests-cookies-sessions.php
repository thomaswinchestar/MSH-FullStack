<?php
// Web app -> Request Data -> 2 types of requests
// URL Query -> https://www.google.com/search?q=php&h
// $_GET -> Superglobal Variable
// Form Data -> <form method="post" action="submit.php">
// $_POST -> Superglobal Variable
// Cookies -> Small pieces of data stored on the client side (browser) and sent to the server with each request. They are often used for session management, personalization, and tracking user behavior.
// Sessions -> A way to store data on the server side that is associated with a specific user. Sessions are typically used to maintain user state and data across multiple requests, such as keeping a user logged in as they navigate through a website.

//setcookie("name", "bob");
//setcookie("theme", "light");

//HTTP/1.1 200 OK
//Set-Cookie: name=bob
// Set-Cookie: theme=light

//localhost
//http://localhost:63342/
//localhost:3000

//Sessions
// Web Server

//session_start();
// $_SESSION["user"] = "Alice";

?>
<!--<!doctype html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
<!--    <title>Document</title>-->
<!--</head>-->
<!--<body>-->
<!--<script>-->
<!--    document.cookie = "name=Alice";-->
<!--    document.cookie = "theme=dark";-->
<!--</script>-->
<!--</body>-->
<!--</html>-->
