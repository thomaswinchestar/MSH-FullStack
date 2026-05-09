# PHP Modules & Namespaces — Real-World Example Project
A mini e-commerce application demonstrating **every namespace, module, and autoloading
concept taught in Week-21**.
---
## Project Structure
```
PHP-Modules-Namespaces/
+-- index.php                    ? Entry point — run this file
+-- autoload.php                 ? Custom PSR-4 autoloader (spl_autoload_register)
+-- README.md                    ? This file
+-- App/                         ? Root namespace: "App"
    +-- Models/                  ? namespace App\Models
    ¦   +-- User.php             ? class User
    ¦   +-- Product.php          ? class Product
    +-- Services/                ? namespace App\Services
    ¦   +-- CartService.php      ? class CartService
    ¦   +-- PaymentService.php   ? class PaymentService
    +-- Helpers/                 ? namespace App\Helpers
    ¦   +-- StringHelper.php     ? class StringHelper
    +-- Exceptions/              ? namespace App\Exceptions
        +-- AppException.php     ? base for all custom exceptions
        +-- ValidationException.php
        +-- NotFoundException.php
        +-- PaymentException.php
```
---
## How to Run
```bash
# From the PHP-Modules-Namespaces/ directory:
php index.php
```
---
## Namespace ? Directory Mapping (PSR-4)
| Namespace | Directory | Example File |
|---|---|---|
| `App\Models` | `App/Models/` | `App/Models/User.php` |
| `App\Services` | `App/Services/` | `App/Services/CartService.php` |
| `App\Helpers` | `App/Helpers/` | `App/Helpers/StringHelper.php` |
| `App\Exceptions` | `App/Exceptions/` | `App/Exceptions/AppException.php` |
**Rule:** Namespace separator `\` = directory separator `/`
---
## Key Concepts Demonstrated
### 1. `require_once` vs `include` (in `index.php`)
```php
require_once 'autoload.php';  // REQUIRED — fatal if missing, loaded once
include 'header.php';         // OPTIONAL — warning if missing, continues
```
### 2. `spl_autoload_register` (in `autoload.php`)
```php
spl_autoload_register(function (string $class): void {
    $filePath = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});
```
One autoloader replaces dozens of `require_once` calls.
### 3. Namespace Declaration (in every class file)
```php
// App/Models/User.php
namespace App\Models;  // ? FIRST line after <?php
class User { ... }
```
### 4. `use` Statements — Import Classes
```php
// In index.php
use App\Models\User;                        // full import
use App\Helpers\StringHelper as Str;        // with alias
```
### 5. Cross-Namespace Dependencies
```php
// In App/Services/CartService.php (namespace App\Services)
use App\Models\Product;           // import from App\Models
use App\Exceptions\NotFoundException;  // import from App\Exceptions
```
### 6. Exception Hierarchy
```
\Exception
+-- App\Exceptions\AppException
    +-- App\Exceptions\ValidationException
    +-- App\Exceptions\NotFoundException
    +-- App\Exceptions\PaymentException
```
---
## What Each Demo Shows
| Demo | Concept |
|---|---|
| **Demo 1** | `use ... as` alias — `StringHelper as Str`, static helper methods |
| **Demo 2** | PSR-4 autoloading — `User` and `Product` loaded automatically |
| **Demo 3** | Cross-namespace `use` — CartService uses Models + Exceptions |
| **Demo 4** | Service-to-service — PaymentService uses CartService (same namespace) |
| **Demo 5** | Exception hierarchy — catch `AppException` catches all subtypes |
| **Demo 6** | `Throwable` — catch PHP engine `Error` and your `Exception` together |
---
## Laravel Connection
| Concept here | Laravel equivalent |
|---|---|
| `autoload.php` | `vendor/autoload.php` (Composer) |
| `App\Models\User` | `App\Models\User` (same!) |
| `App\Services\CartService` | `App\Services\CartService` (same!) |
| `App\Exceptions\AppException` | `App\Exceptions\Handler` |
| `use ... as Str` | `use Illuminate\Support\Str` |
| `spl_autoload_register` | Composer PSR-4 autoloader |
> Laravel uses the exact same namespace conventions — learning this is learning Laravel structure.
---
## References
- [PHP Namespaces — php.net](https://www.php.net/manual/en/language.namespaces.php)
- [PSR-4 Autoloading Standard](https://www.php-fig.org/psr/psr-4/)
- [spl_autoload_register — php.net](https://www.php.net/manual/en/function.spl-autoload-register.php)
- [Composer Autoloading](https://getcomposer.org/doc/04-schema.md#autoload)
