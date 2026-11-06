<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'type',
        'amount',
        'date',
        'due_date',
        'invoice_number',
        'payment_method',
        'status',
        'received_amount',
        'received_date',
        'reference_number',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'date' => 'date',
        'due_date' => 'date',
        'received_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Accessors & Mutators
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->amount - $this->received_amount);
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date &&
               $this->due_date < now() &&
               $this->status !== 'received' &&
               $this->status !== 'cancelled';
    }

    public function getNetAmountAttribute()
    {
        return (float) $this->amount;
    }

    public function getPaymentProgressAttribute()
    {
        if ($this->amount <= 0) return 0;
        return min(100, ($this->received_amount / $this->amount) * 100);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'received' => 'success',
            'partial' => 'warning',
            'overdue' => 'danger',
            'cancelled' => 'secondary',
            'invoiced' => 'info',
            default => 'primary'
        };
    }

    public function getFormattedAmountAttribute()
    {
        return '$' . number_format((float) $this->amount, 2);
    }

    public function getFormattedReceivedAmountAttribute()
    {
        return '$' . number_format((float) $this->received_amount, 2);
    }

    public function getFormattedRemainingAmountAttribute()
    {
        return '$' . number_format((float) $this->remaining_amount, 2);
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) return 0;
        return Carbon::parse($this->due_date)->diffInDays(now());
    }

    // Get income types for dropdown
    public static function getTypes(): array
    {
        return [
            'initial_deposit' => 'Initial Deposit',
            'milestone_payment' => 'Milestone Payment',
            'progress_payment' => 'Progress Payment',
            'final_payment' => 'Final Payment',
            'change_order' => 'Change Order',
            'bonus' => 'Bonus Payment',
            'retention_release' => 'Retention Release',
            'other' => 'Other'
        ];
    }

    // Get payment methods for dropdown
    public static function getPaymentMethods(): array
    {
        return [
            'cash' => 'Cash',
            'check' => 'Check',
            'credit_card' => 'Credit Card',
            'bank_transfer' => 'Bank Transfer',
            'financing' => 'Financing',
            'wire_transfer' => 'Wire Transfer'
        ];
    }

    // Get status options for dropdown
    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'invoiced' => 'Invoiced',
            'partial' => 'Partial Payment',
            'received' => 'Fully Received',
            'overdue' => 'Overdue',
            'cancelled' => 'Cancelled'
        ];
    }

    // Business Logic Methods
    public function markAsReceived(float $amount = null): bool
    {
        $amount = $amount ?? (float) $this->amount;

        $this->update([
            'received_amount' => $amount,
            'received_date' => now(),
            'status' => $amount >= (float) $this->amount ? 'received' : 'partial'
        ]);

        return true;
    }

    public function addPartialPayment(float $amount): bool
    {
        $newReceived = (float) $this->received_amount + $amount;

        if ($newReceived > (float) $this->amount) {
            return false; // Cannot receive more than the total amount
        }

        $this->update([
            'received_amount' => $newReceived,
            'received_date' => now(),
            'status' => $newReceived >= (float) $this->amount ? 'received' : 'partial'
        ]);

        return true;
    }

    public function approve($userId = null): bool
    {
        $this->update([
            'approved_by' => $userId,
            'approved_at' => now(),
            'status' => 'invoiced'
        ]);

        return true;
    }

    public function cancel(string $reason = null): bool
    {
        $this->status = 'cancelled';
        if ($reason) {
            $this->notes = ($this->notes ? $this->notes . "\n\n" : '') . "Cancelled: {$reason}";
        }

        return $this->save();
    }

    // Scopes
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereIn('status', ['pending', 'invoiced', 'partial']);
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'invoiced', 'partial']);
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
