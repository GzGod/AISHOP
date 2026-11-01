<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    protected $table = 'as_orders';
    protected $guarded = ['id'];
    protected $casts = [
        'quantity'         => 'integer',
        'amount'           => 'float',
        'status'           => 'integer',
        'delivery_status'  => 'integer',
        'secrets'           => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'order_id');
    }

    public function scopeUnpaid(Builder $q): Builder { return $q->where('status', 0); }
    public function scopePaid(Builder $q): Builder   { return $q->where('status', 1); }

    public function isPaid(): bool { return (int)$this->status === 1; }
    public function isDelivered(): bool { return (int)$this->delivery_status === 1; }
}
