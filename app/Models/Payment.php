<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'as_payments';
    protected $guarded = ['id'];
    protected $casts = [
        'is_active' => 'integer',
        'sort'      => 'integer',
        'config'    => 'array',
    ];

    public function scopeActive($q) { return $q->where('is_active', 1)->orderBy('sort'); }
}
