<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Material extends Model
{
    protected $table = 'material';

    protected $fillable = [
        'name',
        'material_type',
        'unit',
        'description',
    ];

    public function stock(): HasOne
    {
        return $this->hasOne(MaterialStock::class);
    }
}
