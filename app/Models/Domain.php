<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Domain ITIL V3, contoh: SO (Service Operation).
 */
class Domain extends Model
{
    protected $table = 'itil_domains';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['code', 'name', 'description'];

    public function processes(): HasMany
    {
        return $this->hasMany(ItilProcess::class, 'domain_code');
    }
}
