<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Atribut proses yang dinilai pada level tertentu,
 * contoh: PP (Level 1 & 2), PM, WPM, PD, PDep (Level 3), PMeas, PC (Level 4), PI, PO (Level 5).
 */
class ProcessAttribute extends Model
{
    protected $table = 'process_attributes';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['code', 'name', 'level', 'sort_order', 'description'];

    protected $casts = [
        'level' => 'integer',
        'sort_order' => 'integer',
    ];

    /** Level yang dinilai. Level 1 & 2 digabung dan disimpan sebagai 2. */
    public const LEVELS = [2, 3, 4, 5];

    public const LEVEL_LABELS = [
        2 => 'Level 1 & 2',
        3 => 'Level 3 & 3+',
        4 => 'Level 4',
        5 => 'Level 5',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'attribute_code');
    }

    public function getLevelLabelAttribute(): string
    {
        return self::LEVEL_LABELS[$this->level] ?? "Level {$this->level}";
    }
}
