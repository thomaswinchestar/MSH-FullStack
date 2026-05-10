# Week-22 Notes: PHP Composer, HTTP Requests, Cookies & Sessions

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
3. [PHP Cookies](#3-php-cookies)
   - 3.1 [What is a Cookie?](#31-what-is-a-cookie)
   - 3.2 [How Cookies Work](#32-how-cookies-work)
   - 3.3 [setcookie() – Creating Cookies](#33-setcookie--creating-cookies)
   - 3.4 [$_COOKIE – Reading Cookies](#34-_cookie--reading-cookies)
   - 3.5 [Deleting a Cookie](#35-deleting-a-cookie)
   - 3.6 [Cookie Path & Scope](#36-cookie-path--scope)
   - 3.7 [Cookies vs. Sessions – Quick Preview](#37-cookies-vs-sessions--quick-preview)
   - 3.8 [Real-World Scenarios](#38-real-world-scenarios)
4. [PHP Sessions](#4-php-sessions)
   - 4.1 [What is a Session?](#41-what-is-a-session)
   - 4.2 [How Sessions Work Under the Hood](#42-how-sessions-work-under-the-hood)
   - 4.3 [session_start() – Starting a Session](#43-session_start--starting-a-session)
   - 4.4 [$_SESSION – Storing & Reading Data](#44-_session--storing--reading-data)
   - 4.5 [Destroying a Session (Logout)](#45-destroying-a-session-logout)
   - 4.6 [Cookies vs Sessions – Full Comparison](#46-cookies-vs-sessions--full-comparison)
   - 4.7 [Real-World Scenarios](#47-real-world-scenarios)
5. [Mini Project – Login System with Sessions](#5-mini-project--login-system-with-sessions)
   - 5.1 [Project Overview](#51-project-overview)
   - 5.2 [Project File Structure](#52-project-file-structure)
   - 5.3 [How It All Connects](#53-how-it-all-connects)
   - 5.4 [Key Concepts Demonstrated](#54-key-concepts-demonstrated)
   - 5.5 [Real-World Expansion Ideas](#55-real-world-expansion-ideas)
6. [References](#6-references)

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

---

## 3. PHP Cookies

### 3.1 What is a Cookie?

A **cookie** is a small piece of text data that a web server stores **in the user's browser**. Every time the user visits the same website, the browser automatically sends those cookies back to the server.

> **Real-World Analogy**: Think of a cookie like a **membership stamp on your hand** at an event. When you come back in, the staff sees the stamp and knows you've already paid — they don't need to ask again.

**What cookies are used for:**
- Remembering a logged-in user ("Remember Me")
- Saving user preferences (theme, language, currency)
- Tracking shopping carts across visits
- Analytics and ad personalisation (Google Ads, Facebook Pixel)

**Key facts:**
- Cookies are stored **in the browser** (client-side)
- They are sent with **every HTTP request** to the same domain
- Each cookie has a **name, value, and expiry time**
- Cookies contain **text only** (not arrays/objects directly)
- Max size: ~**4 KB** per cookie

---

### 3.2 How Cookies Work

```
FIRST VISIT:
  Browser ──── GET /page.php ────────────────────► PHP Server
  Browser ◄─── Set-Cookie: theme=dark; Expires=... ─ PHP Server
                  ↓
              Browser saves cookie

NEXT VISIT (same domain):
  Browser ──── GET /page.php + Cookie: theme=dark ► PHP Server
                                                      PHP reads $_COOKIE['theme'] = 'dark'
```

1. **PHP sets the cookie** using `setcookie()` — the server sends a `Set-Cookie` header in the response
2. **Browser stores the cookie** associated with that domain
3. **On the next request**, the browser automatically attaches the cookie in the `Cookie` header
4. **PHP reads it** from `$_COOKIE` superglobal

---

### 3.3 setcookie() – Creating Cookies

```php
setcookie(name, value, expires, path, domain, secure, httponly);
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `name` | string | Cookie name (key) |
| `value` | string | Cookie value (text only) |
| `expires` | int | Unix timestamp when cookie expires. `0` = session cookie (until browser closes) |
| `path` | string | URL path where the cookie is valid. `"/"` = entire site |
| `domain` | string | Domain scope (e.g., `.example.com`) |
| `secure` | bool | Only send over HTTPS if `true` |
| `httponly` | bool | If `true`, JS cannot access this cookie (prevents XSS theft) |

**Examples from class:**

```php
<?php
// Set a cookie that lasts 1 hour (3600 seconds from now)
setcookie("name", "mgmg", time() + 3600);

// Set a cookie with no expiry time → session cookie (gone when browser closes)
setcookie("theme", "lightgrey");

// Set a cookie only available on /form/ path
setcookie("path", "cookie", time() + 3600, "/form/");

// Delete a cookie → set expiry to the past
setcookie("name", "", time() - 1);
```

> **Important**: `setcookie()` must be called **before any HTML output** (`echo`, whitespace, HTML tags). It works by sending an HTTP header, and headers must be sent first.

```php
<?php
// ✅ Correct — setcookie before any output
setcookie("user", "John", time() + 86400);
echo "Cookie set!";

// ❌ Wrong — output before setcookie causes "headers already sent" error
echo "Hello";
setcookie("user", "John", time() + 86400); // ERROR!
```

---

### 3.4 $_COOKIE – Reading Cookies

`$_COOKIE` is a **PHP superglobal** that holds all cookies sent by the browser for the current request.

```php
<?php
// view-cookie.php
print_r($_COOKIE);
// Array
// (
//     [theme] => lightgrey
//     [name]  => mgmg
// )

// Access a single cookie safely:
$theme = $_COOKIE['theme'] ?? 'default';
$userName = $_COOKIE['name'] ?? 'Guest';

echo "Welcome $userName! Your theme is: $theme";
```

> **Note**: A cookie you just set with `setcookie()` in the **same request** is NOT yet available in `$_COOKIE`. It becomes available on the **next request** because the browser must receive it first, then send it back.

```php
<?php
setcookie("color", "blue", time() + 3600);

// This will NOT work in the same request:
echo $_COOKIE['color']; // undefined!

// But this works after the next page load:
echo $_COOKIE['color'] ?? 'not set yet'; // "blue" on next request
```

---

### 3.5 Deleting a Cookie

To delete a cookie, set its expiry to a time **in the past**:

```php
<?php
// Delete the "name" cookie — set expiry to 1 second ago
setcookie("name", "", time() - 1);

// Also unset from the current request's $_COOKIE array:
unset($_COOKIE['name']);
```

> After calling this, the browser will discard the cookie on its next response. The `$_COOKIE` array still has the old value until you `unset()` it manually.

---

### 3.6 Cookie Path & Scope

The **path** parameter controls which URLs the cookie is sent to:

```php
// Available on ALL pages of the site
setcookie("user", "John", time() + 3600, "/");

// Only available on /admin/ pages
setcookie("admin_token", "abc123", time() + 3600, "/admin/");

// Only available on /form/ pages (from class example)
setcookie("path", "cookie", time() + 3600, "/form/");
```

```
Domain: example.com

setcookie(..., path: "/")        → cookie sent to ALL requests
setcookie(..., path: "/admin/")  → only sent to /admin/dashboard, /admin/users, etc.
                                   NOT sent to /shop or /home
```

**Secure Cookies (HTTPS only):**
```php
// Only send over HTTPS — never over HTTP
setcookie("session_token", "xyz", time() + 3600, "/", "", true, true);
//                                                        ^^^^  ^^^^
//                                                        secure httponly
```

---

### 3.7 Cookies vs. Sessions – Quick Preview

| | Cookie | Session |
|---|---|---|
| Where stored? | Browser (client) | Server |
| Data type | Text only | Array, Object, any PHP type |
| Expiry | You set it | Until browser closes (default) |
| Can user see/edit? | ✅ Yes (insecure) | ❌ No (server-side) |
| Max size | ~4 KB | Limited by server memory |
| Best for | Preferences, "Remember me" | Login state, cart, sensitive data |

*(Full comparison in Section 4.6)*

---

### 3.8 Real-World Scenarios

**Scenario 1 – Remember User Theme Preference**
```php
// Save theme preference when user changes it:
setcookie("theme", "dark", time() + (86400 * 30), "/"); // 30 days

// On every page load, apply the saved theme:
$theme = $_COOKIE['theme'] ?? 'light';
echo "<body class='theme-$theme'>";
```

**Scenario 2 – "Remember Me" Login**
```php
// After successful login, if user checked "Remember Me":
if (isset($_POST['remember_me'])) {
    setcookie("remember_token", $token, time() + (86400 * 30), "/", "", true, true);
}

// On next visit, check if remember token exists:
if (isset($_COOKIE['remember_token'])) {
    $user = getUserByToken($_COOKIE['remember_token']);
    if ($user) {
        $_SESSION['user'] = $user; // auto-login
    }
}
```

**Scenario 3 – Shopping Cart (Guest User)**
```php
// Store cart item count in cookie for non-logged-in users:
$cart = json_decode($_COOKIE['cart'] ?? '[]', true);
$cart[] = ['product_id' => 42, 'qty' => 1];
setcookie("cart", json_encode($cart), time() + 86400, "/");
```

**Scenario 4 – Language Preference**
```php
$availableLangs = ['en', 'mm', 'zh', 'ja'];

// User clicks "Change to Burmese":
if (isset($_GET['lang']) && in_array($_GET['lang'], $availableLangs)) {
    setcookie("lang", $_GET['lang'], time() + (86400 * 365), "/");
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

$lang = $_COOKIE['lang'] ?? 'en';
```

---

## 4. PHP Sessions

### 4.1 What is a Session?

A **session** is a way to store data on the **server** that persists across multiple page requests from the same user.

While cookies store data in the browser, sessions store data securely on the server — the browser only holds a **session ID** (a random token), not the actual data.

> **Real-World Analogy**: Think of a session like a **coat check at a restaurant**. When you arrive, the cloakroom gives you a **ticket number** (session ID). They keep your actual coat (data) locked in the back room (server). When you leave, you show your ticket and get your coat back. Nobody can access your coat without your exact ticket.

**Why sessions are better than cookies for sensitive data:**
- The actual data (username, role, cart items) stays on the server
- The browser only sees a random ID like `PHPSESSID=abc123xyz` — meaningless on its own
- Users cannot fake or edit session data (unlike cookies which can be manually changed)

---

### 4.2 How Sessions Work Under the Hood

```
STEP 1 — User visits login page and submits form:
  Browser ──── POST /login.php (email + password) ────► PHP Server

STEP 2 — PHP verifies credentials and starts session:
  PHP: session_start()                     ← starts session system
  PHP: $_SESSION['user'] = 'John'          ← stores data on SERVER
  PHP generates: PHPSESSID = "a1b2c3d4e5"  ← random unique ID
  PHP: Set-Cookie: PHPSESSID=a1b2c3d4e5   ← sends ID to browser

STEP 3 — Browser stores the session ID cookie:
  Browser saves: PHPSESSID=a1b2c3d4e5

STEP 4 — User visits profile page:
  Browser ──── GET /profile.php + Cookie: PHPSESSID=a1b2c3d4e5 ─► PHP
  PHP: session_start()                     ← reads PHPSESSID from cookie
  PHP: looks up session file on server     ← finds data for a1b2c3d4e5
  PHP: $_SESSION['user'] = 'John'          ← data restored automatically!

STEP 5 — On logout:
  PHP: session_destroy()                   ← deletes session file from server
  PHPSESSID cookie becomes useless
```

**Key facts about PHPSESSID:**
- Auto-generated by PHP — a random, unpredictable hash
- Stored as a **cookie** in the browser (but only contains the ID, not the data)
- Cannot be faked — guessing someone else's session ID is practically impossible
- Session **data** is stored in files on the server (default: `/tmp/` directory)
- Sessions have **no set expiry time** — they expire when the browser closes (unless you configure otherwise)

---

### 4.3 session_start() – Starting a Session

`session_start()` must be called at the **top of every PHP file** that uses sessions, before any HTML output.

```php
<?php
session_start(); // ← MUST be first line (before any output)

// Now $_SESSION is available
$_SESSION['user'] = ['username' => 'John Doe'];
echo "Session started!";
```

> **Important**: Just like `setcookie()`, `session_start()` sends HTTP headers. It must be called **before** any `echo`, HTML, or whitespace output.

---

### 4.4 $_SESSION – Storing & Reading Data

`$_SESSION` is a PHP superglobal array that persists across requests for the same user.

```php
<?php
// _actions/login.php — after verifying credentials:
session_start();

$email    = $_POST["email"];
$password = $_POST["password"];

// Simple credential check (in real apps: check against database)
if ($email === 'john.doe@gmail.com' && $password === '123456') {

    // Store user data in session (server-side, safe!)
    $_SESSION['user'] = ['username' => 'John Doe'];

    // Redirect to profile page
    header('location: ../profile.php');
    exit;

} else {
    // Wrong credentials - redirect back with error flag
    header('location: ../index.php?incorrect=1');
    exit;
}
```

```php
<?php
// profile.php — accessing session data:
session_start();

// Guard clause: if not logged in, redirect to login
if (!isset($_SESSION['user'])) {
    header('location: index.php');
    exit(); // Always exit after header redirect!
}

// Now safe to use session data:
$user = $_SESSION['user'];
echo "Welcome, " . $user['username'];
```

**Storing different data types:**
```php
<?php
session_start();

// String
$_SESSION['username'] = 'John Doe';

// Integer
$_SESSION['user_id'] = 42;

// Array (unlike cookies, sessions support full PHP types!)
$_SESSION['user'] = [
    'id'       => 42,
    'username' => 'John Doe',
    'role'     => 'admin',
    'email'    => 'john@example.com'
];

// Nested array (shopping cart)
$_SESSION['cart'] = [
    ['product_id' => 1, 'name' => 'Laptop', 'qty' => 1, 'price' => 999.00],
    ['product_id' => 5, 'name' => 'Mouse',  'qty' => 2, 'price' => 25.00],
];

// Remove a single session variable:
unset($_SESSION['username']);
```

---

### 4.5 Destroying a Session (Logout)

To properly log a user out, you must:
1. Start the session (to access it)
2. Clear all session data
3. Destroy the session
4. Optionally delete the session cookie

```php
<?php
// _actions/logout.php
session_start();

// Step 1: Clear all session variables
$_SESSION = [];

// Step 2: Delete the session cookie (PHPSESSID) from the browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Step 3: Destroy the session data on the server
session_destroy();

// Step 4: Redirect to login page
header('location: ../index.php');
exit;
```

**Quick version (sufficient for most cases):**
```php
<?php
session_start();
session_destroy();
header('location: ../index.php');
exit;
```

---

### 4.6 Cookies vs Sessions – Full Comparison

| Feature | Cookies | Sessions |
|---------|---------|---------|
| **Storage location** | Browser (client-side) | Server (server-side) |
| **Data types** | Text / strings only | Any PHP type (arrays, objects) |
| **Max size** | ~4 KB per cookie | Limited by server memory/disk |
| **Expiry** | You set it (can be days/years) | Browser close (default) |
| **Security** | User can see & edit in browser | User only sees a random ID |
| **Accessible by JS?** | Yes (unless httponly) | No (server-side only) |
| **Travels with request?** | Yes (every request) | Only the PHPSESSID cookie travels |
| **Best for** | Preferences, "remember me", theme, language, analytics | Login state, shopping cart, sensitive user data, roles |
| **PHP variable** | `$_COOKIE` | `$_SESSION` |
| **Set with** | `setcookie()` | `session_start()` + `$_SESSION[]=` |
| **Delete with** | `setcookie(..., time()-1)` | `session_destroy()` |

**When to use which:**

```
Use COOKIES when:
  ✅ Data is not sensitive (theme, language, currency)
  ✅ Data needs to persist after browser is closed
  ✅ You want the user to be able to clear it themselves
  ✅ Small data (under 4KB)

Use SESSIONS when:
  ✅ Data is sensitive (user ID, role, auth status)
  ✅ Data involves arrays/objects (cart items, user profile)
  ✅ You need server-side control (force logout across all tabs)
  ✅ Security matters — user should NOT be able to edit the data
```

---

### 4.7 Real-World Scenarios

**Scenario 1 – User Authentication (Login/Logout Flow)**
```
User fills login form
    → POST to login.php
    → PHP checks email + password
    → If valid: session_start(), $_SESSION['user'] = [...], redirect to dashboard
    → If invalid: redirect back with ?error=1

On every protected page:
    → session_start()
    → if (!isset($_SESSION['user'])) → redirect to login
    → else → show page with user data

On logout:
    → session_start(), session_destroy(), redirect to login
```

**Scenario 2 – Role-Based Access Control**
```php
session_start();

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header('location: /login');
    exit;
}

// Check role
if ($user['role'] !== 'admin') {
    header('location: /403-forbidden');
    exit;
}

// Only admins reach here
echo "Admin Dashboard";
```

**Scenario 3 – Shopping Cart with Sessions**
```php
session_start();

// Add item to cart
if ($_POST['action'] === 'add') {
    $productId = (int) $_POST['product_id'];
    $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + 1;
}

// Remove item from cart
if ($_POST['action'] === 'remove') {
    $productId = (int) $_POST['product_id'];
    unset($_SESSION['cart'][$productId]);
}

// Display cart
foreach ($_SESSION['cart'] as $id => $qty) {
    echo "Product #$id — Qty: $qty <br>";
}
```

**Scenario 4 – Flash Messages (one-time notifications)**
```php
// After form submit, store message in session:
$_SESSION['flash'] = ['type' => 'success', 'msg' => 'Profile updated!'];
header('location: profile.php');
exit;

// On profile.php — show and immediately remove:
session_start();
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']); // show only once
    echo "<div class='alert alert-{$flash['type']}'>{$flash['msg']}</div>";
}
```

---

## 5. Mini Project – Login System with Sessions

### 5.1 Project Overview

The **`project/`** folder contains an ongoing mini web application that demonstrates cookies and sessions in a realistic context:

**What it does:**
- User visits the **login page** (`index.php`) — a clean Bootstrap form
- User enters hardcoded credentials (`john.doe@gmail.com` / `123456`)
- PHP verifies credentials, creates a session, and redirects to the **profile page**
- Profile page is **protected** — only accessible if logged in (session guard)
- If not logged in, user gets redirected back to login instantly
- A **logout** link ends the session

---

### 5.2 Project File Structure

```
project/
│
├── index.php              ← Login page (form + error message display)
├── profile.php            ← Protected page (requires active session)
├── register.php           ← Registration page (ongoing — not complete yet)
│
├── _actions/
│   ├── login.php          ← Processes POST form, starts session, redirects
│   └── logout.php         ← Destroys session, redirects to login (ongoing)
│
└── css/
    └── bootstrap.min.css  ← Bootstrap for styling
```

---

### 5.3 How It All Connects

**Step-by-step flow:**

```
1. User opens index.php (Login Page)
   └─ Shows Bootstrap login form
   └─ If ?incorrect=1 in URL → shows "Incorrect Email or Password" alert

2. User submits form → POST to _actions/login.php
   └─ session_start()
   └─ Reads $_POST['email'] and $_POST['password']
   └─ Checks: email === 'john.doe@gmail.com' AND password === '123456'
   └─ ✅ Match  → $_SESSION['user'] = ['username' => 'John Doe']
                  header('location: ../profile.php')
   └─ ❌ No match → header('location: ../index.php?incorrect=1')

3. profile.php loads
   └─ session_start()
   └─ if(!isset($_SESSION['user'])) → redirect to index.php  (GUARD)
   └─ If session exists → show profile content
   └─ Shows hardcoded name, email, phone, address (to be made dynamic)
   └─ "Logout" link → _actions/logout.php

4. logout.php (ongoing)
   └─ session_start()
   └─ session_destroy()
   └─ header('location: ../index.php')
```

**Code breakdown – login.php (action):**
```php
<?php
session_start();

$email    = $_POST["email"];
$password = $_POST["password"];

if ($email === 'john.doe@gmail.com' and $password === '123456') {
    $_SESSION['user'] = ['username' => 'John Doe'];  // store in session
    header('location: ../profile.php');               // go to protected page
} else {
    header('location: ../index.php?incorrect=1');     // back to login + error flag
}
```

**Code breakdown – profile.php (protected page):**
```php
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: index.php');
    exit(); // ALWAYS exit() after header redirect!
}
// Past this point = user is authenticated
```

**The login error display in index.php:**
```php
<?php if (isset($_GET['incorrect'])) : ?>
    <div class="alert alert-warning">
        Incorrect Email or Password!
    </div>
<?php endif; ?>
```

> Notice: the error is passed as a GET flag (`?incorrect=1`), NOT by exposing why it failed (don't say "wrong password" vs "wrong email" — security best practice).

---

### 5.4 Key Concepts Demonstrated

| Concept | Where in Project | What it Shows |
|---------|-----------------|---------------|
| `session_start()` | login.php, profile.php | Must be called before using $_SESSION |
| `$_SESSION` write | `_actions/login.php` | Storing authenticated user in session |
| `$_SESSION` read | `profile.php` | Accessing session to verify login |
| Session guard | `profile.php` top | Redirect unauthenticated users |
| `header()` redirect | login.php, profile.php | PHP redirect after processing |
| `exit()` after redirect | `profile.php` | Stops PHP from continuing to run |
| GET error flag | `index.php?incorrect=1` | Passing simple state via URL |
| POST form | Login form → `_actions/login.php` | Sending credentials via POST (not GET) |
| `$_POST` reading | `_actions/login.php` | Receiving form data |

---

### 5.5 Real-World Expansion Ideas

This mini project is a **foundation** — in real apps it would be extended to:

```
Current Project           →  Real-World Version
─────────────────────────────────────────────────────────────
Hardcoded credentials     →  Database lookup (MySQL + PDO)
Plain text password check →  password_verify() + password_hash()
Static profile page       →  Dynamic: load from $_SESSION['user']['id']
Empty register.php        →  Registration form + DB insert
Empty logout.php          →  session_destroy() + cookie clear
No roles                  →  $_SESSION['user']['role'] = 'admin'/'user'
No CSRF protection        →  hidden token field in forms
No input validation       →  filter_input(), htmlspecialchars()
```

**What a production login flow looks like:**
```php
// login action (production-grade):
session_start();

$email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

$user = $db->query("SELECT * FROM users WHERE email = ?", [$email])->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    session_regenerate_id(true); // prevent session fixation attacks
    $_SESSION['user'] = [
        'id'       => $user['id'],
        'username' => $user['name'],
        'role'     => $user['role'],
    ];
    header('Location: /dashboard');
} else {
    header('Location: /login?error=1');
}
exit;
```

---

## 6. References

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

### PHP Cookies
- [PHP Manual – setcookie()](https://www.php.net/manual/en/function.setcookie.php)
- [PHP Manual – $_COOKIE](https://www.php.net/manual/en/reserved.variables.cookies.php)
- [W3Schools – PHP Cookies](https://www.w3schools.com/php/php_cookies.asp)
- [PHP Tutorial – PHP Cookie](https://www.phptutorial.net/php-tutorial/php-cookie/)
- [MDN – HTTP Cookies](https://developer.mozilla.org/en-US/docs/Web/HTTP/Cookies)

### PHP Sessions
- [PHP Manual – session_start()](https://www.php.net/manual/en/function.session-start.php)
- [PHP Manual – $_SESSION](https://www.php.net/manual/en/reserved.variables.session.php)
- [PHP Manual – session_destroy()](https://www.php.net/manual/en/function.session-destroy.php)
- [W3Schools – PHP Sessions](https://www.w3schools.com/php/php_sessions.asp)
- [PHP Tutorial – PHP Session](https://www.phptutorial.net/php-tutorial/php-session/)
- [PHP The Right Way – Sessions](https://phptherightway.com/#sessions)

