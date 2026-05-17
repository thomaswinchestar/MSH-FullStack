<?php
print_r($_FILES);

$name = $_FILES['photo']['name'];
$tmp = $_FILES['photo']['tmp_name'];

move_uploaded_file($tmp, $name);

// $_FILES is a super global variable in PHP that contains information about uploaded files. It is an associative
// array that holds details about the files being uploaded, such as their names, types, sizes


// name - file name
// type - file type - MIME type
// tmp_name - temporary file name on the server
// error - error code associated with the file upload
// size - file size in bytes