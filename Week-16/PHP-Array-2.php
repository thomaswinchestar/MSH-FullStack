<?php

//array_push()
//array_pop()
//array_unshift()
//array_shift()
//$users = ["alice", "bob"];
//array_push($users, "tom");
//print_r($users);
//
//echo "</br>";
//
//array_pop($users);
//print_r($users);
//
//echo "</br>";

//array_splice()
//$users = ["tom", "bob", "alice"];
//$result = array_splice($users, 1, 1);
//
//print_r($users);
//echo "</br>";
//print_r($result);

//array_keys();
//array_values();
//$user = ["name" => "Alice", "age" => 20];
//$keys = array_keys($user);
//$vals = array_values($user);
//
//print_r($keys);
//echo "</br>";
//print_r($vals);

//sort() - array value sorting
//rsort() - reverse array sorting
//ksort() - index array sorting
//krsort() - index array reverse sorting

//$users = ["tom" => 23, "bob" => 22, "alice" => 24];
//sort($users);
//print_r($users);
//echo "<br>";

//$users = ["tom" => 23, "bob" => 22, "alice" => 24];
//ksort($users);
//print_r($users);

//these array functions are not giving new array, it's modified the original array.

//explode(), implode()
$input = "A quick brown fox.";
$arr = explode(" ", $input);
print_r($arr);
echo "<br>";
$str = implode(" ", $arr);
echo $str;



