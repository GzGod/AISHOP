<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Payment;

class PaymentController
{
    private function auth(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['admin_id'])) redirect(url('admin'));
    }

    public function index(): string
    {
        $this->auth();
        $payments = Payment::orderBy('sort')->get();
        return view('admin.payments.index', ['payments' => $payments]);
    }

    public function update(int $id): never
    {
        $this->auth();
        $payment = Payment::findOrFail($id);

        // Merge posted config fields into existing config (never overwrite with empty)
        $existing = $payment->config ?? [];
        $posted   = $_POST['config'] ?? [];
        if (is_array($posted)) {
            foreach ($posted as $k => $v) {
                if (trim((string)$v) !== '') {
                    $existing[htmlspecialchars($k, ENT_QUOTES, 'UTF-8')] = $v;
                }
            }
        }
        $payment->update([
            'name'      => input('name', $payment->name),
            'is_active' => (int) input('is_active', 0),
            'config'    => $existing,
        ]);
        redirect(url('admin/payments?saved=1'));
    }
}
