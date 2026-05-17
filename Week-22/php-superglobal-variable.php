<?php
//$GLOBALS & $_SERVER
//$GLOBALS is a superglobal variable in PHP that contains a reference to all variables that are currently defined in the global scope of the script. It allows you to access and manipulate global variables from anywhere in your code, including within functions and classes.
//$GLOBALS - $_GET, $_POST, $_COOKIE, $_SESSION, $_FILES, $_REQUEST, $_ENV, $_SERVER

// $name = "Alice";

// function hello()
// {
//     echo "Hello, " . $GLOBALS['name'] . "!";
// }
// hello(); // Output: Hello, Alice!

//$_SERVER is a superglobal variable in PHP that contains information about the server and the execution environment. It provides various details such as headers, paths, and script locations. You can use $_SERVER to access information about the current request, server environment, and more.

// $_SERVER -> User Agent, Request Method, Request URI, Server Name, Server Port, Remote Address, Query String, HTTP Referer, HTTP Host, Script Name, etc.

print_r("<pre>" . print_r($_SERVER, true) . "</pre>");
// echo "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "<br>";
// echo "Request Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
// echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
// echo "Server Name: " . $_SERVER['SERVER_NAME'] . "<br>";
// echo "Server Port: " . $_SERVER['SERVER_PORT'] . "<br>";
// echo "Remote Address: " . $_SERVER['REMOTE_ADDR'] . "<br>";
// echo "Query String: " . $_SERVER['QUERY_STRING'] . "<br>";
// echo "HTTP Referer: " . $_SERVER['HTTP_REFERER'] . "<br>";
// echo "HTTP Host: " . $_SERVER['HTTP_HOST'] . "<br>";
// echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";