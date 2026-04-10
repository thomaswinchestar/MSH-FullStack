<?php

//Return Type Hinting
//function add(Array $nums) : float {
//    return array_sum($nums); // return the value, not echo it
//}
//echo add([1, 2]); // echo the returned float value

//Union Type

//function price(int|float $n) {
//    return "Price is \$$n";
//}
//echo price(3.1);
//echo "<br>";
//echo price(2);

//function parameters -> pass by value and pass by Reference
//PHP Default -> Pass by Value

//Pass by Value
//$name = "Alice";
//function hello($n) {
//    $n = "Bob";
//    echo "Hello $n";
//}
//
//hello($name);
//echo "<br>";
//echo $name;

//Pass by Reference
//$name = "Alice";
//
//function hello(&$n) { // pass by reference using &
//    $n = "Bob";
//    echo "Hello $n";
//}
//hello($name);
//echo "<br>";
//echo $name;

//function one() {
//    function two() {
//        echo "Two";
//    }
//}
//one();
//two();

//$name = "Alice";
//function hello() {
//    global $name;
//    $name = "Bob";
//    echo "Hello $name";
//}
//hello();
//echo"<br>";
//echo $name;


//PHP Variable Function
//function add($a, $b){
//    echo $a + $b;
//}
//$name = "add";
//$name(1, 2); //add()

//$nums = [1, 2, 3, 4];
//function two($n) {
//    return $n * 2;
//}
//$result = array_map("two", $nums);
//print_r($result);

//$nums = [1, 2, 3, 4];
//$result = array_map(function ($n) {
//    return $n * 2;
//}, $nums);
//print_r($result);

//$two = function($n) {
//    echo $n * 2;
//};
//$two(2);

//$name = "Alice";
//$hello = function() use ($name) {
//    echo "Hello $name";
//};
//$hello();


//$name = "Alice";
//$hello = function() use ($name) {
//    $name = "Bob";
//    echo "Hello $name";
//};
//$hello();
//echo $name;

//$two = fn ($n) => $n * 2; // Arrow function
//echo $two(2);

//$x = 3;
//$add = fn($y) => $x + $y;
//echo $add(5);

//Named Arguments
//function profile($name, $email, $age) {
//    echo "$name ($age) @ $email";
//}
//profile("Alice", "alice@gmail.com", 22);

function profile($name, $email, $age) {
    echo "$name ($age) @ $email";
}
profile(
    age: 23,
    name: "Bob",
    email: "bob@gmail.com",
);