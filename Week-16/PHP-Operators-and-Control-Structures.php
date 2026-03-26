<?php
//PHP -> +,-,*,/ Operator
//PHP -> +
//PHP -> .

//$greet = "Welcome!";
//$name = "Bob";
//
//echo $greet . " " . $name; // Output: Welcome! Bob
//
//$greet = "Welcome!";
//$name = "Bob";
//
//echo "$greet $name";
//
//$data = ["Apple", "Orange"];
////echo $data[0] . " and " . $data[1] . "<br>";
//echo $data[0], " and ", $data[1];

// %, **
//echo 5 % 3;
//echo "<br>";
//echo 2 ** 3;

# Comments
# "//" Operator - Single line comment
# /*  */ - Multi-line comment
# # - Single Line Comment

# This is a valid Comment

# = -> Assignment Operator, +=, -=, *=, /=, %=, **=
# == -> Comparison Operator, ===, !=, !==, >, <, >=, <=
# && -> Logical AND, || -> Logical OR, ! -> Logical NOT

//$num = 3;
//$num += 2;
//echo $num;

# .= Operator

//$result = "Welcome";
//$result .= " ";
//$result .= "Bob";
//
//echo $result;


// ++, --
//$x = 3;
//$y = $x++;
//echo "x: $x, y: $y"; // Output: x: 4, y: 3
//echo "<br>";
//$x = 3;
//$y = ++$x;
//echo "x: $x, y: $y";

//Comparison Operators -> ==, !=, ===(Identical Equal Operator),  !==(Identical Not Equal Operator), <, >, >=, <=
// <>

//echo 5 == "5";
//echo "<br>";
//echo 5 === "5";

//Spaceship Operator
//echo 3 <=> 5; // -1
//echo "<br>";
//echo 5 <=> 5; // 0
//echo "<br>";
//echo 5 <=> 3; // 1

//Logical Operators
# PHP -> ! NOT Operator
# && -> AND Operator
# || -> OR Operator
# and , or

$x = 3;
$y = 5;

//echo $x === $y || $x === 3; //1 true
//echo "<br>";
//echo $x === $y or $x === 3; // 1 true
//echo "<br>";
//echo $x === $y && $x === 3; // empty false
//echo "<br>";
//echo $x === $y and $x === 3; // empty false
//echo "<br>";
//echo !($x === $y and $x === 3); // 1 true
//
//echo "<br>";
# PHP -> XOR Operator (xor)
echo $x < $y or $x === 3; // 1 true
echo "<br>";
echo $x < $y xor $x === 3; // empty(false)

