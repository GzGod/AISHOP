<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;

class OrderController
{
    private OrderService $svc;
    public function __construct() { $this->svc = new OrderService(); }

    /** POST /api/order/create */
    public function create(): never
    {
        try {
            $data = [
                'product_id'      => (int) input('product_id'),
                'quantity'        => (int) input('quantity', 1),
                'buyer_email'     => input('buyer_email'),
                'buyer_contact'   => input('buyer_contact', ''),
                'payment_channel' => input('payment_channel', 'placeholder'),
            ];
            $result = $this->svc->create($data);
            $order  = $result['order'];
            json([
                'code'      => 0,
                'msg'       => 'ok',
                'trade_no'  => $order->trade_no,
                'amount'    => $order->amount,
                'pay_url'   => $result['pay_url'],
            ]);
        } catch (\Throwable $e) {
            json(['code' => 1, 'msg' => $e->getMessage()], 422);
        }
    }

    /** GET /api/order/state?trade_no=ASxxx */
    public function state(): never
    {
        $tradeNo = input('trade_no', '');
        if (!$tradeNo) {
            json(['code' => 1, 'msg' => '缺少 trade_no'], 400);
        }
        $order = Order::where('trade_no', $tradeNo)->first();
        if (!$order) {
            json(['code' => 1, 'msg' => '订单不存在'], 404);
        }
        $payload = [
            'code'            => 0,
            'trade_no'        => $order->trade_no,
            'status'          => (int) $order->status,
            'delivery_status' => (int) $order->delivery_status,
            'amount'          => $order->amount,
        ];
        if ($order->isDelivered()) {
            $payload['secrets'] = $order->secrets ?? [];
        }
        json($payload);
    }
}
