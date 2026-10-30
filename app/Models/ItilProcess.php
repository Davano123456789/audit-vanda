<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Subdomain Service Operation, contoh: IM (Incident Management).
 */
class ItilProcess extends Model
{
    protected $table = 'itil_processes';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['code', 'domain_code', 'name', 'description'];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class, 'domain_code');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'process_code');
    }

    public function assessmentProcesses(): HasMany
    {
        return $this->hasMany(AssessmentProcess::class, 'process_code');
    }
}
