<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Order;
use App\Services\OrderService;

class OrderController
{
    private function auth(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['admin_id'])) redirect(url('admin'));
    }

    public function index(): string
    {
        $this->auth();
        $status = input('status');
        $q      = Order::with('product')->orderByDesc('id');
        if ($status !== null && $status !== '') {
            $q->where('status', (int)$status);
        }
        $orders = $q->take(200)->get();
        return view('admin.orders.index', ['orders' => $orders, 'status' => $status]);
    }

    /** POST /admin/orders/{id}/deliver — manual delivery: paste secrets. */
    public function manualDeliver(int $id): never
    {
        $this->auth();
        $order   = Order::findOrFail($id);
        $rawText = input('secrets_text', '');
        $secrets = array_values(array_filter(
            preg_split('/\r?\n/', (string)$rawText),
            fn($l) => trim($l) !== ''
        ));
        if (empty($secrets)) {
            redirect(url('admin/orders?error=empty_secrets'));
        }
        (new OrderService())->manualDeliver($order, $secrets);
        redirect(url('admin/orders?delivered=1'));
    }
}
