<?php
// URL Query, Form Data, - Plain Text Data, File Data(Binary Data)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>

<body>
    <!-- $_FILES is a super global variable in PHP that contains information about uploaded files. It is an associative
    array that holds details about the files being uploaded, such as their names, types, sizes, and temporary locations
    on the server. When a user submits a form with a file input field, the $_FILES array is populated with the relevant
    information about the uploaded file(s). This allows developers to access and process the uploaded files in their PHP
    scripts. -->

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <!-- enctype="multipart/form-data" is required for file upload  -->
        <input type="file" name="photo">
        <button type="submit">Send</button>
    </form>

</body>

</html>