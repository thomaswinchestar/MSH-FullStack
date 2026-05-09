<?php
/**
 * App/Models/Product.php
 *
 * Namespace: App\Models
 *
 * Represents a product in the e-commerce store.
 * Notice how both User and Product live in the same namespace "App\Models"
 * but are in separate files — namespaces group related classes logically.
 */
namespace App\Models;
class Product
{
    public int    $id;
    public string $name;
    public float  $price;
    public int    $stock;
    public function __construct(int $id, string $name, float $price, int $stock)
    {
        $this->id    = $id;
        $this->name  = $name;
        $this->price = $price;
        $this->stock = $stock;
    }
    public function isInStock(int $qty = 1): bool
    {
        return $this->stock >= $qty;
    }
    public function __toString(): string
    {
        return "Product#{$this->id} \"{$this->name}\" \${$this->price} (stock: {$this->stock})";
    }
}
