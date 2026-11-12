<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkingStation extends Model
{
    protected $table = 'working_station';

    protected $fillable = [
        'station_name',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'station_id');
    }
}
