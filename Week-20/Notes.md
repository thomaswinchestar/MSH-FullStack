# Week 20 — OOP Pillars Review, SOLID Principles & PHP Design Patterns

---

## Table of Contents

1. [The Four Pillars of OOP — A Deep Review](#1-the-four-pillars-of-oop--a-deep-review)
   - [Objects — Properties & Methods](#11-objects--properties--methods)
   - [Encapsulation — Information Hiding](#12-encapsulation--information-hiding)
   - [Inheritance — Composition & Reuse](#13-inheritance--composition--reuse)
   - [Polymorphism — One Interface, Many Forms](#14-polymorphism--one-interface-many-forms)
2. [Robust Code Architecture vs Messy Code](#2-robust-code-architecture-vs-messy-code)
3. [Object-Oriented Design Principles — SOLID](#3-object-oriented-design-principles--solid)
   - [S — Single Responsibility Principle](#31-s--single-responsibility-principle)
   - [O — Open/Closed Principle](#32-o--openclosed-principle)
   - [L — Liskov Substitution Principle](#33-l--liskov-substitution-principle)
   - [I — Interface Segregation Principle](#34-i--interface-segregation-principle)
   - [D — Dependency Inversion Principle](#35-d--dependency-inversion-principle)
4. [Design Patterns — GoF (Gang of Four)](#4-design-patterns--gof-gang-of-four)
5. [Singleton Pattern](#5-singleton-pattern)
6. [Builder Pattern](#6-builder-pattern)
7. [Factory Pattern](#7-factory-pattern)
8. [Strategy Pattern](#8-strategy-pattern)
9. [Facade Pattern](#9-facade-pattern)
10. [Provider Pattern](#10-provider-pattern)
11. [Patterns Used in Laravel](#11-patterns-used-in-laravel)
12. [References](#12-references)

---

## 1. The Four Pillars of OOP — A Deep Review

Object-Oriented Programming is built on **4 core pillars**. Everything we write in OOP is based on these ideas. Before learning design patterns, you need to truly understand them.

---

### 1.1 Objects — Properties & Methods

An **object** is a real-world *thing* represented in code. Every object has:

- **Properties** (also called attributes or fields) — what the object *knows* / *has*
- **Methods** (functions inside a class) — what the object *can do*

```php
class Car {
    // Properties — what a car HAS
    public string $brand;
    public string $color;
    private int $speed = 0;

    public function __construct(string $brand, string $color) {
        $this->brand = $brand;
        $this->color = $color;
    }

    // Methods — what a car CAN DO
    public function accelerate(int $amount): void {
        $this->speed += $amount;
    }

    public function getSpeed(): int {
        return $this->speed;
    }
}

$car = new Car("Toyota", "Red");
$car->accelerate(60);
echo $car->getSpeed(); // 60
```

> 🌍 **Real-world analogy:** A `User` object on a social media platform has properties like `name`, `email`, `profilePicture` and methods like `post()`, `follow()`, `like()`. You interact with the user object — you don't manipulate raw database columns.

---

### 1.2 Encapsulation — Information Hiding

**Encapsulation** means **bundling data (properties) and behavior (methods) together** inside a class, and **restricting direct access** to internal details.

The goal is: *hide what doesn't need to be seen, expose only what needs to be used.*

**Access Modifiers:**

| Modifier    | Same Class | Child Class | Outside Code |
|-------------|:----------:|:-----------:|:------------:|
| `public`    | ✅         | ✅          | ✅           |
| `protected` | ✅         | ✅          | ❌           |
| `private`   | ✅         | ❌          | ❌           |

```php
class BankAccount {
    private float $balance; // ❌ nobody can touch this directly from outside

    public function __construct(float $initialBalance) {
        $this->balance = $initialBalance;
    }

    // ✅ Controlled access through methods (getters/setters)
    public function deposit(float $amount): void {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Deposit must be positive.");
        }
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void {
        if ($amount > $this->balance) {
            throw new RuntimeException("Insufficient funds.");
        }
        $this->balance -= $amount;
    }

    public function getBalance(): float {
        return $this->balance;
    }
}

$account = new BankAccount(1000.00);
$account->deposit(500.00);
$account->withdraw(200.00);
echo $account->getBalance(); // 1300.00

// ❌ This would throw an error — balance is private
// $account->balance = 999999;
```

> 🌍 **Real-world analogy:** An ATM machine. You can deposit, withdraw, and check your balance. But you can NOT directly open the machine and take money. The internal wiring (balance storage logic) is **hidden** — you interact through the designed interface only.

**Why Encapsulation matters:**
- Prevents invalid state (e.g., negative balance)
- Hides implementation details — the class can change internally without breaking external code
- Makes code easier to maintain and debug

---

### 1.3 Inheritance — Composition & Reuse

**Inheritance** allows a child class to **reuse** code from a parent class. The child **inherits** all `public` and `protected` properties and methods from the parent.

```
Vehicle (Parent)
├── Car    (Child — inherits Vehicle, adds drive())
├── Truck  (Child — inherits Vehicle, adds cargoCapacity)
└── Bike   (Child — inherits Vehicle, adds pedal())
```

```php
class Vehicle {
    protected string $brand;

    public function __construct(string $brand) {
        $this->brand = $brand;
    }

    public function start(): void {
        echo "$this->brand engine started.\n";
    }
}

class Car extends Vehicle {
    private int $doors;

    public function __construct(string $brand, int $doors) {
        parent::__construct($brand); // call parent constructor
        $this->doors = $doors;
    }

    public function drive(): void {
        echo "$this->brand is driving with $this->doors doors.\n";
    }
}

$car = new Car("Toyota", 4);
$car->start(); // "Toyota engine started." ← inherited from Vehicle
$car->drive(); // "Toyota is driving with 4 doors." ← Car's own method
```

**Inheritance vs Composition:**

| | Inheritance (`extends`) | Composition (using objects as properties) |
|---|---|---|
| Relationship | "is-a" — Car **is a** Vehicle | "has-a" — Car **has an** Engine |
| Coupling | Tightly coupled to parent | Loosely coupled |
| Flexibility | Less flexible (one parent) | More flexible (swap parts) |
| Best for | Clear "is-a" hierarchies | Complex behaviours built from parts |

> 💡 **Prefer composition over deep inheritance chains.** If you need more than 2–3 levels of inheritance, consider redesigning with composition.

```php
// Composition example — Car HAS-A Engine (not is-a)
class Engine {
    public function start(): string {
        return "Engine started.";
    }
}

class Car {
    private Engine $engine;

    public function __construct() {
        $this->engine = new Engine(); // Car "has" an Engine
    }

    public function start(): void {
        echo $this->engine->start(); // delegate to Engine
    }
}
```

---

### 1.4 Polymorphism — One Interface, Many Forms

**Polymorphism** means "many forms." The same method name behaves **differently** depending on which object calls it.

There are two types:
- **Subtype Polymorphism** — via Interfaces / Inheritance (method overriding)
- **Parametric Polymorphism** — via type hints / generics

```php
interface Shape {
    public function area(): float;
    public function describe(): string;
}

class Circle implements Shape {
    public function __construct(private float $radius) {}

    public function area(): float {
        return M_PI * $this->radius ** 2;
    }

    public function describe(): string {
        return "Circle with radius {$this->radius}, area: " . round($this->area(), 2);
    }
}

class Rectangle implements Shape {
    public function __construct(
        private float $width,
        private float $height
    ) {}

    public function area(): float {
        return $this->width * $this->height;
    }

    public function describe(): string {
        return "Rectangle {$this->width}x{$this->height}, area: " . $this->area();
    }
}

class Triangle implements Shape {
    public function __construct(
        private float $base,
        private float $height
    ) {}

    public function area(): float {
        return 0.5 * $this->base * $this->height;
    }

    public function describe(): string {
        return "Triangle base:{$this->base} height:{$this->height}, area: " . $this->area();
    }
}

// Polymorphism in action — same loop, same method call, different behaviour
$shapes = [
    new Circle(5),
    new Rectangle(4, 6),
    new Triangle(8, 3),
];

foreach ($shapes as $shape) {
    echo $shape->describe() . "\n";
}
// Circle with radius 5, area: 78.54
// Rectangle 4x6, area: 24
// Triangle base:8 height:3, area: 12
```

> 🌍 **Real-world analogy:** A delivery company has `EmailNotification`, `SMSNotification`, and `PushNotification`. All implement a `Notifiable` interface with a `send()` method. The billing system calls `$notification->send()` without knowing — or caring — which type it is. Swapping one for another requires **zero changes** to the calling code.

---

## 2. Robust Code Architecture vs Messy Code

**Robust code** is code that is:
- Easy to **read** and understand
- Easy to **change** without breaking other parts
- Easy to **test** in isolation
- Easy to **extend** with new features

**Messy code (Spaghetti code)** is the opposite — everything is tangled together, changing one thing breaks five others.

### What makes code messy?

```php
// ❌ BAD — Messy, everything in one place
function processOrder($orderId, $userId, $productId, $qty, $coupon) {
    // Validation
    if ($qty <= 0) die("Invalid quantity");

    // Database queries
    $product = mysqli_query($conn, "SELECT * FROM products WHERE id=$productId");
    $user    = mysqli_query($conn, "SELECT * FROM users WHERE id=$userId");

    // Business logic
    $price = $product['price'] * $qty;
    if ($coupon === "SAVE10") $price *= 0.9;

    // Payment
    $result = file_get_contents("https://paymentgateway.com/charge?amount=$price");

    // Email
    mail($user['email'], "Order Confirmed", "Your order #$orderId is confirmed");

    // Update DB
    mysqli_query($conn, "UPDATE products SET stock=stock-$qty WHERE id=$productId");
    mysqli_query($conn, "INSERT INTO orders (user_id, total) VALUES ($userId, $price)");

    echo "Order placed!";
}
```

**Problems with this code:**
- One function does 6 different jobs
- SQL injection vulnerabilities
- Impossible to test the pricing logic alone
- Changing the email provider means rewriting this function
- Changing the payment provider means touching order logic

### What does robust code look like?

```php
// ✅ GOOD — Each class has ONE job, they work together cleanly

class OrderValidator {
    public function validate(int $qty): void {
        if ($qty <= 0) throw new InvalidArgumentException("Invalid quantity.");
    }
}

class PricingService {
    public function calculate(float $price, int $qty, ?string $coupon): float {
        $total = $price * $qty;
        if ($coupon === "SAVE10") $total *= 0.9;
        return $total;
    }
}

class PaymentService {
    public function charge(float $amount): bool {
        // isolated payment logic
        return true;
    }
}

class NotificationService {
    public function sendOrderConfirmation(string $email, int $orderId): void {
        // isolated email logic
    }
}

class OrderRepository {
    public function save(Order $order): void {
        // isolated DB logic
    }
}

// All pieces work together cleanly — easy to test, easy to swap
class OrderProcessor {
    public function __construct(
        private OrderValidator     $validator,
        private PricingService     $pricing,
        private PaymentService     $payment,
        private NotificationService $notifier,
        private OrderRepository    $repository
    ) {}

    public function process(OrderRequest $request): void {
        $this->validator->validate($request->qty);
        $total = $this->pricing->calculate($request->price, $request->qty, $request->coupon);
        $this->payment->charge($total);
        $this->notifier->sendOrderConfirmation($request->userEmail, $request->orderId);
        $this->repository->save(new Order($request->orderId, $total));
    }
}
```

> 💡 **Key principle:** Each class does ONE thing. If you need to change how emails are sent, you only touch `NotificationService`. The rest of the system is safe.

---

## 3. Object-Oriented Design Principles — SOLID

**SOLID** is an acronym for **5 principles** that guide you toward robust, maintainable OOP code. These were introduced by Robert C. Martin ("Uncle Bob").

```
S — Single Responsibility Principle
O — Open/Closed Principle
L — Liskov Substitution Principle
I — Interface Segregation Principle
D — Dependency Inversion Principle
```

---

### 3.1 S — Single Responsibility Principle

> **"A class should have only ONE reason to change."**

Every class should do **one job** and do it well. If a class has multiple responsibilities, a change to one responsibility might break the other.

```php
// ❌ BAD — UserManager does THREE things: manages users, sends emails, logs activity
class UserManager {
    public function createUser(string $name, string $email): User {
        // creates user in DB
        $user = new User($name, $email);
        // saves to DB...

        // sends welcome email
        mail($email, "Welcome!", "Hi $name, welcome to our app!");

        // logs the action
        file_put_contents('app.log', "User $name created at " . date('Y-m-d'));

        return $user;
    }
}
```

```php
// ✅ GOOD — Each class has ONE responsibility
class UserRepository {
    public function save(User $user): void {
        // saves user to DB
    }
}

class Mailer {
    public function sendWelcome(User $user): void {
        mail($user->email, "Welcome!", "Hi {$user->name}, welcome!");
    }
}

class Logger {
    public function log(string $message): void {
        file_put_contents('app.log', $message . PHP_EOL, FILE_APPEND);
    }
}

class UserService {
    public function __construct(
        private UserRepository $repository,
        private Mailer         $mailer,
        private Logger         $logger
    ) {}

    public function createUser(string $name, string $email): User {
        $user = new User($name, $email);
        $this->repository->save($user);
        $this->mailer->sendWelcome($user);
        $this->logger->log("User $name created at " . date('Y-m-d'));
        return $user;
    }
}
```

> 🌍 **Real-world scenario:** A chef cooks food. A waiter serves food. A cashier handles payment. Each person has ONE job. If you fire the chef, the waiter and cashier keep working fine. Same idea in code.

---

### 3.2 O — Open/Closed Principle

> **"Software entities should be OPEN for extension, but CLOSED for modification."**

You should be able to **add new features** without **changing existing working code**. Add, don't modify.

```php
// ❌ BAD — every time a new payment method is added, you modify this class
class PaymentProcessor {
    public function pay(string $method, float $amount): void {
        if ($method === 'credit_card') {
            echo "Charging credit card: $$amount";
        } elseif ($method === 'paypal') {
            echo "Charging PayPal: $$amount";
        } elseif ($method === 'crypto') {
            // added later — forces modification of existing class!
            echo "Charging crypto: $$amount";
        }
    }
}
```

```php
// ✅ GOOD — use abstraction; add new methods WITHOUT touching PaymentProcessor
interface PaymentMethod {
    public function pay(float $amount): void;
}

class CreditCard implements PaymentMethod {
    public function pay(float $amount): void {
        echo "Charging credit card: $$amount\n";
    }
}

class PayPal implements PaymentMethod {
    public function pay(float $amount): void {
        echo "Charging PayPal: $$amount\n";
    }
}

// Adding crypto? Just create a NEW class — no modification needed!
class Crypto implements PaymentMethod {
    public function pay(float $amount): void {
        echo "Charging crypto wallet: $$amount\n";
    }
}

class PaymentProcessor {
    // CLOSED for modification, OPEN for extension via interface
    public function process(PaymentMethod $method, float $amount): void {
        $method->pay($amount);
    }
}

$processor = new PaymentProcessor();
$processor->process(new CreditCard(), 100.00); // ✅
$processor->process(new PayPal(), 49.99);      // ✅
$processor->process(new Crypto(), 0.005);      // ✅ — added without changing PaymentProcessor
```

> 🌍 **Real-world scenario:** A smartphone. When a new app is released, you install it without opening the phone and rewiring it. The phone is *open for extension* (new apps) but *closed for modification* (you don't change the hardware).

---

### 3.3 L — Liskov Substitution Principle

> **"Subclasses should be substitutable for their base classes without breaking the program."**

If class `B` extends class `A`, you should be able to use `B` anywhere `A` is expected — and the program must still work correctly. A child should **honour the contract** of the parent.

```php
// ❌ BAD — Square breaks Rectangle's contract
class Rectangle {
    protected int $width;
    protected int $height;

    public function setWidth(int $w): void  { $this->width  = $w; }
    public function setHeight(int $h): void { $this->height = $h; }
    public function area(): int { return $this->width * $this->height; }
}

class Square extends Rectangle {
    // Square forces both dimensions to always be equal — BREAKS the parent contract!
    public function setWidth(int $w): void  { $this->width = $this->height = $w; }
    public function setHeight(int $h): void { $this->width = $this->height = $h; }
}

function calculateArea(Rectangle $rect): void {
    $rect->setWidth(4);
    $rect->setHeight(5);
    echo $rect->area(); // Expected: 20
}

calculateArea(new Rectangle()); // 20 ✅
calculateArea(new Square());    // 25 ❌ — substitution breaks expected behaviour!
```

```php
// ✅ GOOD — Use a shared interface, each class independently honours the contract
interface Shape {
    public function area(): float;
}

class Rectangle implements Shape {
    public function __construct(
        private float $width,
        private float $height
    ) {}

    public function area(): float {
        return $this->width * $this->height;
    }
}

class Square implements Shape {
    public function __construct(private float $side) {}

    public function area(): float {
        return $this->side ** 2;
    }
}

// Both work perfectly — no unexpected surprises
function printArea(Shape $shape): void {
    echo "Area: " . $shape->area() . "\n";
}

printArea(new Rectangle(4, 5)); // Area: 20 ✅
printArea(new Square(4));       // Area: 16 ✅
```

> 🌍 **Real-world analogy:** If you hire a "driver" (parent contract), whether it's someone who drives a car or a truck, they should both be able to drive. If one of them crashes every time they take the wheel, they're not a proper substitution for "driver."

---

### 3.4 I — Interface Segregation Principle

> **"Clients should NOT be forced to implement interfaces they do not use."**

Don't create one huge "fat" interface. Instead, split it into **smaller, focused interfaces** so classes only implement what they actually need.

```php
// ❌ BAD — One fat interface forces all classes to implement irrelevant methods
interface Animal {
    public function eat(): void;
    public function run(): void;
    public function fly(): void;   // 🐧 Penguins can't fly!
    public function swim(): void;  // 🦅 Eagles don't swim!
}

// Penguin is forced to implement fly() even though it can't fly
class Penguin implements Animal {
    public function eat(): void  { echo "Penguin eating"; }
    public function run(): void  { echo "Penguin running"; }
    public function fly(): void  { /* ??? Penguin can't fly — forced to leave empty */ }
    public function swim(): void { echo "Penguin swimming"; }
}
```

```php
// ✅ GOOD — Split into small, focused interfaces
interface Eatable {
    public function eat(): void;
}

interface Runnable {
    public function run(): void;
}

interface Flyable {
    public function fly(): void;
}

interface Swimmable {
    public function swim(): void;
}

// Each class only implements what it CAN do
class Penguin implements Eatable, Runnable, Swimmable {
    public function eat(): void  { echo "Penguin eating\n"; }
    public function run(): void  { echo "Penguin waddling\n"; }
    public function swim(): void { echo "Penguin swimming\n"; }
    // ✅ No fly() — penguins don't fly, so we don't force it
}

class Eagle implements Eatable, Runnable, Flyable {
    public function eat(): void { echo "Eagle eating\n"; }
    public function run(): void { echo "Eagle running\n"; }
    public function fly(): void { echo "Eagle soaring\n"; }
    // ✅ No swim() — eagles don't swim
}

class Duck implements Eatable, Runnable, Flyable, Swimmable {
    public function eat(): void  { echo "Duck eating\n"; }
    public function run(): void  { echo "Duck waddling\n"; }
    public function fly(): void  { echo "Duck flying\n"; }
    public function swim(): void { echo "Duck swimming\n"; }
}
```

> 🌍 **Real-world scenario:** A remote control for a TV has buttons for volume, channel, and power. A remote control for an AC has buttons for temperature and fan speed. You don't take the TV remote and force it to have temperature buttons just because "it's a remote."

---

### 3.5 D — Dependency Inversion Principle

> **"High-level modules should NOT depend on low-level modules. Both should depend on abstractions (interfaces)."**

Instead of a class **creating** its own dependencies (tight coupling), those dependencies should be **injected from outside** (loose coupling). This is also called **Dependency Injection (DI)**.

```php
// ❌ BAD — OrderService is tightly coupled to MySQLDatabase
class MySQLDatabase {
    public function save(array $data): void {
        echo "Saved to MySQL: " . json_encode($data) . "\n";
    }
}

class OrderService {
    private MySQLDatabase $db;

    public function __construct() {
        $this->db = new MySQLDatabase(); // ❌ hard-coded dependency
    }

    public function placeOrder(array $order): void {
        $this->db->save($order);
    }
}

// If you switch to PostgreSQL or MongoDB, you MUST rewrite OrderService!
```

```php
// ✅ GOOD — Depend on an abstraction (interface), not a concrete class
interface Database {
    public function save(array $data): void;
}

class MySQLDatabase implements Database {
    public function save(array $data): void {
        echo "Saved to MySQL: " . json_encode($data) . "\n";
    }
}

class PostgreSQLDatabase implements Database {
    public function save(array $data): void {
        echo "Saved to PostgreSQL: " . json_encode($data) . "\n";
    }
}

class MongoDatabase implements Database {
    public function save(array $data): void {
        echo "Saved to MongoDB: " . json_encode($data) . "\n";
    }
}

class OrderService {
    // Inject the dependency — OrderService doesn't care WHICH database
    public function __construct(private Database $db) {}

    public function placeOrder(array $order): void {
        $this->db->save($order);
    }
}

// Swap the database with ZERO changes to OrderService
$service = new OrderService(new MySQLDatabase());
$service->placeOrder(['id' => 1, 'product' => 'Laptop']);
// "Saved to MySQL: {"id":1,"product":"Laptop"}"

$service2 = new OrderService(new MongoDatabase());
$service2->placeOrder(['id' => 2, 'product' => 'Phone']);
// "Saved to MongoDB: {"id":2,"product":"Phone"}"
```

> 🌍 **Real-world analogy:** A lamp doesn't care if it's plugged into a wall socket, a power bank, or a generator. It depends on the **standard plug interface**, not on *how* the electricity is generated. Swap the power source freely — the lamp works the same.

---

**SOLID Summary Table:**

| Principle | One-liner | Violation Sign |
|---|---|---|
| **S**ingle Responsibility | One class = one job | "God classes" that do everything |
| **O**pen/Closed | Extend, don't modify | Giant `if/elseif` chains for types |
| **L**iskov Substitution | Child must honour parent's contract | Child overrides break parent behaviour |
| **I**nterface Segregation | Small, focused interfaces | Fat interfaces with unused methods |
| **D**ependency Inversion | Depend on abstractions | `new ConcreteClass()` deep in logic |

---

## 4. Design Patterns — GoF (Gang of Four)

### What are Design Patterns?

A **design pattern** is a **reusable solution** to a **commonly occurring problem** in software design. They are not code you copy-paste — they are **templates** or **strategies** that you adapt to your specific situation.

The most famous catalogue is **"Design Patterns: Elements of Reusable Object-Oriented Software"** (1994) by the **Gang of Four (GoF)** — Erich Gamma, Richard Helm, Ralph Johnson, and John Vlissides. It documented **23 patterns**.

### The 23 GoF Patterns — Grouped by Purpose

| Category | Purpose | Patterns |
|---|---|---|
| **Creational** | How objects are *created* | Singleton, Factory Method, Abstract Factory, Builder, Prototype |
| **Structural** | How objects are *composed / structured* | Adapter, Bridge, Composite, Decorator, Facade, Flyweight, Proxy |
| **Behavioural** | How objects *communicate / interact* | Chain of Responsibility, Command, Iterator, Mediator, Memento, Observer, State, Strategy, Template Method, Visitor, Interpreter |

> 💡 This week we focused on **Creational Patterns** (Singleton, Builder, Factory) and **Behavioural Patterns** (Strategy) and a **Structural Pattern** (Facade), plus the **Provider Pattern** used heavily in modern frameworks.

---

## 5. Singleton Pattern

### What is it?

The **Singleton Pattern** ensures that a class has **only ONE instance** throughout the entire application — no matter how many times you try to instantiate it, you always get the same object.

```
"One class, one instance — always."
```

### How it works

The trick is in three steps:
1. Make the constructor **`protected`** (or `private`) so nobody can call `new ClassName()` from outside.
2. Store the single instance in a **`static` property**.
3. Provide a **`static` factory method** (usually `create()` or `getInstance()`) that either creates the instance the first time, or returns the existing one.

```php
class Setting {
    static $setting = null; // holds the one-and-only instance
    public $dark = 0;       // a setting property

    protected function __construct() {
        // protected — cannot be called from outside with `new`
    }

    static function create() {
        if (!static::$setting) {
            // First call — create the instance
            static::$setting = new static;
        }
        // Every call after — return the SAME instance
        return static::$setting;
    }
}

$setting1 = Setting::create();
$setting1->dark = 1; // change dark mode on setting1

$setting2 = Setting::create();
echo $setting2->dark; // 1 — SAME object! $setting1 and $setting2 are identical
```

**What's happening:**

```
First call:  Setting::create() → $setting is null → creates new Setting → stores it → returns it
Second call: Setting::create() → $setting exists  → returns the SAME stored instance
```

### Why Use Singleton?

| Without Singleton | With Singleton |
|---|---|
| Multiple `Config` objects — each might have different data | One shared `Config` — all code reads the same config |
| Multiple `Logger` instances — logs written to different places | One `Logger` — all logs go to the same file/service |
| Multiple DB connections — wastes resources | One connection — shared efficiently |

### Real-World Use Cases

**1. Application Configuration:**

```php
class AppConfig {
    private static ?AppConfig $instance = null;
    private array $config = [];

    protected function __construct() {
        // Load config once from file/env
        $this->config = [
            'app_name'  => 'MyShop',
            'debug'     => false,
            'db_host'   => 'localhost',
            'db_name'   => 'shop_db',
        ];
    }

    public static function getInstance(): static {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function get(string $key, mixed $default = null): mixed {
        return $this->config[$key] ?? $default;
    }
}

// In UserController
$config = AppConfig::getInstance();
echo $config->get('app_name'); // "MyShop"

// In OrderController — SAME instance, no re-reading files
$config2 = AppConfig::getInstance();
echo $config2->get('db_host'); // "localhost"
// $config === $config2 → true — same object!
```

**2. Database Connection Pooling:**

```php
class Database {
    private static ?Database $instance = null;
    private PDO $pdo;

    protected function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=shop', 'root', '');
        echo "DB connected.\n"; // prints ONCE — connection created only once
    }

    public static function getInstance(): static {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function query(string $sql): mixed {
        return $this->pdo->query($sql);
    }
}

$db1 = Database::getInstance(); // "DB connected."
$db2 = Database::getInstance(); // (no output — same instance returned)
$db3 = Database::getInstance(); // (no output — same instance returned)

// All three variables point to the same connection — efficient!
```

> ⚠️ **When NOT to use Singleton:**
> - Singleton introduces global state — it can make code harder to test (you can't easily swap a mock singleton).
> - In modern frameworks like Laravel, the **Service Container** (IoC Container) manages single instances through dependency injection instead of the Singleton pattern directly.

> 💡 **Used in Laravel:** `config()`, `app()`, `DB::`, `Log::` — many Laravel facades are effectively backed by a single instance managed by the service container.

---

## 6. Builder Pattern

### What is it?

The **Builder Pattern** separates the **construction** of a complex object from its **representation**. Instead of passing many arguments to a constructor, you build the object **step by step** using chain-able setter methods.

```
"Build complex objects step by step, fluently."
```

### The Problem It Solves

Imagine creating a `Profile` object with 10 optional fields:

```php
// ❌ BAD — messy constructor with many parameters (called "Telescoping Constructor")
$profile = new Profile("John", "0112345678", "john@email.com", "Yangon", "male", 28, "bio here", null, null, null);
// Which argument is which? Hard to read, hard to maintain.
```

### How the Builder Works

```php
// ✅ GOOD — Build step by step with readable, chainable methods

class ProfileBuilder {
    private string  $name;
    private string  $phone;
    private ?string $email = null;
    private ?string $bio   = null;

    public function setName(string $name): static {
        $this->name = $name;
        return $this; // returns $this to enable method chaining
    }

    public function setPhone(string $phone): static {
        $this->phone = $phone;
        return $this;
    }

    public function setEmail(string $email): static {
        $this->email = $email;
        return $this;
    }

    public function setBio(string $bio): static {
        $this->bio = $bio;
        return $this;
    }

    public function getName(): string  { return $this->name; }
    public function getPhone(): string { return $this->phone; }
    public function getEmail(): ?string { return $this->email; }
    public function getBio(): ?string  { return $this->bio; }

    public function build(): Profile {
        return new Profile($this); // pass builder to Profile
    }
}

class Profile {
    public string  $name;
    public string  $phone;
    public ?string $email;
    public ?string $bio;

    public function __construct(ProfileBuilder $pb) {
        $this->name  = $pb->getName();
        $this->phone = $pb->getPhone();
        $this->email = $pb->getEmail();
        $this->bio   = $pb->getBio();
    }

    // Alternative: static factory method on the product class itself
    public static function builder(): ProfileBuilder {
        return new ProfileBuilder();
    }
}

// Usage — clean, readable, self-documenting
$user = Profile::builder()
    ->setName('John Doe')
    ->setPhone('0112345678')
    ->setEmail('john@example.com')
    ->setBio('PHP developer from Yangon')
    ->build();

var_dump($user);
// object(Profile)#3 {
//   ["name"] => "John Doe"
//   ["phone"] => "0112345678"
//   ...
// }
```

### Method Chaining — How it works

The key is **`return $this`** in every setter. This makes each setter return the same builder object, so you can chain calls:

```
Profile::builder()      → returns a ProfileBuilder object
  ->setName(...)        → sets name, returns the SAME ProfileBuilder
  ->setPhone(...)       → sets phone, returns the SAME ProfileBuilder
  ->setEmail(...)       → sets email, returns the SAME ProfileBuilder
  ->build()             → creates and returns the final Profile object
```

### Real-World Use Cases

**1. SQL Query Builder:**

```php
class QueryBuilder {
    private string  $table;
    private array   $conditions = [];
    private ?int    $limit      = null;
    private string  $orderBy    = '';

    public function from(string $table): static {
        $this->table = $table;
        return $this;
    }

    public function where(string $condition): static {
        $this->conditions[] = $condition;
        return $this;
    }

    public function limit(int $limit): static {
        $this->limit = $limit;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    public function build(): string {
        $sql = "SELECT * FROM {$this->table}";
        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }
        if ($this->orderBy) {
            $sql .= " {$this->orderBy}";
        }
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }
        return $sql;
    }
}

$query = (new QueryBuilder())
    ->from('users')
    ->where("age > 18")
    ->where("status = 'active'")
    ->orderBy('name')
    ->limit(10)
    ->build();

echo $query;
// SELECT * FROM users WHERE age > 18 AND status = 'active' ORDER BY name ASC LIMIT 10
```

**2. HTTP Request Builder:**

```php
class HttpRequestBuilder {
    private string $method  = 'GET';
    private string $url     = '';
    private array  $headers = [];
    private ?array $body    = null;

    public function get(string $url): static {
        $this->method = 'GET';
        $this->url    = $url;
        return $this;
    }

    public function post(string $url): static {
        $this->method = 'POST';
        $this->url    = $url;
        return $this;
    }

    public function withHeader(string $key, string $value): static {
        $this->headers[$key] = $value;
        return $this;
    }

    public function withBody(array $data): static {
        $this->body = $data;
        return $this;
    }

    public function build(): array {
        return [
            'method'  => $this->method,
            'url'     => $this->url,
            'headers' => $this->headers,
            'body'    => $this->body,
        ];
    }
}

$request = (new HttpRequestBuilder())
    ->post('https://api.example.com/users')
    ->withHeader('Content-Type', 'application/json')
    ->withHeader('Authorization', 'Bearer my-token')
    ->withBody(['name' => 'Alice', 'email' => 'alice@example.com'])
    ->build();

print_r($request);
```

> 💡 **Used in Laravel:** Laravel's **Query Builder** (`DB::table('users')->where(...)->orderBy(...)->get()`) is a classic Builder Pattern implementation. Laravel's `Http` client facade also uses the Builder Pattern.

> 💡 **In Laravel, the Builder Pattern is sometimes called the "Manager" pattern** when it manages multiple "drivers" (e.g., `Cache::store('redis')`, `Mail::mailer('smtp')`).

---

## 7. Factory Pattern

### What is it?

The **Factory Pattern** provides a way to **create objects without specifying the exact class** to create. Instead of calling `new ClassName()` everywhere, you delegate object creation to a **factory class**.

```
"Let a factory decide how to create objects — you just ask for what you need."
```

### The Problem It Solves

```php
// ❌ BAD — object creation scattered everywhere, hard to manage
$data = [
    ["name" => "Alice", "phone" => "321456"],
    ["name" => "Bob"],    // missing phone!
    ["name" => "John", "phone" => "123456"],
];

$profiles = [];
foreach ($data as $item) {
    // ❌ Every time: manually check if phone exists, set default, create object
    $name  = $item["name"]  ?? "Unknown";
    $phone = $item["phone"] ?? "N/A";
    $profiles[] = new Profile($name, $phone); // scattered creation logic
}
```

### The Factory Solution

```php
// ✅ GOOD — centralise all creation logic in a Factory

class Profile {
    private string $name;
    private string $phone;

    public function __construct(string $name, string $phone) {
        $this->name  = $name;
        $this->phone = $phone;
    }

    public function __toString(): string {
        return "Profile({$this->name}, {$this->phone})";
    }
}

$data = [
    ["name" => "Alice", "phone" => "321456"],
    ["name" => "Bob"],         // missing phone
    ["name" => "John", "phone" => "123456"],
];

class ProfileFactory {
    private array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function create(): array {
        $result = [];
        foreach ($this->data as $item) {
            $name  = $item["name"]  ?? "Unknown";
            $phone = $item["phone"] ?? "N/A"; // default value handled HERE — once
            $result[] = new Profile($name, $phone);
        }
        return $result;
    }
}

$pf       = new ProfileFactory($data);
$profiles = $pf->create();

foreach ($profiles as $profile) {
    echo $profile . "\n";
}
// Profile(Alice, 321456)
// Profile(Bob, N/A)       ← default applied by factory
// Profile(John, 123456)
```

### Factory Method Pattern (Extended)

The pure **Factory Method** pattern uses an abstract factory class where subclasses decide which concrete class to create:

```php
abstract class NotificationFactory {
    // Factory Method — subclasses override this
    abstract public function createNotification(): Notification;

    // Template — uses the factory method
    public function send(string $message): void {
        $notification = $this->createNotification();
        $notification->send($message);
    }
}

interface Notification {
    public function send(string $message): void;
}

class EmailNotification implements Notification {
    public function send(string $message): void {
        echo "📧 Email: $message\n";
    }
}

class SMSNotification implements Notification {
    public function send(string $message): void {
        echo "📱 SMS: $message\n";
    }
}

class PushNotification implements Notification {
    public function send(string $message): void {
        echo "🔔 Push: $message\n";
    }
}

class EmailFactory extends NotificationFactory {
    public function createNotification(): Notification {
        return new EmailNotification();
    }
}

class SMSFactory extends NotificationFactory {
    public function createNotification(): Notification {
        return new SMSNotification();
    }
}

class PushFactory extends NotificationFactory {
    public function createNotification(): Notification {
        return new PushNotification();
    }
}

// Usage
function sendAlert(NotificationFactory $factory, string $message): void {
    $factory->send($message); // works with ANY factory
}

sendAlert(new EmailFactory(), "Your order has shipped!");
// 📧 Email: Your order has shipped!

sendAlert(new SMSFactory(), "Your OTP is 1234");
// 📱 SMS: Your OTP is 1234

sendAlert(new PushFactory(), "You have a new message");
// 🔔 Push: You have a new message
```

### Real-World Use Cases

**1. User Role Factory — Creating different user types:**

```php
interface User {
    public function getPermissions(): array;
    public function getRole(): string;
}

class AdminUser implements User {
    public function getPermissions(): array {
        return ['create', 'read', 'update', 'delete', 'manage_users'];
    }
    public function getRole(): string { return 'admin'; }
}

class EditorUser implements User {
    public function getPermissions(): array {
        return ['create', 'read', 'update'];
    }
    public function getRole(): string { return 'editor'; }
}

class GuestUser implements User {
    public function getPermissions(): array {
        return ['read'];
    }
    public function getRole(): string { return 'guest'; }
}

class UserFactory {
    public static function create(string $role): User {
        return match ($role) {
            'admin'  => new AdminUser(),
            'editor' => new EditorUser(),
            'guest'  => new GuestUser(),
            default  => throw new InvalidArgumentException("Unknown role: $role"),
        };
    }
}

// Usage — caller doesn't need to know which class to instantiate
$admin  = UserFactory::create('admin');
$editor = UserFactory::create('editor');
$guest  = UserFactory::create('guest');

echo $admin->getRole();               // admin
print_r($editor->getPermissions());   // ['create', 'read', 'update']
```

**2. Database Connection Factory:**

```php
interface DatabaseConnection {
    public function connect(): string;
}

class MySQLConnection implements DatabaseConnection {
    public function connect(): string {
        return "Connected to MySQL";
    }
}

class SQLiteConnection implements DatabaseConnection {
    public function connect(): string {
        return "Connected to SQLite";
    }
}

class DatabaseFactory {
    public static function create(string $driver): DatabaseConnection {
        return match ($driver) {
            'mysql'  => new MySQLConnection(),
            'sqlite' => new SQLiteConnection(),
            default  => throw new \InvalidArgumentException("Unsupported driver: $driver"),
        };
    }
}

$driver = getenv('DB_DRIVER') ?: 'mysql'; // read from environment
$db = DatabaseFactory::create($driver);
echo $db->connect();
// Development (sqlite): "Connected to SQLite"
// Production (mysql):   "Connected to MySQL"
```

### Factory vs Builder

| | Factory Pattern | Builder Pattern |
|---|---|---|
| Creates | Simple or varied objects | Complex objects with many optional parts |
| How | One method call | Multiple chained steps |
| Returns | Ready-made object | Assembled object at the end |
| Best for | Creating one of many types | Configuring an object step by step |
| Example | `UserFactory::create('admin')` | `Profile::builder()->setName()->build()` |

---

## 8. Strategy Pattern

### What is it?

The **Strategy Pattern** defines a **family of algorithms** (behaviours), puts each one in a separate class, and makes them **interchangeable**. The caller selects which strategy to use at runtime — without changing the calling code.

```
"Same operation, different algorithms — swap them freely."
```

It is a **Behavioural Pattern** because it changes *how* objects communicate and delegate work.

### The Problem It Solves

Without Strategy, adding a new payment method means opening and editing the existing `Payment` class — violating the Open/Closed Principle:

```php
// ❌ BAD — adding new payment type forces you to modify existing class
class Payment {
    public function pay(string $context): int {
        switch ($context) {
            case "cash":   return 100;
            case "mobile": return 90;
            // Adding "crypto" here forces modification of this working class!
            default:       return 100;
        }
    }
}
```

### The Strategy Solution

```php
// Step 1 — Define the strategy interface (the "contract")
interface PaymentInterface {
    public function amount(): int;
}

// Step 2 — Concrete strategies, each in its own class
class CashPayment implements PaymentInterface {
    public function amount(): int {
        return 100; // full price
    }
}

class MobilePayment implements PaymentInterface {
    public function amount(): int {
        return 90; // 10% discount for mobile payments
    }
}

// Step 3 — Context class that USES the strategy
class Payment {
    private PaymentInterface $paymentMethod;

    public function pay(string $context): int {
        // Select the strategy
        $this->paymentMethod = match ($context) {
            "cash"   => new CashPayment(),
            "mobile" => new MobilePayment(),
            default  => new CashPayment(),
        };
        return $this->paymentMethod->amount();
    }
}

$payment = new Payment();
echo $payment->pay("cash")   . " USD\n"; // 100 USD
echo $payment->pay("mobile") . " USD\n"; // 90 USD
```

### Improved Version — Inject the Strategy (DIP + Strategy together)

The real power of Strategy is **injecting** it from outside (Dependency Inversion), so the `Payment` class never needs to change:

```php
interface PaymentInterface {
    public function amount(): int;
    public function label(): string;
}

class CashPayment implements PaymentInterface {
    public function amount(): int  { return 100; }
    public function label(): string { return "Cash"; }
}

class MobilePayment implements PaymentInterface {
    public function amount(): int  { return 90; }
    public function label(): string { return "Mobile Money"; }
}

class CardPayment implements PaymentInterface {
    public function amount(): int  { return 95; }
    public function label(): string { return "Credit Card"; }
}

// ✅ Context — closed for modification, open for new strategies
class Checkout {
    public function __construct(private PaymentInterface $strategy) {}

    public function processPayment(): void {
        echo "Processing " . $this->strategy->label()
           . " payment. Amount: " . $this->strategy->amount() . " USD\n";
    }
}

// Swap strategies freely — Checkout never changes
$order1 = new Checkout(new CashPayment());
$order1->processPayment(); // Processing Cash payment. Amount: 100 USD

$order2 = new Checkout(new MobilePayment());
$order2->processPayment(); // Processing Mobile Money payment. Amount: 90 USD

$order3 = new Checkout(new CardPayment());
$order3->processPayment(); // Processing Credit Card payment. Amount: 95 USD
```

### Real-World Use Cases

**1. E-commerce Checkout — Multiple Payment Methods:**

An online shop supports Cash on Delivery, Mobile Banking, Stripe, PayPal, and Crypto. Each is a separate strategy. The checkout flow never changes — new payment providers are added as new strategy classes only.

**2. Sorting Algorithms:**

```php
interface SortStrategy {
    public function sort(array $data): array;
}

class BubbleSort implements SortStrategy {
    public function sort(array $data): array {
        // bubble sort implementation...
        sort($data); // simplified
        return $data;
    }
}

class QuickSort implements SortStrategy {
    public function sort(array $data): array {
        // quick sort implementation...
        sort($data); // simplified
        return $data;
    }
}

class DataProcessor {
    public function __construct(private SortStrategy $sorter) {}

    public function process(array $data): array {
        return $this->sorter->sort($data);
    }
}

// For small data sets use BubbleSort, for large data sets use QuickSort
$smallDataProcessor = new DataProcessor(new BubbleSort());
$largeDataProcessor = new DataProcessor(new QuickSort());
```

**3. File Export (PDF, CSV, Excel):**

A reporting system needs to export reports in different formats. Each format (PDF, CSV, Excel) is a Strategy. The `ReportExporter` class stays the same, and new formats are added without touching it.

```php
interface ExportStrategy {
    public function export(array $data): string;
}

class PDFExport implements ExportStrategy {
    public function export(array $data): string {
        return "Exporting " . count($data) . " rows as PDF...";
    }
}

class CSVExport implements ExportStrategy {
    public function export(array $data): string {
        return implode(",", array_column($data, 'name'));
    }
}

class ExcelExport implements ExportStrategy {
    public function export(array $data): string {
        return "Exporting " . count($data) . " rows as Excel...";
    }
}

class ReportExporter {
    public function __construct(private ExportStrategy $strategy) {}

    public function export(array $data): string {
        return $this->strategy->export($data);
    }
}

$data = [['name' => 'Alice'], ['name' => 'Bob']];

$exporter = new ReportExporter(new CSVExport());
echo $exporter->export($data); // Alice,Bob

$exporter = new ReportExporter(new PDFExport());
echo $exporter->export($data); // Exporting 2 rows as PDF...
```

### Strategy vs switch-case vs if-else

| Approach | Adding new behaviour | Risk |
|---|---|---|
| `if/elseif/switch` | Edit existing class — risk of breaking things | High |
| **Strategy Pattern** | Add a new class — existing code untouched | Zero |

> 🌍 **Real-world analogy:** A GPS navigation app. You choose a route strategy — "Fastest", "Shortest", "Avoid Tolls", "Scenic Route." The app (context) doesn't change. Only the routing algorithm (strategy) changes. Add a new strategy like "Avoid Highways" without touching the app's navigation logic.

> 💡 **Used in Laravel:** Authentication guards use Strategy — `config/auth.php` lets you switch between `session`, `token`, or custom guards. Each guard is a strategy. Laravel's encryption and hashing drivers work the same way.

---

## 9. Facade Pattern

### What is it?

The **Facade Pattern** provides a **simple, unified interface** to a **complex subsystem** of classes. It hides the internal complexity behind one easy-to-use interface.

```
"A single door into a complex building."
```

It is a **Structural Pattern** because it changes how objects are *composed* and *accessed*.

### The Problem It Solves

Imagine starting a car. Behind the scenes there are many subsystems — oil pressure check, brake fluid check, battery check, fuel injection, ignition. Without a Facade, every caller would need to know and manually coordinate all these subsystems.

### Simple Facade — The Car Example

```php
// Complex subsystems — each does one specific thing
class CheckOilPressure {
    public function check(): void {
        echo "Oil Pressure OK.\n";
    }
}

class CheckBreakFluid {
    public function check(): void {
        echo "Brake Fluid OK.\n";
    }
}

class CheckBattery {
    public function check(): void {
        echo "Battery OK.\n";
    }
}

// ✅ Facade — hides all the complexity behind one simple start() call
class Car {
    private CheckOilPressure $oil;
    private CheckBreakFluid  $brake;
    private CheckBattery     $battery;

    public function __construct() {
        $this->oil     = new CheckOilPressure();
        $this->brake   = new CheckBreakFluid();
        $this->battery = new CheckBattery();
    }

    // Single simple interface — caller just calls start()
    public function start(): void {
        $this->oil->check();
        $this->brake->check();
        $this->battery->check();
        echo "🚗 Car Engine Started!\n";
    }
}

// Caller sees NOTHING of the complexity
$car = new Car();
$car->start();
// Oil Pressure OK.
// Brake Fluid OK.
// Battery OK.
// 🚗 Car Engine Started!
```

### Laravel-Style Facade — Static API over Instance

Laravel's Facade pattern goes further. It lets you call methods **statically** on a Facade class, but behind the scenes resolves a **real object instance** from the service container.

```php
// Base Facade using __callStatic magic method
class Facade {
    static function __callStatic(string $name, array $args): void {
        $method = strtoupper($name); // e.g., "get" → "GET"
        $path   = $args[0] ?? "/";
        echo "Sending $method to $path\n";
    }
}

// Route facade — just extends Facade, adds nothing
class Route extends Facade {
    // inherits __callStatic from Facade
}

// Usage — looks like a static call, but can be backed by an instance
Route::get("/comments");  // Sending GET to /comments
Route::post("/users");    // Sending POST to /users
Route::delete("/posts/1"); // Sending DELETE to /posts/1
```

### How `__callStatic` Works

`__callStatic` is a PHP magic method that is automatically called when you invoke a **static method that doesn't exist** on the class:

```
Route::get("/comments")
     ↓
PHP sees: get() doesn't exist statically on Route
     ↓
PHP calls: __callStatic("get", ["/comments"])
     ↓
Our Facade handles it → "Sending GET to /comments"
```

This is how Laravel's `Route::get()`, `DB::table()`, `Cache::get()`, `Mail::send()` all work!

### Real-World Facade — Full E-Commerce Order System

```php
// Complex subsystems
class InventoryService {
    public function reserve(int $productId, int $qty): void {
        echo "✅ Reserved $qty units of product #$productId\n";
    }
}

class PaymentGateway {
    public function charge(float $amount, string $method): void {
        echo "💳 Charged $$amount via $method\n";
    }
}

class InvoiceService {
    public function generate(int $orderId): void {
        echo "🧾 Invoice generated for order #$orderId\n";
    }
}

class EmailService {
    public function sendConfirmation(string $email, int $orderId): void {
        echo "📧 Confirmation email sent to $email for order #$orderId\n";
    }
}

// ✅ Order Facade — simple interface for a complex multi-step process
class OrderFacade {
    private InventoryService $inventory;
    private PaymentGateway   $payment;
    private InvoiceService   $invoice;
    private EmailService     $email;

    public function __construct() {
        $this->inventory = new InventoryService();
        $this->payment   = new PaymentGateway();
        $this->invoice   = new InvoiceService();
        $this->email     = new EmailService();
    }

    // ONE method the controller calls — caller is shielded from all subsystems
    public function placeOrder(
        int    $orderId,
        int    $productId,
        int    $qty,
        float  $amount,
        string $paymentMethod,
        string $userEmail
    ): void {
        $this->inventory->reserve($productId, $qty);
        $this->payment->charge($amount, $paymentMethod);
        $this->invoice->generate($orderId);
        $this->email->sendConfirmation($userEmail, $orderId);
        echo "🎉 Order #$orderId placed successfully!\n";
    }
}

// Controller — clean and simple, knows nothing about subsystems
$order = new OrderFacade();
$order->placeOrder(1001, 42, 2, 199.98, "Mobile Pay", "alice@example.com");
// ✅ Reserved 2 units of product #42
// 💳 Charged $199.98 via Mobile Pay
// 🧾 Invoice generated for order #1001
// 📧 Confirmation email sent to alice@example.com for order #1001
// 🎉 Order #1001 placed successfully!
```

### Facade vs Direct Subsystem Access

| | Without Facade | With Facade |
|---|---|---|
| Caller complexity | Must know all subsystems | Calls one method |
| Coupling | Tightly coupled to every subsystem | Coupled to Facade only |
| Change impact | Change any subsystem → update every caller | Change subsystem → update Facade only |
| Readability | Long, complex controller code | Short, readable controller code |

### Real-World Use Cases

| Scenario | Facade hides... |
|---|---|
| **Car start button** | Oil check, brake check, battery check, ignition sequence |
| **Hotel check-in desk** | Room assignment, keycard activation, billing setup, housekeeping notification |
| **ATM machine** | Authentication, balance check, cash dispenser, transaction logging, receipt printing |
| **Package manager (`npm install`)** | Dependency resolution, download, extraction, linking, cache management |
| **Laravel `Mail::send()`** | SMTP connection, message building, queue handling, logging |

> 🌍 **Real-world analogy:** When you press the "Order Now" button on an e-commerce site, the button is the Facade. Behind it: inventory reservation, payment processing, invoice generation, and email notifications all fire — but you only pressed one button.

> ⚠️ **Facade Pitfall:** Don't let the Facade grow into a "God class" that does everything. It should be a **thin coordinator** that delegates to specialised subsystems — not a place for business logic.

> 💡 **Used in Laravel:** `Mail::send()`, `Route::get()`, `DB::table()`, `Cache::get()`, `Storage::put()`, `Auth::check()` — every Laravel Facade is backed by a real service instance resolved from the IoC Container through `__callStatic`.

---

## 10. Provider Pattern

### What is it?

The **Provider Pattern** is a **service registration and resolution** mechanism. You **register** services (class names or instances) into a container, then **resolve** (retrieve) them by name or key anywhere in the app.

```
"Register what you have. Ask for what you need. The provider delivers it."
```

It forms the backbone of modern Dependency Injection (DI) Containers and Service Containers — used in Laravel, .NET, Angular, and many other frameworks.

### The Problem It Solves

Without a provider/container, every class that needs a `Logger` or `Database` must either:
- Create it with `new` (tight coupling), or
- Have it manually passed in everywhere (tedious wiring)

With a provider, you **register once, use anywhere**.

### The Provider Pattern from Your Code

```php
// Step 1 — Define a common interface
interface Log {
    public function write(): void;
}

// Step 2 — Concrete implementations
class Text implements Log {
    public function write(): void {
        echo "Saving to text file\n";
    }
}

class Memory implements Log {
    public function write(): void {
        echo "Saving to memory cache\n";
    }
}

// Step 3 — Services container — holds a registry of class names
class Services {
    public array $container = [];

    // Register: name → class
    public function register(string $name, string $class): void {
        $this->container[$name] = $class;
    }
}

$services = new Services();
$services->register("text",   Text::class);   // register the Text logger
$services->register("memory", Memory::class); // register the Memory logger

// Step 4 — Provider — resolves (creates) instances by name
class Provider {
    private array $services;

    public function __construct(Services $services) {
        $this->services = $services->container;
    }

    public function make(string $service): ?Log {
        if (isset($this->services[$service])) {
            return new $this->services[$service]; // instantiate on demand
        }
        return null;
    }
}

$provider = new Provider($services);

// Resolve by name — no `new Text()` or `new Memory()` in calling code
$log = $provider->make("text");
$log->write(); // Saving to text file

$log = $provider->make("memory");
$log->write(); // Saving to memory cache
```

### How the Resolution Works

```
$services->register("text", Text::class)
         ↓
container = ["text" => "Text", "memory" => "Memory"]

$provider->make("text")
         ↓
looks up "text" in container → finds "Text"
         ↓
new "Text"() → returns a new Text instance
         ↓
caller calls ->write() on it
```

### Enhanced Provider — With Interface Type Checking

```php
interface Log {
    public function write(string $message): void;
}

class FileLog implements Log {
    public function write(string $message): void {
        echo "[FILE]   " . date('H:i:s') . " — $message\n";
        // In real code: file_put_contents('app.log', $message . PHP_EOL, FILE_APPEND);
    }
}

class DatabaseLog implements Log {
    public function write(string $message): void {
        echo "[DB]     " . date('H:i:s') . " — $message\n";
        // In real code: INSERT INTO logs (message, created_at) VALUES (...)
    }
}

class SlackLog implements Log {
    public function write(string $message): void {
        echo "[SLACK]  " . date('H:i:s') . " — $message\n";
        // In real code: send HTTP POST to Slack webhook URL
    }
}

class ServiceContainer {
    private array $bindings = [];

    public function bind(string $name, string $class): void {
        $this->bindings[$name] = $class;
    }

    public function make(string $name): object {
        if (!isset($this->bindings[$name])) {
            throw new \RuntimeException("Service '$name' not registered.");
        }
        return new $this->bindings[$name];
    }

    public function has(string $name): bool {
        return isset($this->bindings[$name]);
    }
}

// Bootstrap — register services once, at app startup
$container = new ServiceContainer();
$container->bind('log.file',     FileLog::class);
$container->bind('log.database', DatabaseLog::class);
$container->bind('log.slack',    SlackLog::class);

// Usage anywhere in the app — just ask by name
/** @var Log $logger */
$logger = $container->make('log.file');
$logger->write("User logged in");   // [FILE]  12:00:01 — User logged in

$logger = $container->make('log.database');
$logger->write("Order #1001 created"); // [DB]  12:00:01 — Order #1001 created

$logger = $container->make('log.slack');
$logger->write("Critical error!"); // [SLACK]  12:00:01 — Critical error!

// Swap the logger in one place — all callers automatically use the new one
```

### Real-World Use Cases

**1. Switching environments (dev vs production):**

```php
// In development
$container->bind('mailer', MailhogDriver::class); // catches emails locally

// In production
$container->bind('mailer', SmtpDriver::class);    // sends real emails

// Application code never changes — it just calls $container->make('mailer')
```

**2. A/B Testing Pricing Strategies:**

```php
$container->bind('pricing', $useNewAlgorithm ? NewPricingStrategy::class : OldPricingStrategy::class);

$pricingService = $container->make('pricing');
echo $pricingService->calculate($cart); // uses whichever is registered
```

**3. Plugin / Driver Architecture:**

CMS platforms use a Provider/Container so that third-party plugins can register their own services and override existing ones without touching core code.

### Provider Pattern vs Other Patterns

| Pattern | Purpose | Creates objects |
|---|---|---|
| **Factory** | Creates objects of *one family* | Yes — specific type |
| **Builder** | Builds *one complex object* step by step | Yes — step by step |
| **Strategy** | Selects *an algorithm* at runtime | No — just uses object |
| **Provider** | *Registers and resolves* any service by name | Yes — on demand from registry |

### Key Concepts Summary

```
Registering    →  $container->bind('key', ClassName::class)
Resolving      →  $container->make('key')  → new ClassName()
Dependency Inversion → callers depend on the container/interface, not concrete classes
```

> 🌍 **Real-world analogy:** A hotel concierge desk. You tell the hotel "we have a restaurant, a gym, and a spa" (register). A guest just says "I want the restaurant" (resolve). The concierge (provider) knows where everything is and delivers it. Guests never need to know the restaurant's address.

> 💡 **This is the foundation of Laravel's IoC Container.** In Laravel:
> - `app()->bind('log', FileLog::class)` — register
> - `app()->make('log')` — resolve  
> - `app()->singleton('db', Database::class)` — register as a singleton (resolved once, reused)
> - Every time you type-hint in a Laravel controller constructor, Laravel's container automatically resolves and injects the dependency for you.

---

## 11. Patterns Used in Laravel

Once you understand these patterns, you'll recognise them everywhere in Laravel:

| Pattern | Laravel Example |
|---|---|
| **Singleton** | `App::singleton()`, Facades (one instance behind the scenes) |
| **Builder** | Query Builder `DB::table()->where()->get()`, Mail, HTTP Client |
| **Factory** | Database Factories (`User::factory()->create()`), `Auth::guard()` |
| **Factory Method** | `Cache::driver()`, `Mail::mailer()`, `Storage::disk()` |
| **Strategy** | Authentication guards (`session`, `token`, OAuth), Encryption drivers, Hashing algorithms |
| **Facade** | `Route::get()`, `DB::table()`, `Cache::get()`, `Mail::send()`, `Auth::check()`, `Storage::put()` |
| **Provider** | Service Container (`app()->bind()`, `app()->make()`), Service Providers (`AppServiceProvider`, etc.) |
| **Observer** | Eloquent model events (`creating`, `updated`, `deleted`) |
| **Decorator** | Middleware pipeline — wraps request/response handling |

---

## 12. References

- [Refactoring Guru — Design Patterns](https://refactoring.guru/design-patterns)
- [Refactoring Guru — Refactoring](https://refactoring.guru/refactoring)
- [Source Making — Design Patterns](https://sourcemaking.com/design_patterns)
- [OO Design — Design Principles](https://www.oodesign.com/design-principles/)
- [OO Design — Design Patterns](https://www.oodesign.com/)
- [GeeksForGeeks — Software Design Patterns](https://www.geeksforgeeks.org/system-design/software-design-patterns/)
- [Kamran Ahmed — Design Patterns for Humans](https://github.com/kamranahmedse/design-patterns-for-humans)
- [ByteByteGo — Design Patterns Cheat Sheet](https://blog.bytebytego.com/p/ep17-design-patterns-cheat-sheet)
- [Martin Fowler — Inversion of Control & Dependency Injection](https://martinfowler.com/articles/injection.html)
- [James Shore — Dependency Injection Demystified](https://www.jamesshore.com/v2/blog/2006/dependency-injection-demystified)
- [GoF Book (PDF)](https://github.com/deepakkum21/Books/blob/master/Design%20Patterns%20-%20Elements%20of%20Reusable%20Object%20Oriented%20Software%20-%20GOF.pdf)

