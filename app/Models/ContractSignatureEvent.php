<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractSignatureEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'user_id',
        'event_type',
        'signature_data',
        'ip_address',
        'user_agent',
        'signed_at',
        'comments',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
