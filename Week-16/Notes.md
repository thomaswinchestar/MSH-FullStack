# Week 16 – PHP Array Methods

---

## 1. `array_push()`

Adds one or more elements to the **end** of an array.

```php
$users = ["alice", "bob"];
array_push($users, "tom");
print_r($users);
// Output: Array ( [0] => alice [1] => bob [2] => tom )
```

### 🌍 Real-world Usage
Adding a new user to a list of registered users, or appending a new item to a shopping cart.

---

## 2. `array_pop()`

Removes and returns the **last** element of an array.

```php
$users = ["alice", "bob", "tom"];
$removed = array_pop($users);
echo $removed; // tom
print_r($users);
// Output: Array ( [0] => alice [1] => bob )
```

### 🌍 Real-world Usage
Removing the most recently added item (undo functionality), or processing items in a stack (Last In, First Out).

---

## 3. `array_unshift()`

Adds one or more elements to the **beginning** of an array.

```php
$users = ["bob", "alice"];
array_unshift($users, "admin");
print_r($users);
// Output: Array ( [0] => admin [1] => bob [2] => alice )
```

### 🌍 Real-world Usage
Pinning an important announcement to the top of a list, or adding a default/guest user to the front of a user list.

---

## 4. `array_shift()`

Removes and returns the **first** element of an array.

```php
$queue = ["task1", "task2", "task3"];
$next = array_shift($queue);
echo $next; // task1
print_r($queue);
// Output: Array ( [0] => task2 [1] => task3 )
```

### 🌍 Real-world Usage
Processing items in a queue (First In, First Out) — like handling support tickets or print jobs in order.

---

## 5. `array_splice()`

Removes, replaces, or inserts elements at a specific **position** in an array.

```php
// array_splice($array, $start, $deleteCount, $replacement)

$users = ["tom", "bob", "alice"];
$removed = array_splice($users, 1, 1); // Remove 1 element at index 1

print_r($users);
// Output: Array ( [0] => tom [1] => alice )

print_r($removed);
// Output: Array ( [0] => bob )
```

#### Inserting a replacement:
```php
$fruits = ["apple", "banana", "cherry"];
array_splice($fruits, 1, 1, ["mango", "grape"]);
print_r($fruits);
// Output: Array ( [0] => apple [1] => mango [2] => grape [3] => cherry )
```

### 🌍 Real-world Usage
Editing a specific item in a list (like updating a product in a catalog), removing a banned user from a specific position, or inserting new items mid-list.

---

## 6. `array_keys()`

Returns all the **keys** of an array as a new indexed array.

```php
$user = ["name" => "Alice", "age" => 20, "email" => "alice@example.com"];
$keys = array_keys($user);
print_r($keys);
// Output: Array ( [0] => name [1] => age [2] => email )
```

### 🌍 Real-world Usage
Getting a list of all form field names, or checking what properties exist in an associative array (like a database row).

---

## 7. `array_values()`

Returns all the **values** of an array as a new indexed array (re-indexes the array).

```php
$user = ["name" => "Alice", "age" => 20, "email" => "alice@example.com"];
$vals = array_values($user);
print_r($vals);
// Output: Array ( [0] => Alice [1] => 20 [2] => alice@example.com )
```

#### Also useful to re-index after deletion:
```php
$fruits = ["apple", "banana", "cherry"];
unset($fruits[1]);
$fruits = array_values($fruits); // Re-index
print_r($fruits);
// Output: Array ( [0] => apple [1] => cherry )
```

### 🌍 Real-world Usage
Converting an associative array into a plain list for display, or re-indexing an array after removing elements.

---

## 8. `sort()`

Sorts an **indexed array** in ascending order (modifies the original array).

```php
$numbers = [3, 1, 4, 1, 5, 9, 2];
sort($numbers);
print_r($numbers);
// Output: Array ( [0] => 1 [1] => 1 [2] => 2 [3] => 3 [4] => 4 [5] => 5 [6] => 9 )
```

#### Sorting strings:
```php
$names = ["Charlie", "Alice", "Bob"];
sort($names);
print_r($names);
// Output: Array ( [0] => Alice [1] => Bob [2] => Charlie )
```

### 🌍 Real-world Usage
Sorting product prices from lowest to highest, alphabetically sorting a list of names, or ranking scores on a leaderboard.

---

## 9. `rsort()`

Sorts an **indexed array** in **descending (reverse) order** — biggest/last value comes first. Modifies the original array.

```php
$scores = [50, 90, 70, 30, 80];
rsort($scores);
print_r($scores);
// Output: Array ( [0] => 90 [1] => 80 [2] => 70 [3] => 50 [4] => 30 )
```

#### Reverse sort strings:
```php
$names = ["Charlie", "Alice", "Bob"];
rsort($names);
print_r($names);
// Output: Array ( [0] => Charlie [1] => Bob [2] => Alice )
```

### 🌍 Real-world Usage
Displaying a leaderboard where highest score appears first, or showing newest items by reversing a date-sorted list.

---

## 10. `ksort()`

Sorts an **associative array by its keys** in ascending order. The key-value relationships are preserved.

```php
$users = ["tom" => 23, "bob" => 22, "alice" => 24];
ksort($users);
print_r($users);
// Output: Array ( [alice] => 24 [bob] => 22 [tom] => 23 )
```

> 💡 Notice: `sort()` would **destroy the keys** and re-index. `ksort()` keeps keys intact.

### 🌍 Real-world Usage
Sorting a configuration array or a product catalogue alphabetically by key name, or sorting a dictionary/lookup table for display.

---

## 11. `krsort()`

Sorts an **associative array by its keys** in **descending (reverse) order**. Keys are preserved.

```php
$users = ["tom" => 23, "bob" => 22, "alice" => 24];
krsort($users);
print_r($users);
// Output: Array ( [tom] => 23 [bob] => 22 [alice] => 24 )
```

### 🌍 Real-world Usage
Displaying categories in reverse alphabetical order, or presenting version numbers from newest to oldest.

---

## 12. `explode()`

**Splits a string** into an array by a given delimiter (separator).

```php
// explode(separator, string)

$input = "A quick brown fox.";
$words = explode(" ", $input);
print_r($words);
// Output: Array ( [0] => A [1] => quick [2] => brown [3] => fox. )
```

#### More examples:
```php
// Split a CSV line
$csv = "alice,bob,charlie";
$names = explode(",", $csv);
print_r($names);
// Output: Array ( [0] => alice [1] => bob [2] => charlie )

// Split date parts
$date = "2026-03-20";
$parts = explode("-", $date);
echo $parts[0]; // 2026 (year)
echo $parts[1]; // 03   (month)
echo $parts[2]; // 20   (day)
```

### 🌍 Real-world Usage
Parsing a comma-separated list from a form input (e.g., tags), splitting a date string into year/month/day, or processing rows from a CSV file.

---

## 13. `implode()`

**Joins array elements** into a single string using a given separator. The opposite of `explode()`.

```php
// implode(separator, array)

$words = ["A", "quick", "brown", "fox."];
$sentence = implode(" ", $words);
echo $sentence;
// Output: A quick brown fox.
```

#### More examples:
```php
// Build a comma-separated tag list
$tags = ["php", "web", "backend"];
$tagString = implode(", ", $tags);
echo $tagString; // php, web, backend

// Build an SQL IN clause
$ids = [1, 2, 3, 4];
$sql = "SELECT * FROM users WHERE id IN (" . implode(",", $ids) . ")";
echo $sql;
// Output: SELECT * FROM users WHERE id IN (1,2,3,4)
```

### 🌍 Real-world Usage
Building a comma-separated list for display or SQL queries, joining words back into a sentence, or converting array data into a formatted string for output.

---

## 📋 Quick Reference Summary — Array Methods

| Method | Action | Works On |
|---|---|---|
| `array_push()` | Add to **end** | Indexed |
| `array_pop()` | Remove from **end** | Indexed |
| `array_unshift()` | Add to **beginning** | Indexed |
| `array_shift()` | Remove from **beginning** | Indexed |
| `array_splice()` | Remove/replace at **position** | Indexed |
| `array_keys()` | Get all **keys** | Associative / Indexed |
| `array_values()` | Get all **values** / re-index | Associative / Indexed |
| `sort()` | Sort **ascending** (by value) | Indexed |
| `rsort()` | Sort **descending** (by value) | Indexed |
| `ksort()` | Sort **ascending** (by key) | Associative |
| `krsort()` | Sort **descending** (by key) | Associative |
| `explode()` | **String → Array** (split) | String |
| `implode()` | **Array → String** (join) | Indexed |

---

> 💡 **Tips:**
> - `array_push` / `array_pop` = **Stack** (LIFO). `array_unshift` / `array_shift` = **Queue** (FIFO).
> - `sort()` / `rsort()` re-index keys. `ksort()` / `krsort()` preserve keys.
> - `explode()` and `implode()` are inverses — split and join strings.

---

---

# PHP Operators

Operators are symbols that tell PHP to perform specific mathematical, comparison, or logical operations.

---

## 1. Arithmetic Operators

Used to perform basic **math calculations**.

| Operator | Name | Example | Result |
|---|---|---|---|
| `+` | Addition | `5 + 3` | `8` |
| `-` | Subtraction | `5 - 3` | `2` |
| `*` | Multiplication | `5 * 3` | `15` |
| `/` | Division | `9 / 3` | `3` |
| `%` | Modulus (remainder) | `5 % 3` | `2` |
| `**` | Exponentiation (power) | `2 ** 3` | `8` |

```php
echo 5 + 3;   // 8
echo 5 - 3;   // 2
echo 5 * 3;   // 15
echo 9 / 3;   // 3
echo 5 % 3;   // 2  (remainder of 5 ÷ 3)
echo 2 ** 3;  // 8  (2 to the power of 3)
```

### 🌍 Real-world Usage
Calculating a shopping cart total (`price * quantity`), finding if a number is even/odd with `%`, or computing compound interest with `**`.

---

## 2. String Operators

### `.` — Concatenation Operator
Joins two strings together.

```php
$greet = "Welcome!";
$name  = "Bob";

echo $greet . " " . $name;  // Welcome! Bob
```

### String Interpolation (inside double quotes)
Variables inside double-quoted strings are automatically expanded.

```php
$greet = "Hello";
$name  = "Alice";

echo "$greet $name";  // Hello Alice
```

### `.=` — Concatenation Assignment
Appends a string to an existing variable.

```php
$result  = "Welcome";
$result .= " ";
$result .= "Bob";

echo $result;  // Welcome Bob
```

### 🌍 Real-world Usage
Building dynamic HTML, constructing SQL queries, or generating personalised email greetings.

---

## 3. Comments in PHP

Comments are ignored by PHP — they are notes for the developer.

```php
// Single-line comment  (same as JavaScript)

# Single-line comment   (unique to PHP)

/*
   Multi-line comment
   spans multiple lines
*/
```

---

## 4. Assignment Operators

Used to **assign or update** a value in a variable.

| Operator | Meaning | Example | Equivalent To |
|---|---|---|---|
| `=` | Assign | `$x = 5` | — |
| `+=` | Add & assign | `$x += 2` | `$x = $x + 2` |
| `-=` | Subtract & assign | `$x -= 2` | `$x = $x - 2` |
| `*=` | Multiply & assign | `$x *= 2` | `$x = $x * 2` |
| `/=` | Divide & assign | `$x /= 2` | `$x = $x / 2` |
| `%=` | Modulus & assign | `$x %= 2` | `$x = $x % 2` |
| `**=` | Power & assign | `$x **= 2` | `$x = $x ** 2` |
| `.=` | Concatenate & assign | `$s .= "!"` | `$s = $s . "!"` |

```php
$num = 3;
$num += 2;
echo $num;  // 5

$result  = "Hello";
$result .= " World";
echo $result;  // Hello World
```

### 🌍 Real-world Usage
Accumulating a running total (e.g., cart total), counting loop iterations, or building a long string piece by piece.

---

## 5. Increment / Decrement Operators

Used to **add or subtract 1** from a variable.

| Operator | Name | Description |
|---|---|---|
| `$x++` | Post-increment | Use `$x`, THEN add 1 |
| `++$x` | Pre-increment | Add 1, THEN use `$x` |
| `$x--` | Post-decrement | Use `$x`, THEN subtract 1 |
| `--$x` | Pre-decrement | Subtract 1, THEN use `$x` |

```php
// Post-increment
$x = 3;
$y = $x++;        // y gets old value (3), then x becomes 4
echo "x: $x, y: $y";  // x: 4, y: 3

// Pre-increment
$x = 3;
$y = ++$x;        // x becomes 4 first, then y gets 4
echo "x: $x, y: $y";  // x: 4, y: 4
```

### 🌍 Real-world Usage
Loop counters, tracking the number of attempts (e.g., login failures), or pagination page numbers.

---

## 6. Comparison Operators

Used to **compare two values**. Returns `true` (1) or `false` (empty).

| Operator | Name | Example | Result |
|---|---|---|---|
| `==` | Equal (loose) | `5 == "5"` | `true` |
| `===` | Identical (strict) | `5 === "5"` | `false` |
| `!=` | Not equal | `5 != 3` | `true` |
| `!==` | Not identical | `5 !== "5"` | `true` |
| `<>` | Not equal (alt) | `5 <> 3` | `true` |
| `<` | Less than | `3 < 5` | `true` |
| `>` | Greater than | `5 > 3` | `true` |
| `<=` | Less than or equal | `3 <= 3` | `true` |
| `>=` | Greater than or equal | `5 >= 3` | `true` |

```php
echo 5 == "5";   // 1 (true)  — loose: only checks value
echo 5 === "5";  // (empty/false) — strict: checks value AND type
```

> ⚠️ **Key Rule:** Always prefer `===` over `==` to avoid unexpected type-coercion bugs.

### 🌍 Real-world Usage
Validating form input, checking if a user's age is above 18, or comparing a submitted password hash.

---

## 7. Spaceship Operator `<=>`

Compares two values and returns:
- **-1** if left < right
- **0** if left == right
- **1** if left > right

```php
echo 3 <=> 5;  // -1  (3 is less than 5)
echo 5 <=> 5;  //  0  (equal)
echo 5 <=> 3;  //  1  (5 is greater than 3)
```

### 🌍 Real-world Usage
Custom sorting with `usort()` — the spaceship operator is perfect as a comparator callback.

```php
$numbers = [3, 1, 4, 1, 5];
usort($numbers, fn($a, $b) => $a <=> $b);
print_r($numbers); // sorted ascending
```

---

## 8. Logical Operators

Used to **combine or negate** boolean conditions.

| Operator | Keyword Alt | Meaning |
|---|---|---|
| `&&` | `and` | Both conditions must be true |
| `\|\|` | `or` | At least one condition must be true |
| `!` | — | Reverses the boolean value (NOT) |
| `xor` | — | True only if exactly ONE condition is true |

```php
$x = 3;
$y = 5;

// AND — both must be true
echo $x === 3 && $y === 5;   // 1 (true)
echo $x === 3 && $y === 10;  // (false)

// OR — at least one must be true
echo $x === 3 || $y === 10;  // 1 (true)

// NOT — flip the result
echo !($x === 3);  // (false, because $x IS 3)

// XOR — only one can be true (not both, not neither)
echo $x < $y xor $x === 3;  // (false — both are true, XOR needs exactly one)
echo $x < $y xor $x === 10; // 1 (true — only first is true)
```

### `&&` vs `and` — Important Difference!
They behave the same logically, but `and` / `or` have **lower precedence** than assignment `=`, while `&&` / `||` have higher precedence.

```php
$result = true && false;   // $result = false  ✅ (expected)
$result = true and false;  // $result = true   ⚠️ (assignment happens first!)
```

> 💡 **Best practice:** Use `&&` and `||` in most cases to avoid precedence surprises.

### 🌍 Real-world Usage
- `&&` — Check if user is logged in AND has the right role.
- `||` — Show a default value if a variable is empty OR null.
- `!` — Redirect if a user is NOT logged in.
- `xor` — Toggle logic where exactly one flag should be active at a time.

---

## 📋 Quick Reference Summary — Operators

| Category | Operators |
|---|---|
| Arithmetic | `+` `-` `*` `/` `%` `**` |
| String | `.` `.=` |
| Assignment | `=` `+=` `-=` `*=` `/=` `%=` `**=` `.=` |
| Increment/Decrement | `++` `--` (pre & post) |
| Comparison | `==` `===` `!=` `!==` `<>` `<` `>` `<=` `>=` |
| Spaceship | `<=>` |
| Logical | `&&` `\|\|` `!` `and` `or` `xor` |

---

> 💡 **Golden Rules:**
> - Use `===` instead of `==` to avoid type-coercion bugs.
> - Use `&&` / `||` instead of `and` / `or` to avoid operator-precedence bugs.
> - `xor` = "one or the other, but not both."

