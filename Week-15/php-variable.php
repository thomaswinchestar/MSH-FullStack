<?php
//PHP is like JS - Loosely Typed Language - Type Juggling
//In JS -> typeof
//PHP Data Type Check -> var_dump()

//$var;
//var_dump($var);
//
//$var = 123;
//var_dump($var);
//
//$var = "abc";
//var_dump($var);

//PHP Var are Context Scope

//Global Var can be only used in Global Scope, but cannot use in function

//$name = "Bob"; //Global Variable
//function hello() {
//    global $name;
//    echo $name;
//}
//hello();

//function hello() {
//    if(true) {
//        $name = "Alice";
//    }
//    echo $name;
//}
//hello();
//isset() true-> 1, false -> 0

//echo isset($name);
//$name = "Bob";
//echo isset($name);

//PHP Constant -> define()
define("MIN", 1);
define("MAX", 10);

echo MAX;
//MAX = 20;

