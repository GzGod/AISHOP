<?php
declare(strict_types=1);

namespace App\Services\Gateways;

use App\Contracts\PaymentGateway;
use App\Models\Order;

/**
 * Placeholder payment gateway — simulates a successful payment without any
 * real API credentials. Swap this out with WechatGateway / AlipayGateway
 * once you add real credentials to .env.
 */
class PlaceholderGateway implements PaymentGateway
{
    public function createPayUrl(Order $order): string
    {
        // In production: build a real payment URL here.
        // For now: return the order page URL with a mock flag.
        return url('order/' . $order->trade_no . '?mock=1');
    }

    public function verifyCallback(array $payload, Order $order): bool
    {
        // Placeholder: always returns true for mock callbacks.
        // In production: verify HMAC / RSA signature from the gateway.
        return true;
    }

    public function label(): string { return '模拟支付（占位）'; }
}
