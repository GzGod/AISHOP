<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Models\Product;

class ProductController
{
    /** GET /api/products?category=chatgpt */
    public function index(): never
    {
        $cat = input('category', 'all');
        $q   = Product::where('is_active', 1)->orderBy('sort');
        if ($cat !== 'all') {
            $q->where('category', $cat);
        }
        $products = $q->get()->map(fn($p) => [
            'id'             => $p->id,
            'name'           => $p->name,
            'category'       => $p->category,
            'description'    => $p->description,
            'price'          => $p->price,
            'original_price' => $p->original_price,
            'tag'            => $p->tag,
            'stock_state'    => $p->stockState(),
            'stock_count'    => $p->stockCount(),
            'delivery_type'  => $p->delivery_type,
        ]);
        json(['code' => 0, 'data' => $products]);
    }
}
