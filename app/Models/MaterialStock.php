<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialStock extends Model
{
    protected $table = 'material_stock';

    protected $fillable = [
        'material_id',
        'quantity',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
