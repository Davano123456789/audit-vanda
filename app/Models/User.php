<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'photo',
    ];

    public const ROLE_ADMIN = 'admin';
    public const ROLE_ASESOR = 'asesor';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Assessment yang ditugaskan kepada asesor ini.
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class, 'asesor_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isAsesor(): bool
    {
        return $this->role === self::ROLE_ASESOR;
    }

    /**
     * Label role untuk ditampilkan di tampilan.
     */
    public function getRoleLabelAttribute(): string
    {
        return $this->isAdmin() ? 'Administrator' : 'Asesor';
    }

    public function getPhotoUrlAttribute(): ?string
    {
        // asset() mengikuti alamat yang sedang dibuka, tidak bergantung APP_URL
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    /**
     * Inisial nama untuk avatar, contoh: "Allya Vanda" -> "AV".
     */
    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', trim($this->name)))
            ->filter()
            ->take(2)
            ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
    }
}
