<?php
/**
 * App/Services/PaymentService.php
 *
 * Namespace: App\Services
 *
 * PaymentService handles the checkout process.
 *
 * Namespace concepts:
 *  - Same namespace as CartService (App\Services) — no "use" needed for CartService
 *  - Uses App\Models\User and App\Exceptions — different namespaces, needs "use"
 *  - Demonstrates cross-module communication via namespaces
 */
namespace App\Services;
use App\Models\User;
use App\Exceptions\ValidationException;
use App\Exceptions\PaymentException;
class PaymentService
{
    // Simulated list of declined card tokens (in real app: Stripe/PayPal API response)
    private array $declinedCards = ['declined_card', 'stolen_card', 'expired_card'];
    /**
     * Process payment for a cart.
     *
     * Real-world scenario:
     *   User clicks "Place Order" ? PaymentService:
     *   1. Validates the cart is not empty
     *   2. Checks the user has enough balance (wallet) or card is valid
     *   3. Processes the payment
     *   4. Returns an order confirmation number
     */
    public function checkout(User $user, CartService $cart, string $paymentMethod = 'wallet'): string
    {
        // Guard: cart must not be empty
        if ($cart->isEmpty()) {
            throw new ValidationException("Cannot checkout with an empty cart.");
        }
        $total = $cart->getTotal();
        echo "\n  ?? Processing payment of $" . number_format($total, 2) . " for {$user->name}...\n";
        if ($paymentMethod === 'wallet') {
            return $this->payWithWallet($user, $total);
        } elseif ($paymentMethod === 'card') {
            return $this->payWithCard($user, $total);
        } else {
            throw new ValidationException("Unknown payment method: $paymentMethod");
        }
    }
    private function payWithWallet(User $user, float $total): string
    {
        if ($user->balance < $total) {
            throw new PaymentException(
                "Insufficient wallet balance. You have $" . number_format($user->balance, 2) .
                " but need $" . number_format($total, 2) . "."
            );
        }
        $user->balance -= $total;
        $orderNumber = strtoupper(uniqid('ORD-'));
        echo "  ? Wallet payment successful!\n";
        echo "  ?? Confirmation sent to: {$user->email}\n";
        echo "  ?? Order Number: {$orderNumber}\n";
        echo "  ?? Remaining Balance: $" . number_format($user->balance, 2) . "\n";
        return $orderNumber;
    }
    private function payWithCard(User $user, float $total): string
    {
        // Simulate card token (in real app comes from Stripe JS)
        $cardToken = 'valid_card_token';
        if (in_array($cardToken, $this->declinedCards)) {
            throw new PaymentException(
                "Card declined. Please use a different payment method.",
                402  // HTTP Payment Required
            );
        }
        $orderNumber = strtoupper(uniqid('ORD-'));
        echo "  ? Card payment successful!\n";
        echo "  ?? Confirmation sent to: {$user->email}\n";
        echo "  ?? Order Number: {$orderNumber}\n";
        return $orderNumber;
    }
}
