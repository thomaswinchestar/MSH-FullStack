<?php
session_start();
if(!isset($_SESSION['user'])){
    header('location: index.php');
    exit(); //die()
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-3">John Doe (Manager)</h1>
        <ul class="list-group">
            <li class="list-group-item">
                <b>Email :</b> john.doe@gmail.com
            </li>
            <li class="list-group-item">
                <b>Phone :</b> (123) 456-7890
            </li>
            <li class="list-group-item">
                <b>Address :</b> 123 Main St, Anytown, USA
            </li>
        </ul>
        <br>
        <a href="_actions/logout.php">Logout</a>
    </div>
</body>
</html>
