<?php

setcookie("name", "mgmg", time() + 3600);
setcookie("theme", "lightgrey");
setcookie("path", "cookie", time() + 3600, "/form/"); //path=cookie - cookie data
setcookie("name", "", time() - 1);

//$_COOKIE - Superglboal Variable

echo "See view-cookie.php";