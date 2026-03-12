<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
</head>
<body>
    <h1>Home Page</h1>
    <?php
        $hour = date('h');
    ?>
    <p>
<!--        // Template-->
<!--        // Template - Smarty, Twig, Blade-->
<!--        --><?php //if($hour < 6 || $hour > 18) { ?>
<!--            <b>Night Time</b>-->
<!--        --><?php //} else { ?>
<!--            <i>Day Time</i>-->
<!--        --><?php //} ?>
    </p>

    <p>
        //Alternative Syntax - while, for, switch, Array - foreach
        <?php if ($hour < 6 || $hour > 18) : ?>
            <b>Night Time</b>
        <?php else: ?>
            <i>Day Time</i>
        <?php endif; ?>
    </p>
</body>
</html>