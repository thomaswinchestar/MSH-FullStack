<?php
/**
 * App/Helpers/StringHelper.php
 *
 * Namespace: App\Helpers
 *
 * A utility/helper class with static methods for string operations.
 * Static helpers are a common pattern — no need to instantiate,
 * just call StringHelper::slugify("Hello World").
 *
 * In Laravel this is similar to using Str::slug(), Str::ucfirst(), etc.
 * Laravel\s Str class is in the Illuminate\Support namespace.
 */
namespace App\Helpers;
class StringHelper
{
    /**
     * Convert a string to a URL-friendly slug.
     * "Hello World" ? "hello-world"
     * "PHP & Namespaces!" ? "php-namespaces"
     */
    public static function slugify(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text); // remove special chars
        $text = preg_replace('/[\s-]+/', '-', $text);       // spaces/hyphens ? single -
        return trim($text, '-');
    }
    /**
     * Mask an email address for display.
     * "alice@example.com" ? "al***@example.com"
     */
    public static function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email);
        $masked = substr($local, 0, 2) . str_repeat('*', max(strlen($local) - 2, 3));
        return $masked . '@' . $domain;
    }
    /**
     * Truncate a string to a max length and append "...".
     * Useful for product descriptions in listings.
     */
    public static function truncate(string $text, int $maxLength = 50): string
    {
        if (strlen($text) <= $maxLength) {
            return $text;
        }
        return substr($text, 0, $maxLength - 3) . '...';
    }
    /**
     * Format a price for display.
     * 1999.9 ? "$1,999.90"
     */
    public static function formatPrice(float $amount, string $currency = '$'): string
    {
        return $currency . number_format($amount, 2);
    }
}
