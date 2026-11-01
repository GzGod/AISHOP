<?php
declare(strict_types=1);

namespace App\Controllers\Shop;

use App\Models\Order;
use App\Services\OrderService;

class OrderController
{
    /** GET /order/{tradeNo} — payment waiting / delivery result page. */
    public function show(string $tradeNo): string
    {
        $order = Order::where('trade_no', $tradeNo)->first();
        if (!$order) {
            abort(404, '订单不存在');
        }

        // Mock flow: if ?mock=1, immediately simulate payment success
        if (isset($_GET['mock']) && $_GET['mock'] === '1' && !$order->isPaid()) {
            $svc = new OrderService();
            try {
                $order = $svc->markPaid($order, 'MOCK_' . time());
            } catch (\Throwable $e) {
                // Silently continue — will show as pending if delivery fails
            }
            // Redirect to clean URL after mock payment
            redirect(url('order/' . $tradeNo));
        }

        return view('shop.order', ['order' => $order]);
    }
}
