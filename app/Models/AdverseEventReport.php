<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdverseEventReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'primary_product_name',
        'products',
        'reaction_description',
        'symptom_evolution',
        'onset_date',
        'resolution_date',
        'outcome',
        'is_serious',
        'seriousness_criteria',
        'treatment_needed',
        'treatment_description',
        'hcp_name',
        'hcp_contact',
        'patient_initials',
        'patient_gender',
        'patient_age',
        'patient_age_unit',
        'ongoing_medical_conditions',
        'concomitant_medications_notes',
        'reporter_type',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'reporter_location',
        'consent_to_contact',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'ip_address',
    ];

    protected $casts = [
        'products' => 'array',
        'seriousness_criteria' => 'array',
        'is_serious' => 'boolean',
        'consent_to_contact' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public const STATUSES = [
        'new' => 'New / Unreviewed',
        'under_review' => 'Under Safety Review',
        'follow_up_needed' => 'Follow-up Requested',
        'reported_to_authority' => 'Submitted to Authority',
        'closed' => 'Closed',
    ];

    public static function generateReferenceNumber(): string
    {
        do {
            $ref = 'IGEO-PV-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (self::where('reference_number', $ref)->exists());

        return $ref;
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }
}
