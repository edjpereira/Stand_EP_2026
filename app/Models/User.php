<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'birthday',
        'phone',
        'photo',
        'must_change_password',
        'last_login_at',
        'role',
        'admin_request_status',
    ];

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
            'birthday' => 'date',
            'last_login_at' => 'datetime',
            'must_change_password' => 'boolean',
        ];
    }

    /**
     * Retorna o URL da foto ou o gerador de iniciais com o primeiro e último nome
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }

        // Extrai o primeiro e o último nome para gerar as iniciais exatas (ex: "Eduardo Silva" -> "ES")
        $words = explode(' ', $this->name);
        $firstInitials = isset($words[0]) ? Str::substr($words[0], 0, 1) : '';
        $lastInitials = count($words) > 1 ? Str::substr(end($words), 0, 1) : '';
        $initials = strtoupper($firstInitials . $lastInitials);

        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&background=0D6EFD&color=fff&size=128&bold=true';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
