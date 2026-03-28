<?php
//JS
// Number, String
// "Hello".length // 5
// 3.1416.toFixed(2) // 3.14
// Array
// [1, 2, 3].length //3
// [1, 2, 3].reduce((a, b) => a +b ) //6
// Standard Function - Global Obj, Window Obj Method
// alert() -> window.alert() -> Object Method
// Imperative, Procedural, OOP

//PHP -> Procedural Language
// Imperative, Procedural, OOP
// strlen() function -> to want string length
//count()
//array_reduce()

//function add($a, $b) {
//    echo $a + $b;
//}
//add(1 ,2, 3); //3

//function add($a, $b) {
//    return $a + $b;
//}
//$result = add(1, 2);
//
//echo add(1, 2);

//default parameter in func php
//function add($a, $b = 0) {
//    echo $a + $b;
//}
//add(1, 2);
//echo "<br>";
//add(9);

//Rest Parameter
//function add($a, ...$b) {
//    print_r($b);
//}
//add(1,2,3,4);

//before rest parameter in php
//function add() {
//    $args = func_get_args();
//    print_r($args);
//}
//add(1, 2, 3, 4);

//Type Hint
// Scalar Type Hinting
function add(Array $nums) {
    return array_sum($nums);
}
echo add([1, 2]);