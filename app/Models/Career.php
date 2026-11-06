<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'department',
        'location',
        'type',
        'is_active',
        'deadline',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deadline' => 'date',
    ];

    /**
     * Scope a query to only include active careers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
