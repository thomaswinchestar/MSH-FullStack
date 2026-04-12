# Week 19 — Advanced PHP OOP: Inheritance, Abstract Classes, Interfaces, Traits & Magic Methods

---

## Table of Contents

1. [Inheritance](#1-inheritance)
   - [What is Inheritance?](#11-what-is-inheritance)
   - [Basic Inheritance](#12-basic-inheritance)
   - [The `protected` Access Modifier](#13-the-protected-access-modifier)
   - [Overriding Methods](#14-overriding-methods)
   - [Calling the Parent Constructor — `parent::`](#15-calling-the-parent-constructor--parent)
   - [Multilevel / Chained Inheritance](#16-multilevel--chained-inheritance)
2. [Multiple Inheritance — Why PHP Doesn't Support It](#2-multiple-inheritance--why-php-doesnt-support-it)
3. [The `final` Keyword](#3-the-final-keyword)
   - [Final Class](#31-final-class)
   - [Final Method](#32-final-method)
4. [Abstract Classes](#4-abstract-classes)
   - [What is an Abstract Class?](#41-what-is-an-abstract-class)
   - [Abstract Methods](#42-abstract-methods)
   - [Real-World Use Cases](#43-real-world-use-cases)
5. [Interfaces](#5-interfaces)
   - [What is an Interface?](#51-what-is-an-interface)
   - [Implementing Multiple Interfaces](#52-implementing-multiple-interfaces)
   - [Using Interfaces for Type Safety](#53-using-interfaces-for-type-safety)
6. [Abstract Class vs Interface](#6-abstract-class-vs-interface)
7. [Traits](#7-traits)
   - [What is a Trait?](#71-what-is-a-trait)
   - [Using Multiple Traits](#72-using-multiple-traits)
   - [Real-World Traits](#73-real-world-traits)
8. [Class Constants](#8-class-constants)
9. [Magic Methods](#9-magic-methods)
   - [`__call()` and `__callStatic()`](#91-__call-and-__callstatic)
   - [`__invoke()`](#92-__invoke)
   - [`__set()` and `__get()`](#93-__set-and-__get)
   - [`__toString()`](#94-__tostring)
   - [Other Useful Magic Methods](#95-other-useful-magic-methods)
10. [Real-World Scenarios](#10-real-world-scenarios)
11. [References](#11-references)

---

## 1. Inheritance

### 1.1 What is Inheritance?

**Inheritance** is one of the four core pillars of OOP. It lets a **child class** (subclass) **inherit** all the `public` and `protected` properties and methods of a **parent class** (superclass), then:

- **Add** its own new properties and methods.
- **Override** inherited methods to give them a different implementation.

> 🌍 **Real-world analogy:**  
> Think of a company hierarchy. A **Manager** is a **type of Employee** — they share base attributes (name, salary, ID), but a Manager also has extra responsibilities like managing a team. Inheritance models this "is-a" relationship in code.

```
Animal (Parent)
├── Dog   (Child — inherits Animal, adds bark())
├── Cat   (Child — inherits Animal, adds purr())
└── Bird  (Child — inherits Animal, overrides move())
```

**Why Inheritance?**

| Problem without Inheritance | Inheritance Solution |
|---|---|
| Duplicating the same code in many classes | Write common logic once in a parent class |
| Hard to maintain (change one thing, update everywhere) | Change the parent — all children benefit |
| No logical relationship between similar classes | Model "is-a" relationships naturally |

---

### 1.2 Basic Inheritance

Use the `extends` keyword to create a child class:

```php
class Animal {
    protected $name;  // protected — accessible to this class AND children

    public function __construct($name) {
        $this->name = $name;
    }

    public function run() {
        echo "$this->name is running...";
    }
}

class Dog extends Animal {
    // Inherits __construct() and run() from Animal
    // Adds its own method
    public function bark() {
        echo "$this->name: Woof... Woof...";
    }
}

$bobby = new Dog("Bobby");
$bobby->run();  // Inherited from Animal → "Bobby is running..."
$bobby->bark(); // Dog's own method  → "Bobby: Woof... Woof..."
```

---

### 1.3 The `protected` Access Modifier

This is the access modifier that makes inheritance truly useful:

| Modifier    | Same Class | Child Class | Outside Code |
|-------------|:----------:|:-----------:|:------------:|
| `public`    | ✅         | ✅          | ✅           |
| `protected` | ✅         | ✅          | ❌           |
| `private`   | ✅         | ❌          | ❌           |

```php
class Animal {
    private $name;    // ❌ Dog CANNOT access this
    protected $name;  // ✅ Dog CAN access this
}
```

> ⚠️ If you declare a property as `private` in the parent, child classes **cannot access it directly** — they must use public/protected methods (getters) to read it. Use `protected` when you *intend* for children to access a property.

> 💡 **Rule of thumb:** Default to `private`. Promote to `protected` only when children genuinely need direct access. This keeps your inheritance hierarchy clean.

---

### 1.4 Overriding Methods

A child class can **replace** (override) an inherited method by redefining it with the same name:

```php
class Animal {
    protected $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function move(): string {
        return "$this->name moves.";
    }
}

class Bird extends Animal {
    // Override the parent's move() with a specific implementation
    public function move(): string {
        return "$this->name flies through the sky!";
    }
}

class Fish extends Animal {
    public function move(): string {
        return "$this->name swims through the water!";
    }
}

$bird = new Bird("Eagle");
$fish = new Fish("Nemo");

echo $bird->move(); // "Eagle flies through the sky!"
echo $fish->move(); // "Nemo swims through the water!"
```

> 💡 This is also the foundation of **Polymorphism** — the same method name (`move()`) behaves differently depending on which class's object calls it.

---

### 1.5 Calling the Parent Constructor — `parent::`

When a child class has its own `__construct()`, the parent's constructor is **NOT called automatically**. You must explicitly call it using `parent::__construct()`.

```php
class Animal {
    protected $name;

    public function __construct($name) {
        $this->name = $name;
    }
}

class Dog extends Animal {
    private $color;

    public function __construct($name, $color) {
        parent::__construct($name); // ✅ Call parent constructor first
        $this->color = $color;      // Then set Dog-specific properties
    }

    public function profile() {
        echo "$this->name has $this->color color";
    }
}

$bobby = new Dog("Bobby", "brown");
$bobby->profile(); // "Bobby has brown color"
```

> ⚠️ **Always call `parent::__construct()`** in a child constructor if the parent has one. If you don't, the parent's setup code won't run, which can leave the object in a broken state.

You can also use `parent::` to call any overridden parent method (not just the constructor):

```php
class Dog extends Animal {
    public function describe(): string {
        $base = parent::describe(); // call parent's version first
        return $base . ", and I am a dog!";
    }
}
```

---

### 1.6 Multilevel / Chained Inheritance

PHP supports **chained (multilevel) inheritance** — a class can extend another class that itself extends another class:

```php
class Animal {
    static function info() {
        echo "Animal Class";
    }
}

class Dog extends Animal {
    // inherits info() from Animal
}

class Fox extends Dog {
    // inherits info() from Dog (which got it from Animal)
}

Fox::info(); // "Animal Class" — inherited down the chain
```

```
Animal
  └── Dog
        └── Fox  ← inherits from both Animal and Dog
```

> ⚠️ Deep inheritance chains (more than 3 levels) often become hard to understand and maintain. Prefer **composition** (using other objects as properties) over deep inheritance when possible.

---

## 2. Multiple Inheritance — Why PHP Doesn't Support It

**Multiple inheritance** means a class extending **more than one** parent class simultaneously. PHP **does NOT support** this:

```php
// ❌ This is NOT valid PHP
class Dog extends Animal, Mammal, Domestic {
    //
}
```

**Why? — The Diamond Problem:**

```
          Animal
         /      \
      Mammal   Reptile
         \      /
           ???
```

If both `Mammal` and `Reptile` have a method called `breathe()`, which version does the child class inherit? This ambiguity is the "Diamond Problem" — it causes confusion and bugs.

**PHP's solution:** Use **Traits** (for sharing code) and **Interfaces** (for sharing contracts). You'll see both in the sections below.

---

## 3. The `final` Keyword

### 3.1 Final Class

A `final` class **cannot be extended** — no other class can inherit from it.

```php
final class PaymentGateway {
    public function charge(float $amount): string {
        return "Charged: \$$amount";
    }
}

// ❌ This will throw a Fatal Error
class EvilPaymentGateway extends PaymentGateway {
    public function charge(float $amount): string {
        return "Stealing \$$amount"; // prevented!
    }
}
```

> 🌍 **Real-world use:**
> - Security-critical classes (payment processors, authentication handlers) — you don't want anyone accidentally overriding the logic.
> - Utility classes that should never be subclassed (e.g., a `Uuid` generator, a `Hash` helper).

---

### 3.2 Final Method

You can also mark individual **methods** as `final`. The class itself can still be extended, but the final method **cannot be overridden** in a child class:

```php
class Animal {
    final public function breathe(): void {
        echo "Breathing oxygen..."; // logic locked — children can't change this
    }

    public function move(): void {
        echo "Moving..."; // children CAN override this
    }
}

class Dog extends Animal {
    // ❌ Cannot override breathe() — it's final
    // ✅ CAN override move()
    public function move(): void {
        echo "Running on four legs!";
    }
}
```

**`final` summary:**

| | Purpose | Can still be extended? | Can method be overridden? |
|---|---|:---:|:---:|
| `final class` | Prevent the class from being extended at all | ❌ | — |
| `final method` | Lock one specific method while allowing the class to be extended | ✅ | ❌ (for that method) |

---

## 4. Abstract Classes

### 4.1 What is an Abstract Class?

An **abstract class** is a **half-finished blueprint** that:

- **Cannot be instantiated** directly (you can't do `new AbstractClass()`).
- Can contain **concrete methods** (fully implemented — inherited by children).
- Can contain **abstract methods** (no body — each child class **must** implement them).

Think of it like a **template contract** — the parent says "here's some shared code, and here are some things you **must** define yourself."

```php
abstract class Animal {
    public abstract function talk(); // Abstract — NO body, MUST be implemented by children

    public function run() {          // Concrete — HAS a body, shared by all children
        echo "Running....";
    }
}

// ❌ Cannot do this:
// $a = new Animal(); // Fatal error: Cannot instantiate abstract class

// ✅ Must extend it:
class Dog extends Animal {
    public function talk() {
        echo "Woof!";
    }
}

$dog = new Dog();
$dog->talk(); // "Woof!" — Dog's own implementation
$dog->run();  // "Running..." — inherited from Animal
```

> ⚠️ If a child class **does not implement** all abstract methods, it **must also be declared abstract** — otherwise PHP throws a fatal error.

---

### 4.2 Abstract Methods

An abstract method:
- Is declared with the `abstract` keyword.
- Has **no body** (no `{ }`).
- **Forces every concrete child class** to provide its own implementation.

```php
abstract class Shape {
    abstract public function area(): float;    // every shape must define area()
    abstract public function perimeter(): float; // every shape must define perimeter()

    // Shared concrete method
    public function describe(): string {
        return get_class($this) . " → Area: " . round($this->area(), 2)
            . ", Perimeter: " . round($this->perimeter(), 2);
    }
}

class Circle extends Shape {
    public function __construct(private float $radius) {}

    public function area(): float {
        return M_PI * $this->radius ** 2;
    }

    public function perimeter(): float {
        return 2 * M_PI * $this->radius;
    }
}

class Rectangle extends Shape {
    public function __construct(
        private float $width,
        private float $height
    ) {}

    public function area(): float {
        return $this->width * $this->height;
    }

    public function perimeter(): float {
        return 2 * ($this->width + $this->height);
    }
}

$shapes = [new Circle(5), new Rectangle(4, 6)];

foreach ($shapes as $shape) {
    echo $shape->describe() . "\n";
}
// Circle → Area: 78.54, Perimeter: 31.42
// Rectangle → Area: 24, Perimeter: 20
```

---

### 4.3 Real-World Use Cases

**Scenario — Payment Methods in an E-Commerce App:**

```php
abstract class PaymentMethod {
    abstract public function charge(float $amount): string;
    abstract public function refund(float $amount): string;

    // Shared logging — all payment methods use this
    protected function log(string $message): void {
        echo "[Payment Log] " . date('Y-m-d H:i:s') . " — $message\n";
    }
}

class CreditCard extends PaymentMethod {
    public function __construct(private string $cardNumber) {}

    public function charge(float $amount): string {
        $this->log("Credit card charge: \$$amount");
        return "Charged \$$amount to card ending in " . substr($this->cardNumber, -4);
    }

    public function refund(float $amount): string {
        $this->log("Credit card refund: \$$amount");
        return "Refunded \$$amount to card ending in " . substr($this->cardNumber, -4);
    }
}

class PayPal extends PaymentMethod {
    public function __construct(private string $email) {}

    public function charge(float $amount): string {
        $this->log("PayPal charge: \$$amount");
        return "Charged \$$amount via PayPal account $this->email";
    }

    public function refund(float $amount): string {
        $this->log("PayPal refund: \$$amount");
        return "Refunded \$$amount via PayPal to $this->email";
    }
}

$payment = new CreditCard("4111111111111234");
echo $payment->charge(99.99);
// [Payment Log] 2025-... — Credit card charge: $99.99
// Charged $99.99 to card ending in 1234
```

---

## 5. Interfaces

### 5.1 What is an Interface?

An **interface** is a **pure contract** — it defines *what methods a class must have*, but provides absolutely **no implementation**. It's the strictest form of abstraction.

- All methods in an interface are automatically `public` and `abstract`.
- A class uses `implements` (not `extends`) to honour an interface.
- A class can implement **multiple interfaces**.

```php
interface Animal {
    public function move();
}

class Dog implements Animal {
    public function move() {
        echo "The Dog is running";
    }
}

class Fish implements Animal {
    public function move() {
        echo "The Fish is swimming";
    }
}

// Type-safe function — accepts ANY object that implements Animal
function app(Animal $obj) {
    $obj->move();
}

app(new Dog);   // "The Dog is running"
echo "<br>";
app(new Fish);  // "The Fish is swimming"
```

> 💡 Without the interface, if you type-hint `function app(Dog $obj)`, you can't pass a `Fish`. The interface gives both `Dog` and `Fish` a **shared type** so they can be used interchangeably. This is called **Polymorphism**.

---

### 5.2 Implementing Multiple Interfaces

A class can implement **more than one interface** — this is how PHP achieves the flexibility of multiple inheritance without the Diamond Problem:

```php
interface Animal {
    public function move();
}

interface Livestock {
    public function isFriendly(): bool;
}

class Cow implements Animal, Livestock {
    public function move() {
        echo "The Cow is walking";
    }

    public function isFriendly(): bool {
        return true;
    }
}

$cow = new Cow();
$cow->move();                                          // "The Cow is walking"
echo $cow->isFriendly() ? "Friendly" : "Not Friendly"; // "Friendly"
```

> 💡 Each interface represents a **capability** or **role**. `Animal` = "can move", `Livestock` = "is farm-friendly". A `Cow` is both — so it implements both contracts.

---

### 5.3 Using Interfaces for Type Safety

Interfaces are especially powerful when used as **type hints** in function/method parameters:

```php
interface Logger {
    public function log(string $message): void;
}

class FileLogger implements Logger {
    public function log(string $message): void {
        // writes to a log file
        file_put_contents('app.log', $message . PHP_EOL, FILE_APPEND);
    }
}

class DatabaseLogger implements Logger {
    public function log(string $message): void {
        // saves to a database table
        echo "[DB] Saving log: $message";
    }
}

class ConsoleLogger implements Logger {
    public function log(string $message): void {
        echo "[CONSOLE] $message\n";
    }
}

// This function works with ANY logger — just swap the implementation!
function processOrder(int $orderId, Logger $logger): void {
    $logger->log("Processing order #$orderId");
    // ... order logic ...
    $logger->log("Order #$orderId completed.");
}

processOrder(42, new ConsoleLogger());
// [CONSOLE] Processing order #42
// [CONSOLE] Order #42 completed.
```

> 🌍 **Real-world scenario:** During development, use `ConsoleLogger`. In production, swap to `FileLogger` or `DatabaseLogger` — **without changing `processOrder()` at all**. This is the **Dependency Inversion Principle** — code against abstractions (interfaces), not concrete classes.

---

## 6. Abstract Class vs Interface

This is one of the most common OOP questions. Here is a clear comparison:

| Feature | Interface | Abstract Class |
|---|---|---|
| Method bodies? | ❌ No (pure declarations) | ✅ Concrete + Abstract |
| Properties? | ❌ No instance properties | ✅ Yes |
| Constructor? | ❌ No | ✅ Yes |
| Can be instantiated? | ❌ No | ❌ No |
| Keyword | `implements` | `extends` |
| Multiple? | ✅ A class can implement many | ❌ Only one parent class |
| Access modifiers | Only `public` | Any (`public`, `protected`, `private`) |
| Use when | Unrelated classes need the same contract | Related classes share code + structure |

**Decision guide:**

```
Does the child class IS-A version of the parent?
    YES → Think about Abstract Class (shared code + required overrides)

Do multiple unrelated classes need to honour the same contract?
    YES → Use Interface

Do you need to share code across unrelated classes WITHOUT inheritance?
    YES → Use Traits (next section)
```

**Side-by-side example:**

```php
// Interface — contract only, no code
interface Printable {
    public function print(): void;
}

// Abstract Class — shared code + required method
abstract class Document {
    protected string $content;

    public function __construct(string $content) {
        $this->content = $content;
    }

    // Shared concrete method
    public function getWordCount(): int {
        return str_word_count($this->content);
    }

    // Must be implemented by each subclass
    abstract public function getTitle(): string;
}

// A PDF is a Document (shares code) AND is Printable (honours contract)
class PdfDocument extends Document implements Printable {
    public function __construct(
        string $content,
        private string $title
    ) {
        parent::__construct($content);
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function print(): void {
        echo "Printing PDF: {$this->title} ({$this->getWordCount()} words)";
    }
}

$pdf = new PdfDocument("Hello World, this is a PDF document.", "My Report");
$pdf->print(); // "Printing PDF: My Report (7 words)"
```

---

## 7. Traits

### 7.1 What is a Trait?

A **Trait** is a mechanism for **code reuse** in PHP that solves a specific problem: *"I want to share methods across classes that don't share an inheritance hierarchy."*

- A trait is like a **copy-paste of methods** — PHP literally inserts the trait's code into the class.
- A class can use **multiple traits** (unlike `extends`, which only allows one parent).
- Traits are defined with the `trait` keyword and included with `use`.

**The problem traits solve:**

```php
// ❌ PHP only allows single inheritance:
class Calculator extends Area  // can't also extend Math!
```

```php
// ✅ Solution: Use traits instead of inheritance for code sharing
trait Math {
    public function add($a, $b) {
        echo $a + $b;
    }
}

trait Area {
    private $PI = 3.14;

    public function circle($r) {
        echo $this->PI * $r * $r;
    }
}

class Calculator {
    use Math, Area; // use MULTIPLE traits — no Diamond Problem!
}

$calc = new Calculator();
$calc->add(10, 20);    // 30
echo "<br>";
$calc->circle(5);      // 78.5
```

---

### 7.2 Using Multiple Traits

```php
trait Greetable {
    public function greet(): string {
        return "Hello, I am {$this->name}!";
    }
}

trait Serializable {
    public function toJson(): string {
        return json_encode(get_object_vars($this));
    }
}

trait Timestampable {
    private string $createdAt;

    public function setCreatedAt(): void {
        $this->createdAt = date('Y-m-d H:i:s');
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }
}

class User {
    use Greetable, Serializable, Timestampable;

    public function __construct(public string $name, public string $email) {
        $this->setCreatedAt();
    }
}

$user = new User("Alice", "alice@example.com");
echo $user->greet();           // "Hello, I am Alice!"
echo $user->toJson();          // {"name":"Alice","email":"alice@example.com","createdAt":"..."}
echo $user->getCreatedAt();    // "2025-04-12 10:30:00"
```

---

### 7.3 Real-World Traits

**Scenario — Laravel-style soft deletes:**

```php
trait SoftDeletable {
    private ?string $deletedAt = null;

    public function delete(): void {
        $this->deletedAt = date('Y-m-d H:i:s');
        echo get_class($this) . " soft-deleted at {$this->deletedAt}\n";
    }

    public function restore(): void {
        $this->deletedAt = null;
        echo get_class($this) . " restored.\n";
    }

    public function isDeleted(): bool {
        return $this->deletedAt !== null;
    }
}

class Post {
    use SoftDeletable;

    public function __construct(public string $title) {}
}

class Comment {
    use SoftDeletable;

    public function __construct(public string $body) {}
}

$post = new Post("Hello World");
$post->delete();                                               // "Post soft-deleted at 2025-..."
echo $post->isDeleted() ? "Post is deleted" : "Post is live"; // "Post is deleted"
$post->restore();                                              // "Post restored."

$comment = new Comment("Nice article!");
$comment->delete(); // Works the same way — same trait, different class
```

> 💡 **Traits vs Inheritance vs Interface:**
> - **Inheritance** (`extends`) = "is-a" relationship + code sharing (one parent only)
> - **Interface** (`implements`) = "can-do" contract, no code (multiple allowed)
> - **Trait** (`use`) = code sharing across unrelated classes (no relationship required, multiple allowed)

---

## 8. Class Constants

A **class constant** is a value defined with `const` that belongs to the **class itself** (not instances) and **never changes**.

```php
class Area {
    const PI = 3.14;

    public function circle($r) {
        echo self::PI * $r * $r; // use self:: inside the class
    }
}

echo Area::PI;          // 3.14 — access on the class directly
$area = new Area();
$area->circle(5);       // 78.5
```

> 💡 **`Area::class`** — a special constant available on every class. Returns the fully-qualified class name as a string:
> ```php
> echo Area::class; // "Area"
> // Useful for: logging, factory patterns, service containers
> ```

**`const` vs `static` vs regular property:**

| | `const` | `static $prop` | `$prop` |
|---|---|---|---|
| Belongs to | Class | Class | Object instance |
| Value changes? | ❌ Never | ✅ Can change | ✅ Can change |
| Access | `Class::CONST` | `Class::$prop` | `$obj->prop` |
| `$` symbol | No | Yes | Yes |

**Real-world — Status codes:**

```php
class OrderStatus {
    const PENDING   = 'pending';
    const PAID      = 'paid';
    const SHIPPED   = 'shipped';
    const DELIVERED = 'delivered';
    const CANCELLED = 'cancelled';
}

$status = OrderStatus::PAID;

if ($status === OrderStatus::PAID) {
    echo "Payment confirmed! Preparing shipment.";
}
```

---

## 9. Magic Methods

**Magic methods** are special PHP methods that start with double underscores (`__`). PHP calls them automatically when certain actions happen — you never call them directly.

> ✅ We covered `__construct()` and `__destruct()` in Week 18. Here we go deeper into the rest.

---

### 9.1 `__call()` and `__callStatic()`

**`__call($name, $arguments)`** is triggered when you call a **non-existent instance method**.  
**`__callStatic($name, $arguments)`** is triggered when you call a **non-existent static method**.

```php
class Math {
    public function __call($name, $arguments) {
        echo "Method $name doesn't exist\n";
        // $name      = the method name that was called
        // $arguments = array of arguments passed
    }

    static function __callStatic($name, $arguments) {
        echo "Static Method $name doesn't exist\n";
    }
}

$obj = new Math();
$obj->add(1, 2);    // "Method add doesn't exist"
Math::multiply(3);  // "Static Method multiply doesn't exist"
```

**Real-world use — Dynamic method routing (like Laravel's magic methods):**

```php
class QueryBuilder {
    private array $conditions = [];

    // Enables: $query->whereId(5), $query->whereName("Alice"), etc.
    public function __call(string $name, array $args): static {
        if (str_starts_with($name, 'where')) {
            $column = strtolower(substr($name, 5)); // "whereId" → "id"
            $this->conditions[] = "$column = '{$args[0]}'";
            return $this;
        }
        throw new \BadMethodCallException("Method $name does not exist.");
    }

    public function build(): string {
        $where = implode(' AND ', $this->conditions);
        return "SELECT * FROM table WHERE $where";
    }
}

$query = new QueryBuilder();
echo $query->whereId(5)->whereName("Alice")->build();
// "SELECT * FROM table WHERE id = '5' AND name = 'Alice'"
```

---

### 9.2 `__invoke()`

**`__invoke()`** is called when you try to **call an object as if it were a function** (using `$obj()`).

```php
class Math {
    public function __invoke() {
        echo "This is not a function — but it is now!";
    }
}

$obj = new Math();
$obj(); // Calls __invoke() → "This is not a function — but it is now!"
```

**Real-world use — Callable objects (like middleware handlers):**

```php
class MultiplyBy {
    public function __construct(private int $factor) {}

    public function __invoke(int $value): int {
        return $value * $this->factor;
    }
}

$triple   = new MultiplyBy(3);
$double   = new MultiplyBy(2);

echo $triple(5);  // 15
echo $double(10); // 20

// Can be passed anywhere a callable is expected:
$numbers  = [1, 2, 3, 4, 5];
$tripled  = array_map($triple, $numbers);
print_r($tripled); // [3, 6, 9, 12, 15]
```

> 💡 `__invoke()` makes objects **callable** — they can be passed to `array_map()`, `usort()`, `call_user_func()`, and any function that accepts a `callable`. This is great for building configurable, reusable transformers and validators.

---

### 9.3 `__set()` and `__get()`

**`__get($name)`** is called when you try to **read** an inaccessible (private/undefined) property.  
**`__set($name, $value)`** is called when you try to **write** an inaccessible property.

```php
class Math {
    private $PI = 3.14;

    public function __get($name) {
        echo "Cannot get $name\n";
        // $name = the property name that was accessed
    }

    public function __set($name, $value) {
        echo "Cannot set $name with $value\n";
        // $name  = the property name
        // $value = the value being assigned
    }
}

$obj = new Math();
echo $obj->PI;     // Cannot get PI   (PI is private — __get triggered)
$obj->PI = 3.142;  // Cannot set PI with 3.142 (__set triggered)
```

**Real-world use — Dynamic properties (like Eloquent models in Laravel):**

```php
class DynamicModel {
    private array $data = [];

    // Allow setting any "column" as if it's a property
    public function __set(string $name, mixed $value): void {
        $this->data[$name] = $value;
    }

    // Allow reading any "column" as if it's a property
    public function __get(string $name): mixed {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        throw new \RuntimeException("Property '$name' does not exist.");
    }

    // Check if a "column" is set
    public function __isset(string $name): bool {
        return isset($this->data[$name]);
    }
}

$user = new DynamicModel();
$user->name  = "Alice";        // triggers __set
$user->email = "alice@example.com"; // triggers __set

echo $user->name;              // "Alice"   — triggers __get
echo $user->email;             // "alice@example.com" — triggers __get
echo isset($user->name) ? "Set" : "Not set"; // "Set" — triggers __isset
```

---

### 9.4 `__toString()`

**`__toString()`** is called when you **use an object as a string** (e.g., `echo $obj` or concatenation). Without it, PHP throws a fatal error when you try to use an object as a string.

```php
class Math {
    private $PI = 3.14;

    public function __toString() {
        return "PI = $this->PI";  // must return a string
    }
}

$obj = new Math;
echo $obj;         // "PI = 3.14"  — __toString() is called automatically
$str = "Value: " . $obj; // also triggers __toString()
echo $str;         // "Value: PI = 3.14"
```

**Real-world use — Friendly object output:**

```php
class User {
    public function __construct(
        private string $name,
        private string $email,
        private int    $age
    ) {}

    public function __toString(): string {
        return "User({$this->name}, {$this->email}, age {$this->age})";
    }
}

class Money {
    public function __construct(
        private float  $amount,
        private string $currency = 'USD'
    ) {}

    public function __toString(): string {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }
}

$user  = new User("Alice", "alice@example.com", 28);
$price = new Money(1999.9);

echo $user;   // "User(Alice, alice@example.com, age 28)"
echo $price;  // "1,999.90 USD"

// Very useful in logs, templates, debug output:
echo "Checkout — Product price: $price, Buyer: $user";
// "Checkout — Product price: 1,999.90 USD, Buyer: User(Alice, alice@example.com, age 28)"
```

---

### 9.5 Other Useful Magic Methods

PHP has **17 magic methods** in total. Here are the most useful ones beyond what we've covered:

#### `__destruct()`

Called **automatically when an object is destroyed** — either when the script ends, when the variable is unset, or when the object goes out of scope.

```php
class DatabaseConnection {
    private $connection;

    public function __construct(string $dsn) {
        $this->connection = "Connected to $dsn"; // normally a real PDO connection
        echo "DB connected.\n";
    }

    public function __destruct() {
        $this->connection = null; // close the connection automatically
        echo "DB connection closed.\n";
    }
}

$db = new DatabaseConnection("mysql:host=localhost;dbname=shop");
// ... use $db ...
// When script ends (or $db goes out of scope):
// "DB connection closed." — automatically!
```

> 💡 `__destruct()` is great for automatic **resource cleanup** — closing file handles, DB connections, releasing memory.

---

#### `__isset()` and `__unset()`

- **`__isset($name)`** — triggered when `isset($obj->property)` or `empty($obj->property)` is called on an inaccessible property.
- **`__unset($name)`** — triggered when `unset($obj->property)` is called on an inaccessible property.

```php
class DataStore {
    private array $store = [];

    public function __set($name, $value)  { $this->store[$name] = $value; }
    public function __get($name)          { return $this->store[$name] ?? null; }
    public function __isset($name): bool  { return isset($this->store[$name]); }
    public function __unset($name): void  { unset($this->store[$name]); }
}

$s = new DataStore();
$s->name = "Alice";
echo isset($s->name) ? "Set"     : "Not Set"; // "Set"
unset($s->name);
echo isset($s->name) ? "Still Set" : "Removed"; // "Removed"
```

---

#### `__clone()`

Called when an object is **cloned** with the `clone` keyword. Use it to do a **deep copy** of nested objects (the default clone is a **shallow copy**).

```php
class Address {
    public function __construct(public string $city) {}
}

class User {
    public Address $address;

    public function __construct(string $city) {
        $this->address = new Address($city);
    }

    // Deep clone — clone the nested Address object too
    public function __clone() {
        $this->address = clone $this->address;
    }
}

$alice = new User("Yangon");
$bob   = clone $alice; // triggers __clone()

$bob->address->city = "Mandalay"; // change Bob's city

echo $alice->address->city; // "Yangon" — Alice unaffected (deep copy!)
echo $bob->address->city;   // "Mandalay"
```

> ⚠️ Without `__clone()`, `$alice->address` and `$bob->address` would point to the **same** `Address` object — changing Bob's city would also change Alice's!

---

#### `__debugInfo()`

Controls what is shown when `var_dump($obj)` is called — useful for hiding sensitive data:

```php
class User {
    public function __construct(
        private string $name,
        private string $password,
        private string $email
    ) {}

    public function __debugInfo(): array {
        return [
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => '*** HIDDEN ***', // never expose raw passwords in debug!
        ];
    }
}

$user = new User("Alice", "superSecret123!", "alice@example.com");
var_dump($user);
// object(User)#1 (3) {
//   ["name"] => "Alice"
//   ["email"] => "alice@example.com"
//   ["password"] => "*** HIDDEN ***"
// }
```

---

**Complete Magic Method Reference:**

| Magic Method | Triggered When |
|---|---|
| `__construct()` | `new ClassName()` is called |
| `__destruct()` | Object is destroyed / script ends |
| `__get($name)` | Reading inaccessible property |
| `__set($name, $val)` | Writing to inaccessible property |
| `__isset($name)` | `isset()` / `empty()` on inaccessible property |
| `__unset($name)` | `unset()` on inaccessible property |
| `__call($name, $args)` | Calling non-existent instance method |
| `__callStatic($name, $args)` | Calling non-existent static method |
| `__toString()` | Object used as a string |
| `__invoke()` | Object used as a function `$obj()` |
| `__clone()` | `clone $obj` is called |
| `__debugInfo()` | `var_dump($obj)` is called |
| `__sleep()` | Object is about to be serialised |
| `__wakeup()` | Object is unserialised |
| `__serialize()` | `serialize($obj)` (PHP 7.4+) |
| `__unserialize($data)` | `unserialize($data)` (PHP 7.4+) |
| `__set_state($array)` | `var_export()` is called |

---

## 10. Real-World Scenarios

### Scenario 1: Animal Kingdom — Inheritance + Abstract + Interface

```php
interface Trainable {
    public function learn(string $trick): void;
}

abstract class Animal {
    public function __construct(protected string $name) {}

    abstract public function makeSound(): string;

    public function describe(): string {
        return "{$this->name} says: " . $this->makeSound();
    }
}

class Dog extends Animal implements Trainable {
    private array $tricks = [];

    public function makeSound(): string {
        return "Woof!";
    }

    public function learn(string $trick): void {
        $this->tricks[] = $trick;
        echo "{$this->name} learned: $trick\n";
    }

    public function showTricks(): void {
        echo "{$this->name}'s tricks: " . implode(', ', $this->tricks) . "\n";
    }
}

class Cat extends Animal {
    public function makeSound(): string {
        return "Meow!";
    }
}

$dog = new Dog("Rex");
echo $dog->describe() . "\n"; // "Rex says: Woof!"
$dog->learn("sit");
$dog->learn("fetch");
$dog->showTricks();            // "Rex's tricks: sit, fetch"

$cat = new Cat("Whiskers");
echo $cat->describe() . "\n"; // "Whiskers says: Meow!"
// $cat->learn("sit");         // ❌ Cat doesn't implement Trainable!
```

---

### Scenario 2: Plugin System Using Traits

```php
trait HasTimestamps {
    private string $createdAt;
    private string $updatedAt;

    public function touch(): void {
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public function initTimestamps(): void {
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = $this->createdAt;
    }

    public function getTimestamps(): array {
        return ['created_at' => $this->createdAt, 'updated_at' => $this->updatedAt];
    }
}

trait HasSoftDelete {
    private ?string $deletedAt = null;

    public function softDelete(): void {
        $this->deletedAt = date('Y-m-d H:i:s');
    }

    public function isDeleted(): bool {
        return $this->deletedAt !== null;
    }
}

class Article {
    use HasTimestamps, HasSoftDelete;

    public function __construct(public string $title) {
        $this->initTimestamps();
    }
}

$article = new Article("PHP OOP Deep Dive");
print_r($article->getTimestamps());
// ['created_at' => '2025-04-12 ...', 'updated_at' => '2025-04-12 ...']

$article->softDelete();
echo $article->isDeleted() ? "Archived" : "Active"; // "Archived"
```

---

### Scenario 3: Magic Methods for a Flexible Config Object

```php
class Config {
    private array $data = [];

    public function __construct(array $data = []) {
        $this->data = $data;
    }

    // $config->db_host = "localhost"
    public function __set(string $key, mixed $value): void {
        $this->data[$key] = $value;
    }

    // echo $config->db_host
    public function __get(string $key): mixed {
        return $this->data[$key] ?? null;
    }

    // isset($config->db_host)
    public function __isset(string $key): bool {
        return isset($this->data[$key]);
    }

    // echo $config
    public function __toString(): string {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }

    // $config(['db_host']) — callable config accessor
    public function __invoke(string $key, mixed $default = null): mixed {
        return $this->data[$key] ?? $default;
    }
}

$config = new Config([
    'app_name' => 'MyShop',
    'debug'    => false,
]);

$config->db_host = 'localhost';
$config->db_port = 3306;

echo $config->app_name;                    // "MyShop"
echo $config->db_host;                     // "localhost"
echo isset($config->debug) ? "Yes" : "No"; // "Yes"
echo $config('db_port', 3306);             // 3306  — __invoke
echo $config;                              // Pretty JSON of all config
```

---

### Scenario 4: Final Class for Security

```php
final class PasswordHasher {
    private const ALGO    = PASSWORD_BCRYPT;
    private const OPTIONS = ['cost' => 12];

    // Can't be instantiated — all methods are static
    private function __construct() {}

    public static function hash(string $plain): string {
        return password_hash($plain, self::ALGO, self::OPTIONS);
    }

    public static function verify(string $plain, string $hashed): bool {
        return password_verify($plain, $hashed);
    }
}

$hash = PasswordHasher::hash("mySecret123");
echo PasswordHasher::verify("mySecret123", $hash) ? "Valid" : "Invalid"; // "Valid"
echo PasswordHasher::verify("wrongPassword", $hash) ? "Valid" : "Invalid"; // "Invalid"

// PasswordHasher cannot be extended — no one can change the hashing logic!
```

---

## 11. References

- [PHP Manual — Inheritance](https://www.php.net/manual/en/language.oop5.inheritance.php)
- [PHP Manual — Classes and Objects](https://www.php.net/manual/en/language.oop5.php)
- [W3Schools — PHP OOP Inheritance](https://www.w3schools.com/php/php_oop_inheritance.asp)
- [W3Schools — Access Modifiers](https://www.w3schools.com/php/php_oop_access_modifiers.asp)
- [W3Schools — Abstract Classes](https://www.w3schools.com/php/php_oop_classes_abstract.asp)
- [W3Schools — Interfaces](https://www.w3schools.com/php/php_oop_interfaces.asp)
- [W3Schools — Traits](https://www.w3schools.com/php/php_oop_traits.asp)
- [W3Schools — Static Methods](https://www.w3schools.com/php/php_oop_static_methods.asp)
- [W3Schools — Static Properties](https://www.w3schools.com/php/php_oop_static_properties.asp)
- [W3Schools — Destructor](https://www.w3schools.com/php/php_oop_destructor.asp)
- [W3Schools — Constructor](https://www.w3schools.com/php/php_oop_constructor.asp)
- [W3Schools — Constants](https://www.w3schools.com/php/php_oop_constants.asp)
- [GeeksForGeeks — Inheritance in PHP](https://www.geeksforgeeks.org/php/what-is-inheritance-in-php/)
- [Dev.to — Inheritance in PHP OOP](https://dev.to/thecodeliner/inheritance-in-php-oop-a-simple-guide-1e0c)

