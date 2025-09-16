<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

   // relasi ke profile mahasiswa
    public function mahasiswaProfile(): HasOne
    {
        return $this->hasOne(MahasiswaProfile::class, 'user_id');
    }

    // relasi ke profile dosen
    public function dosenProfile(): HasOne
    {
        return $this->hasOne(DosenProfile::class, 'user_id');
    }

    // ambil mahasiswa saja
    public function scopeStudents($query)
    {
        return $query->whereHas('mahasiswaProfile');
    }

    // ambil dosen saja
    public function scopeDosen($query)
    {
        return $query->whereHas('dosenProfile');
    }

    public function projects() {
        return $this->hasOne(Project::class, 'user_id');
    }
}
