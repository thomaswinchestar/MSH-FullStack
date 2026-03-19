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

## 📋 Quick Reference Summary

| Method | Action | Works On |
|---|---|---|
| `array_push()` | Add to **end** | Indexed |
| `array_pop()` | Remove from **end** | Indexed |
| `array_unshift()` | Add to **beginning** | Indexed |
| `array_shift()` | Remove from **beginning** | Indexed |
| `array_splice()` | Remove/replace at **position** | Indexed |
| `array_keys()` | Get all **keys** | Associative / Indexed |
| `array_values()` | Get all **values** / re-index | Associative / Indexed |
| `sort()` | Sort **ascending** | Indexed |

---

> 💡 **Tip:** `array_push` / `array_pop` = **Stack** (LIFO). `array_unshift` / `array_shift` = **Queue** (FIFO).

