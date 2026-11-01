<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\Card;
use Illuminate\Database\Capsule\Manager as DB;

class DashboardController
{
    public function index(): string
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['admin_id'])) redirect(url('admin'));

        $stats = [
            'total_orders'     => Order::count(),
            'paid_orders'      => Order::where('status', 1)->count(),
            'pending_orders'   => Order::where('status', 0)->count(),
            'today_revenue'    => Order::where('status', 1)
                                       ->whereDate('paid_at', date('Y-m-d'))
                                       ->sum('amount'),
            'total_revenue'    => Order::where('status', 1)->sum('amount'),
            'total_products'   => Product::where('is_active', 1)->count(),
            'total_cards'      => Card::count(),
            'unsold_cards'     => Card::where('status', 0)->count(),
            'pending_delivery' => Order::where('status', 1)->where('delivery_status', 0)->count(),
        ];

        $recent_orders = Order::orderByDesc('created_at')->take(10)->get();

        return view('admin.dashboard', [
            'stats'         => $stats,
            'recent_orders' => $recent_orders,
        ]);
    }
}
