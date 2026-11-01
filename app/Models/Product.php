<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $table = 'as_products';
    protected $guarded = ['id'];
    protected $casts = [
        'price'          => 'float',
        'original_price' => 'float',
        'delivery_type'  => 'integer',
        'is_active'      => 'integer',
        'sort'           => 'integer',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'product_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    /** Available (unsold) stock count. */
    public function stockCount(): int
    {
        return (int) $this->cards()->where('status', 0)->count();
    }

    /** Stock state for UI badge: in | few | sold. */
    public function stockState(): string
    {
        $n = $this->stockCount();
        if ($n === 0) return 'sold';
        if ($n <= 5)  return 'few';
        return 'in';
    }
}
