# Week 17 - PHP Control Structures, Ternary & Null Coalescing Operators, PHP Functions & Programming Paradigms

---

## Table of Contents

1. [Control Structures](#1-control-structures)
   - [if / else](#11-if--else)
   - [if / elseif / else](#12-if--elseif--else)
   - [Alternative Syntax](#13-alternative-syntax-for-control-structures)
   - [switch Statement](#14-switch-statement)
   - [match Expression](#15-match-expression-php-80)
2. [Loops](#2-loops)
   - [while](#21-while-loop)
   - [do-while](#22-do-while-loop)
   - [for](#23-for-loop)
   - [foreach](#24-foreach-loop)
   - [break & continue](#25-break--continue)
3. [Ternary Operator](#3-ternary-operator)
   - [Standard Ternary](#31-standard-ternary)
   - [Short Ternary (Elvis Operator)](#32-short-ternary-elvis-operator-)
4. [Null Coalescing Operator](#4-null-coalescing-operator)
   - [Null Coalescing (??)](#41-null-coalescing-operator-)
   - [Null Coalescing Assignment (??=)](#42-null-coalescing-assignment-operator-)
5. [Key Differences & Comparison Table](#5-key-differences--comparison-table)
6. [Real-World Usage](#6-real-world-usage)
7. [References](#7-references)
8. [Programming Paradigms](#8-programming-paradigms)
   - [Imperative Programming](#81-imperative-programming)
   - [Procedural Programming](#82-procedural-programming)
   - [Declarative Programming](#83-declarative-programming)
   - [Object-Oriented Programming (OOP)](#84-object-oriented-programming-oop)
   - [Paradigm Comparison Table](#85-paradigm-comparison-table)
9. [Modular Architecture](#9-modular-architecture)
   - [What is Modularity?](#91-what-is-modularity)
   - [Modular Monolith](#92-modular-monolith)
   - [PHP Modularity in Practice](#93-php-modularity-in-practice)
10. [First-Class Functions](#10-first-class-functions)
    - [What Does "First-Class" Mean?](#101-what-does-first-class-mean)
    - [Functions as Values in PHP](#102-functions-as-values-in-php)
    - [Higher-Order Functions](#103-higher-order-functions)
11. [Functional Programming](#11-functional-programming)
    - [Core Principles](#111-core-principles)
    - [Pure Functions](#112-pure-functions)
    - [Immutability](#113-immutability)
    - [Functional PHP Built-ins](#114-functional-php-built-ins)
12. [PHP Functions (Part 1)](#12-php-functions-part-1)
    - [Declaring Functions](#121-declaring-functions)
    - [Parameters & Return Values](#122-parameters--return-values)
    - [Default Parameters](#123-default-parameters)
    - [Rest Parameters](#124-rest-parameters)
    - [Type Hints](#125-type-hints)
    - [Useful Built-in Functions](#126-useful-built-in-functions)

---

## 1. Control Structures

Control structures determine the flow of your program — which code runs and when.

### 1.1 if / else

The most basic control structure. It checks a condition and runs code based on whether it's **true** or **false**.

```php
$time = date("H:i:s");

// Single-line form (no braces needed for one statement)
if ($time > 6 and $time < 18) echo "Day Time";
else echo "Night Time";

// Block form (recommended for readability)
if ($time > 6 and $time < 18) {
    echo "Day Time";
} else {
    echo "Night Time";
}
```

**How it works:**
- `date("H:i:s")` gets the current time in hours:minutes:seconds format.
- If the time is between 6 and 18 (6 AM to 6 PM), it prints "Day Time".
- Otherwise, it prints "Night Time".

> 💡 **Best Practice:** Always use curly braces `{}` even for single-line `if` statements. It makes the code easier to read and less error-prone.

---

### 1.2 if / elseif / else

When you have **more than two conditions** to check, use `elseif`:

```php
$day = date("D"); // Returns: "Sun", "Mon", "Tue", etc.

if ($day === "Sun") {
    echo "Today is Sunday";
} elseif ($day === "Sat") {
    echo "Today is Saturday";
} else {
    echo "Today is a weekday.";
}
```

**How it works:**
- PHP checks each condition **from top to bottom**.
- The first condition that is `true` gets executed.
- If none match, the `else` block runs as a fallback.

---

### 1.3 Alternative Syntax for Control Structures

PHP provides an **alternative syntax** using colons (`:`) and `endif`. This is especially useful when mixing PHP with HTML in templates:

```php
if ($time > 6 and $time < 18):
    echo "Day Time";
else:
    echo "Night Time";
endif;
```

**Why use it?**
- It's cleaner when embedding PHP inside HTML (e.g., in template files).
- Easier to see where blocks end compared to matching curly braces in large files.

---

### 1.4 switch Statement

`switch` is useful when you need to compare **one value** against **many possible cases**. It uses **loose comparison (`==`)**.

```php
$day = date("D");

switch ($day) {
    case "Sat":
    case "Sun":
        echo "Weekend";
        break;
    case "Fri":
        echo "TGIF";
        break;
    default:
        echo "Weekday";
}
```

**Key points:**
- `switch` uses **loose comparison (`==`)**, so `"5" == 5` is `true`.
- `break` is required to stop "fall-through" — without it, PHP continues executing the next case.
- Multiple cases can share the same block (e.g., `"Sat"` and `"Sun"` both print "Weekend").
- `default` acts like `else` — it runs when no case matches.

---

### 1.5 match Expression (PHP 8.0+)

`match` is a modern alternative to `switch`. It uses **strict comparison (`===`)** and **returns a value**.

```php
$day = date("D");

$result = match ($day) {
    "Sat", "Sun" => "Weekend",
    "Fri" => "TGIF",
    default => "Weekday"
};

echo $result;
```

**Differences from `switch`:**

| Feature           | `switch`              | `match`                 |
| ----------------- | --------------------- | ----------------------- |
| Comparison        | Loose (`==`)          | Strict (`===`)          |
| Returns a value?  | No                    | Yes                     |
| Needs `break`?    | Yes                   | No                      |
| Multiple values   | Stacked `case` blocks | Comma-separated values  |
| Throws on no match| No (uses `default`)   | `UnhandledMatchError`   |

> 💡 `match` is preferred over `switch` in PHP 8+ because it's safer (strict comparison) and more concise.

---

## 2. Loops

Loops let you execute a block of code **repeatedly** based on a condition.

### 2.1 while Loop

The condition is checked **before** each iteration. If the condition is `false` from the start, the body **never executes**.

```php
$nums = [12, 42, -2, 8, 621];
$i = 0;
$result = 0;

while ($i < count($nums)) {
    $result += $nums[$i];
    $i++;
}

echo $result; // 681
```

**How it works:**
1. Check: Is `$i < count($nums)`?
2. If yes → add `$nums[$i]` to `$result`, increment `$i`.
3. Repeat until the condition is `false`.

> 💡 **Built-in alternatives:** `array_sum($nums)` or `array_reduce()` can do this without a loop.

---

### 2.2 do-while Loop

Similar to `while`, but the body executes **at least once** because the condition is checked **after** the first iteration.

```php
$nums = [12, 42, -2, 8, 621];
$i = 0;
$result = 0;

do {
    $result += $nums[$i];
    $i++;
} while ($i < count($nums));

echo $result; // 681
```

**When to use:**
- When you need the loop body to run **at least once** regardless of the condition.
- Example: Prompting for user input until valid input is provided.

---

### 2.3 for Loop

A compact loop where the **initialization**, **condition**, and **increment** are all in one line:

```php
$nums = [12, 42, -2, 8, 621];
$result = 0;

for ($i = 0; $i < count($nums); $i++) {
    $result += $nums[$i];
}

echo $result; // 681
```

**Structure:** `for (init; condition; increment) { ... }`
- **init** → runs once before the loop starts.
- **condition** → checked before each iteration.
- **increment** → runs after each iteration.

---

### 2.4 foreach Loop

Designed specifically for **iterating over arrays**. It's the cleanest and most common way to loop through arrays in PHP.

**Indexed array (values only):**

```php
$nums = [12, 42, -2, 8, 621];
$result = 0;

foreach ($nums as $num) {
    $result += $num;
}

echo $result; // 681
```

**Associative array (keys and values):**

```php
$user = ["alice" => 98, "bob" => 95];
$result = [];

foreach ($user as $name => $point) {
    $result[] = $name;
}

print_r($result); // Array ( [0] => alice [1] => bob )
```

> 💡 **Shortcut:** `array_keys($user)` returns all keys without needing a loop.

---

### 2.5 break & continue

These keywords control loop flow:

- **`continue`** — Skips the **current** iteration and moves to the next one.
- **`break`** — Exits the loop **entirely**.

**`continue` example — skip negative numbers:**

```php
$nums = [12, 42, -2, 8, 621];
$i = 0;
$result = 0;

while ($i < count($nums)) {
    if ($nums[$i] < 0) {
        $i++;
        continue; // Skip this number, go to next iteration
    }
    $result += $nums[$i];
    $i++;
}

echo $result; // 683 (sum without -2)
```

**`break` example — stop at first negative number:**

```php
$nums = [12, 42, -2, 8, 621];
$i = 0;
$result = 0;

while ($i < count($nums)) {
    if ($nums[$i] < 0) break; // Stop the loop entirely
    $result += $nums[$i];
    $i++;
}

echo $result; // 54 (only 12 + 42)
```

---

## 3. Ternary Operator

The ternary operator is a **shorthand for simple `if/else`** statements. It's great for assigning values based on a condition in a single line.

### 3.1 Standard Ternary

**Syntax:** `condition ? value_if_true : value_if_false`

```php
$name = "";
echo $name ? $name : "Unknown"; // "Unknown" (empty string is falsy)

$name = "Alice";
echo $name ? $name : "Unknown"; // "Alice"
```

**How it works:**
- If `$name` is **truthy** (not empty, not null, not 0, not false) → use `$name`.
- If `$name` is **falsy** → use `"Unknown"`.

**PHP Falsy Values:** `""`, `0`, `0.0`, `"0"`, `null`, `false`, `[]` (empty array)

---

### 3.2 Short Ternary (Elvis Operator `?:`)

When the true value is the **same as the condition**, you can skip repeating it:

**Syntax:** `value ?: fallback`

```php
$name = "";
echo $name ?: "Unknown"; // "Unknown"

$name = "Alice";
echo $name ?: "Unknown"; // "Alice"
```

This is equivalent to `$name ? $name : "Unknown"` but shorter.

> ⚠️ **Warning:** The Elvis operator triggers a **notice/warning** if the variable is **undefined**. It only checks for falsy values, not whether the variable exists.

---

## 4. Null Coalescing Operator

### 4.1 Null Coalescing Operator (`??`)

Checks if a value **exists and is not null**. It does **NOT** trigger warnings for undefined variables.

**Syntax:** `value ?? fallback`

```php
// $name is not defined
echo $name ?? "Unknown"; // "Unknown" (no warning!)

$name = "Alice";
echo $name ?? "Unknown"; // "Alice"

$name = null;
echo $name ?? "Unknown"; // "Unknown"
```

**This is equivalent to:**

```php
echo isset($name) ? $name : "Unknown";
```

> 💡 `??` is safer than `?:` because it uses `isset()` internally — it won't trigger warnings for undefined variables.

**Chaining:**

```php
// Check multiple values, use the first non-null one
echo $firstName ?? $username ?? "Guest";
```

---

### 4.2 Null Coalescing Assignment Operator (`??=`)

Assigns a value **only if the variable is null or undefined**.

**Syntax:** `$variable ??= value`

```php
$result = "Alice";
$result ??= "Default"; // $result stays "Alice" (it's not null)
echo $result; // "Alice"

$result = null;
$result ??= "Default"; // $result becomes "Default"
echo $result; // "Default"
```

**This is equivalent to:**

```php
$result = $result ?? "Default";
```

**Real-world use:** Setting default configuration values:

```php
$config['timeout'] ??= 30;    // Only set if not already configured
$config['retries'] ??= 3;
```

---

## 5. Key Differences & Comparison Table

| Operator | Syntax | Checks For | Warning on Undefined? |
|---|---|---|---|
| Standard Ternary | `$x ? $a : $b` | Truthiness | ⚠️ Yes |
| Elvis (Short Ternary) | `$x ?: $b` | Truthiness | ⚠️ Yes |
| Null Coalescing | `$x ?? $b` | `null` / undefined | ✅ No |
| Null Coalescing Assignment | `$x ??= $b` | `null` / undefined | ✅ No |

**Important distinction:**

```php
$name = "";    // Empty string

echo $name ?: "Unknown";  // "Unknown" (empty string is falsy)
echo $name ?? "Unknown";  // "" (empty string is NOT null)
```

- `?:` treats `""`, `0`, `false`, `[]` as falsy → uses the fallback.
- `??` only treats `null` and undefined as triggers → keeps the value.

---

## 6. Real-World Usage

### Setting Default User Display Name

```php
// Using null coalescing — safe for potentially undefined values
$displayName = $_GET['name'] ?? $_SESSION['username'] ?? "Guest";
```

### Form Input Handling

```php
// Using ternary — show submitted value or empty string
$email = isset($_POST['email']) ? $_POST['email'] : "";

// Cleaner with null coalescing
$email = $_POST['email'] ?? "";
```

### Configuration Defaults

```php
$config = loadConfig();
$config['db_host'] ??= "localhost";
$config['db_port'] ??= 3306;
$config['debug'] ??= false;
```

### Display Logic in Templates

```php
<?php if ($isLoggedIn): ?>
    <p>Welcome, <?= $username ?: "User" ?></p>
<?php else: ?>
    <p>Please log in.</p>
<?php endif; ?>
```

### Loop with break — Search for an Item

```php
$users = ["Alice", "Bob", "Charlie"];
$found = null;

foreach ($users as $user) {
    if ($user === "Bob") {
        $found = $user;
        break; // Stop searching once found
    }
}

echo $found ?? "Not found";
```

### Loop with continue — Filter Data

```php
$scores = [85, -1, 92, 0, 78, -5, 95];
$validScores = [];

foreach ($scores as $score) {
    if ($score < 0) continue; // Skip invalid scores
    $validScores[] = $score;
}

// Alternatively: array_filter($scores, fn($s) => $s >= 0);
```

### match for HTTP Status Codes

```php
$statusCode = 404;

$message = match (true) {
    $statusCode >= 200 && $statusCode < 300 => "Success",
    $statusCode >= 300 && $statusCode < 400 => "Redirect",
    $statusCode >= 400 && $statusCode < 500 => "Client Error",
    $statusCode >= 500 => "Server Error",
    default => "Unknown"
};

echo $message; // "Client Error"
```

---

## 7. References

- [PHP Manual — Comparison Operators](https://www.php.net/manual/en/language.operators.comparison.php)
- [PHP Manual — Control Structures](https://www.php.net/manual/en/language.control-structures.php)
- [Codementor — Ternary Operator in PHP](https://www.codementor.io/@sayantinideb/ternary-operator-in-php-how-to-use-the-php-ternary-operator-x0ubd3po6)
- [SitePoint — Using the Ternary Operator](https://www.sitepoint.com/using-the-ternary-operator/)
- [Matt Stauffer — Shorter Ternary Operators in PHP](https://mattstauffer.com/blog/even-shorter-ternary-operators-in-php-using/)
- [PHP.Watch — Ternary and Coalescing](https://php.watch/articles/php-ternary-and-coalescing)
- [PHP Tutorial — Null Coalescing Operator](https://www.phptutorial.net/php-tutorial/php-null-coalescing-operator/)

---

## 8. Programming Paradigms

A **programming paradigm** is simply a **style or way of thinking** about how to write code. Different paradigms give you different tools and mental models for solving problems.

> Think of it like cooking styles — you can bake, fry, or steam the same ingredient. Paradigms are different approaches to writing the same program.

PHP and JavaScript both **support multiple paradigms** — you can mix and match depending on what the problem needs.

---

### 8.1 Imperative Programming

**"Tell the computer HOW to do things, step by step."**

Imperative programming is the most natural style — you write instructions one by one, just like a recipe. You manage state explicitly (variables that change over time).

```php
// Imperative: manually add up numbers step by step
$nums = [1, 2, 3, 4, 5];
$total = 0;

for ($i = 0; $i < count($nums); $i++) {
    $total += $nums[$i]; // mutate $total each step
}

echo $total; // 15
```

**Key traits:**
- You control every step of execution.
- Variables change (mutable state).
- Easy to understand for beginners.
- Can become hard to manage in large codebases.

> 💡 **Real world:** Writing a script that reads a file line by line, processes each line, and writes output — that's imperative.

---

### 8.2 Procedural Programming

**"Imperative programming + organising code into reusable procedures (functions)."**

Procedural programming takes imperative code and groups related steps into named **functions/procedures**. PHP was originally designed as a procedural language.

```php
// Procedural: same logic wrapped in a reusable function
function sumArray(array $nums): int {
    $total = 0;
    foreach ($nums as $num) {
        $total += $num;
    }
    return $total;
}

echo sumArray([1, 2, 3, 4, 5]); // 15
echo sumArray([10, 20, 30]);     // 60
```

**Key traits:**
- Code is broken into functions (procedures).
- Functions can call other functions.
- Easier to reuse and test than raw imperative code.
- Still uses mutable state.

> 💡 **Real world:** Classic PHP scripts like `login.php`, `register.php` with functions like `validateEmail()`, `hashPassword()`, `saveUser()` — all procedural.

**Procedural vs Imperative — the simple difference:**

| | Imperative | Procedural |
|---|---|---|
| Core idea | Step-by-step instructions | + Grouped into functions |
| Code reuse | Copy-paste | Call a function |
| Example | Raw `for` loop | Function wrapping a loop |

> Procedural **is** imperative, but adds the concept of procedures (functions) to organise the steps.

---

### 8.3 Declarative Programming

**"Tell the computer WHAT you want, not HOW to do it."**

In declarative programming, you describe the *result* you want and let the language/runtime figure out the steps. SQL is the classic example — you say "give me all users over 18" and the database decides how to find them.

```php
// Imperative approach — describe HOW
$nums = [1, 2, 3, 4, 5];
$result = [];
foreach ($nums as $num) {
    if ($num % 2 === 0) {
        $result[] = $num * 2;
    }
}

// Declarative approach — describe WHAT (using built-in functions)
$result = array_map(
    fn($n) => $n * 2,
    array_filter($nums, fn($n) => $n % 2 === 0)
);

print_r($result); // [4, 8]
```

**Key traits:**
- Focus on *what* the outcome should be.
- Hide the implementation details.
- Examples: SQL, HTML, CSS, regex, PHP's `array_map` / `array_filter`.
- Usually shorter and more readable for the right problems.

> 💡 **Real world:** HTML is declarative — you say `<button>Click me</button>` and the browser figures out how to render it. You don't write pixel-drawing code.

---

### 8.4 Object-Oriented Programming (OOP)

**"Organise code into objects that bundle data (properties) and behaviour (methods) together."**

OOP models the world as **objects** — an object is a thing that has:
- **Properties** (data it knows about itself)
- **Methods** (actions it can perform)

```php
// OOP: a User is an object with data + behaviour
class User {
    public string $name;
    public string $email;

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

**Four pillars of OOP:**

| Pillar | Simple explanation |
|---|---|
| **Encapsulation** | Bundle data + behaviour; hide internal details |
| **Inheritance** | A child class gets the parent's properties/methods |
| **Polymorphism** | Different objects respond to the same method differently |
| **Abstraction** | Expose only what's necessary; hide complexity |

> 💡 **Real world:** A `Cart` class that holds items and has `addItem()`, `removeItem()`, `getTotal()` methods — that's OOP.

---

### 8.5 Paradigm Comparison Table

| Paradigm | Focus | State | PHP / JS Use Case |
|---|---|---|---|
| **Imperative** | HOW — step by step | Mutable | Simple scripts, loops |
| **Procedural** | HOW — with functions | Mutable | PHP scripts, utility files |
| **Declarative** | WHAT — describe the outcome | Often immutable | SQL queries, `array_map/filter` |
| **OOP** | WHO — objects with state & behaviour | Encapsulated | Laravel models, JS classes |
| **Functional** | WHAT — pure functions, no side effects | Immutable | Data pipelines, transformations |

> 🧠 **PHP is multi-paradigm** — you can write procedural code, OOP code, and functional-style code in the same file. Use whatever fits the problem best.

---

## 9. Modular Architecture

### 9.1 What is Modularity?

**Modularity** means breaking your application into **small, independent, self-contained pieces** (modules) — each responsible for one specific thing.

Think of LEGO bricks — each brick does its own job, and you can combine them to build anything.

**Why it matters:**
- 🔍 **Easier to understand** — each piece is small and focused.
- ♻️ **Reusable** — use the same module in multiple places.
- 🧪 **Easier to test** — test each piece in isolation.
- 🛠 **Easier to maintain** — changing one module doesn't break everything else.
- 👥 **Team-friendly** — different developers can work on different modules.

> 💡 **Single Responsibility Principle (SRP):** A module should do **one thing** and do it well.

---

### 9.2 Modular Monolith

A **monolith** is one big application. A **microservice** architecture splits everything into tiny networked services. A **modular monolith** is the sweet spot in between.

```
Traditional Monolith          Modular Monolith            Microservices
─────────────────────        ──────────────────────       ──────────────────
One giant codebase           One codebase, split          Many separate apps,
Everything tangled           into clear modules           each deployed alone
                             [Users] [Orders] [Products]
```

**Modular Monolith benefits:**
- Still one deployable application (simple to deploy).
- Code is organised into well-defined modules with clear boundaries.
- Modules only communicate through defined interfaces (APIs/contracts).
- Easy to later extract a module into a microservice if needed.

> 💡 **Real world:** Laravel is a perfect example of a modular monolith — it has separate modules for Auth, Mail, Queue, Database, etc., all inside one application.

---

### 9.3 PHP Modularity in Practice

PHP achieves modularity through:

**`include` / `require` — load external files:**

```php
// functions/math.php
function add(int $a, int $b): int {
    return $a + $b;
}

// index.php
require 'functions/math.php'; // load the module
echo add(3, 4); // 7
```

**`require` vs `include`:**

| | `include` | `require` |
|---|---|---|
| File not found | Warning, continues | Fatal error, stops |
| Use when | File is optional | File is essential |

**Namespaces — avoid name conflicts between modules:**

```php
// Module: App\Auth
namespace App\Auth;

class User {
    public function login(string $email, string $password): bool {
        // login logic
        return true;
    }
}

// Module: App\Blog
namespace App\Blog;

class User {           // Different User — no conflict!
    public string $slug;
}
```

> 💡 **Autoloading:** Modern PHP (with Composer) auto-loads files by namespace, so you don't need to manually `require` every file.

---

## 10. First-Class Functions

### 10.1 What Does "First-Class" Mean?

In programming, when we say something is a **"first-class citizen"**, it means that thing can be:

1. ✅ **Assigned to a variable**
2. ✅ **Passed as an argument** to another function
3. ✅ **Returned from** a function
4. ✅ **Stored in data structures** (arrays, objects)

**First-class functions** means functions are treated like any other value — just like a number or a string.

> 💡 Both **PHP** and **JavaScript** support first-class functions. This is what makes functional programming possible in these languages.

---

### 10.2 Functions as Values in PHP

**1. Assign a function to a variable (anonymous function / closure):**

```php
// A function stored in a variable
$greet = function(string $name): string {
    return "Hello, $name!";
};

echo $greet("Alice"); // "Hello, Alice!"
echo $greet("Bob");   // "Hello, Bob!"
```

**2. Arrow functions (PHP 7.4+) — shorter syntax:**

```php
$double = fn($n) => $n * 2;

echo $double(5);  // 10
echo $double(10); // 20
```

**3. Pass a function as an argument:**

```php
function applyToAll(array $items, callable $fn): array {
    $result = [];
    foreach ($items as $item) {
        $result[] = $fn($item);
    }
    return $result;
}

$nums    = [1, 2, 3, 4, 5];
$doubled = applyToAll($nums, fn($n) => $n * 2);

print_r($doubled); // [2, 4, 6, 8, 10]
```

**4. Return a function from a function:**

```php
// A "function factory" — creates and returns a new function
function makeMultiplier(int $factor): callable {
    return fn($n) => $n * $factor;
}

$triple = makeMultiplier(3);
$times5 = makeMultiplier(5);

echo $triple(4); // 12
echo $times5(4); // 20
```

---

### 10.3 Higher-Order Functions

A **higher-order function (HOF)** is a function that:
- Takes one or more functions as **arguments**, OR
- **Returns** a function

```php
// array_map is a built-in higher-order function
$names  = ["alice", "bob", "charlie"];
$upper  = array_map('strtoupper', $names);

print_r($upper); // ["ALICE", "BOB", "CHARLIE"]

// array_filter is also a HOF
$scores = [45, 72, 38, 91, 55, 88];
$passed = array_filter($scores, fn($s) => $s >= 60);

print_r($passed); // [72, 91, 88]
```

> 💡 **JavaScript parallel:**
> ```js
> // JS has the same pattern
> const double = n => n * 2;
> [1, 2, 3].map(double); // [2, 4, 6]
> ```

---

## 11. Functional Programming

### 11.1 Core Principles

**Functional Programming (FP)** is a paradigm that treats computation as **evaluation of pure functions**, avoiding shared state and mutable data.

**Core ideas:**
1. **Pure functions** — same input always gives same output, no side effects.
2. **Immutability** — don't change existing data; create new data instead.
3. **Function composition** — build complex behaviour from simple functions.
4. **Higher-order functions** — functions that work with other functions.
5. **Declarative style** — describe *what* to compute, not *how*.

> 💡 FP doesn't mean "no variables ever" — it means preferring functions that don't cause hidden side effects and don't depend on external state.

---

### 11.2 Pure Functions

A **pure function**:
- Always returns the **same output** for the same input.
- Has **no side effects** (doesn't modify external state, no database calls, no printing).

```php
// ✅ PURE — same input always gives same output
function add(int $a, int $b): int {
    return $a + $b;
}

echo add(2, 3); // always 5
echo add(2, 3); // always 5

// ❌ IMPURE — depends on external state ($total)
$total = 0;
function addToTotal(int $n): void {
    global $total;
    $total += $n; // modifies external variable — side effect!
}
```

**Why pure functions matter:**
- Easy to **test** — no setup or teardown needed.
- Easy to **reason about** — no hidden surprises.
- Safe to run in **parallel** — no shared state to conflict.

---

### 11.3 Immutability

Instead of changing existing data, **create new data**:

```php
// ❌ Mutable — modifying the original array
$prices = [100, 200, 300];
foreach ($prices as &$price) {
    $price *= 1.1; // mutates original!
}

// ✅ Immutable — create a new array, leave original untouched
$prices    = [100, 200, 300];
$newPrices = array_map(fn($p) => $p * 1.1, $prices);

print_r($prices);    // [100, 200, 300] — unchanged
print_r($newPrices); // [110, 220, 330] — new array
```

> 💡 **Real world:** In a shopping cart, don't mutate the cart directly — return a new cart with the item added. This makes undo/redo, history, and debugging much simpler.

---

### 11.4 Functional PHP Built-ins

PHP has excellent built-in higher-order functions that let you write functional-style code:

**`array_map` — transform every item:**

```php
$names  = ["alice", "bob", "charlie"];
$titled = array_map('ucfirst', $names);
// ["Alice", "Bob", "Charlie"]
```

**`array_filter` — keep items that pass a test:**

```php
$ages   = [15, 22, 17, 30, 13, 25];
$adults = array_filter($ages, fn($age) => $age >= 18);
// [22, 30, 25]
```

**`array_reduce` — collapse an array to a single value:**

```php
$nums  = [1, 2, 3, 4, 5];
$total = array_reduce($nums, fn($carry, $item) => $carry + $item, 0);
echo $total; // 15

// Real world: total price of cart items
$cart = [
    ['name' => 'Book',   'price' => 15],
    ['name' => 'Pen',    'price' => 5],
    ['name' => 'Laptop', 'price' => 999],
];
$cartTotal = array_reduce($cart, fn($sum, $item) => $sum + $item['price'], 0);
echo $cartTotal; // 1019
```

**Chaining functional operations:**

```php
// Get the total price of only items priced above $10
$cart = [
    ['name' => 'Notebook', 'price' => 3],
    ['name' => 'Book',     'price' => 15],
    ['name' => 'Laptop',   'price' => 999],
    ['name' => 'Pen',      'price' => 2],
];

$total = array_reduce(
    array_filter($cart, fn($item) => $item['price'] > 10),
    fn($sum, $item) => $sum + $item['price'],
    0
);

echo $total; // 1014 (15 + 999)
```

---

## 12. PHP Functions (Part 1)

### 12.1 Declaring Functions

A **function** is a named, reusable block of code. You define it once, call it many times.

**Basic syntax:**

```php
function functionName(parameters): returnType {
    // function body
    return value;
}
```

**Simple example:**

```php
function greet(string $name): string {
    return "Hello, $name!";
}

echo greet("Alice"); // "Hello, Alice!"
echo greet("Bob");   // "Hello, Bob!"
```

> 💡 **JS parallel:** PHP functions work the same way as JavaScript functions, just with `$` for variables and optional type hints.
> ```js
> function greet(name) { return `Hello, ${name}!`; }
> ```

---

### 12.2 Parameters & Return Values

**Parameters** are inputs to the function. **Return values** are the output.

```php
// echo inside — no return value (procedure style)
function printSum(int $a, int $b): void {
    echo $a + $b;
}
printSum(3, 4); // prints: 7

// return a value — caller decides what to do with it
function add(int $a, int $b): int {
    return $a + $b;
}

$result = add(3, 4);          // store it
echo add(10, 20);             // or print directly — 30
$doubled = add(5, 5) * 2;     // or use in an expression — 20
```

> 💡 **Prefer `return` over `echo` inside functions.** Returning a value is more flexible — you can echo it, store it, pass it somewhere else, or test it.

---

### 12.3 Default Parameters

You can give a parameter a **default value** — it's used when the caller doesn't pass that argument.

```php
function add(int $a, int $b = 0): int {
    return $a + $b;
}

echo add(5, 3); // 8  — $b = 3
echo add(5);    // 5  — $b = 0 (default)

// Real world: optional tax rate
function calculateTotal(float $price, float $taxRate = 0.1): float {
    return $price + ($price * $taxRate);
}

echo calculateTotal(100);      // 110 (10% tax)
echo calculateTotal(100, 0.2); // 120 (20% tax)
```

> ⚠️ **Rule:** Parameters with default values must come **after** required parameters.
> ```php
> // ✅ Correct
> function greet(string $name, string $prefix = "Hello"): string { ... }
>
> // ❌ Wrong — required after optional
> function greet(string $prefix = "Hello", string $name): string { ... }
> ```

---

### 12.4 Rest Parameters

When you don't know how many arguments a function will receive, use the **rest parameter** (`...`).

**Modern PHP (rest parameter — PHP 5.6+):**

```php
function add(int $a, int ...$b): int {
    return $a + array_sum($b);
}

echo add(1);          // 1
echo add(1, 2, 3, 4); // 10 ($b = [2, 3, 4])
```

**Older PHP (before rest parameter — `func_get_args()`):**

```php
function add(): int {
    $args = func_get_args(); // gets all arguments as an array
    return array_sum($args);
}

echo add(1, 2, 3, 4); // 10
```

> 💡 **JS parallel:**
> ```js
> function add(a, ...b) { return a + b.reduce((s, n) => s + n, 0); }
> add(1, 2, 3, 4); // 10
> ```

---

### 12.5 Type Hints

**Type hints** (also called type declarations) let you specify what **type** a parameter or return value must be. PHP will enforce this at runtime.

**Scalar type hints (PHP 7+):** `int`, `float`, `string`, `bool`

```php
function multiply(int $a, int $b): int {
    return $a * $b;
}

echo multiply(3, 4);     // 12
// multiply("3", "4");   // TypeError in strict mode
```

**Array type hint:**

```php
function sumNumbers(array $nums): int {
    return array_sum($nums);
}

echo sumNumbers([1, 2, 3]); // 6
```

**Nullable type (`?type` — PHP 7.1+):**

```php
function findUser(?int $id): ?string {
    if ($id === null) return null;
    return "User #$id";
}

echo findUser(5);    // "User #5"
echo findUser(null); // null (no error)
```

**Why use type hints?**
- 🐛 Catch bugs early — PHP tells you when you pass the wrong type.
- 📖 Self-documenting — anyone reading the code knows what types to use.
- 🛠 Better IDE support — autocomplete and warnings.

---

### 12.6 Useful Built-in Functions

PHP has hundreds of built-in functions. Here are the ones from today's session:

**String functions:**

```php
strlen("Hello");          // 5 — string length
strtoupper("hello");      // "HELLO"
strtolower("HELLO");      // "hello"
ucfirst("hello world");   // "Hello world"
str_replace("o", "0", "Hello World"); // "Hell0 W0rld"
```

**Array functions:**

```php
$nums = [3, 1, 4, 1, 5, 9];

count($nums);             // 6 — number of elements
array_sum($nums);         // 23 — sum all numbers
array_push($nums, 2);     // add to end
array_pop($nums);         // remove from end
sort($nums);              // sort ascending
array_reverse($nums);     // reverse order

// Functional-style
array_map(fn($n) => $n * 2, $nums);       // transform
array_filter($nums, fn($n) => $n > 3);    // filter
array_reduce($nums, fn($c, $n) => $c + $n, 0); // reduce
```

**JS ↔ PHP cheat sheet:**

| JavaScript | PHP |
|---|---|
| `"hello".length` | `strlen("hello")` |
| `[1,2,3].length` | `count([1,2,3])` |
| `[1,2,3].reduce((a,b) => a+b)` | `array_sum([1,2,3])` or `array_reduce(...)` |
| `[1,2,3].map(fn)` | `array_map(fn, [1,2,3])` |
| `[1,2,3].filter(fn)` | `array_filter([1,2,3], fn)` |
| `Math.round(3.7)` | `round(3.7)` |
| `"alert".toFixed(2)` | `number_format(3.1416, 2)` |

---

## 13. References

- [PHP Manual — Functions](https://www.php.net/manual/en/language.functions.php)
- [PHP Tutorial — PHP Functions](https://www.phptutorial.net/php-tutorial/php-functions/)
- [TutorialsPoint — PHP Functions](https://www.tutorialspoint.com/php/php_functions.htm)
- [GeeksForGeeks — PHP Functions](https://www.geeksforgeeks.org/php/php-functions/)
- [GeeksForGeeks — Procedural vs OOP](https://www.geeksforgeeks.org/software-engineering/differences-between-procedural-and-object-oriented-programming/)
- [MDN — First-class Function](https://developer.mozilla.org/en-US/docs/Glossary/First-class_Function)
- [GeeksForGeeks — First-Class Functions in JavaScript](https://www.geeksforgeeks.org/javascript/what-is-the-first-class-function-in-javascript/)
- [JScrambler — Higher-Order & First-Class Functions Explained](https://jscrambler.com/blog/high-order-function-first-class-function-explained)
- [The Server Side — Principles of Functional Programming](https://www.theserverside.com/tip/Understanding-the-principles-of-functional-programming)
- [Milan Jovanović — What is a Modular Monolith](https://www.milanjovanovic.tech/blog/what-is-a-modular-monolith)
- [dotNET Full Stack Dev — Modular Monolith](https://dotnetfullstackdev.medium.com/modular-monolith-the-sweet-spot-between-monoliths-and-microservices-c515246f585d)
- [Anjireddy Kata — Architecture 101: Modular Monolith](https://anjireddy-kata.medium.com/architecture-101-modular-monolith-a-primer-36864f045697)

