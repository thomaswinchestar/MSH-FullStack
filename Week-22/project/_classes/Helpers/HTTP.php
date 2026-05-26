<?php
namespace Helpers;
class HTTP
{
    static $base = "http://localhost:63342/JS-Basic/Week-22/project/"; //http://localhost/project/
    static function redirect($path, $query = "")
    {
        $url = static::$base . $path;
        if($query) $url .= "?$query";
        header("location: $url");
        exit;
        //HTTP::redirect("/users", "query=value);
    }
}