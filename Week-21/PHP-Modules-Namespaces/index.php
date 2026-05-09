<?php
declare(strict_types=1);
/**
 * index.php — Application Entry Point
 * -----------------------------------------------------------------
 * This file demonstrates ALL the namespace, module, autoloading,
 * and error-handling concepts taught in Week-21 Day 2.
 *
 * --- NAMESPACE CONCEPTS SHOWN ------------------------------------
 *  1. require_once vs include — why require_once for critical files
 *  2. spl_autoload_register  — one autoloader loads ALL classes
 *  3. "use" statements       — import classes from namespaces
 *  4. Aliases "use ... as"   — shorter names for long namespaces
 *  5. PSR-4 structure        — namespace mirrors directory path
 *  6. Cross-namespace usage  — Services use Models and Exceptions
 *
 * --- PROJECT STRUCTURE -------------------------------------------
 *  PHP-Modules-Namespaces/
 *  +-- index.php                  ? YOU ARE HERE (entry point)
 *  +-- autoload.php               ? custom PSR-4 autoloader
 *  +-- App/
 *      +-- Models/
 *      ¦   +-- User.php           namespace App\Models
 *      ¦   +-- Product.php        namespace App\Models
 *      +-- Services/
 *      ¦   +-- CartService.php    namespace App\Services
 *      ¦   +-- PaymentService.php namespace App\Services
 *      +-- Helpers/
 *      ¦   +-- StringHelper.php   namespace App\Helpers
 *      +-- Exceptions/
 *          +-- AppException.php
 *          +-- ValidationException.php
 *          +-- NotFoundException.php
 *          +-- PaymentException.php
 * -----------------------------------------------------------------
 */
// --- 1. BOOTSTRAP ------------------------------------------------
// require_once is used here (not include) because without the
// autoloader the entire app is broken — it is REQUIRED to run.
require_once 'autoload.php';
// --- 2. IMPORTS (use statements) ---------------------------------
// After registering the autoloader above, we can "use" any class.
// The autoloader will find and load the file automatically.
use App\Models\User;
use App\Models\Product;
use App\Services\CartService;
use App\Services\PaymentService;
use App\Helpers\StringHelper as Str;   // ? alias: Str instead of StringHelper
use App\Exceptions\AppException;
use App\Exceptions\ValidationException;
use App\Exceptions\NotFoundException;
use App\Exceptions\PaymentException;
// --- 3. GLOBAL EXCEPTION HANDLER ---------------------------------
// Catches any UNCAUGHT exception as a last resort.
// In a web app this would show a 500 error page.
set_exception_handler(function (\Throwable $e): void {
    echo "\n?? [Unhandled Error] " . get_class($e) . ": " . $e->getMessage() . "\n";
    echo "   at " . $e->getFile() . ":" . $e->getLine() . "\n";
});
// --- DEMO HELPERS -------------------------------------------------
function section(string $title): void {
    echo "\n" . str_repeat("-", 55) . "\n";
    echo "  {$title}\n";
    echo str_repeat("-", 55) . "\n";
}
function scenario(string $text): void {
    echo "\n  ? {$text}\n";
}
// ------------------------------------------------------------------
//  DEMO 1 — StringHelper (Helpers namespace, using alias "Str")
// ------------------------------------------------------------------
section("DEMO 1: App\\Helpers\\StringHelper (aliased as Str)");
echo "\n  Namespace concept: 'use App\\Helpers\\StringHelper as Str'\n";
echo "  Now we use Str:: instead of StringHelper:: or App\\Helpers\\StringHelper::\n\n";
$productName = "PHP & Namespaces — A Complete Guide!";
echo "  Original:   $productName\n";
echo "  Slug:       " . Str::slugify($productName) . "\n";
echo "  Truncated:  " . Str::truncate($productName, 30) . "\n";
echo "  Price:      " . Str::formatPrice(1999.9) . "\n";
echo "  Email mask: " . Str::maskEmail("alice.johnson@example.com") . "\n";
// ------------------------------------------------------------------
//  DEMO 2 — Models (cross-namespace: App\Models used here)
// ------------------------------------------------------------------
section("DEMO 2: App\\Models\\User & App\\Models\\Product");
echo "\n  PSR-4: namespace 'App\\Models' ? directory 'App/Models/'\n\n";
// Both User and Product are in the same namespace (App\Models)
// but in separate files — loaded by autoloader on first use
$alice = new User(1, "Alice Johnson", "alice@example.com", "customer", 500.00);
$bob   = new User(2, "Bob Smith",    "bob@example.com",   "customer", 20.00);
echo "  User 1: $alice\n";   // __toString()
echo "  User 2: $bob\n";
$laptop  = new Product(101, "Laptop Pro 15",      1299.99, 5);
$mouse   = new Product(102, "Wireless Mouse",       29.99, 50);
$monitor = new Product(103, "4K Monitor",          399.99, 2);
$airpods = new Product(104, "Wireless Earbuds",     79.99, 0); // out of stock!
echo "\n  Products:\n";
foreach ([$laptop, $mouse, $monitor, $airpods] as $p) {
    $stockLabel = $p->isInStock() ? "? in stock" : "? out of stock";
    echo "    $p — $stockLabel\n";
}
// ------------------------------------------------------------------
//  DEMO 3 — CartService (App\Services, uses App\Models & Exceptions)
// ------------------------------------------------------------------
section("DEMO 3: App\\Services\\CartService with Exception Handling");
echo "\n  Namespace concept: CartService 'use'-s classes from\n";
echo "  App\\Models and App\\Exceptions across namespace boundaries.\n";
$cart = new CartService();
// 3a. Normal operation — adding items
scenario("Alice adds items to her cart:");
try {
    $cart->addItem($laptop, 1);
    $cart->addItem($mouse,  2);
    $cart->addItem($monitor, 1);
} catch (ValidationException $e) {
    echo "  ? Validation: " . $e->getMessage() . "\n";
}
$cart->showCart();
// 3b. Try to add an out-of-stock item
scenario("Alice tries to add out-of-stock Wireless Earbuds:");
try {
    $cart->addItem($airpods, 1);
} catch (ValidationException $e) {
    echo "  ? Caught ValidationException: " . $e->getMessage() . "\n";
}
// 3c. Try to add more than available stock
scenario("Alice tries to add 10 Laptops (only 5 in stock):");
try {
    $cart->addItem($laptop, 10);
} catch (ValidationException $e) {
    echo "  ? Caught ValidationException: " . $e->getMessage() . "\n";
}
// 3d. Remove an item
scenario("Alice removes the monitor:");
try {
    $cart->removeItem(103);
} catch (NotFoundException $e) {
    echo "  ? Not Found: " . $e->getMessage() . "\n";
}
// 3e. Try to remove something not in cart
scenario("Alice tries to remove a product not in her cart (ID=999):");
try {
    $cart->removeItem(999);
} catch (NotFoundException $e) {
    echo "  ? Caught NotFoundException: " . $e->getMessage() . "\n";
}
$cart->showCart();
// ------------------------------------------------------------------
//  DEMO 4 — PaymentService Checkout
// ------------------------------------------------------------------
section("DEMO 4: App\\Services\\PaymentService — Checkout");
$payment = new PaymentService();
// 4a. Successful checkout — Alice has enough balance
scenario("Alice checks out with wallet (balance: $500.00, total ~$1,389.96):");
// Cart has: Laptop $1299.99 + 2x Mouse $59.98 = $1,359.97
// Alice balance is $500 — this will fail
try {
    $orderNum = $payment->checkout($alice, $cart, 'wallet');
    echo "  ?? Order placed: $orderNum\n";
} catch (PaymentException $e) {
    echo "  ? Caught PaymentException: " . $e->getMessage() . "\n";
}
// 4b. Bob has a smaller cart — enough balance
scenario("Bob buys 1 Wireless Mouse ($29.99) with wallet (balance: $20.00):");
$bobCart = new CartService();
try {
    $bobCart->addItem($mouse, 1);
    $bobCart->showCart();
    $orderNum = $payment->checkout($bob, $bobCart, 'wallet');
    echo "  ?? Order placed: $orderNum\n";
} catch (PaymentException $e) {
    echo "  ? Caught PaymentException: " . $e->getMessage() . "\n";
} catch (ValidationException $e) {
    echo "  ? Caught ValidationException: " . $e->getMessage() . "\n";
}
// 4c. Empty cart checkout
scenario("Alice tries to checkout with an empty cart:");
$emptyCart = new CartService();
try {
    $payment->checkout($alice, $emptyCart);
} catch (ValidationException $e) {
    echo "  ? Caught ValidationException: " . $e->getMessage() . "\n";
}
// ------------------------------------------------------------------
//  DEMO 5 — Exception Hierarchy (AppException catch-all)
// ------------------------------------------------------------------
section("DEMO 5: Exception Hierarchy — catch (AppException \$e)");
echo "\n  All custom exceptions extend AppException.\n";
echo "  This lets you catch any app error with one catch block.\n\n";
$exceptions = [
    new ValidationException("Age must be a positive number."),
    new NotFoundException("User #42 not found."),
    new PaymentException("Card expired."),
];
foreach ($exceptions as $ex) {
    try {
        throw $ex;
    } catch (AppException $e) {
        // One catch for all — still know the specific type via get_class()
        echo "  [" . get_class($e) . "] ? " . $e->getMessage() . "\n";
    }
}
// ------------------------------------------------------------------
//  DEMO 6 — Throwable: catching PHP engine errors too
// ------------------------------------------------------------------
section("DEMO 6: PHP 7+ Throwable — catching Error and Exception");
scenario("Catching a TypeError (strict_types enforces this):");
function divideInts(int $a, int $b): float {
    if ($b === 0) throw new \DivisionByZeroError("Cannot divide by zero.");
    return $a / $b;
}
try {
    echo "  10 / 2 = " . divideInts(10, 2) . "\n";
    echo "  10 / 0 = " . divideInts(10, 0) . "\n";
} catch (\DivisionByZeroError $e) {
    echo "  ? Caught DivisionByZeroError: " . $e->getMessage() . "\n";
} catch (\Throwable $t) {
    echo "  ? Caught Throwable [" . get_class($t) . "]: " . $t->getMessage() . "\n";
}
echo "\n" . str_repeat("-", 55) . "\n";
echo "  ? All demos completed — no uncaught exceptions!\n";
echo str_repeat("-", 55) . "\n\n";
