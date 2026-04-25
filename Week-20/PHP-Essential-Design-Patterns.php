<?php
// OOP
// Objects(Property, Method),
// Encapsulation(Information Hiding) - (Private, Protected ),
// Inheritance(Composition) - (Parent, Child, Super, Sub Class), Abstract Class
// Polymorphism - Subtyping (Interface)
// Robust Code Architecture
// Messy Code
// Object-Oriented Design Principles - SOLID
// S - Single Responsibility Principle
// O - Open/Closed Principle
// L - Liskov Substitution Principle - Interface
// I - Interface Segregation Principle
// D - Dependency Inversion Principle

// Design Patterns: Elements of Reusable Object-Oriented Software (GoF- Gang of Four Design Patterns) - 23 Patterns
// Laravel

// Singleton Pattern
//class Setting
//{
//    static $setting = null;
//    public $dark = 0;
//
//    protected function __construct()
//    {
//        //
//    }
//    static function create()
//    {
//        if(!static::$setting) {
//            static::$setting = new static;
//        }
//        return static::$setting;
//    }
//}
//$setting1 = Setting::create();
//$setting1->dark = 1;
//
//$setting2 = Setting::create();
//echo $setting2->dark;

// Builder Pattern
//$builder = new Builder();
//$builder->property1 = value1;
//$builder->property2 = value2;
//
//$object = $builder->build();

//class ProfileBuilder
//{
//    private $name;
//    private $phone;
//
//    public function setName($name)
//    {
//        $this->name = $name;
//        return $this;
//    }
//    public function setPhone($phone)
//    {
//        $this->phone = $phone;
//        return $this;
//    }
//    public function getName()
//    {
//        return $this->name;
//    }
//    public function getPhone()
//    {
//        return $this->phone;
//    }
//    public function build()
//    {
//        return new Profile($this);
//    }
//}
//class Profile
//{
//    public $name;
//    public $phone;
//
//    public function __construct(ProfileBuilder $pb) {
//        $this->name = $pb->getName();
//        $this->phone = $pb->getPhone();
//    }
//    static function builder()
//    {
//        return new ProfileBuilder();
//    }
//}
//
//$user = Profile::builder()
//    ->setName('John Doe')
//    ->setPhone('0112345678')
//    ->build();
//
//var_dump($user);
////in laravel, the builder pattern called "Manager"

//Factory Pattern
class Profile
{
    private $name;
    private $phone;
    public function __construct($name, $phone)
    {
        $this->name = $name;
        $this->phone = $phone;
    }
}

$data = [
    ["name" => "Alice", "phone" => "321456"],
    ["name" => "Bob"],
    ["name" => "John", "phone" => "123456"],
];
class ProfileFactory
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function create()
    {
        $result = [];
        foreach ($this->data as $data) {
            $name = $data["name"] ?? "Unknown";
            $phone = $data["phone"] ?? "N/A";
            $result[] = new Profile($name, $phone);
        }
        return $result;
    }
}

$pf = new ProfileFactory($data);
$profiles = $pf->create();