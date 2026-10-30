<?php

namespace App\Models;

use App\Support\Itil;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Proyek penilaian maturity level yang ditugaskan ke seorang asesor.
 */
class Assessment extends Model
{
    protected $table = 'assessments';

    protected $fillable = ['name', 'company_name', 'description', 'asesor_id', 'status', 'maturity_index'];

    protected $casts = [
        'asesor_id' => 'integer',
        'maturity_index' => 'float',
    ];

    public const STATUSES = [
        'draft' => 'Draf',
        'in_progress' => 'Berjalan',
        'completed' => 'Selesai',
    ];

    public const STATUS_COLORS = [
        'draft' => 'gray',
        'in_progress' => 'amber',
        'completed' => 'green',
    ];

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asesor_id');
    }

    public function processes(): HasMany
    {
        return $this->hasMany(AssessmentProcess::class, 'assessment_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'assessment_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    /**
     * Maturity level dari nilai kematangan domain, contoh: 2.00 -> 2.
     */
    public function getMaturityLevelAttribute(): ?int
    {
        return Itil::maturityLevel($this->maturity_index);
    }

    /**
     * Nilai kematangan domain siap tampil, contoh: "2,00".
     */
    public function getMaturityDisplayAttribute(): string
    {
        return Itil::formatMaturity($this->maturity_index);
    }

    public function getMaturityLevelNameAttribute(): string
    {
        return Itil::levelName($this->maturity_level);
    }
}
