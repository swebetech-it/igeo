<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'client_id',
        'created_by',
        'value',
        'contract_number',
        'start_date',
        'end_date',
        'type',
        'document_path',
        'signed_document_path',
        'status',
        'required_signatories',
        'signatures',
        'signatures_required',
        'signatures_received',
        'approval_workflow',
        'approved_by',
        'approved_at',
        'sent_for_signature_at',
        'fully_signed_at',
        'activated_at',
        'completed_at',
        'terms_and_conditions',
        'metadata',
        'is_template',
    ];

    protected $casts = [
        'required_signatories' => 'array',
        'signatures' => 'array',
        'approval_workflow' => 'array',
        'metadata' => 'array',
        'approved_at' => 'datetime',
        'sent_for_signature_at' => 'datetime',
        'fully_signed_at' => 'datetime',
        'activated_at' => 'datetime',
        'completed_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
        'value' => 'decimal:2',
        'is_template' => 'boolean',
    ];

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function signatureEvents(): HasMany
    {
        return $this->hasMany(ContractSignatureEvent::class);
    }

    // Helper Methods
    public function isFullySigned(): bool
    {
        return $this->signatures_received >= $this->signatures_required;
    }

    public function canBeSigned(): bool
    {
        return in_array($this->status, ['awaiting_signatures', 'partially_signed']);
    }

    public function hasUserSigned(int $userId): bool
    {
        if (!$this->signatures) {
            return false;
        }

        foreach ($this->signatures as $signature) {
            if (isset($signature['user_id']) && $signature['user_id'] == $userId) {
                return true;
            }
        }

        return false;
    }

    public function addSignature(int $userId, string $signatureData, ?string $comments = null): bool
    {
        if ($this->hasUserSigned($userId)) {
            return false;
        }

        $signatures = $this->signatures ?? [];
        $signatures[] = [
            'user_id' => $userId,
            'signature_data' => $signatureData,
            'signed_at' => now()->toDateTimeString(),
            'ip_address' => request()->ip(),
            'comments' => $comments,
        ];

        $this->signatures = $signatures;
        $this->signatures_received = count($signatures);

        // Update status
        if ($this->isFullySigned()) {
            $this->status = 'fully_signed';
            $this->fully_signed_at = now();
        } else {
            $this->status = 'partially_signed';
        }

        return $this->save();
    }

    public function sendForSignature(): bool
    {
        if ($this->status !== 'draft' && $this->status !== 'pending_review') {
            return false;
        }

        $this->status = 'awaiting_signatures';
        $this->sent_for_signature_at = now();

        return $this->save();
    }

    public function activate(): bool
    {
        if (!$this->isFullySigned()) {
            return false;
        }

        $this->status = 'active';
        $this->activated_at = now();

        return $this->save();
    }

    public function complete(): bool
    {
        $this->status = 'completed';
        $this->completed_at = now();

        return $this->save();
    }

    public function getProgressPercentage(): int
    {
        if ($this->signatures_required == 0) {
            return 0;
        }

        return (int) (($this->signatures_received / $this->signatures_required) * 100);
    }

    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date->isPast() && $this->status !== 'completed';
    }

    // Boot method for auto-generating contract number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            if (!$contract->contract_number) {
                $contract->contract_number = 'CNT-' . strtoupper(uniqid());
            }
        });
    }
}
