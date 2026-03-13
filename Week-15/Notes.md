# Week-15 — PHP Fundamentals

> **Reference Links**
> - [PHP: Basic Syntax](https://www.php.net/manual/en/language.basic-syntax.php)
> - [PHP: Variables](https://www.php.net/manual/en/language.variables.php)
> - [PHP: Types](https://www.php.net/manual/en/language.types.php)
> - [PHP: Constants](https://www.php.net/manual/en/language.constants.php)
> - [PHP: echo](https://www.php.net/manual/en/function.echo.php)
> - [PHP: String Functions](https://www.php.net/manual/en/ref.strings.php)
> - [PHP: Arrays](https://www.php.net/manual/en/language.types.array.php)
> - [PHP: Array Functions](https://www.php.net/manual/en/ref.array.php)

---

## Table of Contents

1. [PHP Basic Syntax](#1-php-basic-syntax)
2. [PHP Data Types](#2-php-data-types)
3. [PHP Variables & Scope](#3-php-variables--scope)
4. [PHP Constants](#4-php-constants)
5. [PHP `echo`](#5-php-echo)
6. [PHP Strings & String Functions](#6-php-strings--string-functions)
7. [PHP Arrays & Array Functions](#7-php-arrays--array-functions)

---

## 1. PHP Basic Syntax

### Concept & Theory

PHP (Hypertext Preprocessor) is a server-side scripting language designed for web development. PHP code is embedded inside HTML using special tags and is executed on the server before the page is sent to the browser.

**PHP tags:**

| Tag | Usage |
|-----|-------|
| `<?php ... ?>` | Standard opening/closing tag |
| `<?= ... ?>` | Short echo tag (outputs a value directly) |

PHP files are saved with the `.php` extension. A PHP file can contain HTML, CSS, JavaScript, and PHP code together.

### PHP in HTML (Templating)

PHP is often used as a **template engine** — you mix PHP logic with HTML markup. Popular dedicated template engines built on top of PHP include **Smarty**, **Twig**, and **Blade** (Laravel).

**Standard syntax:**

```php
<?php if ($hour < 6 || $hour > 18) { ?>
    <b>Night Time</b>
<?php } else { ?>
    <i>Day Time</i>
<?php } ?>
```

**Alternative syntax** (cleaner for templates — works with `if`, `while`, `for`, `switch`, `foreach`):

```php
<?php if ($hour < 6 || $hour > 18) : ?>
    <b>Night Time</b>
<?php else: ?>
    <i>Day Time</i>
<?php endif; ?>
```

> The alternative syntax replaces `{` with `:` and `}` with `endif;`, `endwhile;`, `endfor;`, `endforeach;`, or `endswitch;`.

**Example — `index.php`:**

```php
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <h1>Home Page</h1>
    <?php $hour = date('h'); ?>
    <p>
        <?php if ($hour < 6 || $hour > 18) : ?>
            <b>Night Time</b>
        <?php else: ?>
            <i>Day Time</i>
        <?php endif; ?>
    </p>
</body>
</html>
```

### Summary

- PHP code lives between `<?php` and `?>` tags.
- PHP is a **server-side** language — the browser only sees the final HTML output.
- PHP can be embedded directly in HTML files.
- Use **alternative syntax** (`if: ... endif;`) for cleaner HTML templates.

---

## 2. PHP Data Types

### Concept & Theory

PHP is a **loosely typed** (dynamically typed) language — similar to JavaScript. You do not need to declare a variable's type; PHP figures it out at runtime based on the value assigned. This is called **Type Juggling**.

**In JavaScript:** `typeof` is used to check a type.  
**In PHP:** `var_dump()` is used to check a type AND its value.

### PHP Data Types Overview

| Category | Type | Description |
|----------|------|-------------|
| **Scalar** | `string` | Text — sequence of characters |
| **Scalar** | `integer` | Whole numbers (32-bit) |
| **Scalar** | `float` | Decimal numbers (64-bit) |
| **Scalar** | `boolean` | `true` or `false` |
| **Compound** | `array` | Ordered map / list |
| **Compound** | `object` | Instance of a class |
| **Special** | `NULL` | Variable has no value |
| **Special** | `resource` | Reference to an external resource (e.g. file handle, DB connection) |

> **Memory model:** When you declare a variable `$var`, PHP allocates memory and the variable name `$var` points to that memory location.

### Examples

```php
<?php
// Type Juggling — PHP determines the type automatically
$var;
var_dump($var);       // NULL

$var = 123;
var_dump($var);       // int(123)

$var = "abc";
var_dump($var);       // string(3) "abc"

$var = 3.14;
var_dump($var);       // float(3.14)

$var = true;
var_dump($var);       // bool(true)
```

### Summary

- PHP has **4 scalar types** (string, integer, float, boolean), **2 compound types** (array, object), and **2 special types** (NULL, resource).
- PHP automatically detects the type — no need to declare it.
- Use `var_dump()` to inspect both the **type** and **value** of a variable.

---

## 3. PHP Variables & Scope

### Concept & Theory

A **variable** in PHP:
- Starts with a `$` sign followed by the variable name.
- Is **case-sensitive** (`$name` and `$Name` are different).
- Can hold any data type and can change type at any time.

**Variable syntax:**
```php
$variableName = value;
```

### Variable Scope

PHP variables have **context-based scoping**, which differs from JavaScript:

| Scope | Description |
|-------|-------------|
| **Global** | Variables declared outside functions. NOT accessible inside functions by default. |
| **Local** | Variables declared inside a function. NOT accessible outside. |
| **Block** | PHP does NOT have block scope — variables declared inside `if`, `for`, etc. are accessible in the enclosing function. |

> **Key difference from JavaScript:** In JS, a variable in the outer scope is automatically accessible inside a function (closure). In PHP, you must explicitly use the `global` keyword to access a global variable inside a function.

### Examples

**Global scope & `global` keyword:**
```php
<?php
$name = "Bob"; // Global variable

function hello() {
    global $name;  // Must declare 'global' to access it
    echo $name;    // "Bob"
}
hello();
```

**Block scope (PHP does NOT have it):**
```php
<?php
function hello() {
    if (true) {
        $name = "Alice"; // Declared inside if-block
    }
    echo $name; // Still accessible — "Alice"
    // In JavaScript (with let/const), this would be a ReferenceError
}
hello();
```

**`isset()` — Check if a variable is declared and not NULL:**
```php
<?php
echo isset($name); // (empty / false) — $name not declared yet

$name = "Bob";
echo isset($name); // 1 (true)
```

> `isset()` returns `true` (outputs `1`) if the variable exists and is not NULL, otherwise returns `false` (outputs nothing / empty string).

### Summary

- All PHP variables start with `$`.
- PHP is **loosely typed** — no `var`, `let`, or `const` needed.
- PHP has **no block scope** — `if`, `for` blocks do not create a new scope.
- Global variables are **not** automatically available inside functions — use the `global` keyword.
- Use `isset()` to safely check if a variable has been set.

---

## 4. PHP Constants

### Concept & Theory

A **constant** holds a value that **cannot be changed** once defined. Unlike variables:
- Constants do **not** use the `$` prefix.
- Constants are **globally accessible** — available everywhere in the script, including inside functions (no need for `global`).
- Constants are defined using the `define()` function.

**Syntax:**
```php
define("CONSTANT_NAME", value);
```

By convention, constant names are written in **ALL_CAPS**.

### Examples

```php
<?php
define("MIN", 1);
define("MAX", 10);

echo MAX;   // 10
echo MIN;   // 1

// MAX = 20; // ❌ Error — cannot reassign a constant
```

**Constants inside functions (no `global` needed):**
```php
<?php
define("APP_NAME", "MyApp");

function showApp() {
    echo APP_NAME; // ✅ Works without 'global'
}
showApp();
```

**PHP 7+ class constants with `const`:**
```php
<?php
class Config {
    const VERSION = "1.0.0";
}
echo Config::VERSION; // "1.0.0"
```

### Summary

- Use `define("NAME", value)` to create a constant.
- Constants are **case-sensitive** by default and written in ALL_CAPS by convention.
- No `$` prefix for constants.
- Constants are **globally scoped** — accessible inside functions without `global`.
- Cannot be redefined or unset after creation.

---

## 5. PHP `echo`

### Concept & Theory

`echo` is a language construct (not a true function) used to **output** one or more strings to the browser/screen.

- `echo` can output multiple values separated by commas.
- `echo` has no return value.
- `print` is similar but can only output one value and returns `1`.

**Syntax:**
```php
echo "Hello, World!";
echo "Hello", " ", "World!"; // Multiple values
```

### Examples

```php
<?php
echo "Hello, World!";           // Hello, World!
echo "<br>";                    // HTML line break
echo 42;                        // 42
echo 3.14;                      // 3.14
echo true;                      // 1
echo false;                     // (empty string)
echo null;                      // (empty string)

// Concatenation with . operator
$name = "Alice";
echo "Hello, " . $name . "!";   // Hello, Alice!

// Short echo tag
$age = 25;
?>
<p>Age: <?= $age ?></p>         <!-- Age: 25 -->
```

**Arithmetic with `echo`:**
```php
<?php
$num1 = 3;
$num2 = 2;
echo $num1 + $num2; // 5
```

### Summary

- `echo` outputs text/HTML to the browser.
- Use `.` (dot) to **concatenate** strings in PHP (not `+`).
- `<?= $value ?>` is a shorthand for `<?php echo $value; ?>`.
- `echo` can output multiple comma-separated values.

---

## 6. PHP Strings & String Functions

### Concept & Theory

A **string** in PHP is a sequence of characters. PHP supports two types of string delimiters with different behaviors:

| Quote Type | Variable Interpolation | Escape Sequences |
|------------|----------------------|------------------|
| Double quotes `"..."` | ✅ Yes — variables are expanded | ✅ Yes — `\n`, `\t`, `\"`, `\\`, etc. |
| Single quotes `'...'` | ❌ No — treated as literal text | Limited — only `\'` and `\\` |

### String Declaration Examples

```php
<?php
$name = "Alice";
$role = "Web Developer";
$company = "Acme Inc";

// Double quote — variable interpolation
echo "$name is a $role at $company";
// Output: Alice is a Web Developer at Acme Inc

// Embedding special characters
$fruit = "Apple";
$price = 1.99;
echo "Buy some $fruit for \$$price each.";
// Output: Buy some Apple for $1.99 each.

// Escaping quotes
echo "This tree is 10' 8\" long.";
// Output: This tree is 10' 8" long.

// Escaping backslash
echo "C:\\xampp\\htdocs";
// Output: C:\xampp\htdocs

// Single quote — no interpolation
$name = 'Bob';
echo 'Hello $name, welcome.';
// Output: Hello $name, welcome.   (variable NOT expanded)
```

### Key String Functions

#### `strlen()` — Get the length of a string

```php
<?php
echo strlen("Hello World"); // 11
echo strlen("ကခဂ");         // Byte count, not character count for multibyte strings
```

> **Note:** `strlen()` counts **bytes**, not characters. For multibyte strings (e.g., Myanmar Unicode), use `mb_strlen()`.

#### `substr()` — Extract part of a string

**Syntax:** `substr(string, start, length)`

```php
<?php
$str = "A quick brown fox.";
echo substr($str, 0, 7); // "A quick"
//          ^--- start at index 0, take 7 characters
```

| Parameter | Description |
|-----------|-------------|
| `string` | The input string |
| `start` | Starting index (0-based). Negative = count from end |
| `length` | Number of characters to extract (optional) |

#### `str_replace()` — Replace all occurrences of a substring

**Syntax:** `str_replace(search, replace, subject)`

```php
<?php
$str = "Come here, quick, quick.";
echo str_replace("quick", "hurry", $str);
// Output: Come here, hurry, hurry.
```

> `str_replace()` replaces **all** occurrences of the search string.

### More Useful String Functions (Reference)

| Function | Description | Example |
|----------|-------------|---------|
| `strtolower($str)` | Convert to lowercase | `strtolower("Hello")` → `"hello"` |
| `strtoupper($str)` | Convert to uppercase | `strtoupper("Hello")` → `"HELLO"` |
| `trim($str)` | Remove whitespace from both ends | `trim("  hi  ")` → `"hi"` |
| `str_contains($str, $needle)` | Check if string contains substring (PHP 8) | `str_contains("Hello", "ell")` → `true` |
| `str_starts_with($str, $prefix)` | Check if string starts with (PHP 8) | `str_starts_with("Hello", "He")` → `true` |
| `str_ends_with($str, $suffix)` | Check if string ends with (PHP 8) | `str_ends_with("Hello", "lo")` → `true` |
| `explode($delimiter, $str)` | Split string into array | `explode(",", "a,b,c")` → `["a","b","c"]` |
| `implode($glue, $array)` | Join array into string | `implode("-", ["a","b"])` → `"a-b"` |
| `strpos($str, $needle)` | Find position of first occurrence | `strpos("Hello", "l")` → `2` |
| `str_repeat($str, $n)` | Repeat string n times | `str_repeat("ab", 3)` → `"ababab"` |
| `number_format($num, $decimals)` | Format number with thousands separators | `number_format(1234567.891, 2)` → `"1,234,567.89"` |
| `sprintf($format, ...)` | Format a string | `sprintf("%.2f", 3.14159)` → `"3.14"` |

### Summary

- Use **double quotes** when you need variable interpolation or escape sequences.
- Use **single quotes** for plain/literal strings (slightly faster, no variable parsing).
- `strlen()` → length, `substr()` → extract, `str_replace()` → replace.
- PHP's string concatenation operator is `.` (dot), not `+`.

---

## 7. PHP Arrays & Array Functions

### Concept & Theory

An **array** in PHP is an ordered map — it maps **keys** to **values**. PHP arrays are very flexible and can act as:
- A **list** (numeric indexed array)
- A **dictionary/hash map** (associative array)
- A **multi-dimensional array**

### 7.1 Numeric Array (Indexed Array)

Keys are automatically assigned integers starting from `0`.

```php
<?php
// Using array()
$users = array("Alice", "Bob");

// Using short syntax (PHP 5.4+)
$fruits = ["Apple", "Banana"];

echo $users[0];     // Alice
print_r($fruits);   // Array ( [0] => Apple [1] => Banana )
var_dump($fruits);  // Detailed type info
```

**`print_r()` vs `var_dump()`:**

| Function | Output |
|----------|--------|
| `print_r($arr)` | Human-readable structure |
| `var_dump($arr)` | Structure + data types + values |

### 7.2 Associative Array

Keys are custom **strings** (like a dictionary/object in JS).

```php
<?php
$user = ["name" => "Alice", "age" => 22];
print_r($user);
// Array ( [name] => Alice [age] => 22 )

echo $user["name"]; // Alice
```

### 7.3 Multidimensional Array (2D Array)

An array of arrays.

```php
<?php
$users = [
    ["name" => "Alice", "age" => 20],
    ["name" => "Bob",   "age" => 20],
    ["name" => "Charlie", "age" => 20],
];

print_r($users);
print_r($users[0]);         // First user

echo $users[0]["name"];     // Alice
echo $users[1]["name"];     // Bob
```

### 7.4 Adding Elements

```php
<?php
// Adding at a specific index (creates sparse array)
$fruits = ['Apple', 'Orange'];
$fruits[4] = 'Mango';
print_r($fruits);
// Array ( [0] => Apple [1] => Orange [4] => Mango )

// Appending to the end with []
$fruits = ['Apple', 'Orange'];
$fruits[] = 'Mango';
print_r($fruits);
// Array ( [0] => Apple [1] => Orange [2] => Mango )
```

### 7.5 Array Destructuring

**Numeric array destructuring:**

```php
<?php
$user = ["Alice", 22];

// list() — older syntax
list($name, $age) = $user;
echo $name; // Alice

// Short syntax (PHP 7.1+) — preferred
[$name, $age] = $user;
echo $name; // Alice
```

**Associative array destructuring:**

```php
<?php
$user = ["name" => "Alice", "age" => 20];

["name" => $name, "age" => $age] = $user;
echo $name; // Alice
echo $age;  // 20
```

### 7.6 Array Spread Operator

Merge or expand arrays using `...` (PHP 7.4+):

```php
<?php
$nums1 = [1, 2];
$nums2 = [...$nums1, 3];
print_r($nums2);
// Array ( [0] => 1 [1] => 2 [2] => 3 )
```

---

### 7.7 Useful Array Functions

#### `count()` — Number of elements in an array

```php
<?php
$nums = [1, 2, 3, 4];
echo count($nums); // 4
```

#### `is_array()` — Check if a variable is an array

```php
<?php
$users = ["alice", "bob"];
echo is_array($users); // 1 (true)

$name = "alice";
echo is_array($name);  // (empty — false)
```

#### `in_array()` — Check if a value exists in an array

```php
<?php
$users = ["alice", "bob"];
echo in_array("bob", $users);    // 1 (true)
echo in_array("charlie", $users); // (empty — false)
```

#### `array_search()` — Find the key/index of a value

```php
<?php
$users = ["tom", "bob", "alice"];
echo array_search("alice", $users); // 2 (index of "alice")
echo array_search("tom", $users);   // 0

// Returns false if not found
$result = array_search("xyz", $users);
var_dump($result); // bool(false)
```

> **Difference:** `in_array()` returns `true/false`, while `array_search()` returns the **key/index** or `false`.

### More Useful Array Functions (Reference)

| Function | Description | Example |
|----------|-------------|---------|
| `array_push($arr, $val)` | Add to end | `array_push($arr, "d")` |
| `array_pop($arr)` | Remove from end | `array_pop($arr)` |
| `array_shift($arr)` | Remove from start | `array_shift($arr)` |
| `array_unshift($arr, $val)` | Add to start | `array_unshift($arr, "x")` |
| `array_merge($a, $b)` | Merge two arrays | `array_merge([1,2], [3,4])` → `[1,2,3,4]` |
| `array_slice($arr, $start, $len)` | Extract portion | `array_slice([1,2,3,4], 1, 2)` → `[2,3]` |
| `array_splice($arr, $start, $len)` | Remove & replace portion | modifies array in place |
| `array_reverse($arr)` | Reverse an array | `array_reverse([1,2,3])` → `[3,2,1]` |
| `array_unique($arr)` | Remove duplicates | `array_unique([1,1,2])` → `[1,2]` |
| `array_keys($arr)` | Get all keys | `array_keys(["a"=>1])` → `["a"]` |
| `array_values($arr)` | Get all values | `array_values(["a"=>1])` → `[1]` |
| `array_flip($arr)` | Swap keys and values | `array_flip(["a"=>1])` → `[1=>"a"]` |
| `sort($arr)` | Sort ascending (reindex) | `sort([3,1,2])` → `[1,2,3]` |
| `rsort($arr)` | Sort descending (reindex) | `rsort([3,1,2])` → `[3,2,1]` |
| `asort($arr)` | Sort by value, keep keys | for associative arrays |
| `ksort($arr)` | Sort by key | for associative arrays |
| `array_map($fn, $arr)` | Apply function to each element | `array_map('strtoupper', $arr)` |
| `array_filter($arr, $fn)` | Filter elements by condition | removes falsy or matching elements |
| `array_reduce($arr, $fn, $initial)` | Reduce to a single value | like JS `reduce()` |
| `implode($glue, $arr)` | Join array into string | `implode(", ", ["a","b"])` → `"a, b"` |
| `explode($delim, $str)` | Split string into array | `explode(",", "a,b")` → `["a","b"]` |

### Summary

| Topic | Key Points |
|-------|-----------|
| Numeric Array | `["a", "b", "c"]` — integer keys auto-assigned |
| Associative Array | `["key" => "value"]` — string keys |
| 2D Array | Array of arrays — access with `$arr[row][col]` |
| Adding Elements | `$arr[] = val` appends; `$arr[index] = val` sets specific index |
| Destructuring | `[$a, $b] = $arr` (PHP 7.1+) or `["key" => $var] = $arr` |
| Spread | `[...$arr1, ...$arr2]` merges arrays (PHP 7.4+) |
| `count()` | Count elements |
| `is_array()` | Check if it is an array |
| `in_array()` | Check if value exists (returns bool) |
| `array_search()` | Find key/index of a value (returns key or false) |

---

## Overall Week-15 Summary

| Topic | Key Takeaway |
|-------|-------------|
| **Basic Syntax** | PHP is embedded in HTML with `<?php ?>`. Use alternative syntax (`:` / `endif`) in templates. |
| **Data Types** | 4 scalar (string, int, float, bool) + array, object, NULL, resource. PHP is loosely typed. |
| **Variables** | Start with `$`. No block scope. Global vars need `global` keyword inside functions. Use `isset()` to check. |
| **Constants** | `define("NAME", val)` — no `$`, globally accessible, cannot be changed. |
| **echo** | Outputs to screen. Concat with `.`. `<?= $val ?>` is the short form. |
| **Strings** | Double quotes interpolate variables. Single quotes are literal. Key functions: `strlen`, `substr`, `str_replace`. |
| **Arrays** | PHP arrays are ordered maps. Numeric, associative, and multidimensional. Key functions: `count`, `is_array`, `in_array`, `array_search`. |

