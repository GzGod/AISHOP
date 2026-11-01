<?php
declare(strict_types=1);

namespace App\Controllers\Shop;

use App\Models\Product;

class ProductController
{
    /** GET /product/{id}. */
    public function show(int $id): string
    {
        $p = Product::findOrFail($id);
        if ((int)$p->is_active !== 1) {
            abort(404, '商品已下架');
        }
        return view('shop.product', ['product' => $p]);
    }
}
