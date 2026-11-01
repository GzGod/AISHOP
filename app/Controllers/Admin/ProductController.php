<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Product;

class ProductController
{
    private function auth(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['admin_id'])) redirect(url('admin'));
    }

    public function index(): string
    {
        $this->auth();
        $products = Product::orderBy('sort')->get();
        return view('admin.products.index', ['products' => $products]);
    }

    public function create(): string
    {
        $this->auth();
        return view('admin.products.form', ['product' => null]);
    }

    public function store(): never
    {
        $this->auth();
        Product::create([
            'name'           => input('name'),
            'category'       => input('category', 'other'),
            'description'    => input('description', ''),
            'price'          => (float) input('price', 0),
            'original_price' => input('original_price') ? (float) input('original_price') : null,
            'delivery_type'  => (int) input('delivery_type', 1),
            'is_active'      => (int) input('is_active', 1),
            'sort'           => (int) input('sort', 0),
            'tag'            => input('tag') ?: null,
        ]);
        redirect(url('admin/products'));
    }

    public function edit(int $id): string
    {
        $this->auth();
        $product = Product::findOrFail($id);
        return view('admin.products.form', ['product' => $product]);
    }

    public function update(int $id): never
    {
        $this->auth();
        $product = Product::findOrFail($id);
        $product->update([
            'name'           => input('name'),
            'category'       => input('category', 'other'),
            'description'    => input('description', ''),
            'price'          => (float) input('price', 0),
            'original_price' => input('original_price') ? (float) input('original_price') : null,
            'delivery_type'  => (int) input('delivery_type', 1),
            'is_active'      => (int) input('is_active', 1),
            'sort'           => (int) input('sort', 0),
            'tag'            => input('tag') ?: null,
        ]);
        redirect(url('admin/products'));
    }

    public function destroy(int $id): never
    {
        $this->auth();
        Product::findOrFail($id)->delete();
        redirect(url('admin/products'));
    }
}
