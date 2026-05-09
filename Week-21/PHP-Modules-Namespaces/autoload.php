<?php
/**
 * autoload.php — Custom PSR-4 Class Autoloader
 *
 * How it works:
 *   When PHP encounters a class it hasn't loaded yet, it calls all registered
 *   autoload functions in order. Our function converts the fully-qualified
 *   class name (namespace + class) into a file path and includes it.
 *
 * Example:
 *   new App\Models\User
 *   → $class = "App\Models\User"
 *   → str_replace("\\", "/", ...) = "App/Models/User"
 *   → include "App/Models/User.php"   ✅
 */
spl_autoload_register(function (string $class): void {
    // Convert namespace separator \ to directory separator /
    $filePath = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";

    // Only load if the file exists (avoids fatal errors for unknown classes)
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});

