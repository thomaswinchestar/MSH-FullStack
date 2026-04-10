# Week 18 — PHP Functions (Part 2) & PHP OOP Introduction

---

## Table of Contents

1. [PHP Functions — Part 2](#1-php-functions--part-2)
   - [Return Type Hinting](#11-return-type-hinting)
   - [Union Types](#12-union-types)
   - [Pass by Value vs Pass by Reference](#13-pass-by-value-vs-pass-by-reference)
   - [Nested Functions](#14-nested-functions)
   - [Global Variables in Functions](#15-global-variables-in-functions)
   - [Variable Functions](#16-variable-functions)
   - [Anonymous Functions (Closures)](#17-anonymous-functions-closures)
   - [The `use` Keyword in Closures](#18-the-use-keyword-in-closures)
   - [Arrow Functions](#19-arrow-functions)
   - [Named Arguments](#110-named-arguments)
   - [Static Variables in Functions](#111-static-variables-in-functions)
   - [Recursive Functions](#112-recursive-functions)
   - [Special Return Types — void, mixed, never](#113-special-return-types--void-mixed-never)
2. [PHP OOP Introduction](#2-php-oop-introduction)
   - [What is OOP?](#21-what-is-oop)
   - [Class and Object](#22-class-and-object)
   - [Properties and Methods](#23-properties-and-methods)
   - [Access Control — Public, Private, Protected](#24-access-control--public-private-protected)
   - [Constructor (`__construct`)](#25-constructor-__construct)
   - [Magic Methods](#26-magic-methods)
   - [$this Keyword](#27-this-keyword)
   - [Static Members](#28-static-members)
   - [Scope Resolution Operator (`::`)](#29-scope-resolution-operator-)
   - [Constructor Property Promotion](#210-constructor-property-promotion)
   - [Class Constants](#211-class-constants)
   - [Getters and Setters](#212-getters-and-setters)
   - [Inheritance](#213-inheritance)
   - [Interfaces and Abstract Classes — A First Look](#214-interfaces-and-abstract-classes--a-first-look)
3. [OOP vs Procedural — When to Use Which?](#3-oop-vs-procedural--when-to-use-which)
4. [Real-World Scenarios](#4-real-world-scenarios)
5. [References](#5-references)

---

## 1. PHP Functions — Part 2

### 1.1 Return Type Hinting

Last week we looked at **parameter type hints**. This week we extend that to **return type hints** — you declare what type a function will return after the `)` with a `:`.

```php
function add(array $nums): float {
    return array_sum($nums); // must return a float
}

echo add([1, 2]); // 3 (treated as float)
```

**Why it matters:**
- PHP throws a `TypeError` if the function returns the wrong type.
- Makes function contracts explicit — callers know exactly what they'll get back.
- Improves IDE autocompletion and code readability.

> ⚠️ **Common mistake from this week:**
> ```php
> function add(array $nums): float {
>     // forgot to return! → Fatal TypeError
> }
> echo add([1, 2]); // Fatal error: Return value must be of type float, none returned
> ```
> If you declare a return type, **you must always return a value of that type.**

---

### 1.2 Union Types

Sometimes a parameter or return value can be **more than one type**. PHP 8.0 introduced **union types** using `|`.

```php
function price(int|float $n): string {
    return "Price is \$$n";
}

echo price(3.1);  // "Price is $3.1"
echo price(2);    // "Price is $2"
```

**Real-world usage:**

```php
// A user ID can be int (from DB) or string (from URL)
function findUser(int|string $id): ?array {
    // query logic...
    return null;
}

findUser(42);         // int
findUser("user-abc"); // string
```

> 💡 **JS Parallel:** JavaScript doesn't enforce types at all — PHP's union types give you flexibility while still keeping type safety.

---

### 1.3 Pass by Value vs Pass by Reference

#### Pass by Value (PHP default)

By default, PHP **copies** the value when you pass it into a function. Changes inside the function **do NOT affect** the original variable.

```php
$name = "Alice";

function hello($n) {
    $n = "Bob";        // only changes the local copy
    echo "Hello $n";   // "Hello Bob"
}

hello($name);
echo $name; // still "Alice" — unchanged
```

Think of it like photocopying a document — you edit the copy, not the original.

#### Pass by Reference

If you want the function to **modify the original variable**, use `&` before the parameter name.

```php
$name = "Alice";

function hello(&$n) { // & means "pass the original, not a copy"
    $n = "Bob";
    echo "Hello $n";  // "Hello Bob"
}

hello($name);
echo $name; // "Bob" — original was changed!
```

Think of it like giving someone your actual document to edit directly.

**When to use pass by reference:**

| Use pass by value | Use pass by reference |
|---|---|
| Most cases (safer) | When you intentionally need to modify the original |
| Pure functions | Built-in functions like `sort()`, `shuffle()` |
| Returning new data | When returning is impractical (large data structures) |

**Real-world example:**

```php
// sort() modifies the array in-place (pass by reference internally)
$scores = [85, 42, 91, 67];
sort($scores); // modifies the original array
print_r($scores); // [42, 67, 85, 91]
```

---

### 1.4 Nested Functions

In PHP, you can **define a function inside another function**. The inner function only becomes available **after the outer function is called**.

```php
function one() {
    function two() {
        echo "Two";
    }
}

// two(); // ❌ Error! — "two" doesn't exist yet

one();    // calling one() makes two() available
two();    // ✅ Now works — prints "Two"
```

> ⚠️ **Important:** `two()` is defined globally once `one()` runs. It's not truly "private" to `one()`. For private/encapsulated functions, use closures or OOP instead.

---

### 1.5 Global Variables in Functions

By default, functions in PHP **cannot access variables outside their scope** — unlike JavaScript.

```php
$name = "Alice";

function hello() {
    echo $name; // ❌ Undefined variable — PHP functions have own scope
}
```

To access an outer variable, use the `global` keyword:

```php
$name = "Alice";

function hello() {
    global $name;  // pull $name from global scope
    $name = "Bob"; // this also changes the global $name!
    echo "Hello $name"; // "Hello Bob"
}

hello();
echo $name; // "Bob" — global variable was changed
```

> ⚠️ **Best practice:** Avoid `global` whenever possible. It creates **hidden dependencies** that make code hard to understand and test. Pass variables as **parameters** instead:
> ```php
> // ✅ Better — explicit, testable
> function hello(string $name): string {
>     return "Hello $name";
> }
> echo hello("Alice");
> ```

---

### 1.6 Variable Functions

In PHP, you can store a function **name as a string** in a variable and call it using `$variableName()`.

```php
function add($a, $b) {
    echo $a + $b;
}

$fn = "add";   // store the function name as a string
$fn(1, 2);     // calls add(1, 2) → prints 3
```

This is useful for **callbacks** and **dynamic dispatch**:

```php
$nums = [1, 2, 3, 4];

function doubleIt($n) {
    return $n * 2;
}

$result = array_map("doubleIt", $nums); // pass function name as string
print_r($result); // [2, 4, 6, 8]
```

> 💡 This is one form of PHP's **first-class function** support. However, using anonymous functions (closures) is generally cleaner and more modern.

---

### 1.7 Anonymous Functions (Closures)

An **anonymous function** is a function **without a name**. You assign it to a variable or pass it directly as an argument. These are also called **closures** in PHP.

**Assigned to a variable:**

```php
$double = function($n) {
    return $n * 2;
};

echo $double(5);  // 10
echo $double(10); // 20
```

**Passed directly as an argument (inline callback):**

```php
$nums   = [1, 2, 3, 4];
$result = array_map(function($n) {
    return $n * 2;
}, $nums);

print_r($result); // [2, 4, 6, 8]
```

> 💡 **JS Parallel:** This is exactly like JavaScript's anonymous functions:
> ```js
> const double = function(n) { return n * 2; };
> [1, 2, 3].map(function(n) { return n * 2; });
> ```

---

### 1.8 The `use` Keyword in Closures

PHP closures do **not automatically capture** outer variables (unlike JavaScript). You must explicitly import them using `use`.

```php
$name = "Alice";

$greet = function() use ($name) {
    echo "Hello $name"; // "Hello Alice"
};

$greet();
```

**`use` imports a copy by default:**

```php
$name = "Alice";

$greet = function() use ($name) {
    $name = "Bob";      // changes local copy only
    echo "Hello $name"; // "Hello Bob"
};

$greet();
echo $name; // still "Alice"
```

**`use` by reference (`&`) to capture the original:**

```php
$count = 0;

$increment = function() use (&$count) {
    $count++;
};

$increment();
$increment();
echo $count; // 2 — original was modified
```

**Real-world usage — building a filter with a captured value:**

```php
$minAge = 18;

$adults = array_filter([15, 22, 17, 30, 13, 25], function($age) use ($minAge) {
    return $age >= $minAge;
});

print_r($adults); // [22, 30, 25]
```

---

### 1.9 Arrow Functions

**Arrow functions** (PHP 7.4+) are a shorter syntax for anonymous functions. They **automatically capture** outer variables — no `use` keyword needed.

```php
// Anonymous function
$double = function($n) { return $n * 2; };

// Arrow function — shorter, cleaner
$double = fn($n) => $n * 2;

echo $double(5); // 10
```

**Automatic capture of outer scope:**

```php
$x = 3;
$add = fn($y) => $x + $y; // $x is automatically captured

echo $add(5); // 8 — no "use" needed!
```

**Used with built-in higher-order functions:**

```php
$nums    = [1, 2, 3, 4, 5];
$doubled = array_map(fn($n) => $n * 2, $nums);
$evens   = array_filter($nums, fn($n) => $n % 2 === 0);

print_r($doubled); // [2, 4, 6, 8, 10]
print_r($evens);   // [2, 4]
```

**Arrow function vs Anonymous function:**

| Feature | Anonymous function | Arrow function |
|---|---|---|
| Syntax | `function($x) { return ...; }` | `fn($x) => ...` |
| Multiple statements | ✅ Yes | ❌ No (single expression) |
| Outer variables | Must `use ($var)` | Auto-captured |
| PHP version | PHP 5.3+ | PHP 7.4+ |

> 💡 **JS Parallel:**
> ```js
> // JS arrow function
> const double = n => n * 2;
> [1, 2, 3].map(n => n * 2);
> ```
> PHP arrow functions were inspired by JS arrow functions!

---

### 1.10 Named Arguments

Normally you pass arguments **positionally** — in the same order as the function defines them. **Named arguments** (PHP 8.0+) let you pass arguments **by name**, in any order.

```php
function profile($name, $email, $age) {
    echo "$name ($age) @ $email";
}

// Positional (normal way)
profile("Alice", "alice@gmail.com", 22);

// Named arguments — order doesn't matter!
profile(
    age: 23,
    name: "Bob",
    email: "bob@gmail.com",
);
// Output: "Bob (23) @ bob@gmail.com"
```

**Why named arguments are useful:**

```php
// Hard to read — what do these numbers mean?
array_slice($arr, 1, 5, true);

// Named arguments — self-documenting!
array_slice(array: $arr, offset: 1, length: 5, preserve_keys: true);
```

**Skip optional parameters:**

```php
// Without named args — must pass all intermediate defaults
htmlspecialchars($str, ENT_QUOTES, 'UTF-8', false);

// With named args — skip to the one you care about
htmlspecialchars($str, double_encode: false);
```

> 💡 Named arguments make function calls **self-documenting** and eliminate confusion when functions have many optional parameters.

---

### 1.11 Static Variables in Functions

A **static variable** inside a function keeps its value **between function calls** — it is initialised only once and persists for the life of the script. This is completely different from class-level `static` properties.

```php
function counter() {
    static $count = 0; // initialised once, then remembered
    $count++;
    echo $count . "\n";
}

counter(); // 1
counter(); // 2
counter(); // 3
```

Without `static`, `$count` would reset to `0` on every call.

**How to think about it:**

```
First call  → $count is created (= 0), incremented → 1, remembered
Second call → $count already exists (= 1), incremented → 2, remembered
Third call  → $count already exists (= 2), incremented → 3, remembered
```

**Real-world use — generating unique IDs:**

```php
function generateId(): int {
    static $id = 0;
    return ++$id;
}

echo generateId(); // 1
echo generateId(); // 2
echo generateId(); // 3
// Each call gives the next unique integer — no globals needed
```

**Real-world use — caching an expensive result:**

```php
function getConfig(): array {
    static $config = null;

    if ($config === null) {
        // Imagine this reads from a file or DB — expensive!
        $config = ['debug' => false, 'version' => '1.0.0'];
        echo "(loaded from disk)\n";
    }

    return $config;
}

getConfig(); // "(loaded from disk)" — runs once
getConfig(); // returns cached result silently
getConfig(); // returns cached result silently
```

> 💡 **JS Parallel:** JavaScript doesn't have static local variables. The equivalent pattern in JS is a closure with an outer variable:
> ```js
> const counter = (() => { let count = 0; return () => ++count; })();
> counter(); // 1
> counter(); // 2
> ```

---

### 1.12 Recursive Functions

A **recursive function** is one that **calls itself**. Every recursive function needs:

1. **A base case** — the condition that stops the recursion (otherwise: infinite loop → stack overflow).
2. **A recursive case** — the function calling itself with a smaller/simpler version of the problem.

**Classic example — Factorial:**

```
5! = 5 × 4 × 3 × 2 × 1 = 120
   = 5 × 4!
       = 4 × 3!
           = 3 × 2!
               = 2 × 1!
                   = 1  ← base case
```

```php
function factorial(int $n): int {
    if ($n <= 1) return 1;      // base case — stop here
    return $n * factorial($n - 1); // recursive case
}

echo factorial(5); // 120
echo factorial(6); // 720
```

**Visualising the call stack:**

```
factorial(5)
  → 5 * factorial(4)
         → 4 * factorial(3)
                → 3 * factorial(2)
                       → 2 * factorial(1)
                              → 1  (base case)
                       ← 2 * 1 = 2
                ← 3 * 2 = 6
         ← 4 * 6 = 24
  ← 5 * 24 = 120
```

**Real-world use — traversing a nested menu:**

```php
$menu = [
    ['label' => 'Home',     'children' => []],
    ['label' => 'Products', 'children' => [
        ['label' => 'Phones',   'children' => []],
        ['label' => 'Laptops',  'children' => [
            ['label' => 'Gaming',    'children' => []],
            ['label' => 'Business',  'children' => []],
        ]],
    ]],
    ['label' => 'Contact',  'children' => []],
];

function renderMenu(array $items, int $depth = 0): void {
    foreach ($items as $item) {
        echo str_repeat('  ', $depth) . "- " . $item['label'] . "\n";
        if (!empty($item['children'])) {
            renderMenu($item['children'], $depth + 1); // recurse deeper
        }
    }
}

renderMenu($menu);
// - Home
// - Products
//   - Phones
//   - Laptops
//     - Gaming
//     - Business
// - Contact
```

> ⚠️ **Always define a base case.** Without one, the function calls itself forever until PHP hits the recursion limit and throws a fatal error.

> 💡 **When to use recursion:** Tree-shaped data (menus, file systems, categories, org charts), divide-and-conquer algorithms (merge sort, binary search). For simple loops, use `for`/`foreach` instead.

---

### 1.13 Special Return Types — `void`, `mixed`, `never`

PHP provides special return types for functions with unusual return behaviour.

#### `void` — The function returns nothing

```php
function logMessage(string $msg): void {
    echo "[LOG] $msg\n";
    // no return statement — or bare "return;" is allowed
}

logMessage("User logged in"); // [LOG] User logged in
// $result = logMessage("test"); // $result is always NULL
```

> ⚠️ You cannot `return $value` inside a `void` function — PHP will throw a `TypeError`.

#### `mixed` — The function can return any type (PHP 8.0+)

Use `mixed` when a function intentionally returns different types depending on circumstances:

```php
function findValue(array $data, string $key): mixed {
    return $data[$key] ?? null; // could be string, int, array, null...
}

$config = ['host' => 'localhost', 'port' => 3306, 'debug' => false];

var_dump(findValue($config, 'host'));  // string(9) "localhost"
var_dump(findValue($config, 'port'));  // int(3306)
var_dump(findValue($config, 'debug')); // bool(false)
var_dump(findValue($config, 'xyz'));   // NULL
```

> 💡 Prefer specific types whenever possible. Use `mixed` only when the ambiguity is genuinely intentional (e.g., JSON deserialisers, generic container classes).

#### `never` — The function never returns (PHP 8.1+)

A function with return type `never` either **throws an exception** or **terminates the script** — it will never return normally to the caller.

```php
function redirect(string $url): never {
    header("Location: $url");
    exit(); // script ends here — never returns
}

function abort(int $code, string $message): never {
    http_response_code($code);
    echo $message;
    exit(); // same — never returns
}

// Usage
if (!$user) {
    redirect('/login'); // execution stops here
}
```

**Summary table:**

| Return type | Meaning | Returns? |
|---|---|---|
| `void` | Intentionally returns nothing | ✅ Returns (with `null`) |
| `mixed` | Can return any type | ✅ Returns (any value) |
| `never` | Throws or exits | ❌ Never returns to caller |
| `?Type` | Returns `Type` or `null` | ✅ Returns (or null) |

---

## 2. PHP OOP Introduction

### 2.1 What is OOP?

**Object-Oriented Programming (OOP)** is a way of writing code by modelling the world as **objects**. An object is a self-contained unit that bundles:

- **Data** (properties / attributes) — what it *knows*
- **Behaviour** (methods / functions) — what it *can do*

> 🌍 **Real-world analogy:** A car is an object.
> - **Properties:** color, brand, speed, fuelLevel
> - **Methods:** start(), accelerate(), brake(), refuel()
>
> Every car shares the same blueprint (class), but each individual car (object) has its own state.

**Why OOP?**

| Problem with Procedural Code | OOP Solution |
|---|---|
| Functions and data are separate | Bundle data + behaviour in one object |
| Hard to scale large codebases | Organise into classes with clear responsibilities |
| Code is duplicated | Use inheritance to share behaviour |
| Hard to model complex systems | Model real-world entities as objects |

---

### 2.2 Class and Object

A **class** is the **blueprint** (template). An **object** is an **instance** created from that blueprint.

```php
// Define the blueprint
class Animal {
    // properties and methods go here
}

// Create instances (objects) from the blueprint
$dog = new Animal();
$cat = new Animal();

var_dump($dog); // object(Animal)#1 (0) {}
var_dump($cat); // object(Animal)#2 (0) {}
```

> 💡 Think of a class like a cookie cutter — you can make many cookies (objects) from the same cutter (class). Each cookie is its own thing, but they all share the same shape.

---

### 2.3 Properties and Methods

A **property** is a variable that belongs to a class.  
A **method** is a function that belongs to a class.

```php
class Animal {
    public $name;           // Property

    public function run() { // Method
        echo "$this->name is running...";
    }
}

$dog = new Animal();
$dog->name = "Bobby";  // set the property
$dog->run();           // "Bobby is running..."
```

> 💡 **`->` is the object operator** (Dart uses it too; Java, JavaScript, Python use `.` dot).
> - Access a property: `$object->propertyName`
> - Call a method: `$object->methodName()`

---

### 2.4 Access Control — Public, Private, Protected

PHP has three **visibility modifiers** that control who can access a property or method:

| Modifier | Accessible from... |
|---|---|
| `public` | Anywhere — inside the class, outside, in subclasses |
| `private` | Only inside the **same class** |
| `protected` | Inside the class and its **subclasses** (children) |

```php
class Animal {
    public $name;    // anyone can read/write
    private $secret; // only Animal methods can touch this
}

$dog = new Animal();
$dog->name   = "Bobby"; // ✅ works — public
$dog->secret = "info";  // ❌ Fatal error — private property!
```

**Real-world analogy:**

| Modifier | Real-world equivalent |
|---|---|
| `public` | A shop's front entrance — open to everyone |
| `private` | The back office — staff only |
| `protected` | A family home — family members and direct relatives only |

**Why use private properties?**  
To protect your object's internal data from being accidentally changed from outside:

```php
class BankAccount {
    private float $balance = 0;  // can't be changed directly from outside

    public function deposit(float $amount): void {
        if ($amount > 0) {
            $this->balance += $amount; // controlled modification
        }
    }

    public function getBalance(): float {
        return $this->balance; // read-only access
    }
}

$account = new BankAccount();
$account->deposit(500);
echo $account->getBalance(); // 500

// $account->balance = 1000000; // ❌ Not allowed — private!
```

> This concept is called **Encapsulation** — one of OOP's four core pillars.

---

### 2.5 Constructor (`__construct`)

A **constructor** is a special method that **runs automatically** when you create an object with `new`. Use it to set up the initial state of the object.

#### Old-Style Constructor (PHP 4/5 — Legacy)

In older PHP, the constructor method had the **same name as the class**:

```php
class Animal {
    public function Animal() { // old style — class name as constructor
        echo "Creating Animal Object";
    }
}

$dog = new Animal(); // "Creating Animal Object"
```

> ⚠️ This style is **deprecated**. Don't use it in modern code.

#### Modern Constructor — `__construct()`

```php
class Animal {
    public function __construct() { // Magic Method
        echo "Creating Animal Object!";
    }
}

$dog = new Animal(); // "Creating Animal Object!"
```

**Initialising properties with a constructor:**

```php
class Animal {
    private $name;

    public function __construct($name) {
        $this->name = $name; // set property when object is created
    }

    public function run() {
        echo "$this->name is running...";
    }
}

$dog = new Animal("Bobby");
$dog->run(); // "Bobby is running..."
```

**Real-world example — a User object:**

```php
class User {
    private string $name;
    private string $email;

    public function __construct(string $name, string $email) {
        $this->name  = $name;
        $this->email = $email;
    }

    public function greet(): string {
        return "Hello, {$this->name}!";
    }
}

$user = new User("Alice", "alice@example.com");
echo $user->greet(); // "Hello, Alice!"
```

---

### 2.6 Magic Methods

**Magic methods** are special PHP methods that start with double underscores (`__`). PHP calls them automatically in response to certain events — you never call them directly.

| Magic Method | When is it called? |
|---|---|
| `__construct()` | When `new ClassName()` is called |
| `__destruct()` | When the object is destroyed |
| `__toString()` | When the object is used as a string (e.g., `echo $obj`) |
| `__get($name)` | When accessing an undefined/private property |
| `__set($name, $val)` | When setting an undefined/private property |
| `__call($name, $args)` | When calling an undefined method |

```php
class Animal {
    public function __construct() {
        echo "Animal created!\n";   // auto-called on new Animal()
    }

    public function __destruct() {
        echo "Animal destroyed!\n"; // auto-called when object is cleaned up
    }
}

$dog = new Animal(); // prints: "Animal created!"
// At end of script: prints: "Animal destroyed!"
```

> 💡 Magic methods are what make PHP frameworks like Laravel feel "magical" — they use `__get`, `__set`, `__call` extensively to give you clean, dynamic APIs.

---

### 2.7 `$this` Keyword

Inside a class, `$this` refers to the **current object instance** — whichever object the method is being called on.

```php
class Animal {
    private $name;

    public function __construct($name) {
        $this->name = $name; // "this object's name property = $name"
    }

    public function run() {
        echo "$this->name is running..."; // "this object's name"
    }
}

$dog = new Animal("Bobby");
$cat = new Animal("Whiskers");

$dog->run(); // "Bobby is running..."
$cat->run(); // "Whiskers is running..."
```

> 💡 `$this` always refers to **the specific object the method was called on**. When `$dog->run()` is called, `$this` is `$dog`. When `$cat->run()` is called, `$this` is `$cat`.
>
> **JS Parallel:** It works just like `this` in JavaScript classes:
> ```js
> class Animal {
>     constructor(name) { this.name = name; }
>     run() { console.log(`${this.name} is running...`); }
> }
> ```

---

### 2.8 Static Members

**Static properties and methods** belong to the **class itself**, not to any individual object. They're shared across all instances.

```php
class Animal {
    static $type = "Mammal"; // class-level property

    static function info() {  // class-level method
        echo "Group: " . static::$type;
    }
}

// Access without creating an object
echo Animal::$type;  // "Mammal"
Animal::info();      // "Group: Mammal"
```

**When to use static:**

```php
class Counter {
    private static int $count = 0;

    public static function increment(): void {
        self::$count++;
    }

    public static function getCount(): int {
        return self::$count;
    }
}

Counter::increment();
Counter::increment();
Counter::increment();

echo Counter::getCount(); // 3 — shared across all instances
```

**Real-world usage:**

```php
class Database {
    private static ?Database $instance = null;

    // Singleton pattern — only one DB connection
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

$db1 = Database::getInstance();
$db2 = Database::getInstance();
// $db1 and $db2 are the same object!
```

---

### 2.9 Scope Resolution Operator (`::`)

The `::` operator is called the **Scope Resolution Operator** (or **Double Colon Operator**). It's used to access **static properties**, **static methods**, and **constants** on a class.

```php
// Accessing static property
echo Animal::$type;

// Calling static method
Animal::info();

// Class constants
class MathHelper {
    const PI = 3.14159;
}
echo MathHelper::PI; // 3.14159
```

**Inside a class:**
- `self::` — refers to the **current class**
- `static::` — refers to the **called class** (late static binding — used in inheritance)
- `parent::` — refers to the **parent class**

```php
class Animal {
    static $type = "Animal";

    static function describe() {
        echo "I am a " . static::$type; // static:: supports inheritance
    }
}
```

> 💡 **`->` vs `::`**
> - `->` (arrow/dart operator) = instance member (you need an object)
> - `::` (scope resolution) = class/static member (no object needed)
> - JS and Python use `.` for both; PHP separates them for clarity

---

### 2.10 Constructor Property Promotion

**Constructor Property Promotion** (PHP 8.0+) is a shorthand that lets you **declare and assign properties directly in the constructor signature** — no need to repeat yourself.

**Without promotion (verbose):**

```php
class Animal {
    private $name; // 1. declare property

    public function __construct($name) {
        $this->name = $name; // 2. assign in constructor
    }

    public function run() {
        echo "$this->name is running...";
    }
}
```

**With Constructor Property Promotion (clean):**

```php
class Animal {
    public function __construct(private $name) {
        // That's it! Property declared AND assigned in one line
    }

    public function run() {
        echo "$this->name is running...";
    }
}

$dog = new Animal("Rambo");
$dog->run(); // "Rambo is running..."
```

**Real-world example:**

```php
// Without promotion — lots of repetition
class Product {
    private string $name;
    private float $price;
    private int $stock;

    public function __construct(string $name, float $price, int $stock) {
        $this->name  = $name;
        $this->price = $price;
        $this->stock = $stock;
    }
}

// With promotion — clean and DRY
class Product {
    public function __construct(
        private string $name,
        private float  $price,
        private int    $stock
    ) {
        // body can be empty or contain additional setup logic
    }

    public function getInfo(): string {
        return "{$this->name}: \${$this->price} ({$this->stock} left)";
    }
}

$p = new Product("Laptop", 999.99, 10);
echo $p->getInfo(); // "Laptop: $999.99 (10 left)"
```

> 💡 Constructor property promotion is a huge quality-of-life improvement. Modern PHP frameworks like Laravel use it everywhere.

---

### 2.11 Class Constants

A **class constant** is a value that **never changes** — it belongs to the class (not an instance), like a static property but immutable. Define them with `const`.

```php
class Circle {
    const PI = 3.14159265; // never changes — belongs to the class

    public function __construct(private float $radius) {}

    public function area(): float {
        return self::PI * $this->radius ** 2;
    }

    public function circumference(): float {
        return 2 * self::PI * $this->radius;
    }
}

$c = new Circle(5);
echo $c->area();           // 78.539816...
echo $c->circumference();  // 31.415926...

// Access the constant directly on the class — no object needed
echo Circle::PI;           // 3.14159265
```

**Real-world use — Status codes, roles, states:**

```php
class Order {
    const STATUS_PENDING   = 'pending';
    const STATUS_PAID      = 'paid';
    const STATUS_SHIPPED   = 'shipped';
    const STATUS_CANCELLED = 'cancelled';

    private string $status = self::STATUS_PENDING;

    public function pay(): void {
        $this->status = self::STATUS_PAID;
    }

    public function ship(): void {
        if ($this->status !== self::STATUS_PAID) {
            throw new \Exception("Cannot ship an unpaid order.");
        }
        $this->status = self::STATUS_SHIPPED;
    }

    public function getStatus(): string {
        return $this->status;
    }
}

$order = new Order();
echo $order->getStatus(); // "pending"
$order->pay();
echo $order->getStatus(); // "paid"
$order->ship();
echo $order->getStatus(); // "shipped"
```

**`const` vs `static` property:**

| | `const` | `static $property` |
|---|---|---|
| Value changes? | ❌ Never | ✅ Can change |
| Access | `ClassName::CONST` | `ClassName::$prop` |
| `$` symbol? | No | Yes |
| Use for | Fixed values (statuses, config) | Shared mutable state |

---

### 2.12 Getters and Setters

Since properties are often `private` (encapsulation), you need **getter** and **setter** methods to read and write them in a controlled way.

- **Getter** — reads a property (usually `getPropertyName()`)
- **Setter** — writes a property with validation (usually `setPropertyName($value)`)

```php
class User {
    private string $name;
    private int    $age;

    public function __construct(string $name, int $age) {
        $this->setName($name); // use setter — validation happens here
        $this->setAge($age);
    }

    // --- Getters ---
    public function getName(): string {
        return $this->name;
    }

    public function getAge(): int {
        return $this->age;
    }

    // --- Setters with validation ---
    public function setName(string $name): void {
        $name = trim($name);
        if (empty($name)) {
            throw new \InvalidArgumentException("Name cannot be empty.");
        }
        $this->name = ucwords(strtolower($name));
    }

    public function setAge(int $age): void {
        if ($age < 0 || $age > 150) {
            throw new \InvalidArgumentException("Age must be between 0 and 150.");
        }
        $this->age = $age;
    }
}

$user = new User("  alice smith  ", 25);
echo $user->getName(); // "Alice Smith" — cleaned automatically
echo $user->getAge();  // 25

$user->setAge(30);
echo $user->getAge();  // 30

// $user->setAge(-5); // ❌ Throws InvalidArgumentException
```

**Why getters and setters matter:**

```php
// ❌ Without encapsulation — anyone can set invalid data
$user->age = -999;    // nothing stops this!
$user->name = "";     // empty name — no check!

// ✅ With getters/setters — all changes go through validation
$user->setAge(-999);  // throws exception — caught early
$user->setName("");   // throws exception — caught early
```

> 💡 **Fluent interface / Method chaining** — setters can return `$this` to allow chaining:
> ```php
> public function setName(string $name): static {
>     $this->name = $name;
>     return $this; // returns the object so you can chain
> }
>
> $user->setName("Alice")->setAge(25); // clean chained calls
> ```

---

### 2.13 Inheritance

**Inheritance** lets a child class **extend** a parent class — it automatically gets all of the parent's `public` and `protected` properties and methods, and can add its own or **override** existing ones.

```
Parent class (Animal)
   ├── Dog  (child — inherits from Animal, adds bark())
   ├── Cat  (child — inherits from Animal, adds purr())
   └── Bird (child — inherits from Animal, overrides move())
```

**Basic inheritance:**

```php
// Parent class
class Animal {
    public function __construct(protected string $name) {}

    public function eat(): void {
        echo "{$this->name} is eating.\n";
    }

    public function sleep(): void {
        echo "{$this->name} is sleeping.\n";
    }
}

// Child class — inherits eat() and sleep(), adds bark()
class Dog extends Animal {
    public function bark(): void {
        echo "{$this->name} says: Woof!\n";
    }
}

// Another child — inherits everything, adds purr()
class Cat extends Animal {
    public function purr(): void {
        echo "{$this->name} says: Purrrr...\n";
    }
}

$dog = new Dog("Rex");
$dog->eat();   // "Rex is eating."    — inherited from Animal
$dog->bark();  // "Rex says: Woof!"  — Dog's own method

$cat = new Cat("Whiskers");
$cat->sleep(); // "Whiskers is sleeping." — inherited
$cat->purr();  // "Whiskers says: Purrrr..." — Cat's own
```

**Overriding a parent method:**

```php
class Animal {
    public function __construct(protected string $name) {}

    public function move(): string {
        return "{$this->name} moves.";
    }
}

class Bird extends Animal {
    // Override the parent's move() with a more specific version
    public function move(): string {
        return "{$this->name} flies through the sky!";
    }
}

class Fish extends Animal {
    public function move(): string {
        return "{$this->name} swims through the water!";
    }
}

$bird = new Bird("Eagle");
$fish = new Fish("Nemo");

echo $bird->move(); // "Eagle flies through the sky!"
echo $fish->move(); // "Nemo swims through the water!"
```

**Calling the parent's method with `parent::`:**

```php
class Animal {
    public function __construct(protected string $name) {}

    public function describe(): string {
        return "I am {$this->name}";
    }
}

class Dog extends Animal {
    public function __construct(string $name, private string $breed) {
        parent::__construct($name); // call parent constructor first!
    }

    public function describe(): string {
        return parent::describe() . ", a {$this->breed} dog.";
        // "I am Rex, a Labrador dog."
    }
}

$dog = new Dog("Rex", "Labrador");
echo $dog->describe(); // "I am Rex, a Labrador dog."
```

**`instanceof` — check if an object is a certain class:**

```php
$dog = new Dog("Rex", "Labrador");

var_dump($dog instanceof Dog);    // true
var_dump($dog instanceof Animal); // true — Dog IS an Animal (inheritance)
var_dump($dog instanceof Cat);    // false
```

**Real-world use — E-Commerce (User roles):**

```php
class User {
    public function __construct(
        protected string $name,
        protected string $email
    ) {}

    public function getRole(): string { return 'user'; }
    public function canPost(): bool   { return false; }
}

class Admin extends User {
    public function getRole(): string { return 'admin'; }
    public function canPost(): bool   { return true; }
    public function deleteUser(User $user): void {
        echo "Admin {$this->name} deleted user {$user->name}.\n";
    }
}

$user  = new User("Bob", "bob@example.com");
$admin = new Admin("Alice", "alice@example.com");

echo $user->getRole();  // "user"
echo $admin->getRole(); // "admin"
echo $admin->canPost() ? "can post" : "cannot post"; // "can post"
```

> ⚠️ PHP supports **single inheritance only** — a class can only `extend` one parent. To share behaviour across unrelated classes, use **Traits** (covered later) or **Interfaces**.

---

### 2.14 Interfaces and Abstract Classes — A First Look

These are mentioned in `PHP-OOP-1.php` as upcoming topics. Here is a clear conceptual introduction so you know what they are before we dive in next week.

#### What is an Interface?

An **interface** is a **contract** — it defines *what methods a class must have*, but not *how* they work. Any class that `implements` an interface must provide all of its methods.

```php
interface Drawable {
    public function draw(): string;   // must be implemented
    public function getColor(): string; // must be implemented
}

class Circle implements Drawable {
    public function __construct(private string $color, private float $radius) {}

    public function draw(): string {
        return "Drawing a {$this->color} circle with radius {$this->radius}";
    }

    public function getColor(): string {
        return $this->color;
    }
}

class Square implements Drawable {
    public function __construct(private string $color, private float $side) {}

    public function draw(): string {
        return "Drawing a {$this->color} square with side {$this->side}";
    }

    public function getColor(): string {
        return $this->color;
    }
}

// Both shapes honour the Drawable contract
$shapes = [new Circle("red", 5), new Square("blue", 4)];

foreach ($shapes as $shape) {
    echo $shape->draw() . "\n";
}
// "Drawing a red circle with radius 5"
// "Drawing a blue square with side 4"
```

**Why interfaces?**
- Enforce a contract across unrelated classes.
- A class can implement **multiple interfaces** (unlike single inheritance).
- Makes code easier to swap out — any `Drawable` can be used where a `Drawable` is expected.

#### What is an Abstract Class?

An **abstract class** is a **half-finished blueprint** — it can have:
- **Concrete methods** (fully implemented — all children inherit them)
- **Abstract methods** (no body — each child *must* provide its own version)

You **cannot** create an object directly from an abstract class.

```php
abstract class Shape {
    // Concrete method — shared by all shapes
    public function describe(): string {
        return "I am a " . get_class($this) . " with color {$this->color}";
    }

    // Abstract method — each shape must define how to calculate its area
    abstract public function area(): float;
}

class Circle extends Shape {
    public function __construct(
        public string $color,
        private float $radius
    ) {}

    // Must implement area() — abstract forces this
    public function area(): float {
        return M_PI * $this->radius ** 2;
    }
}

class Rectangle extends Shape {
    public function __construct(
        public string $color,
        private float $width,
        private float $height
    ) {}

    public function area(): float {
        return $this->width * $this->height;
    }
}

$shapes = [new Circle("red", 5), new Rectangle("blue", 4, 6)];

foreach ($shapes as $shape) {
    echo $shape->describe() . " — Area: " . round($shape->area(), 2) . "\n";
}
// "I am a Circle with color red — Area: 78.54"
// "I am a Rectangle with color blue — Area: 24"

// new Shape(); // ❌ Cannot instantiate abstract class
```

**Interface vs Abstract Class:**

| | Interface | Abstract Class |
|---|---|---|
| Has method bodies? | ❌ No | ✅ Yes (concrete + abstract) |
| Can have properties? | ❌ No | ✅ Yes |
| Can be instantiated? | ❌ No | ❌ No |
| `extends` / `implements` | `implements` | `extends` |
| Multiple? | ✅ One class can implement many | ❌ Only one parent class |
| Use when | You need a contract / shared type | You want to share code + enforce structure |

> 💡 **Simple rule of thumb:**
> - Use an **interface** when different unrelated classes need to honour the same contract.
> - Use an **abstract class** when related classes share code but each must implement specific parts differently.

---

```
┌─────────────────────────────────────────────────────────────────┐
│                    PHP Code Organisation                        │
├────────────────────────┬────────────────────────────────────────┤
│  PROCEDURAL            │  OOP                                   │
├────────────────────────┼────────────────────────────────────────┤
│ Functions + Data       │ Objects (Data + Functions together)    │
│ Global state           │ Encapsulated state ($this)             │
│ Simple scripts         │ Complex applications                   │
│ One-off utilities      │ Reusable, extensible components        │
│ PHP built-ins          │ Laravel, Symfony, WordPress plugins    │
└────────────────────────┴────────────────────────────────────────┘
```

**Use procedural when:**
- Writing small scripts or utilities
- Simple data processing (ETL, CLI scripts)
- Working with PHP's built-in functions (`array_map`, `strlen`, etc.)

**Use OOP when:**
- Building applications with multiple related concepts (User, Product, Order)
- You need code reuse through inheritance
- Working with frameworks like Laravel or Symfony
- Your data and behaviour naturally belong together

> 🧠 PHP is **multi-paradigm** — the best PHP code mixes both. Use functions where functions are clearest, and classes where objects make sense.

---

## 4. Real-World Scenarios

### Scenario 1: E-Commerce Product (OOP)

```php
class Product {
    public function __construct(
        private string $name,
        private float  $price,
        private int    $stock = 0
    ) {}

    public function isAvailable(): bool {
        return $this->stock > 0;
    }

    public function purchase(int $qty = 1): string {
        if ($qty > $this->stock) {
            return "❌ Not enough stock!";
        }
        $this->stock -= $qty;
        $total = $this->price * $qty;
        return "✅ Purchased $qty x {$this->name} for \$$total";
    }

    public function getInfo(): string {
        $status = $this->isAvailable() ? "In Stock ({$this->stock})" : "Out of Stock";
        return "{$this->name} — \${$this->price} — $status";
    }
}

$laptop = new Product("Laptop", 999.99, 5);
echo $laptop->getInfo();        // "Laptop — $999.99 — In Stock (5)"
echo $laptop->purchase(2);      // "✅ Purchased 2 x Laptop for $1999.98"
echo $laptop->getInfo();        // "Laptop — $999.99 — In Stock (3)"
```

---

### Scenario 2: Closures for Data Pipeline (Functional)

```php
// Process a list of orders — filter, transform, summarise
$orders = [
    ['product' => 'Laptop',  'price' => 999,  'qty' => 2],
    ['product' => 'Book',    'price' => 15,   'qty' => 5],
    ['product' => 'Monitor', 'price' => 399,  'qty' => 1],
    ['product' => 'Pen',     'price' => 2,    'qty' => 10],
];

$minValue = 50; // only process orders worth more than $50

$revenue = array_reduce(
    array_filter(
        $orders,
        fn($o) => ($o['price'] * $o['qty']) > $minValue
    ),
    fn($total, $o) => $total + ($o['price'] * $o['qty']),
    0
);

echo "Total revenue (orders > \$$minValue): \$$revenue";
// "Total revenue (orders > $50): $2795"
```

---

### Scenario 3: Named Arguments for Readable Config

```php
function createUser(
    string $name,
    string $email,
    string $role    = "user",
    bool   $active  = true,
    int    $age     = 0
): array {
    return compact('name', 'email', 'role', 'active', 'age');
}

// Without named args — confusing!
$user = createUser("Alice", "alice@example.com", "admin", true, 28);

// With named args — crystal clear!
$user = createUser(
    name:  "Alice",
    email: "alice@example.com",
    role:  "admin",
    age:   28
    // active: defaults to true — skipped!
);
```

---

### Scenario 4: Pass by Reference for Normalising Data

```php
// Normalise a user record — clean up multiple fields in-place
function normaliseUser(array &$user): void {
    $user['name']  = trim(ucwords(strtolower($user['name'])));
    $user['email'] = strtolower(trim($user['email']));
    $user['age']   = max(0, (int) $user['age']); // ensure non-negative
}

$user = ['name' => '  alice SMITH  ', 'email' => '  ALICE@Example.COM  ', 'age' => -5];
normaliseUser($user);

print_r($user);
// ['name' => 'Alice Smith', 'email' => 'alice@example.com', 'age' => 0]
```

---

### Scenario 5: Static Factory Method

```php
class Color {
    private function __construct(
        private int $r,
        private int $g,
        private int $b
    ) {}

    // Static factory methods — named constructors
    public static function fromRGB(int $r, int $g, int $b): self {
        return new self($r, $g, $b);
    }

    public static function fromHex(string $hex): self {
        $hex = ltrim($hex, '#');
        return new self(
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
    }

    public function toCSS(): string {
        return "rgb({$this->r}, {$this->g}, {$this->b})";
    }
}

$red   = Color::fromRGB(255, 0, 0);
$blue  = Color::fromHex("#0000FF");

echo $red->toCSS();  // "rgb(255, 0, 0)"
echo $blue->toCSS(); // "rgb(0, 0, 255)"
```

---

## 5. References

- [PHP Manual — Functions](https://www.php.net/manual/en/language.functions.php)
- [PHP Manual — User-Defined Functions](https://www.php.net/manual/en/functions.user-defined.php)
- [PHP Manual — Classes and Objects](https://www.php.net/manual/en/language.oop5.php)
- [PHP Manual — Constructor Promotion](https://www.php.net/manual/en/language.oop5.decon.php#language.oop5.decon.constructor.promotion)
- [PHP Manual — Named Arguments](https://www.php.net/manual/en/functions.named-arguments.php)
- [PHP Manual — Arrow Functions](https://www.php.net/manual/en/functions.arrow.php)
- [PHP Manual — Anonymous Functions](https://www.php.net/manual/en/functions.anonymous.php)
- [W3Schools — PHP Functions](https://www.w3schools.com/php/php_functions.asp)
- [W3Schools — PHP OOP](https://www.w3schools.com/php/php_oop_what_is.asp)
- [TutorialsPoint — PHP Functions](https://www.tutorialspoint.com/php/php_functions.htm)
- [TutorialsPoint — PHP OOP](https://www.tutorialspoint.com/php/php_object_oriented.htm)
- [GeeksForGeeks — PHP Functions](https://www.geeksforgeeks.org/php/php-functions/)
- [GeeksForGeeks — Procedural vs OOP](https://www.geeksforgeeks.org/software-engineering/differences-between-procedural-and-object-oriented-programming/)
- [PHP Language Spec — Functions](https://phplang.org/spec/13-functions)
- [PHP Tutorial — PHP OOP](https://www.phptutorial.net/php-oop/)
- [Inspector.dev — OOP in PHP](https://inspector.dev/object-oriented-programming-in-php/)
- [WebReference — PHP OOP](https://webreference.com/php/object-oriented/)

