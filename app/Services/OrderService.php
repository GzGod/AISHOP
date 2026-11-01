<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Models\Card;
use App\Models\Order;
use App\Models\Product;
use App\Services\Gateways\AlipayGateway;
use App\Services\Gateways\PlaceholderGateway;
use App\Services\Gateways\WechatGateway;
use Illuminate\Database\Capsule\Manager as DB;
use RuntimeException;

class OrderService
{
    /** Resolve a PaymentGateway by handle name. */
    public function gateway(string $handle): PaymentGateway
    {
        return match ($handle) {
            'wechat'      => new WechatGateway(),
            'alipay'      => new AlipayGateway(),
            'placeholder' => new PlaceholderGateway(),
            default       => new PlaceholderGateway(),
        };
    }

    /**
     * Create an order for a product, return the order + pay URL.
     * @param array{product_id:int,quantity:int,buyer_email:string,buyer_contact?:string,payment_channel:string} $data
     */
    public function create(array $data): array
    {
        $product = Product::findOrFail((int)$data['product_id']);
        if ((int)$product->is_active !== 1) {
            throw new RuntimeException('该商品已下架');
        }
        $qty = max(1, min(10, (int)($data['quantity'] ?? 1)));
        $stock = $product->stockCount();
        if ($stock < $qty) {
            throw new RuntimeException("库存不足，当前剩余 {$stock} 张");
        }
        if (empty($data['buyer_email']) || !filter_var($data['buyer_email'], FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('请填写有效的接收邮箱');
        }

        $amount = (float)$product->price * $qty;
        $order  = DB::connection()->transaction(function () use ($product, $qty, $amount, $data) {
            $order = Order::create([
                'trade_no'        => generate_trade_no(),
                'product_id'      => $product->id,
                'product_name'    => $product->name,
                'quantity'        => $qty,
                'amount'          => $amount,
                'buyer_email'     => $data['buyer_email'],
                'buyer_contact'   => $data['buyer_contact'] ?? null,
                'payment_channel' => $data['payment_channel'] ?? 'placeholder',
                'status'          => 0,
                'delivery_status' => 0,
                'client_ip'       => $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
            return $order;
        });

        $gateway  = $this->gateway($order->payment_channel);
        $payUrl   = $gateway->createPayUrl($order);

        return [
            'order'   => $order,
            'pay_url' => $payUrl,
        ];
    }

    /**
     * Mark an order as paid and trigger automatic CDK delivery.
     * Returns the order with secrets populated.
     */
    public function markPaid(Order $order, ?string $gatewayTradeNo = null): Order
    {
        if ($order->isPaid()) {
            return $order; // idempotent
        }
        return DB::connection()->transaction(function () use ($order, $gatewayTradeNo) {
            $order->status            = 1;
            $order->gateway_trade_no  = $gatewayTradeNo;
            $order->paid_at           = date('Y-m-d H:i:s');
            $order->save();

            $product = $order->product;
            if ((int)$product->delivery_type === 1) {
                $this->deliverAuto($order);
            }
            return $order->fresh();
        });
    }

    /**
     * Auto-deliver: atomically claim N unsold cards for this order's product.
     */
    public function deliverAuto(Order $order): void
    {
        $secrets = DB::connection()->transaction(function () use ($order) {
            $cards = Card::where('product_id', $order->product_id)
                ->where('status', 0)
                ->orderBy('id')
                ->lockForUpdate()
                ->take($order->quantity)
                ->get();

            if ($cards->count() < $order->quantity) {
                throw new RuntimeException('发卡失败：库存不足');
            }

            $secrets = [];
            foreach ($cards as $card) {
                $card->status   = 1;
                $card->order_id  = $order->id;
                $card->sold_at   = date('Y-m-d H:i:s');
                $card->save();
                $secrets[] = $card->secret;
            }
            return $secrets;
        });

        $order->secrets          = $secrets;
        $order->delivery_status  = 1;
        $order->save();

        // ── Email delivery (placeholder — logs to storage/logs/delivery.log) ──
        $logFile = base_path('storage/logs/delivery.log');
        $line    = sprintf(
            "[%s] Order #%d (%s) → %s | secrets: %s\n",
            date('Y-m-d H:i:s'),
            $order->id,
            $order->trade_no,
            $order->buyer_email,
            json_encode($secrets, JSON_UNESCAPED_UNICODE)
        );
        @file_put_contents($logFile, $line, FILE_APPEND);
    }

    /** Manual delivery from admin. */
    public function manualDeliver(Order $order, array $secrets): void
    {
        $order->secrets         = $secrets;
        $order->delivery_status = 1;
        $order->save();
    }
}
