<?php
//PHP Array
//Numeric Array
//$users = array("Alice", "Bob");
//$fruits = ["Apple", "Banana"];
//echo $users;
//echo "<br>";
//print_r($fruits);
//echo "<br>";
//var_dump($fruits);

//Associative Array
//$user = [ "name" => "Alice", "age" => 22 ];
//print_r($user);
//$users = [
//    ["name" => "Alice", "age" => 20],
//    ["name" => "Bob", "age" => 20],
//    ["name" => "Charlie", "age" => 20],
//];
//print_r($users);
//print_r($users[0]);
//Two Dimensional Array
//echo $users[0]["name"];

//$fruits = ['Apple', 'Orange'];
//$fruits[4] = 'Mango';
//print_r($fruits); //index - 0, 1, 4

//$fruits = ['Apple', 'Orange'];
//$fruits[] = 'Mango';
//print_r($fruits);

//$user = ["Alice", 22];
//list($name, $age) = $user; //PHP 7.1
//echo $name;

//$user = ["Alice", 22];
//[$name, $age ] = $user; //PHP 7.1
//echo $name;

//Associative Array - array destructuring
//$user = ["name" => "Alice", "age" => 20];
//["name" => $name, "age" => $age ] = $user;
//echo $name;

//Array Spread
//$nums1 = [1, 2];
//$nums2 = [ ...$nums1, 3 ];
//print_r($nums2);

//PHP Useful Array Function
// item of arrays - count()
//$nums = [1, 2, 3, 4];
//echo count($nums);

// is_array() - 1/empty
//$users = ["alice", "bob"];
//echo is_array($users); //true

//in_array() - 1/empty
//$users = ["alice", "bob"];
//echo in_array('bob', $users);

//array_search
$users = ["tom", "bob", "alice"];
echo array_search("alice", $users);