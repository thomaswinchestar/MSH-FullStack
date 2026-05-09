<?php
/**
 * App/Services/CartService.php
 *
 * Namespace: App\Services
 *
 * CartService handles all shopping cart business logic.
 *
 * Key namespace concepts shown here:
 *  - "use" imports classes from other namespaces (App\Models, App\Exceptions)
 *  - No need to write the full path every time after "use"
 *  - Services depend on Models — cross-namespace dependencies via "use"
 */
namespace App\Services;
// "use" statements — import classes from other namespaces
// Without these, you would have to write App\Models\Product every time
use App\Models\Product;
use App\Models\User;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
class CartService
{
    /** @var array<int, array{product: Product, qty: int}> */
    private array $items = [];
    /**
     * Add a product to the cart.
     *
     * Real-world scenario:
     *   User clicks "Add to Cart" on a product page.
     *   We check if it's in stock before adding.
     *   If the same product is added twice, we increase quantity.
     */
    public function addItem(Product $product, int $qty = 1): void
    {
        // Validation — guard clause pattern
        if ($qty <= 0) {
            throw new ValidationException("Quantity must be at least 1.");
        }
        if (!$product->isInStock($qty)) {
            throw new ValidationException(
                "Sorry! Only {$product->stock} of \"{$product->name}\" left in stock."
            );
        }
        // If product already in cart, increase quantity
        if (isset($this->items[$product->id])) {
            $newQty = $this->items[$product->id]['qty'] + $qty;
            if (!$product->isInStock($newQty)) {
                throw new ValidationException("Cannot add more — stock limit reached.");
            }
            $this->items[$product->id]['qty'] = $newQty;
        } else {
            $this->items[$product->id] = ['product' => $product, 'qty' => $qty];
        }
        echo "  ? Added {$qty}x \"{$product->name}\" to cart.\n";
    }
    /**
     * Remove a product from the cart.
     */
    public function removeItem(int $productId): void
    {
        if (!isset($this->items[$productId])) {
            throw new NotFoundException("Product #{$productId} is not in your cart.");
        }
        $name = $this->items[$productId]['product']->name;
        unset($this->items[$productId]);
        echo "  ???  Removed \"{$name}\" from cart.\n";
    }
    /**
     * Calculate the total price of all items in the cart.
     */
    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item['product']->price * $item['qty'];
        }
        return $total;
    }
    /**
     * Display a summary of the cart.
     */
    public function showCart(): void
    {
        if (empty($this->items)) {
            echo "  ?? Your cart is empty.\n";
            return;
        }
        echo "\n  ---------------------------------\n";
        echo "  ?? Cart Summary:\n";
        foreach ($this->items as $item) {
            $subtotal = $item['product']->price * $item['qty'];
            echo "    - {$item['product']->name} x{$item['qty']} = $" . number_format($subtotal, 2) . "\n";
        }
        echo "  ---------------------------------\n";
        echo "  Total: $" . number_format($this->getTotal(), 2) . "\n\n";
    }
    /**
     * Get all cart items (used by PaymentService at checkout).
     */
    public function getItems(): array
    {
        return $this->items;
    }
    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}
