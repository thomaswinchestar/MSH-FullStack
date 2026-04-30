# Week 21 — PHP Design Patterns Part 2 & PHP Error Handling Part 1

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
4. [Patterns Summary Table](#4-patterns-summary-table)
5. [References](#5-references)

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

## 4. Patterns Summary Table

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

## 5. References

- [Repository Pattern — Eric Evans, Domain-Driven Design](https://martinfowler.com/eaaCatalog/repository.html)
- [Dependency Injection Demystified — James Shore](https://www.jamesshore.com/v2/blog/2006/dependency-injection-demystified)
- [Inversion of Control Containers and the DI Pattern — Martin Fowler](https://martinfowler.com/articles/injection.html)
- [PHP Error Handling — php.net](https://www.php.net/manual/en/book.errorfunc.php)
- [PHP Exceptions — php.net](https://www.php.net/manual/en/language.exceptions.php)
- [PHP Error Types — php.net](https://www.php.net/manual/en/errorfunc.constants.php)
- [Refactoring Guru — Design Patterns](https://refactoring.guru/design-patterns)
- [OODesign — Design Principles](https://www.oodesign.com/design-principles/)
- [Design Patterns for Humans — kamranahmedse](https://github.com/kamranahmedse/design-patterns-for-humans)

