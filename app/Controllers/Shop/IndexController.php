<?php
declare(strict_types=1);

namespace App\Controllers\Shop;

use App\Models\Product;

class IndexController
{
    /** GET / — store front. */
    public function index(): string
    {
        $cat      = input('category', 'all');
        $products = $this->products($cat);
        return view('shop.index', [
            'products' => $products,
            'current'  => $cat,
        ]);
    }

    /** GET /category/{cat}. */
    public function category(string $cat): string
    {
        $products = $this->products($cat);
        return view('shop.index', [
            'products' => $products,
            'current'  => $cat,
        ]);
    }

    private function products(string $cat)
    {
        $q = Product::where('is_active', 1)->orderBy('sort');
        if ($cat !== 'all') {
            $q->where('category', $cat);
        }
        return $q->get();
    }
}
