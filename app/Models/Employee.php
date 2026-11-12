<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'dob',
        'job',
        'station_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dob' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::deleted(function (Employee $employee): void {
            $employee->user?->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function workingStation(): BelongsTo
    {
        return $this->belongsTo(WorkingStation::class, 'station_id');
    }
}
