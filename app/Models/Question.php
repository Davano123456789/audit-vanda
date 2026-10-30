<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Pertanyaan lembar kerja untuk satu subdomain dan satu atribut proses.
 */
class Question extends Model
{
    protected $table = 'itil_questions';

    protected $fillable = ['process_code', 'attribute_code', 'number', 'question_text'];

    protected $casts = [
        'number' => 'integer',
    ];

    public function process(): BelongsTo
    {
        return $this->belongsTo(ItilProcess::class, 'process_code');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProcessAttribute::class, 'attribute_code');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id');
    }
}
