<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Card;
use App\Models\Product;

class CardController
{
    private function auth(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['admin_id'])) redirect(url('admin'));
    }

    public function index(): string
    {
        $this->auth();
        $products = Product::orderBy('name')->get();
        $pid      = input('product_id') ? (int) input('product_id') : null;
        $q        = Card::with('product')->orderByDesc('id');
        if ($pid) $q->where('product_id', $pid);
        $cards = $q->take(200)->get();
        return view('admin.cards.index', [
            'cards'      => $cards,
            'products'   => $products,
            'product_id' => $pid,
        ]);
    }

    /** POST /admin/cards/import  — bulk import from textarea (one card per line). */
    public function import(): never
    {
        $this->auth();
        $pid   = (int) input('product_id');
        $lines = input('cards_text', '');
        if (!$pid || !$lines) redirect(url('admin/cards'));

        Product::findOrFail($pid);
        $now  = date('Y-m-d H:i:s');
        $rows = [];
        foreach (preg_split('/\r?\n/', (string)$lines) as $line) {
            $line = trim($line);
            if ($line !== '') {
                $rows[] = [
                    'product_id' => $pid,
                    'secret'     => $line,
                    'status'     => 0,
                    'created_at' => $now,
                ];
            }
        }
        if ($rows) {
            foreach (array_chunk($rows, 500) as $chunk) {
                Card::insert($chunk);
            }
        }
        redirect(url('admin/cards?product_id=' . $pid . '&imported=' . count($rows)));
    }
}
