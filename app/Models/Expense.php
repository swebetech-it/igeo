<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'category',
        'amount',
        'date',
        'vendor',
        'invoice_number',
        'payment_method',
        'status',
        'receipt_file',
        'receipt_number',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors & Mutators
    public function getNetAmountAttribute()
    {
        return (float) $this->amount;
    }

    public function getRemainingAmountAttribute()
    {
        // For now, return the full amount if not paid, 0 if paid
        return $this->status === 'paid' ? 0 : (float) $this->amount;
    }

    public function getPaymentProgressAttribute()
    {
        return $this->status === 'paid' ? 100 : 0;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'paid' => 'success',
            'approved' => 'info',
            'rejected' => 'danger',
            'pending' => 'warning',
            default => 'secondary'
        };
    }

    public function getFormattedAmountAttribute()
    {
        return '$' . number_format((float) $this->amount, 2);
    }

    public function getFormattedNetAmountAttribute()
    {
        return '$' . number_format($this->net_amount, 2);
    }

    public function getFormattedPaidAmountAttribute()
    {
        return $this->status === 'paid' ? $this->formatted_amount : '$0.00';
    }

    public function getFormattedRemainingAmountAttribute()
    {
        return '$' . number_format($this->remaining_amount, 2);
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'approved' &&
               $this->date < now()->subDays(30);
    }

    public function getPriorityLevelAttribute()
    {
        return 'Normal'; // Default since priority field doesn't exist yet
    }

    // Get expense categories for dropdown
    public static function getCategories(): array
    {
        return [
            'materials' => 'Materials',
            'labor' => 'Labor',
            'equipment' => 'Equipment',
            'permits' => 'Permits & Licenses',
            'utilities' => 'Utilities',
            'transportation' => 'Transportation',
            'insurance' => 'Insurance',
            'professional_services' => 'Professional Services',
            'office_supplies' => 'Office Supplies',
            'marketing' => 'Marketing & Advertising',
            'maintenance' => 'Maintenance & Repairs',
            'legal_fees' => 'Legal Fees',
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
            'debit_card' => 'Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'wire_transfer' => 'Wire Transfer',
            'online_payment' => 'Online Payment',
            'company_account' => 'Company Account',
            'petty_cash' => 'Petty Cash'
        ];
    }

    // Get status options for dropdown
    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'paid' => 'Paid',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled'
        ];
    }

    // Get priority options for dropdown
    public static function getPriorities(): array
    {
        return [
            'low' => 'Low Priority',
            'normal' => 'Normal Priority',
            'medium' => 'Medium Priority',
            'high' => 'High Priority',
            'urgent' => 'Urgent'
        ];
    }

    // Business Logic Methods
    public function approve($userId = null): bool
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now()
        ]);

        return true;
    }

    public function reject(string $reason = null): bool
    {
        $updateData = ['status' => 'rejected'];

        if ($reason) {
            $updateData['notes'] = ($this->notes ? $this->notes . "\n\n" : '') . "Rejected: {$reason}";
        }

        $this->update($updateData);
        return true;
    }

    public function markAsPaid(float $amount = null): bool
    {
        $amount = $amount ?? $this->net_amount;

        $this->update([
            'paid_amount' => $amount,
            'paid_date' => now(),
            'status' => 'paid'
        ]);

        return true;
    }

    public function addPartialPayment(float $amount): bool
    {
        $newPaid = (float) $this->paid_amount + $amount;

        if ($newPaid > $this->net_amount) {
            return false; // Cannot pay more than the net amount
        }

        $status = $newPaid >= $this->net_amount ? 'paid' : 'approved';

        $this->update([
            'paid_amount' => $newPaid,
            'paid_date' => now(),
            'status' => $status
        ]);

        return true;
    }

    public function cancel(string $reason = null): bool
    {
        $updateData = ['status' => 'cancelled'];

        if ($reason) {
            $updateData['notes'] = ($this->notes ? $this->notes . "\n\n" : '') . "Cancelled: {$reason}";
        }

        $this->update($updateData);
        return true;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'approved')
                    ->where('date', '<', now()->subDays(30))
                    ->whereRaw('paid_amount < (amount + COALESCE(tax_amount, 0) - COALESCE(discount_amount, 0))');
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }
}
