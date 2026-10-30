<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Subdomain yang termasuk dalam ruang lingkup sebuah assessment.
 */
class AssessmentProcess extends Model
{
    protected $table = 'assessment_processes';

    protected $fillable = ['assessment_id', 'process_code', 'target_level', 'result_level', 'status'];

    protected $casts = [
        'target_level' => 'integer',
        'result_level' => 'integer',
    ];

    public const STATUSES = [
        'not_started' => 'Belum Dimulai',
        'in_progress' => 'Sedang Dinilai',
        'completed' => 'Selesai',
    ];

    public const STATUS_COLORS = [
        'not_started' => 'gray',
        'in_progress' => 'amber',
        'completed' => 'green',
    ];

    /** Pilihan target level saat membuat assessment */
    public const TARGET_LEVELS = [1, 2, 3, 4, 5];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function process(): BelongsTo
    {
        return $this->belongsTo(ItilProcess::class, 'process_code');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Selisih target dengan level hasil (Rumus 2.4), null jika belum selesai dinilai.
     */
    public function getGapAttribute(): ?int
    {
        return $this->isCompleted() ? max(0, $this->target_level - $this->result_level) : null;
    }

    /**
     * Subdomain yang sudah mulai dinilai tidak boleh dikeluarkan dari assessment.
     */
    public function isLocked(): bool
    {
        return $this->status !== 'not_started';
    }
}
