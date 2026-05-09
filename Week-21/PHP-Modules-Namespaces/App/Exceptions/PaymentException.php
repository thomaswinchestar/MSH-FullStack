<?php
namespace App\Exceptions;
/**
 * PaymentException — thrown when a payment operation fails.
 * Examples: card declined, insufficient funds, gateway timeout.
 */
class PaymentException extends AppException {}
