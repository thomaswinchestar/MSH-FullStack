# Week-22 Notes: PHP Composer & HTTP Requests

## Table of Contents
1. [PHP Composer](#1-php-composer)
   - 1.1 [What is Composer?](#11-what-is-composer)
   - 1.2 [How Composer Works](#12-how-composer-works)
   - 1.3 [composer.json – The Heart of a Package](#13-composerjson--the-heart-of-a-package)
   - 1.4 [Key Composer Commands](#14-key-composer-commands)
   - 1.5 [PSR-4 Autoloading with Composer](#15-psr-4-autoloading-with-composer)
   - 1.6 [Using a Package – Carbon Example](#16-using-a-package--carbon-example)
   - 1.7 [Packagist – The Package Registry](#17-packagist--the-package-registry)
   - 1.8 [Real-World Scenarios](#18-real-world-scenarios)
2. [HTTP Requests in PHP (Introduction)](#2-http-requests-in-php-introduction)
   - 2.1 [What is an HTTP Request?](#21-what-is-an-http-request)
   - 2.2 [Two Types of Request Data in Web Apps](#22-two-types-of-request-data-in-web-apps)
   - 2.3 [$_GET – URL Query String](#23-_get--url-query-string)
   - 2.4 [$_POST – Form Data](#24-_post--form-data)
   - 2.5 [$_REQUEST – Both GET & POST](#25-_request--both-get--post)
   - 2.6 [URL Parsing, Encoding & Decoding](#26-url-parsing-encoding--decoding)
   - 2.7 [HTML Form Example](#27-html-form-example)
   - 2.8 [Real-World Scenarios](#28-real-world-scenarios)
3. [References](#3-references)

---

## 1. PHP Composer

### 1.1 What is Composer?

**Composer** is the official **dependency manager** for PHP — the same concept as `npm` for Node.js or `pip` for Python.

Instead of manually downloading PHP libraries and placing them in your project, Composer handles everything for you:
- Downloads packages automatically from [Packagist](https://packagist.org)
- Manages version constraints and compatibility
- Generates a PSR-4 autoloader so you never need `require` for every file
- Locks exact versions in `composer.lock` for reproducible builds

> **Real-World Analogy**: Think of Composer like an app store for PHP libraries. You declare what libraries (apps) your project needs, and Composer fetches and installs them automatically.

---

### 1.2 How Composer Works

```
Your Project
    │
    ├── composer.json          ← you define your dependencies here
    ├── composer.lock          ← Composer locks exact resolved versions
    └── vendor/                ← all packages are installed here
            ├── autoload.php   ← single file to include; loads everything
            ├── nesbot/carbon/
            └── laravel/...
```

**Workflow:**
1. You declare required packages in `composer.json`
2. Run `composer install` → Composer reads `composer.json`, resolves versions, downloads packages into `vendor/`
3. In your PHP file, include `vendor/autoload.php` once → all classes from all packages are auto-available

```php
<?php
include('vendor/autoload.php');  // just ONE line replaces dozens of require()

use Carbon\Carbon;
echo Carbon::now();  // works immediately
```

---

### 1.3 composer.json – The Heart of a Package

`composer.json` is a JSON configuration file that describes your project and its dependencies.

```json
{
    "name": "thomas/app",
    "description": "demo composer",
    "authors": [
        {
            "name": "thomas"
        }
    ],
    "require": {
        "nesbot/carbon": "^3.11",
        "laravel/laravel": "^13.5"
    },
    "autoload": {
        "psr-4": {
            "App\\": "App/"
        }
    }
}
```

**Key Fields Explained:**

| Field | Purpose |
|-------|---------|
| `name` | `vendor/package-name` format. Your project's identity on Packagist |
| `description` | Short description of what your package does |
| `authors` | Who wrote the package |
| `require` | List of packages your project depends on with version constraints |
| `autoload` | Tells Composer how to autoload your own classes (PSR-4 mapping) |

**Package Naming Convention:**
```
vendor/package-name
 └──────┬──────    └────┬────
  community/org    project name

Examples:
  nesbot/carbon       → the Carbon date library by Nesbot
  laravel/laravel     → the Laravel framework
  symfony/console     → Symfony console component
  guzzlehttp/guzzle   → Guzzle HTTP client
```

**Version Constraints:**

| Constraint | Meaning |
|-----------|---------|
| `"^3.11"` | `>=3.11.0 <4.0.0` — compatible with 3.x |
| `"~3.11"` | `>=3.11.0 <3.12.0` — only patch updates |
| `"3.11.0"` | Exactly that version |
| `"*"` | Any version (not recommended) |

---

### 1.4 Key Composer Commands

```bash
# Install all dependencies listed in composer.json
composer install

# Add a new package and install it immediately
composer require vendor/package-name
# Example:
composer require nesbot/carbon

# Remove a package
composer remove vendor/package-name

# Update all packages to the latest allowed versions
composer update

# Create a new project from a Composer package (like a project template)
composer create-project vendor/package-name project-folder
# Example: create a new Laravel project
composer create-project laravel/laravel my-app

# Regenerate the autoloader after adding your own classes
composer dump-autoload
```

**`composer install` vs `composer update`:**

| Command | What it does |
|---------|-------------|
| `composer install` | Installs exact versions from `composer.lock` — use in production |
| `composer update` | Resolves latest versions, updates `composer.lock` — use in development |

> **Best Practice**: Commit `composer.json` and `composer.lock` to git. Never commit the `vendor/` folder (add it to `.gitignore`).

---

### 1.5 PSR-4 Autoloading with Composer

**PSR-4** is the PHP standard for autoloading classes. It maps **namespaces** to **directories**.

In `composer.json`:
```json
"autoload": {
    "psr-4": {
        "App\\": "App/"
    }
}
```

This means: any class in the `App\` namespace → look for it in the `App/` directory.

```
App\Library\Math  →  App/Library/Math.php
App\Models\User   →  App/Models/User.php
App\Services\Cart →  App/Services/Cart.php
```

After changing `autoload` in `composer.json`, regenerate:
```bash
composer dump-autoload
```

**Your own class (no manual require needed):**
```php
// App/Library/Math.php
namespace App\Library;

class Math
{
    static function add($a, $b)
    {
        return $a + $b;
    }
}
```

```php
// index.php
include('vendor/autoload.php');   // autoloader handles everything

use App\Library\Math;

echo Math::add(1, 2);  // 3
```

---

### 1.6 Using a Package – Carbon Example

**Carbon** is a popular PHP library for working with dates and times in a clean, fluent way.

```bash
composer require nesbot/carbon
```

```php
<?php
include('vendor/autoload.php');

use Carbon\Carbon;

// Current date/time
echo Carbon::now();                    // 2026-05-10 14:30:00

// Add days, months, years
echo Carbon::now()->addDay();          // tomorrow
echo Carbon::now()->addDays(7);        // next week
echo Carbon::now()->addMonth();        // next month

// Formatted output
echo Carbon::now()->format('d M Y');   // 10 May 2026

// Human-readable difference
echo Carbon::now()->subDays(3)->diffForHumans(); // 3 days ago

// Compare dates
Carbon::now()->isFuture();  // false
Carbon::now()->isPast();    // false (it's right now)
```

**Real-World Use Cases for Carbon:**
- Show "Posted 3 minutes ago" on a blog
- Calculate a subscription's expiry date (+1 month)
- Check if a booking date is in the future
- Format a database timestamp into a readable date

---

### 1.7 Packagist – The Package Registry

**[packagist.org](https://packagist.org)** is the main repository of Composer packages — like npm's registry but for PHP.

```
Search → Find Package → Copy composer require command → Run it
```

Every package page shows:
- Install command: `composer require vendor/package`
- Total downloads & GitHub stars (indicators of popularity)
- Documentation link
- Version history

**Popular PHP Packages:**
| Package | Purpose |
|---------|---------|
| `nesbot/carbon` | Date & time manipulation |
| `guzzlehttp/guzzle` | HTTP client (make API calls) |
| `monolog/monolog` | Logging |
| `vlucas/phpdotenv` | `.env` file loader |
| `phpmailer/phpmailer` | Send emails |
| `league/flysystem` | File storage abstraction |
| `laravel/laravel` | Full-stack PHP framework |
| `symfony/console` | CLI application toolkit |

---

### 1.8 Real-World Scenarios

**Scenario 1 – E-commerce Checkout (multiple packages)**
```
Your app → needs:
├── guzzle        → call Stripe payment API
├── carbon        → calculate subscription renewal dates
├── monolog       → log all payment events
└── phpdotenv     → load API keys from .env safely
```

```bash
composer require guzzlehttp/guzzle nesbot/carbon monolog/monolog vlucas/phpdotenv
```

All installed at once. Your team simply runs `composer install` when they clone the repo.

**Scenario 2 – Starting a New Laravel Project**
```bash
composer create-project laravel/laravel my-ecommerce-app
cd my-ecommerce-app
php artisan serve
```
In seconds, you have a full framework with routing, ORM, authentication ready — all managed by Composer.

**Scenario 3 – Team Collaboration**
```
Developer A:            Developer B (joins project):
  composer require       git clone repo
  new-package            composer install
  git push               (gets exact same versions from composer.lock)
```

`composer.lock` guarantees "works on my machine" = "works on your machine".

---

## 2. HTTP Requests in PHP (Introduction)

### 2.1 What is an HTTP Request?

Every time a user visits a webpage, clicks a link, or submits a form, a browser sends an **HTTP Request** to a web server.

```
Browser  ──── HTTP Request ────►  Web Server (PHP)
         ◄─── HTTP Response ────
```

An HTTP Request carries:
- **Method**: GET, POST, PUT, DELETE, PATCH…
- **URL**: where the request is going
- **Headers**: metadata (Content-Type, Authorization…)
- **Body** (optional): data sent with POST, PUT requests

---

### 2.2 Two Types of Request Data in Web Apps

PHP web apps typically receive data in two ways:

```
Web App → Request Data → 2 main sources
├── URL Query String  → $_GET
└── Form Data         → $_POST
```

| Type | How sent | PHP variable | Example |
|------|----------|-------------|---------|
| URL Query | Appended to URL after `?` | `$_GET` | `?q=php&page=2` |
| Form Data | In request body (POST) | `$_POST` | `<form method="post">` |
| Both | Either | `$_REQUEST` | Merges `$_GET` + `$_POST` + `$_COOKIE` |

---

### 2.3 $_GET – URL Query String

**`$_GET`** is a **PHP Superglobal Variable** — it is always available everywhere in your PHP code without importing anything.

It holds all key-value pairs from the **URL query string** (the part after `?` in a URL).

```
URL: https://www.google.com/search?q=php&lang=en&page=2
                                   ───────────────────────
                                        Query String
```

```php
<?php
// URL: /search?q=php&lang=en&page=2

print_r($_GET);
// Output:
// Array
// (
//     [q]    => php
//     [lang] => en
//     [page] => 2
// )

// Access individual values:
$query = $_GET['q']    ?? '';    // "php"
$lang  = $_GET['lang'] ?? 'en'; // "en"
$page  = $_GET['page'] ?? 1;    // "2"

echo "Search: $query";
```

**Characteristics of GET:**
- Data is visible in the URL (not secure for passwords)
- Can be bookmarked and shared
- Limited data size (~2000 characters in most browsers)
- Idempotent — repeating the same GET request has no side effects
- Used for: search, filtering, pagination, sorting

**Real-World GET examples:**
```
Google Search:    ?q=php+composer&hl=en
Product Filter:   ?category=shoes&color=red&size=10&sort=price_asc
Pagination:       ?page=3&per_page=20
Sharing a Post:   ?post_id=42
```

---

### 2.4 $_POST – Form Data

**`$_POST`** holds data submitted through an HTML form with `method="post"`.

Unlike GET, POST data is sent in the **request body**, not in the URL — making it suitable for sensitive data like passwords.

```html
<!-- form/index.php -->
<form action="request.php" method="get">
    <input type="text" name="name" placeholder="Name">
    <input type="text" name="age"  placeholder="Age">
    <button type="submit">Send Data</button>
</form>
```

```php
<?php
// request.php – receives POST data
print_r($_POST);
// Output after submitting "John" and 25:
// Array
// (
//     [name] => John
//     [age]  => 25
// )

$name = $_POST['name'] ?? 'Guest';
$age  = $_POST['age']  ?? 0;

echo "Hello $name, you are $age years old.";
```

**Characteristics of POST:**
- Data is NOT visible in the URL (more private)
- No size limit (can upload files)
- NOT bookmarkable (data is in request body)
- Not idempotent — repeating same POST may create duplicate records
- Used for: login forms, registration, creating data, file uploads

**GET vs. POST Comparison:**

| | GET | POST |
|---|---|---|
| Data in URL? | ✅ Yes | ❌ No |
| Bookmarkable? | ✅ Yes | ❌ No |
| Secure? | ❌ No (visible in URL) | ✓ More secure |
| Data limit? | ~2000 chars | Virtually unlimited |
| Use for | Reading / searching | Creating / writing |
| Caching? | ✅ Browser caches | ❌ Not cached |

---

### 2.5 $_REQUEST – Both GET & POST

**`$_REQUEST`** is a superglobal that merges `$_GET`, `$_POST`, and `$_COOKIE` into one array.

```php
<?php
// request.php
print_r($_REQUEST);
// Contains everything from GET, POST, and cookies

// Access without caring if it came from GET or POST:
$name = $_REQUEST['name'] ?? '';
```

**When to use `$_REQUEST`:**
- When you want to accept both GET and POST for the same parameter
- Generally avoid in production — be explicit with `$_GET` or `$_POST` for clarity and security

```php
// Better approach — explicitly check the method:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
} else {
    $name = $_GET['name'] ?? '';
}
```

---

### 2.6 URL Parsing, Encoding & Decoding

When a user types something in a URL, browsers automatically **encode** special characters. PHP needs to **decode** them to work with the original values.

**Why URL Encoding Exists:**
URLs can only contain certain characters (letters, numbers, `-`, `_`, `.`, `~`). All other characters must be percent-encoded.

**Common URL Encoding:**

| Character | URL Encoded |
|-----------|-------------|
| Space     | `%20` (or `+` in query strings) |
| `@`       | `%40` |
| `#`       | `%23` |
| `$`       | `%24` |
| `%`       | `%25` |
| `&`       | `%26` |
| `+`       | `%2B` |
| `=`       | `%3D` |
| `?`       | `%3F` |
| `/`       | `%2F` |
| `:`       | `%3A` |

```
URL typed by user:  /search?q=hello world&tag=C#
URL sent by browser: /search?q=hello%20world&tag=C%23
```

**PHP URL Functions:**

```php
<?php
// Encode a string for use in a URL
$query = "hello world & more";
echo urlencode($query);    // hello+world+%26+more
echo rawurlencode($query); // hello%20world%20%26%20more

// Decode URL-encoded string back to original
$encoded = "hello+world+%26+more";
echo urldecode($encoded);    // hello world & more

// Parse a full URL into components
$url = "https://example.com/search?q=php&page=2#results";
print_r(parse_url($url));
// Array
// (
//     [scheme]   => https
//     [host]     => example.com
//     [path]     => /search
//     [query]    => q=php&page=2
//     [fragment] => results
// )

// Parse query string into array
parse_str("q=php&page=2&lang=en", $params);
print_r($params);
// Array ( [q] => php [page] => 2 [lang] => en )

// Build a query string from array
$data = ['q' => 'hello world', 'page' => 1];
echo http_build_query($data);  // q=hello+world&page=1
```

**Real-World Use Cases:**

```php
// E-commerce filter URL:
// /products?category=shoes&color=red&price_max=100

$category  = $_GET['category']  ?? 'all';
$color     = $_GET['color']     ?? '';
$priceMax  = $_GET['price_max'] ?? 9999;

// Build a sorted/filtered URL to share or paginate:
$filters = ['category' => $category, 'color' => $color, 'page' => 2];
$queryString = http_build_query($filters);
// category=shoes&color=red&page=2

header("Location: /products?" . $queryString);
```

---

### 2.7 HTML Form Example

This is what was built in class — a simple form that sends data via GET and displays it:

```php
<!-- form/index.php — the form page -->
<!DOCTYPE html>
<html>
<body>
    <h1>PHP Request</h1>
    <form action="request.php" method="get">
        <label>Name: <input type="text" name="name" placeholder="Name"></label><br><br>
        <label>Age:  <input type="text" name="age"  placeholder="Age"></label><br><br>
        <button type="submit">Send Data</button>
    </form>
</body>
</html>
```

```php
<?php
// form/request.php — receives and displays the data
// $_GET, $_POST, $_REQUEST

// print_r($_GET);     // shows only GET data
// print_r($_POST);    // shows only POST data
print_r($_REQUEST);    // shows all incoming data

// Properly accessing values:
$name = htmlspecialchars($_REQUEST['name'] ?? 'Guest');
$age  = (int) ($_REQUEST['age'] ?? 0);

echo "Hello, $name! You are $age years old.";
```

**What happens step by step:**
1. User fills in Name = "John", Age = 25 and clicks Submit  
2. Browser sends `GET /request.php?name=John&age=25`  
3. PHP populates `$_GET['name'] = 'John'` and `$_GET['age'] = '25'`  
4. `$_REQUEST` contains both  
5. PHP processes and responds with output

> **Security Note**: Always use `htmlspecialchars()` when displaying user-provided data to prevent XSS (Cross-Site Scripting) attacks.

---

### 2.8 Real-World Scenarios

**Scenario 1 – Search Feature**
```php
// URL: /search?q=iphone&category=phones&page=1

$query    = htmlspecialchars($_GET['q']        ?? '');
$category = htmlspecialchars($_GET['category'] ?? 'all');
$page     = max(1, (int) ($_GET['page'] ?? 1));

// Use these to query the database and show results
$results = $db->search($query, $category, $page);
```

**Scenario 2 – Login Form (POST)**
```php
// form method="post" → data not shown in URL

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($email && password_verify($password, $storedHash)) {
        $_SESSION['user'] = $email;
        header("Location: /dashboard");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
```

**Scenario 3 – Product Filtering (GET)**
```php
// URL: /products?brand=nike&size=10&sort=price_asc

$brand  = $_GET['brand']  ?? '';
$size   = (int) ($_GET['size']  ?? 0);
$sort   = $_GET['sort']   ?? 'newest';

// Allowed sort values (whitelist for security)
$allowedSorts = ['price_asc', 'price_desc', 'newest', 'popular'];
if (!in_array($sort, $allowedSorts)) {
    $sort = 'newest';
}
```

**Scenario 4 – Contact Form (POST to email)**
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = htmlspecialchars($_POST['name']    ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    mail("admin@mysite.com", $subject, "From: $name\n\n$message");
    echo "Message sent!";
}
```

---

## 3. References

### PHP Composer
- [getcomposer.org — Official Docs](https://getcomposer.org/doc/)
- [Packagist — PHP Package Repository](https://packagist.org)
- [nesbot/carbon — Carbon Date Library](https://carbon.nesbot.com/)
- [Composer Versions & Constraints](https://getcomposer.org/doc/articles/versions.md)
- [PSR-4 Autoloading Standard](https://www.php-fig.org/psr/psr-4/)
- [PHP The Right Way – Dependency Management](https://phptherightway.com/#dependency_management)

### PHP HTTP Requests
- [PHP Manual – $_GET](https://www.php.net/manual/en/reserved.variables.get.php)
- [PHP Manual – $_POST](https://www.php.net/manual/en/reserved.variables.post.php)
- [PHP Manual – $_REQUEST](https://www.php.net/manual/en/reserved.variables.request.php)
- [PHP Manual – URL Functions](https://www.php.net/manual/en/ref.url.php)
- [W3Schools – PHP GET & POST](https://www.w3schools.com/php/php_forms.asp)
- [PHP Tutorial – Handling Forms](https://www.phptutorial.net/php-tutorial/php-form/)
- [MDN – HTTP Overview](https://developer.mozilla.org/en-US/docs/Web/HTTP/Overview)

