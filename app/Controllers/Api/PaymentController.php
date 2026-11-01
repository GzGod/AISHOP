<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\Order;
use App\Services\OrderService;

class PaymentController
{
    private OrderService $svc;
    public function __construct() { $this->svc = new OrderService(); }

    /**
     * POST /api/payment/callback/{channel}
     * Called by payment gateways after a successful payment.
     * For placeholder/mock: accepts GET/POST ?trade_no=xxx&mock=1
     */
    public function callback(string $channel): never
    {
        // Collect raw payload (POST body, GET params, or raw JSON)
        $payload = $_POST;
        if (empty($payload)) {
            $raw = file_get_contents('php://input');
            $decoded = json_decode($raw ?: '{}', true);
            $payload = is_array($decoded) ? $decoded : [];
        }
        $payload = array_merge($_GET, $payload);

        $tradeNo = $payload['trade_no'] ?? $payload['out_trade_no'] ?? '';
        if (!$tradeNo) {
            json(['code' => 1, 'msg' => 'missing trade_no'], 400);
        }

        $order = Order::where('trade_no', $tradeNo)->first();
        if (!$order) {
            json(['code' => 1, 'msg' => 'order not found'], 404);
        }

        // Verify callback signature via the appropriate gateway
        $gateway = $this->svc->gateway($channel);
        if (!$gateway->verifyCallback($payload, $order)) {
            json(['code' => 1, 'msg' => 'signature verification failed'], 403);
        }

        $gatewayTradeNo = $payload['transaction_id'] ?? $payload['trade_no'] ?? null;

        try {
            $this->svc->markPaid($order, $gatewayTradeNo);
        } catch (\Throwable $e) {
            json(['code' => 1, 'msg' => $e->getMessage()], 500);
        }

        // Most gateways expect a plain "success" response body
        http_response_code(200);
        header('Content-Type: text/plain');
        echo 'success';
        exit;
    }
}
