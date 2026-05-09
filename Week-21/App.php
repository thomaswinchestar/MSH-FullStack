<?php
// App.php

//include('Math.php');
//
//include 'Math.php';

//include_once('Math.php');
//include_once('Math.php');

//require_once('Math.php');
//require_once('Math.php');

//require('Math.php');

//require 'Math.php';

//include('Math.php');
//include('Calculator.php');
//
//
//echo Math\add(1 , 2);
//echo "<br>";
//echo Calculator\add([1, 2, 3]);
//
//namespace Math;
//
//include('Math.php');
//include('Calculator.php');
//
//
//echo add(1 , 2);
//echo "<br>";
//echo \Calculator\add([1,2,3]);
// \ -> Global Namespace or Root Namespace
// Math\Calculator\add()

//Sub-Namespace
//namespace Math;
//include('Math.php');
//
//echo Basic\add(1,2);

//Math\Basic\add()

//include('Calculator.php');
//
//// alias - git status -> gs, git init - git add - ga, git commit - gc, git push - gp, git pull - gl
//
//use Library\Helper\Math\Basic\Calculator as Math;
//
////$calc1 = new Library\Helper\Math\Basic\Calculator;
////$calc2 = new Library\Helper\Math\Basic\Calculator;
//
//$calc1 = new Math;
//$calc2 = new Math;

//PSR-4 - PHP Standard Recommendation
// Class Autoloader

// Class Name, Namespace - Capital Case - Math, CarFactory, UserViewManager, same for Interface, Traits
// Code File name should be same with Main Class Name - CarFactory Class Name , CarFactory.php
// Namespace should be same with Directory Structure - Library\Helper\Math\Basic\Calculator Class Name, Library/Helper/Math/Basic/Calculator.php

// Class Autoload
// Library/Helper/Calculator.php

//namespace Library\Helper;
//
//class Calculator
//{
//    public function add($nums)
//    {
//        return array_sum($nums);
//    }
//}

//include('Library/Helper/Calculator.php');
//use Library\Helper\Calculator;
//
//$calc = new Calculator;
//echo $calc->add([1, 2, 3]); //3

include('autoload.php');
use Library\Helper\Calculator;
$calc = new Calculator;
echo $calc->add([1, 2, 3]);

//Composer