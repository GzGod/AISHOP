<?php
declare(strict_types=1);

namespace App\Contracts;

use App\Models\Order;

/**
 * Every payment gateway must implement this interface.
 * Concrete implementations live in app/Services/Gateways/.
 * Placeholder returns a mock URL so the order flow works without real credentials.
 */
interface PaymentGateway
{
    /**
     * Create a payment and return the URL to redirect/render.
     * Returns a URL string the user is redirected to.
     */
    public function createPayUrl(Order $order): string;

    /**
     * Verify a gateway callback payload.
     * Returns true if the signature is valid and the payment is confirmed.
     */
    public function verifyCallback(array $payload, Order $order): bool;

    /**
     * Human-readable channel label.
     */
    public function label(): string;
}
