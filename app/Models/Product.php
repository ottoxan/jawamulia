<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'code',
        'name',
        'unit',
        'product_type',
        'description',
        'status',
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }

    public function goodStock(): HasOne
    {
        return $this->hasOne(ProductStock::class, 'product_id')->where('stock_type', 'good');
    }

    public function rejectedStock(): HasOne
    {
        return $this->hasOne(ProductStock::class, 'product_id')->where('stock_type', 'rejected');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(ProductTransaction::class, 'product_id');
    }
}
