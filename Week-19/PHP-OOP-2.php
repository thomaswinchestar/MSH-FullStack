<?php

// Inheritance
//class Animal
//{
////    private $name;
//    protected $name;
//    public function __construct($name)
//    {
//        $this->name = $name;
//    }
//    public function run()
//    {
//        echo "$this->name is running...";
//    }
//}
//
//class Dog extends Animal
//{
//    public function bark()
//    {
//        echo "$this->name : Woof... Woof....";
//    }
//}
//$bobby = new Dog("Bobby");
//$bobby->bark();

// Multiple inheritance
// class Dog extends Animal, Mammal, Doemestic {}
//class Animal
//{
//    static function info()
//    {
//        echo "Animal Class";
//    }
//}
//class Dog extends Animal
//{
//    //
//}
//class Fox extends Dog
//{
//    //
//}
//Fox::info();

//class Animal
//{
//    protected $name;
//    public function __construct($name)
//    {
//        $this->name = $name;
//    }
//}
//
//class Dog extends Animal
//{
//    private $color;
//    public function __construct($name, $color)
//    {
//        parent::__construct($name);
//        $this->color = $color;
//    }
//    public function profile()
//    {
//        echo "$this->name has $this->color color";
//    }
//}
//$bobby = new Dog("Bobby", "brown");
//$bobby->profile();

//final
//final class Animal
//{
//    final public function run()
//    {
//        echo "Animal is running";
//    }
//}
//class Dog extends Animal
//{
//    public function run()
//    {
//        echo "Dog is running";
//    }
//}

//Abstract Class
//abstract class Animal
//{
//    public abstract function talk(); //Abstract Method
//    public function run()
//    {
//        echo "Running....";
//    }
//}
//class Dog extends Animal
//{
//    //
//}

//Interface
//Abstract vs Interface
//Abstract -> method, abstract method
//Interface -> Abstract method only
//class Dog
//{
//    public function run()
//    {
//        echo "The Dog is running";
//    }
//}
//class Fish
//{
//    public function swim()
//    {
//        echo "The Fish is swimming";
//    }
//}
//
//function app(Dog $obj) {
//    $obj->run();
//}
//
//app(new Dog);
//app(new Fish);

//interface Animal
//{
//    public function move();
//}
//class Dog implements Animal
//{
//    public function move()
//    {
//        echo "The Dog is running";
//    }
//}
//class Fish implements Animal
//{
//    public function move()
//    {
//        echo "The Fish is swimming";
//    }
//}
//
//function app(Animal $obj) {
//    $obj->move();
//}
//
//app(new Dog);
//echo "<br>";
//app(new Fish);

//interface Animal
//{
//    public function move();
//}
//interface Livestock
//{
//    public function isFriendly();
//}
//
//class Cow implements Animal, Livestock
//{
//    public function move()
//    {
//        echo "The Cow is walking";
//    }
//    public function isFriendly()
//    {
//        return true;
//    }
//}

// Traits
//class Math
//{
//    public function add($a, $b)
//    {
//        echo $a + $b;
//    }
//}
//class Area
//{
//    private $PI = 3.14;
//    public function circle($r) {
//        echo $this-> PI * $r * $r ;
//    }
//}
//class Calculator extends Area // Math

//trait Math
//{
//    public function add($a, $b)
//    {
//        echo $a + $b;
//    }
//}
//trait Area
//{
//    private $PI = 3.14;
//    public function circle($r)
//    {
//        echo $this-> PI * $r * $r;
//    }
//}
//
//class Calculator
//{
//    use Math, Area;
//}
//$calc = new Calculator();
//$calc->add(10, 20);
//echo "<br>";
//$calc->circle(5);

//class Area
//{
//    const PI = 3.14;
//    public function circle($r)
//    {
//        echo $this-> PI * $r * $r;
//    }
//}
//echo Area::PI;

// Area::class

//Magic Methods -> __construct() - constructor, __destruct() - Destructor, 17 magic methods
//__call(), __callStatic()
//class Math
//{
//    public function __call($name, $arguments)
//    {
//        echo "Method $name doesn't exist";
//    }
//    static function __callStatic($name, $arguments)
//    {
//        echo "Static Method $name doesn't exist";
//    }
//}
//$obj = new Math();
//$obj->add(); // Method add doesn't exists
//Math::add(); // Static method add doesn't exits

// __invoke()
//class Math
//{
//    public function __invoke()
//    {
//        echo "This is not a fucntion";
//    }
//}
//
//$obj = new Math();
//$obj(); //

// __set(), __get()
//class Math
//{
//    private $PI = 3.14;
//    public function __get($name)
//    {
//        echo "Cannot get $name";
//    }
//    public function __set($name, $value){
//        echo "Cannot set $name with $value";
//    }
//}
//$obj = new Math();
//echo $obj->PI; // Cannot get PI
//echo "<br>";
//$obj->PI = 3.142; // Cannot set PI with 3.142

//__toString()
class Math
{
    private $PI = 3.14;
    public function __toString()
    {
        return "PI = $this->PI";
    }
}
$obj = new Math;
echo $obj;