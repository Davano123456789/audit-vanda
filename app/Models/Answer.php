<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Jawaban asesor untuk satu pertanyaan: Y (Ada), P (Sebagian), N (Tidak).
 */
class Answer extends Model
{
    protected $table = 'assessment_answers';

    protected $fillable = ['assessment_id', 'question_id', 'answer', 'score'];

    protected $casts = [
        'score' => 'float',
    ];

    public const YES = 'Y';
    public const PARTIAL = 'P';
    public const NO = 'N';

    /** Nilai tiap jawaban (Tabel 3.3 Keterangan Kode) */
    public const SCORES = [
        self::YES => 1,
        self::PARTIAL => 0.5,
        self::NO => 0,
    ];

    public const LABELS = [
        self::YES => 'Ada',
        self::PARTIAL => 'Sebagian',
        self::NO => 'Tidak',
    ];

    public static function scoreFor(string $answer): float
    {
        return self::SCORES[$answer] ?? 0;
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
