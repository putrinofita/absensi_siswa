<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    protected $table = 'user';

    public const ROLE_ADMIN = 'admin';
    
    public const ROLE_GURU = 'guru';

    public const ROLE_SISWA = 'siswa';

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

    /**
    * Profil guru yang terhubung dengan akun ini.
    */
    public function profilGuru(): Guru
    {
        return Guru::where('user_id', $this->id)->first();
    }
    /**
    * Profil siswa yang terhubung dengan akun ini.
    */
    public function profilSiswa(): ?Siswa
    {
        return Siswa::where('user_id', $this->id)->first();
    }
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isGuru(): bool
    {
        return $this->role === self::ROLE_GURU;
    }
    public function isSiswa(): bool
    {
        return $this->role === self::ROLE_SISWA;
    }
}
