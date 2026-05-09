<?php
/**
 * App/Models/User.php
 *
 * Namespace: App\Models
 *
 * The Model represents a data entity.
 * It holds the data and knows how to describe itself.
 * In a real app it would connect to a database via an ORM or PDO.
 *
 * PSR-4 rule: class name "User" ? file name "User.php"
 * PSR-4 rule: namespace "App\Models" ? directory "App/Models/"
 */
namespace App\Models;
class User
{
    public int    $id;
    public string $name;
    public string $email;
    public string $role;     // "admin", "customer"
    public float  $balance;  // wallet balance for checkout
    public function __construct(int $id, string $name, string $email, string $role = "customer", float $balance = 0.0)
    {
        $this->id      = $id;
        $this->name    = $name;
        $this->email   = $email;
        $this->role    = $role;
        $this->balance = $balance;
    }
    public function __toString(): string
    {
        return "User#{$this->id} [{$this->role}] {$this->name} <{$this->email}>";
    }
}
