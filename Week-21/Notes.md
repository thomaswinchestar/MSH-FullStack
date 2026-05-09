# Week 21 — PHP Design Patterns Part 2, PHP Error Handling & PHP Modules and Namespaces

---

## Table of Contents

1. [Dependency Injection (DI)](#1-dependency-injection-di)
   - [What is DI?](#11-what-is-di)
   - [DI with Interface (Logger Example)](#12-di-with-interface-logger-example)
   - [DI with Factory & Provider (Service Container)](#13-di-with-factory--provider-service-container)
2. [Repository Pattern](#2-repository-pattern)
   - [What is it?](#21-what-is-it)
   - [Data Access Patterns Overview](#22-data-access-patterns-overview)
   - [Repository Pattern in Code](#23-repository-pattern-in-code)
   - [Real-World Use Cases](#24-real-world-use-cases)
3. [PHP Error Handling Part 1](#3-php-error-handling-part-1)
   - [PHP Error Types](#31-php-error-types)
   - [Strict Types — E_STRICT](#32-strict-types--e_strict)
   - [php.ini — Where PHP Settings Live](#33-phpini--where-php-settings-live)
   - [Controlling Errors with error_reporting()](#34-controlling-errors-with-error_reporting)
   - [PHP Exception Handling — try/catch/throw](#35-php-exception-handling--trycatchthrow)
4. [PHP Error Handling Part 2](#4-php-error-handling-part-2)
   - [Custom Exception Hierarchy](#41-custom-exception-hierarchy)
   - [Exception Chaining](#42-exception-chaining)
   - [PHP 7+ Throwable — Catching Everything](#43-php-7-throwable--catching-everything)
   - [Custom Error Handler — set_error_handler()](#44-custom-error-handler--set_error_handler)
   - [Global Exception Handler — set_exception_handler()](#45-global-exception-handler--set_exception_handler)
   - [Error Handling Best Practices](#46-error-handling-best-practices)
5. [PHP Modules and Namespaces](#5-php-modules-and-namespaces)
   - [The Problem — Name Collisions](#51-the-problem--name-collisions)
   - [PHP File Inclusion — include vs require](#52-php-file-inclusion--include-vs-require)
   - [Namespaces — Organising Your Code](#53-namespaces--organising-your-code)
   - [Global Namespace and Sub-Namespaces](#54-global-namespace-and-sub-namespaces)
   - [use Keyword and Aliases](#55-use-keyword-and-aliases)
   - [PSR-4 — PHP Standard Recommendation](#56-psr-4--php-standard-recommendation)
   - [Class Autoloading — spl_autoload_register](#57-class-autoloading--spl_autoload_register)
   - [Composer Autoloading](#58-composer-autoloading)
   - [Real-World Project Structure](#59-real-world-project-structure)
6. [Patterns Summary Table](#6-patterns-summary-table)
7. [References](#7-references)

---

## 1. Dependency Injection (DI)

### 1.1 What is DI?

**Dependency Injection** is a technique where a class **receives** its dependencies from **outside** instead of creating them itself.

This is the **practical implementation** of the **D** in SOLID — Dependency Inversion Principle.

```
Tight Coupling (BAD):   Class creates its own dependencies with `new`
Loose Coupling (GOOD):  Dependencies are injected (passed in) from outside
```

**Simple Real-Life Analogy:**

> 🍕 A pizza shop that bakes its own cheese is tightly coupled to one cheese brand. If that brand closes, the shop breaks.
>
> A pizza shop that **accepts any cheese delivered to it** is loosely coupled. You can swap cheese suppliers without changing the shop's recipes.

---

### 1.2 DI with Interface (Logger Example)

From your `PHP-Essential-Design-Pattern-2.php`:

```php
// Step 1 — Define an abstraction (interface), NOT a concrete class
interface Log {
    public function write($log);
}

// Step 2 — Concrete implementations
class TextLogger implements Log {
    public function write($log) {
        // Writes to a text file
        echo $log;
    }
}

class DatabaseLogger implements Log {
    public function write($log) {
        // Writes to a database
        echo $log;
    }
}

// Step 3 — App depends on the INTERFACE, not a concrete class
class App {
    private $logger;

    // ✅ Logger is INJECTED via constructor — App doesn't create it
    public function __construct(Log $logger) {
        $this->logger = $logger;
    }

    public function run() {
        $this->logger->write("App is running");
    }
}

// ✅ Swap between loggers with ZERO changes to App
$app = new App(new TextLogger());
$app->run(); // writes to text file

$app = new App(new DatabaseLogger());
$app->run(); // writes to database
```

**What's happening step by step:**

```
Without DI:
App → creates new TextLogger() inside itself
     → tightly coupled, can't swap without editing App

With DI:
App ← receives TextLogger or DatabaseLogger from outside
     → loosely coupled, swap freely without touching App
```

**Why this matters:**

| Scenario | Without DI | With DI |
|---|---|---|
| Switch from file log to DB log | Edit App class | Just inject DatabaseLogger |
| Testing App in isolation | Must mock file system | Inject a fake test logger |
| Adding Slack logger later | Edit App class again | Create SlackLogger, inject it |

---

### 1.3 DI with Factory & Provider (Service Container)

Instead of manually injecting `new TextLogger()` or `new DatabaseLogger()` everywhere, you can combine DI with the **Provider/Factory Pattern** to build a **Service Container** — the heart of modern frameworks like Laravel.

```php
// Register services into a container
$services = new Services();
$services->register("app", function() {
    return new App(new DatabaseLogger()); // wired up here — once
});

// Later, anywhere in the app:
$app = $provider->make("app"); // no `new`, no wiring — just ask by name
$app->run();
```

This is exactly what `app()->bind()` and `app()->make()` do in **Laravel's IoC Container**.

```
IoC = Inversion of Control
→ Instead of YOUR code controlling which dependencies to create,
  the CONTAINER controls it — and injects what's needed automatically.
```

**Real-World Scenario — E-Commerce Platform:**

A web shop has:
- `StripePayment` in production
- `FakePayment` in tests

```php
// In production bootstrap:
$container->bind('payment', StripePayment::class);

// In test bootstrap:
$container->bind('payment', FakePayment::class);

// Your OrderController never changes — it just asks the container:
$payment = $container->make('payment');
$payment->charge(99.99);
```

> 💡 The **Provider/Service Container is Dependency Injection at scale** — it automates the wiring of your entire application so you never need to `new` anything in your business logic.

---

## 2. Repository Pattern

### 2.1 What is it?

The **Repository Pattern** is a **data access pattern** that acts as a **layer between your application logic and your database**. It was made famous by **Eric Evans** in his book *Domain-Driven Design (DDD)*.

```
"A repository is like a collection of objects in memory — but backed by a database."
```

**Without Repository:** Your `App` class directly talks to the database (tight coupling).  
**With Repository:** Your `App` class talks to a `Repository`, which talks to the database.

```
App  →  Repository  →  Database
```

This means your `App` logic doesn't care **how** data is stored — SQL, MongoDB, file, API. You just call `$repo->update($data)`.

---

### 2.2 Data Access Patterns Overview

Before the Repository Pattern became standard, developers used several other approaches to manage data:

| Pattern | Description | Example |
|---|---|---|
| **Raw SQL** | Write SQL queries directly in app code | `mysqli_query($conn, "SELECT * FROM users")` |
| **Table Gateway** | One class per database table, contains all SQL for that table | `UserTable::findById(1)` |
| **ORM (Object Relational Mapping)** | Maps database rows ↔ PHP objects automatically | Eloquent, Doctrine |
| **Repository Pattern** | Abstract layer over data storage — app doesn't know or care about DB internals | `UserRepository::find(1)` |
| **Data Abstraction Layer (DAL)** | A broader term for isolating data access logic from business logic | Any of the above used systematically |

```
Evolution of Data Access:
Raw SQL → Table Gateway → Active Record (ORM) → Repository Pattern (DDD)
```

> **ORM vs Repository:**
> - ORM (like Laravel's Eloquent) handles the *how* (SQL generation, mapping).
> - Repository handles the *what* (business-level data queries like `getUsersWithActiveOrders()`).
> - In Laravel, your Eloquent Model **is** your ORM layer, and you can wrap it in a Repository for even cleaner separation.

---

### 2.3 Repository Pattern in Code

From your `PHP-Essential-Design-Pattern-2.php`:

```php
// Model — represents a data entity, knows how to persist itself
#[AllowDynamicProperties]
class Model {
    public function save(): void {
        echo "Saving $this->name and $this->age";
        // In real code: INSERT or UPDATE in DB via PDO/ORM
    }
}

// Repository — handles all data operations for a specific entity
class Repository {
    public function update($data): void {
        // 1. Extract and sanitise the data
        $name = $data['name'] ?? "Unknown";
        $age  = $data['age']  ?? "Unknown";

        // 2. Create and populate the Model
        $model       = new Model();
        $model->name = $name;
        $model->age  = $age;

        // 3. Persist it
        $model->save();
    }
}

// App — contains business logic, depends on Repository
class App {
    private $repo;

    // Repository is INJECTED (DI in action!)
    public function __construct(Repository $repo) {
        $this->repo = $repo;
    }

    public function update($data): void {
        // App doesn't touch the database — it delegates to the Repository
        $this->repo->update($data);
    }
}

// Usage
$app = new App(new Repository());
$app->update(['name' => 'John Doe', 'age' => 20]);
// Output: Saving John Doe and 20
```

**Layers breakdown:**

```
┌─────────────────────────────────────────────┐
│                App (Business Logic)          │
│  $app->update(['name' => 'John', 'age' => 20])│
└──────────────────────┬──────────────────────┘
                       │ delegates to
┌──────────────────────▼──────────────────────┐
│              Repository (Data Access)        │
│  Handles: validation, defaults, model setup  │
└──────────────────────┬──────────────────────┘
                       │ uses
┌──────────────────────▼──────────────────────┐
│                Model (ORM / DB Layer)        │
│  save() → INSERT/UPDATE via SQL/PDO/ORM      │
└─────────────────────────────────────────────┘
```

### A More Complete Repository Example

In a real project, a Repository usually has multiple methods:

```php
interface UserRepositoryInterface {
    public function findById(int $id): ?array;
    public function findAll(): array;
    public function create(array $data): void;
    public function update(int $id, array $data): void;
    public function delete(int $id): void;
}

class UserRepository implements UserRepositoryInterface {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db; // Database injected — not created here!
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findAll(): array {
        return $this->db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): void {
        $stmt = $this->db->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->execute([$data['name'], $data['email']]);
    }

    public function update(int $id, array $data): void {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['email'], $id]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// UserService — business logic, no SQL anywhere here
class UserService {
    public function __construct(private UserRepositoryInterface $users) {}

    public function registerUser(string $name, string $email): void {
        // Business rule: email must be unique
        $existing = $this->users->findAll();
        foreach ($existing as $user) {
            if ($user['email'] === $email) {
                throw new \RuntimeException("Email already registered.");
            }
        }
        $this->users->create(['name' => $name, 'email' => $email]);
        echo "User '$name' registered successfully.\n";
    }
}

// Wire everything up (or let a Service Container do this automatically)
$pdo        = new PDO('mysql:host=localhost;dbname=shop', 'root', '');
$userRepo   = new UserRepository($pdo);
$userService = new UserService($userRepo);

$userService->registerUser("Alice", "alice@example.com");
```

---

### 2.4 Real-World Use Cases

**1. Switching from MySQL to MongoDB without touching business logic:**

```php
// MySQL version
class MySQLUserRepository implements UserRepositoryInterface { /* ... */ }

// MongoDB version — same interface, different internals
class MongoUserRepository implements UserRepositoryInterface { /* ... */ }

// Business logic never changes — just swap the injected repository
$service = new UserService(new MongoUserRepository($mongoClient));
```

**2. In-memory repository for testing (no real database needed):**

```php
class InMemoryUserRepository implements UserRepositoryInterface {
    private array $users = [];

    public function create(array $data): void {
        $this->users[] = $data;
    }

    public function findAll(): array {
        return $this->users;
    }
    // ... other methods
}

// Tests run fast — no DB connection needed!
$testRepo    = new InMemoryUserRepository();
$userService = new UserService($testRepo);
$userService->registerUser("Test User", "test@example.com");
```

**3. In Laravel — Repository wrapping Eloquent:**

```php
class EloquentUserRepository implements UserRepositoryInterface {
    public function findById(int $id): ?array {
        return User::find($id)?->toArray();
    }

    public function create(array $data): void {
        User::create($data);
    }
    // ...
}

// Registered in a Service Provider:
$this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

// In a Controller — injected automatically by Laravel's IoC Container:
class UserController extends Controller {
    public function __construct(private UserRepositoryInterface $users) {}

    public function store(Request $request) {
        $this->users->create($request->validated());
        return response()->json(['message' => 'User created'], 201);
    }
}
```

> 🌍 **Real-world analogy:** A restaurant menu is your **Repository Interface** — it defines what you can order (findById, findAll, create...). Whether the kitchen uses a gas stove (MySQL), electric (MongoDB), or a microwave (In-memory for tests), you as a customer only interact with the menu. The kitchen implementation can change — your ordering experience does not.

---

## 3. PHP Error Handling Part 1

### 3.1 PHP Error Types

PHP has several levels of errors. Understanding each one helps you know how serious a problem is and whether your script will continue running or crash.

```php
// 1. E_PARSE — Syntax Error
// The code can't even be parsed/read. Script never runs.
echo "Hello World"   // ← missing semicolon = E_PARSE

// 2. E_ERROR — Fatal Error
// Critical problem. Script STOPS immediately.
// Example: calling a function that doesn't exist
nonExistentFunction(); // Fatal Error: Call to undefined function

// 3. E_WARNING — Warning
// Something is wrong but NOT fatal. Script CONTINUES running.
// Example: include a file that doesn't exist
include "missing-file.php"; // Warning: Failed to open stream

// 4. E_NOTICE — Notice
// Minor issue — usually accessing an undefined variable. Script CONTINUES.
echo $undefinedVariable; // Notice: Undefined variable

// 5. E_STRICT — Strict Standards
// Code works but doesn't follow best practices.
// Triggered by declare(strict_types=1)

// 6. E_DEPRECATED
// You're using something that still works but will be REMOVED in a future PHP version.
// Example: using the old `ereg()` function (removed in PHP 7)

// 7. E_ALL
// Shorthand for ALL error levels combined
```

**Error Severity Summary:**

| Error Type | Script Stops? | Severity | Common Cause |
|---|:---:|---|---|
| `E_PARSE` | ✅ Yes | 🔴 Critical | Missing `;`, `{`, `)` |
| `E_ERROR` | ✅ Yes | 🔴 Critical | Undefined function/class, fatal logic |
| `E_WARNING` | ❌ No | 🟠 Medium | Missing file, wrong argument type |
| `E_NOTICE` | ❌ No | 🟡 Minor | Undefined variable, undefined array key |
| `E_STRICT` | ❌ No | 🔵 Style | Non-standard code practices |
| `E_DEPRECATED` | ❌ No | 🟡 Minor | Using old/removed features |

---

### 3.2 Strict Types — E_STRICT

PHP is normally **loosely typed** — it will try to convert values between types automatically:

```php
function add(int $a, int $b) {
    echo $a + $b;
}

add(1, "2"); // PHP silently converts "2" (string) to 2 (int) → outputs 3
```

But when you enable **strict types**, PHP enforces exact types and throws an `E_STRICT` error if wrong type is passed:

```php
declare(strict_types = 1); // Must be the VERY FIRST line in the file

function add(int $a, int $b) {
    echo $a + $b;
}

add(1, "2");
// Fatal error: Uncaught TypeError: add(): Argument #2 ($b) must be of type int, string given
```

**Why use declare(strict_types=1)?**

| Without strict_types | With strict_types |
|---|---|
| PHP silently coerces types | PHP enforces exact types |
| Bugs hide until production | Type errors caught immediately |
| "2.5" passed as int becomes 2 silently | Error thrown — you know right away |
| Harder to debug | Clearer, safer code |

> 💡 **Best practice:** Always use `declare(strict_types=1)` at the top of every PHP file in professional projects. Laravel recommends this.

---

### 3.3 php.ini — Where PHP Settings Live

`php.ini` is PHP's **master configuration file**. Many error settings are controlled here.

**Location:**
- **Windows (XAMPP):** `C:\xampp\php\php.ini`
- **Linux:** `/etc/php/{version}/cli/php.ini` or `/etc/php/{version}/apache2/php.ini`
- Find it programmatically: `php --ini` in the terminal

**Important error settings in php.ini:**

```ini
; Show errors on screen (ON for development, OFF for production!)
display_errors = On

; Log errors to a file (On for production)
log_errors = On
error_log = /path/to/error.log

; What level of errors to report
error_reporting = E_ALL

; Maximum execution time before timeout
max_execution_time = 30
```

> ⚠️ **NEVER** have `display_errors = On` in production! It exposes your code internals, database names, and file paths to users — a security risk.

```
Development php.ini:         Production php.ini:
display_errors = On          display_errors = Off
error_reporting = E_ALL      error_reporting = E_ALL & ~E_DEPRECATED
log_errors = Off             log_errors = On
```

---

### 3.4 Controlling Errors with error_reporting()

You can also control error reporting **directly in your PHP code** (overrides php.ini for that script):

```php
// Turn off ALL error reporting (hide everything — not recommended for development)
error_reporting(0);

// Show ALL errors (best for development)
error_reporting(E_ALL);
error_reporting(-1);   // Same as E_ALL — -1 means all bits set

// Show only Parse + Fatal + Warning errors
error_reporting(E_PARSE | E_ERROR | E_WARNING);
// The | is bitwise OR — combine multiple error levels

// Show everything EXCEPT Notices
error_reporting(E_ALL & ~E_NOTICE);
// The & ~ is bitwise AND NOT — remove a specific level from E_ALL

// Show everything EXCEPT Deprecated + Notices
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
```

**The Bitwise Logic Explained Simply:**

```
E_ALL            = show ALL types
E_ALL & ~E_NOTICE = show ALL types, EXCEPT remove (turn off) E_NOTICE

Analogy:
E_ALL        = "show everything"
~E_NOTICE    = "NOT notice"
& (AND)      = "show everything AND NOT notice" = everything except notices
```

**When to use what:**

```php
// During development — see everything
error_reporting(E_ALL);

// During production — hide notices and deprecations (still see real errors)
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

// When debugging a legacy system that has tons of notices you can't fix yet
error_reporting(E_ERROR | E_WARNING | E_PARSE);
```

---

### 3.5 PHP Exception Handling — try/catch/throw

Errors are PHP's own system. **Exceptions** are how *your code* signals and handles expected error situations gracefully.

**The Pattern:**

```
try   → code that MIGHT fail
throw → signals that something went wrong (throws an Exception object)
catch → catches the exception and handles it (prevents crash)
```

**Basic Example from your code:**

```php
function add($nums) {
    // Guard clause — check input, throw if invalid
    if (!is_array($nums)) {
        throw new Exception("Argument must be an array");
    }
    return array_sum($nums);
}

try {
    echo add(1);       // ← wrong! 1 is not an array
} catch (Exception $e) {
    echo $e->getMessage(); // "Argument must be an array"
}
// Script continues after catch — no crash!
```

**Without try/catch:**

```php
// No protection → Fatal Error → script crashes, user sees a white screen
echo add(1); // Fatal error: Uncaught Exception: Argument must be an array
```

**With try/catch:**

```php
// Protected → exception caught → graceful message, script continues
try {
    echo add(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage(); // ← shown to user
    // Can also: log the error, redirect to error page, etc.
}

echo "Script continues after the catch block..."; // ← this runs!
```

### Exception Object Methods

When you catch an `Exception $e`, the `$e` object has useful methods:

```php
try {
    throw new Exception("Something went wrong", 500);
} catch (Exception $e) {
    echo $e->getMessage();  // "Something went wrong"
    echo $e->getCode();     // 500
    echo $e->getFile();     // /path/to/your/file.php
    echo $e->getLine();     // Line number where throw was called
    echo $e->getTrace();    // Full stack trace array
    echo $e->getTraceAsString(); // Stack trace as readable string
}
```

### Multiple catch Blocks — Catching Specific Exception Types

You can throw and catch different **types** of exceptions:

```php
class NotFoundException extends Exception {}
class ValidationException extends Exception {}
class PaymentException extends Exception {}

function processOrder(int $orderId, array $data): string {
    if ($orderId <= 0) {
        throw new ValidationException("Invalid order ID.");
    }
    if (empty($data['product'])) {
        throw new ValidationException("Product is required.");
    }
    if ($orderId === 999) {
        throw new NotFoundException("Order #$orderId not found.");
    }
    // Simulate payment failure
    throw new PaymentException("Card declined.");
}

try {
    echo processOrder(1, ['product' => 'Laptop']);
} catch (ValidationException $e) {
    // Handle validation errors differently
    echo "❌ Validation Error: " . $e->getMessage();
} catch (NotFoundException $e) {
    // Return a 404
    echo "🔍 Not Found: " . $e->getMessage();
} catch (PaymentException $e) {
    // Show payment retry UI
    echo "💳 Payment Error: " . $e->getMessage();
} catch (Exception $e) {
    // Catch-all for anything else
    echo "⚠️ Unexpected Error: " . $e->getMessage();
} finally {
    // ✅ ALWAYS runs — whether exception thrown or not
    echo "\n🧹 Cleanup done (close DB, release lock, etc.)";
}
```

### finally Block

`finally` runs **no matter what** — whether the try succeeded, or an exception was thrown and caught.

```php
function readFile(string $path): string {
    $handle = fopen($path, 'r');
    try {
        if (!$handle) {
            throw new Exception("Cannot open file: $path");
        }
        return fread($handle, filesize($path));
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return "";
    } finally {
        // ✅ File handle is ALWAYS closed — even if exception occurred
        if ($handle) fclose($handle);
        echo "\nFile handle closed.";
    }
}
```

> 🌍 **Real-world analogy:** Booking a hotel room. You `try` to book it. If it's unavailable, a `NotFoundException` is thrown. If your card is wrong, a `PaymentException` is thrown. Each problem is handled differently — not with a generic "something went wrong." The `finally` block checks you out (cleanup) **whether the booking succeeded or failed**.

### Exceptions vs Errors — The Difference

| | PHP Errors | PHP Exceptions |
|---|---|---|
| Triggered by | PHP engine internally | Your code with `throw` |
| Example | `E_PARSE`, `E_ERROR` | `throw new Exception(...)` |
| Can you catch it? | Not directly (before PHP 7) | ✅ Yes — with `try/catch` |
| When to use | System-level problems | Expected, handleable situations |
| Recovery | Script often crashes | Script continues after `catch` |

> 💡 **PHP 7+ added `Throwable` interface** — both `Error` (PHP engine errors) and `Exception` implement it, so you can catch both with `catch (Throwable $t)`.

---

### Real-World Scenario — API Data Fetcher

```php
class ApiException extends Exception {}
class NetworkException extends ApiException {}
class RateLimitException extends ApiException {}

function fetchUserData(int $userId): array {
    if ($userId <= 0) {
        throw new \InvalidArgumentException("User ID must be positive.");
    }

    // Simulate network/API call
    $response = ['status' => 429, 'body' => null]; // rate limited response

    if ($response['status'] === 404) {
        throw new NotFoundException("User #$userId not found on API.");
    }
    if ($response['status'] === 429) {
        throw new RateLimitException("API rate limit exceeded. Retry after 60s.");
    }
    if ($response['status'] !== 200) {
        throw new NetworkException("API returned status: {$response['status']}");
    }

    return $response['body'];
}

try {
    $user = fetchUserData(42);
    echo "User data: " . json_encode($user);

} catch (RateLimitException $e) {
    // Specific handling: tell user to wait, schedule retry
    echo "⏳ " . $e->getMessage();
    // scheduleRetry(60);

} catch (NotFoundException $e) {
    // Return 404 to browser
    echo "🔍 " . $e->getMessage();
    // http_response_code(404);

} catch (ApiException $e) {
    // General API error — log it and show generic message
    echo "🌐 API Error: " . $e->getMessage();
    // error_log($e->getMessage());

} catch (\InvalidArgumentException $e) {
    echo "❌ Bad Input: " . $e->getMessage();

} finally {
    echo "\n[Cleanup] API connection closed.";
}
```

---

## 4. PHP Error Handling Part 2

### 4.1 Custom Exception Hierarchy

In real projects you never just throw a generic `Exception`. You build a **hierarchy of custom exceptions** so each error type gets its own specific handling.

```
Exception (PHP built-in)
├── AppException            ← base for all your app errors
│   ├── ValidationException
│   ├── NotFoundException
│   ├── AuthException
│   └── DatabaseException
│       └── ConnectionException
```

```php
declare(strict_types=1);

// Base app exception — all your custom exceptions extend this
class AppException extends Exception {}

// Specific types
class ValidationException extends AppException {}
class NotFoundException    extends AppException {}
class AuthException        extends AppException {}
class DatabaseException    extends AppException {}
class ConnectionException  extends DatabaseException {} // sub-type

// Usage
function findUser(int $id): array {
    if ($id <= 0) {
        throw new ValidationException("User ID must be a positive integer.");
    }
    $users = [1 => ['name' => 'Alice'], 2 => ['name' => 'Bob']];
    if (!isset($users[$id])) {
        throw new NotFoundException("User #$id does not exist.");
    }
    return $users[$id];
}

try {
    $user = findUser(99);
} catch (ValidationException $e) {
    echo "❌ Validation: " . $e->getMessage();   // bad input
} catch (NotFoundException $e) {
    echo "🔍 Not Found: " . $e->getMessage();    // HTTP 404
} catch (AppException $e) {
    echo "⚠️ App Error: " . $e->getMessage();    // catch-all for app errors
} catch (Exception $e) {
    echo "💥 Unexpected: " . $e->getMessage();   // truly unexpected
}
```

**Why this matters:**

| Generic `Exception` | Custom Hierarchy |
|---|---|
| All errors look the same | Each error type has its own identity |
| Hard to handle each case differently | Catch specific types with specific responses |
| Poor error messages | Meaningful, context-rich messages |
| Can't map to HTTP status codes easily | `NotFoundException` → 404, `AuthException` → 401 |

---

### 4.2 Exception Chaining

Sometimes you catch a low-level exception and want to throw a higher-level one — but still **keep the original cause** for debugging. This is called **exception chaining**.

```php
class DatabaseException extends AppException {}

function connectToDatabase(string $host): \PDO {
    try {
        // This throws a PDOException if connection fails
        return new \PDO("mysql:host=$host;dbname=shop", "root", "wrongpassword");
    } catch (\PDOException $e) {
        // Wrap the low-level PDOException inside your app exception
        // Pass $e as the 3rd argument → "previous exception"
        throw new DatabaseException(
            "Could not connect to the database.",
            500,
            $e  // ← the ORIGINAL cause is preserved here!
        );
    }
}

try {
    connectToDatabase("localhost");
} catch (DatabaseException $e) {
    echo $e->getMessage();              // "Could not connect to the database."
    echo $e->getPrevious()->getMessage(); // "SQLSTATE[HY000]..." ← original PDO error
}
```

**Why chain exceptions?**

```
Low-level layer:     PDOException: "Access denied for user 'root'@'localhost'"
                         ↓ wrapped into
Business layer:      DatabaseException: "Could not connect to the database."
                         ↓ caught by
Controller/App:      Shows user: "Service temporarily unavailable."
                     Logs:       Full original stack trace for developers
```

> 💡 **Real-world:** Laravel wraps low-level exceptions this way. When Eloquent can't connect, it throws a `QueryException` wrapping the original `PDOException` — your controller catches `QueryException`, the DBA reads the `PDOException`.

---

### 4.3 PHP 7+ Throwable — Catching Everything

Before PHP 7, you could only catch `Exception`. PHP engine errors (like calling an undefined function) could **not** be caught.

PHP 7 introduced the **`Throwable` interface**, which is the **parent of both `Exception` and `Error`**.

```
Throwable (interface)
├── Exception   ← your code throws these
│   ├── RuntimeException
│   ├── InvalidArgumentException
│   └── ... (all custom exceptions)
└── Error       ← PHP engine throws these
    ├── TypeError       (wrong type passed to typed function)
    ├── ParseError      (syntax error at runtime eval)
    ├── DivisionByZeroError
    └── ArithmeticError
```

```php
declare(strict_types=1);

function divide(int $a, int $b): float {
    return $a / $b;
}

// Catch an engine-level Error
try {
    divide(10, 0);
} catch (\DivisionByZeroError $e) {
    echo "Math Error: " . $e->getMessage();
}

// Catch a TypeError (strict_types catches this)
try {
    divide(10, "two");  // string passed where int expected
} catch (\TypeError $e) {
    echo "Type Error: " . $e->getMessage();
}

// Catch EVERYTHING — Exception or Error
try {
    someUndefinedFunction();
} catch (\Throwable $t) {
    echo get_class($t) . ": " . $t->getMessage();
    // Error: Call to undefined function someUndefinedFunction()
}
```

**Summary Table:**

| Throwable | Thrown by | Example |
|---|---|---|
| `Exception` | Your code (`throw new`) | `throw new NotFoundException("...")` |
| `RuntimeException` | Your code | Logic fails at runtime |
| `TypeError` | PHP engine | Wrong type passed to typed function |
| `DivisionByZeroError` | PHP engine | `10 / 0` with integers |
| `Error` | PHP engine | Undefined function/class called |
| `ParseError` | PHP engine | `eval()` with bad syntax |

> 💡 **Best practice:** Use `catch (\Throwable $t)` only in top-level error handlers (global handler, framework bootstrap). In your business logic, always catch **specific** types.

---

### 4.4 Custom Error Handler — set_error_handler()

PHP errors (E_WARNING, E_NOTICE, etc.) are NOT exceptions — you normally can't `try/catch` them. But you can **intercept them with a custom handler** and convert them to exceptions.

```php
// set_error_handler(callable $handler, int $errorTypes = E_ALL)
set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
    // Convert PHP error into an exception so it can be caught
    throw new \ErrorException($errstr, $errno, $errno, $errfile, $errline);
});

// Now even E_WARNING becomes catchable!
try {
    include "does-not-exist.php"; // normally a Warning, script continues
} catch (\ErrorException $e) {
    echo "Caught a PHP error as exception: " . $e->getMessage();
}
```

**Real-world use — catch only specific error levels:**

```php
// Only convert Warnings and Notices to exceptions (ignore Deprecated)
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
}, E_WARNING | E_NOTICE);
```

**Parameters the handler receives:**

| Parameter | What it contains |
|---|---|
| `$errno` | Error level number (e.g. `2` = E_WARNING) |
| `$errstr` | Error message string |
| `$errfile` | File where error occurred |
| `$errline` | Line number where error occurred |
| return `true` | Suppress PHP's default error handler |
| return `false` | Also run PHP's default error handler |

> 🌍 **Real-world:** Laravel's `bootstrap/app.php` calls `set_error_handler()` to convert all PHP errors into catchable exceptions — that's why you see proper error pages instead of raw PHP notices.

---

### 4.5 Global Exception Handler — set_exception_handler()

If an exception goes **uncaught**, PHP shows a white screen of death. `set_exception_handler()` catches it as a **last resort**.

```php
set_exception_handler(function (\Throwable $e): void {
    // 1. Log the full error for developers
    error_log(
        date('[Y-m-d H:i:s]') .
        " [" . get_class($e) . "] " .
        $e->getMessage() .
        " in " . $e->getFile() . ":" . $e->getLine() .
        "\n" . $e->getTraceAsString()
    );

    // 2. Show a friendly message to the user
    http_response_code(500);
    echo json_encode([
        'error'   => 'An unexpected error occurred. Please try again.',
        'code'    => $e->getCode(),
    ]);
});

// Simulate an uncaught exception
throw new \RuntimeException("Database connection failed completely.");
// → User sees: {"error":"An unexpected error occurred..."}
// → Log file has the full stack trace
```

**The full error handling pipeline:**

```
Code runs
   │
   ├── Exception thrown
   │       │
   │       ├── Caught by try/catch?  → YES → Handle gracefully, continue
   │       │
   │       └── NO → set_exception_handler() → Log + show friendly page → Stop
   │
   └── PHP Error (Warning/Notice)
           │
           ├── set_error_handler() set? → YES → Converted to ErrorException → try/catch
           │
           └── NO → PHP logs/displays it according to php.ini settings
```

---

### 4.6 Error Handling Best Practices

```
✅ DO:
  - Use declare(strict_types=1) in every file
  - Build a custom exception hierarchy (AppException as base)
  - Catch specific exception types, not just generic Exception
  - Use finally for cleanup (close files, DB connections)
  - Chain exceptions to preserve original cause
  - Log full stack traces, show friendly messages to users
  - Set a global set_exception_handler() as last resort
  - Use set_error_handler() to convert PHP errors into exceptions

❌ DON'T:
  - catch (Exception $e) { } ← empty catch = swallowing errors silently
  - Show raw PHP errors in production (display_errors = Off)
  - Throw generic Exception — always use or create a specific type
  - Log sensitive data (passwords, tokens) in error messages
```

**Real-World Scenario — E-Commerce Order Processing:**

```php
declare(strict_types=1);

class OrderException      extends AppException {}
class StockException      extends OrderException {}
class PaymentDeclinedException extends OrderException {}

function placeOrder(int $productId, int $qty, string $cardToken): string {
    // 1. Check stock
    $stock = ['product_1' => 5, 'product_2' => 0];
    $key   = "product_$productId";

    if (!isset($stock[$key])) {
        throw new NotFoundException("Product #$productId not found.");
    }
    if ($stock[$key] < $qty) {
        throw new StockException("Only {$stock[$key]} left in stock, $qty requested.");
    }
    // 2. Charge card
    if ($cardToken === 'invalid') {
        throw new PaymentDeclinedException("Card declined. Please try another card.");
    }
    return "Order confirmed! $qty × Product #$productId charged.";
}

try {
    echo placeOrder(2, 1, 'valid_token');
} catch (StockException $e) {
    echo "🛒 Stock Issue: " . $e->getMessage();       // Inform user, suggest alternatives
} catch (PaymentDeclinedException $e) {
    echo "💳 Payment: " . $e->getMessage();            // Ask user to retry payment
} catch (NotFoundException $e) {
    echo "🔍 Not Found: " . $e->getMessage();          // HTTP 404
} catch (AppException $e) {
    error_log($e->getMessage());
    echo "⚠️ Something went wrong. Please try again."; // Generic message for unknown errors
} finally {
    echo "\n[Audit] Order attempt logged.";            // Always log the attempt
}
```

---

## 5. PHP Modules and Namespaces

### 5.1 The Problem — Name Collisions

Imagine you have two third-party libraries and both define a class called `User`:

```php
// Library A: auth/User.php
class User { /* handles authentication */ }

// Library B: payment/User.php
class User { /* handles payment profiles */ }

// In your app:
include 'auth/User.php';
include 'payment/User.php'; // ❌ Fatal Error: Cannot redefine class User
```

This is the **name collision problem**. Without organising your code, class/function names from different files **clash**.

**The solution: Namespaces** — like folders for your code names.

```php
// auth/User.php
namespace Auth;
class User { /* ... */ }

// payment/User.php
namespace Payment;
class User { /* ... */ }

// In your app:
$authUser    = new Auth\User();    // ✅ No conflict
$paymentUser = new Payment\User(); // ✅ No conflict
```

> 🌍 **Real-world analogy:** Think of namespaces like city districts. There can be a "Main Street" in New York AND a "Main Street" in London — they don't conflict because they're in different cities (namespaces). `NY\MainStreet` ≠ `London\MainStreet`.

---

### 5.2 PHP File Inclusion — include vs require

Before namespaces and autoloading, PHP used file inclusion to bring code from other files into your script.

**The four inclusion functions:**

```php
// include — loads file, shows WARNING if file not found, script CONTINUES
include('Math.php');
include 'Math.php'; // parentheses are optional

// include_once — same as include, but won't load the file a 2nd time
include_once('Math.php');
include_once('Math.php'); // 2nd time → silently ignored ✅

// require — loads file, shows FATAL ERROR if file not found, script STOPS
require('Math.php');
require 'Math.php';

// require_once — same as require, but won't load the file a 2nd time
require_once('Math.php');
require_once('Math.php'); // 2nd time → silently ignored ✅
```

**When to use which:**

| Function | File missing → | Use for |
|---|---|---|
| `include` | Warning, continues | Optional template files (header, footer) |
| `include_once` | Warning, continues | Shared helpers that might be included multiple times |
| `require` | Fatal error, stops | Critical files your app cannot work without |
| `require_once` | Fatal error, stops | Class files, config files — load exactly once |

```php
// ✅ Good practice
require_once 'config.php';    // app cannot run without config
require_once 'Database.php';  // app cannot run without DB class
include 'header.php';         // template — missing is recoverable
```

---

### 5.3 Namespaces — Organising Your Code

A **namespace** is a virtual container that groups related classes, functions, and constants together.

**Declaring a namespace — must be first thing in the file:**

```php
<?php
// Math.php
namespace Math\Basic; // ← declare BEFORE any other code (except declare())

function add($a, $b) {
    return $a + $b;
}

define('PI', 3.14); // ← constants defined with define() are GLOBAL
// use const keyword for namespaced constants:
const TAX_RATE = 0.1; // ← this IS namespaced: Math\Basic\TAX_RATE
```

**From your `Math.php`:**

```php
<?php
namespace Math\Basic;

define('PI', 3.14);      // global (not recommended inside namespace)

function add($a, $b) {
    echo $a + $b;
}
```

**Using namespaced code in another file:**

```php
<?php
// App.php
include('Math.php');
include('Calculator.php');

// Full qualified name — namespace\FunctionOrClass
echo Math\add(1, 2);             // calls Math\Basic\add... wait
// Actually if Math.php has namespace Math\Basic:
echo Math\Basic\add(1, 2);       // ✅ Fully Qualified Name (FQN)
echo \Math\Basic\add(1, 2);      // ✅ with leading \ = absolute path
```

**From your `App.php` code:**

```php
include('Math.php');       // namespace Math\Basic
include('Calculator.php'); // namespace Calculator

echo Math\add(1, 2);           // if App.php has no namespace → looks for Math\add
echo Calculator\add([1, 2, 3]); // looks for Calculator\add
```

---

### 5.4 Global Namespace and Sub-Namespaces

**Global Namespace (Root):**

The leading backslash `\` always refers to the **global (root) namespace**:

```php
namespace MyApp;

// PHP built-in classes are in global namespace
$dt = new \DateTime();      // ✅ Must use \ to access global DateTime
$dt = new DateTime();       // ❌ looks for MyApp\DateTime — doesn't exist!

// PHP built-in functions don't need \ but it's more explicit:
$arr = \array_merge([1], [2]); // ✅
$arr = array_merge([1], [2]);  // ✅ also works (PHP fallbacks to global for functions)
```

> Your `App.php` comment: `// \ -> Global Namespace or Root Namespace`

**Sub-Namespaces:**

You can nest namespaces like folder paths:

```php
<?php
// Basic.php
namespace Math\Basic;    // ← Math is parent, Basic is child

function add($a, $b) {
    return $a + $b;
}
```

```php
<?php
// App.php
namespace Math;          // ← App.php is in namespace Math

include('Basic.php');   // Basic.php is namespace Math\Basic

echo Basic\add(1, 2);   // ← inside Math namespace, Basic\add() means Math\Basic\add() ✅

// From global scope (no namespace declared), the call would be:
echo Math\Basic\add(1, 2);
```

> Your `App.php` comment: `//Math\Calculator\add()` — shows the full qualified path structure.

**Visual structure:**

```
Namespace Hierarchy:
Library
└── Helper
    └── Math
        └── Basic
            └── Calculator   →  Library\Helper\Math\Basic\Calculator
```

---

### 5.5 use Keyword and Aliases

Typing `Library\Helper\Math\Basic\Calculator` every time is tedious. The `use` keyword imports a namespace or class into the current scope — like creating a shortcut.

```php
<?php
include('Calculator.php'); // namespace Library\Helper\Math\Basic

// Import the Calculator class
use Library\Helper\Math\Basic\Calculator;

$calc = new Calculator; // ✅ no need to write full path
echo $calc->add([1, 2, 3]);
```

**Aliases — `use ... as ...`:**

From your `App.php`:

```php
// git aliases: git status → gs, git add → ga, git commit → gc
// PHP class aliases work the same way!

use Library\Helper\Math\Basic\Calculator as Math;

$calc1 = new Math;   // ✅ short alias instead of full namespace
$calc2 = new Math;
```

**Aliasing functions and constants:**

```php
use function Math\Basic\add as mathAdd;
use const Math\Basic\TAX_RATE as TAX;

mathAdd(1, 2);  // calls Math\Basic\add
echo TAX;       // Math\Basic\TAX_RATE
```

**Multiple use statements:**

```php
use App\Models\User;
use App\Models\Product;
use App\Services\CartService;
use App\Helpers\StringHelper as Str; // alias
```

---

### 5.6 PSR-4 — PHP Standard Recommendation

**PSR-4** is the official PHP community standard for **autoloading classes**. It defines a strict mapping between **namespace structure** and **directory structure**.

```
Rule: Namespace path must match directory path exactly.

Namespace: Library\Helper\Calculator
File path:  Library/Helper/Calculator.php

Namespace: App\Models\User
File path:  App/Models/User.php (or src/App/Models/User.php with a prefix)
```

**PSR-4 Naming Rules:**

From your `App.php`:

```php
// Class Name, Namespace - Capital Case - Math, CarFactory, UserViewManager
//   → same applies to Interface, Traits

// File name must match the class name exactly:
//   CarFactory class → CarFactory.php
//   UserViewManager class → UserViewManager.php

// Namespace must mirror directory structure:
//   namespace Library\Helper\Math\Basic → file at Library/Helper/Math/Basic/ClassName.php
```

**PSR-4 Example:**

```
project/
├── App/
│   ├── Models/
│   │   └── User.php          → namespace App\Models;  class User {}
│   └── Services/
│       └── CartService.php   → namespace App\Services; class CartService {}
└── index.php
```

```php
// User.php
namespace App\Models;
class User {
    public string $name;
    public string $email;
}

// CartService.php
namespace App\Services;
use App\Models\User;

class CartService {
    public function getCartFor(User $user): array { /* ... */ }
}
```

---

### 5.7 Class Autoloading — spl_autoload_register

Without autoloading, you must `require_once` every class file at the top of every script — tedious and error-prone. **Autoloading** tells PHP: *"When you need a class you don't know about yet, run this function to find and load it."*

**Your `autoload.php`:**

```php
<?php
spl_autoload_register(function ($class) {
    // $class = "Library\Helper\Calculator"
    // Convert namespace to path: Library\Helper\Calculator → Library/Helper/Calculator
    $class = str_replace("\\", "/", $class);
    include($class . ".php");
    // includes Library/Helper/Calculator.php automatically!
});
```

**How it works step by step:**

```
1. PHP encounters: new Library\Helper\Calculator
2. Class not loaded yet → PHP fires autoloader
3. Autoloader runs: "Library\Helper\Calculator" passed to function
4. str_replace turns \ into /  → "Library/Helper/Calculator"
5. include("Library/Helper/Calculator.php")  → file loaded!
6. Class now available → object created ✅
```

**Your `App.php` final working example:**

```php
<?php
// App.php
include('autoload.php');         // register the autoloader once
use Library\Helper\Calculator;  // just the alias — no require needed!

$calc = new Calculator;          // autoloader finds & loads Calculator.php automatically
echo $calc->add([1, 2, 3]);     // 6
```

**Multiple autoloaders:**

```php
spl_autoload_register(function ($class) { /* loader 1 */ });
spl_autoload_register(function ($class) { /* loader 2 */ });
// PHP runs them in order until the class is found
```

---

### 5.8 Composer Autoloading

**Composer** is PHP's dependency manager (like npm for Node.js). It also generates a PSR-4 compliant autoloader automatically — far more robust than a hand-written one.

**`composer.json`:**

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "App/"
        }
    }
}
```

```
This tells Composer:
  Any class starting with namespace "App\" → look in the "App/" folder.
```

**After running `composer dump-autoload`:**

```php
<?php
// index.php
require 'vendor/autoload.php'; // Composer's generated autoloader (replaces your autoload.php!)

use App\Models\User;
use App\Services\CartService;

$user = new User();           // Composer loads App/Models/User.php automatically
$cart = new CartService();    // Composer loads App/Services/CartService.php automatically
```

**Why use Composer over hand-written autoloader?**

| Hand-written autoloader | Composer autoloader |
|---|---|
| You write and maintain it | Generated automatically |
| Simple — one strategy | Supports PSR-4, PSR-0, classmap, files |
| No third-party package support | Loads ALL installed packages too |
| Easy to break | Highly optimised, production-ready |

> 💡 **In production,** run `composer dump-autoload --optimize` for a cached classmap that's 10x faster.

---

### 5.9 Real-World Project Structure

See the `PHP-Modules-Namespaces/` folder in this week's directory for a complete working example of a mini e-commerce module using:
- PSR-4 namespaces matching directory structure
- Custom autoloader (spl_autoload_register)
- Separate modules: Models, Services, Helpers, Exceptions
- Real-world use cases: User registration, Cart management, Payment processing

The folder contains:
- `README.md` — Explanation of the project structure and concepts
- `index.php` — Entry point / bootstrapper
- `autoload.php` — Custom PSR-4 autoloader
- `App/Models/` — User, Product models
- `App/Services/` — CartService, PaymentService
- `App/Helpers/` — StringHelper
- `App/Exceptions/` — Custom exception hierarchy

---

## 6. Patterns Summary Table

| Pattern | Category | Core Idea | Real-World Analogy |
|---|---|---|---|
| **Singleton** | Creational | One instance only | One country president |
| **Builder** | Creational | Build step by step | Burger customisation order |
| **Factory** | Creational | Delegate object creation | Vehicle manufacturing plant |
| **Strategy** | Behavioural | Swap algorithms at runtime | GPS route options (fastest, shortest, scenic) |
| **Facade** | Structural | Simple interface to complex system | Hotel reception desk |
| **Provider** | Structural/DI | Register + resolve services | Supermarket supply chain |
| **DI** | Principle | Inject dependencies, don't create them | Power socket (lamp doesn't generate electricity) |
| **Repository** | Data Access | Separate data access from business logic | Restaurant menu hides kitchen internals |

---

## 7. References

- [Repository Pattern — Eric Evans, Domain-Driven Design](https://martinfowler.com/eaaCatalog/repository.html)
- [Dependency Injection Demystified — James Shore](https://www.jamesshore.com/v2/blog/2006/dependency-injection-demystified)
- [Inversion of Control Containers and the DI Pattern — Martin Fowler](https://martinfowler.com/articles/injection.html)
- [PHP Error Handling — php.net](https://www.php.net/manual/en/book.errorfunc.php)
- [PHP Exceptions — php.net](https://www.php.net/manual/en/language.exceptions.php)
- [PHP Error Types — php.net](https://www.php.net/manual/en/errorfunc.constants.php)
- [PHP Throwable — php.net](https://www.php.net/manual/en/class.throwable.php)
- [set_error_handler — php.net](https://www.php.net/manual/en/function.set-error-handler.php)
- [set_exception_handler — php.net](https://www.php.net/manual/en/function.set-exception-handler.php)
- [PHP Namespaces — php.net](https://www.php.net/manual/en/language.namespaces.php)
- [PSR-4 Autoloading Standard](https://www.php-fig.org/psr/psr-4/)
- [spl_autoload_register — php.net](https://www.php.net/manual/en/function.spl-autoload-register.php)
- [Composer Autoloading](https://getcomposer.org/doc/04-schema.md#autoload)
- [Refactoring Guru — Design Patterns](https://refactoring.guru/design-patterns)
- [OODesign — Design Principles](https://www.oodesign.com/design-principles/)
- [Design Patterns for Humans — kamranahmedse](https://github.com/kamranahmedse/design-patterns-for-humans)

