<?php
// Procedural Language
//Object-Oriented Programming - OOP
// Interface, Abstract Class
//class Animal
//{
//    //Property, Method
//    //Access Control, //Visibility
//}
//$dog = new Animal();
//var_dump($dog);

//Access Control - Public, Private, Protected

//class Animal
//{
//    public $name; //Property
//    public function run() //Method
//    {
//        echo "$this->name is running...";
//    }
//}
//
//$dog = new Animal;
//$dog->name = "Bobby"; // -> Dart Operator, Java, JS, Python use "." Dot Operator
//$dog->run();

//class Animal
//{
//    private $name;
//}
//$dog = new Animal;
//$dog->name = "Bobby";

//Constructor
//class Animal
//{
//    public function Animal()
//    {
//        echo "Creating Animal Object";
//    }
//}
//$dog = new Animal();

// __construct()
//class Animal
//{
//    public function __construct() //Magic Method
//    {
//        echo "Creating Animal Object!";
//    }
//}
//$dog = new Animal;

//class Animal
//{
//    private function __construct()
//    {
//        echo "Creating Animal Object..";
//    }
//}
//$dog = new Animal;

//Static Member, Class Member
//class Animal
//{
//    static $type = "Mammal";
//    static function info()
//    {
//        echo "Group: " . static::$type;
//    }
//}
//echo Animal::$type; //:: -> Scope Resolution Operator, Double Colon Operator
//Animal::info();

//class Animal
//{
//    private $name;
//    public function __construct($name)
//    {
//        $this->name = $name;
//    }
//
//    public function run()
//    {
//        echo "$this->name is running...";
//    }
//}
//
//$dog = new Animal("Bobby");
//$dog->run();

//Constructor Property Promotion
class Animal
{
    public function __construct(private $name)
    {
        //
    }
    public function run() {
        echo "$this->name is running...";
    }
}
$dog = new Animal("Rambo");
$dog->run();