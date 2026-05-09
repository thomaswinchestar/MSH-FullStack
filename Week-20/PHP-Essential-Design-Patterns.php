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
//class Profile
//{
//    private $name;
//    private $phone;
//    public function __construct($name, $phone)
//    {
//        $this->name = $name;
//        $this->phone = $phone;
//    }
//}
//
//$data = [
//    ["name" => "Alice", "phone" => "321456"],
//    ["name" => "Bob"],
//    ["name" => "John", "phone" => "123456"],
//];
//class ProfileFactory
//{
//    private $data;
//    public function __construct($data)
//    {
//        $this->data = $data;
//    }
//    public function create()
//    {
//        $result = [];
//        foreach ($this->data as $data) {
//            $name = $data["name"] ?? "Unknown";
//            $phone = $data["phone"] ?? "N/A";
//            $result[] = new Profile($name, $phone);
//        }
//        return $result;
//    }
//}
//
//$pf = new ProfileFactory($data);
//$profiles = $pf->create();

//Strategy
// Cash-> Object, Card->Object, Mobile Money->Object, Paypal->Object, Stripe->Object, Crypto->Obj
//interface PaymentInterface
//{
//    public function amount();
//}
//
//class CashPayment implements PaymentInterface
//{
//    public function amount()
//    {
//        return 100;
//    }
//}
//
//class MobilePayment implements PaymentInterface
//{
//    public function amount()
//    {
//        return 90;
//    }
//}
//
//class Payment
//{
//    private $paymentMethod;
//    public function pay($context)
//    {
//        switch($context) {
//            case "cash" :
//                $this->paymentMethod = new CashPayment();
//                break;
//            case "mobile" :
//                $this->paymentMethod = new MobilePayment();
//                break;
//            default :
//                $this->paymentMethod = new CashPayment();
//        }
//        return $this->paymentMethod->amount();
//    }
//}
//
//$payment = new Payment();
//
//echo $payment->pay("cash") . "USD";
//echo "<br>";
//echo $payment->pay("mobile") . "USD";

//Facade
// start()

//class CheckOilPressure
//{
//    public function check()
//    {
//        echo "Oil Pressure OK.";
//    }
//}
//
//class CheckBreakFluid
//{
//    public function check()
//    {
//        echo "Break Fluid OK.";
//    }
//}
//
//class Car
//{
//    public $oil;
//    public $break;
//
//    public function __construct()
//    {
//        $this->oil = new CheckOilPressure();
//        $this->break = new CheckBreakFluid();
//    }
//    public function start()
//    {
//        $this->oil->check();
//        $this->break->check();
//
//        echo "Car Engine Started";
//    }
//}
//$car = new Car;
//$car->start();

//class Facade
//{
//    static function __callStatic($name, $args)
//    {
//        $name = strtoupper($name);
//        $arg = $args[0] ?? "/";
//
//        echo "Sending $name to $arg";
//    }
//}
//
//class Route extends Facade
//{
//    //
//}
//
//Route::get("/comments");
//
//Route::post();

//Provider Pattern -> Microsoft .NET Framework, Laravel, Angular, etc...
//interface Log
//{
//    public function write();
//}
//
//class Text implements Log
//{
//    public function write()
//    {
//        echo "Saving to text file";
//    }
//}
//
//class Memory implements Log
//{
//    public function write()
//    {
//        echo "Saving to memory file";
//    }
//}
//
//class Services
//{
//    public $container = [];
//    public function register($name, $class)
//    {
//        $this->container[$name] = $class;
//    }
//}
//
//$services = new Services;
//$services->register("text", Text::class);
//$services->register("memory", Memory::class);
//
//class Provider
//{
//    public $services;
//    public function __construct($services)
//    {
//        $this->services = $services->container;
//    }
//    public function make($service)
//    {
//        if(isset($this->services[$service])){
//            return new $this->services[$service];
//        }
//    }
//}
//
//$provider = new Provider($services);
//
//$log = $provider->make("text");
//$log->write(); // Saving to text file
//
//$log = $provider->make("memory");
//$log->write(); // Saving to memory file



