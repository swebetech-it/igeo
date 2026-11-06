<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'client_id',
        'type',
        'status',
        'budget',
        'actual_cost',
        'start_date',
        'end_date',
        'completion_date',
        'project_manager',
        'location',
        'progress_percentage',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'completion_date' => 'datetime',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'progress_percentage' => 'integer'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_project')
            ->withPivot(['role','assigned_at','released_at'])
            ->withTimestamps();
    }

    // Calculate total expenses for this project
    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->sum('amount');
    }

    // Calculate total income for this project
    public function getTotalIncomeAttribute()
    {
        return $this->incomes()->sum('amount');
    }

    // Calculate profit/loss for this project
    public function getProfitLossAttribute()
    {
        return $this->total_income - $this->total_expenses;
    }

    // Calculate profit margin percentage
    public function getProfitMarginAttribute()
    {
        if ($this->total_income > 0) {
            return ($this->profit_loss / $this->total_income) * 100;
        }
        return 0;
    }

    // Get project completion percentage based on budget vs expenses
    public function getCompletionPercentageAttribute()
    {
        if ($this->budget && $this->budget > 0) {
            return min(($this->total_expenses / $this->budget) * 100, 100);
        }
        return 0;
    }
}
