# Week 17 - PHP Control Structures, Ternary & Null Coalescing Operators

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
