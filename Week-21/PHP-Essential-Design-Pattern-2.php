<?php
//// Dependency Injection - SOLID
//interface Log
//{
//    public function write($log);
//}
//class TextLogger implements Log
//{
//    public function write($log) {
//        // Save $log to text file
//        echo $log;
//    }
//}
//
//class DatabaseLogger implements Log
//{
//    public function write($log) {
//        // Save $log to database
//        echo $log;
//    }
//}
//
//class App
//{
//    private $logger;
//    public function __construct(Log $logger)
//    {
//        $this->logger = $logger;
//    }
//    public function run() {
//        $this->logger->write("App is running");
//    }
//}
////$app = new App(new TextLogger);
////$app->run();
//
//$app = new App(new DatabaseLogger);
//$app->run();

//Dependency Injection - Factory Pattern, Provider Pattern - Laravel - Service Container
//$services = new Services;
//$services->register("text", Text::class);
//$services->register("memory", Memory::class);

//$services->register("app", function() {
//    return new App(new DatabaseLogger);
//});
//
//$services->register("app", function() {
//    return new App(new TextLogger);
//});
//
//
//$app = $provider->make("app");

//Repository Pattern - Eric Evens - Domain Driven Design
//Data -> Database , Create, Read, Update, Delete, Retreive - Patterns
// SQL Query Language
// Table Gateway Pattern
// Object Relational Mapping(ORM) Pattern
// Data Layer or Data Abstraction

//DB->Repository->...
//Model Class, App Class
#[AllowDynamicProperties]
class Model
{

    public function save(): void
    {
        echo "Saving $this->name and $this->age";
    }
}

class Repository
{
    public function update($data)
    {
        $name = $data['name'] ?? "Unknown";
        $age = $data['age'] ?? "Unknown";

        $model = new Model();
        $model->name = $name;
        $model->age = $age;
        $model->save();
    }
}
class App
{
   private $repo;
   public function __construct(Repository $repo)
   {
       $this->repo = $repo;
   }
   public function update($data)
   {
       $this->repo->update($data);
   }
}

$app = new App(new Repository);
$app->update(['name' => 'John Doe', 'age' => 20]);