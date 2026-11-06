<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'about_us';

    protected $fillable = [
        'title',
        'content',
        'mission',
        'vision',
        'values',
        'founded_year',
        'employees_count',
        'projects_completed',
        'clients_served',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'founded_year' => 'integer',
        'employees_count' => 'integer',
        'projects_completed' => 'integer',
        'clients_served' => 'integer',
    ];

    /**
     * Scope a query to only include active records.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
