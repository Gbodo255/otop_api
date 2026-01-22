<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'filiere',
        'niveau',
        'password',
        'surname',
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

    protected static function booted()
    {
        static::creating(function ($user) {

            if (!empty($user->name)) {

                // Lettre de l’avatar
                if (empty($user->avatar_letter)) {
                    $user->avatar_letter = strtoupper(mb_substr($user->name, 0, 1));
                }

                // Couleur de l’avatar
                if (empty($user->avatar_color)) {
                    $user->avatar_color = self::generateAvatarColor($user->name);
                }
            }
        });
    }

    protected static function generateAvatarColor(string $name): string
    {
        $colors = [
            '#1abc9c',
            '#2ecc71',
            '#3498db',
            '#9b59b6',
            '#34495e',
            '#16a085',
            '#27ae60',
            '#2980b9',
            '#8e44ad',
            '#2c3e50',
            '#f39c12',
            '#d35400',
            '#c0392b',
            '#7f8c8d',
            '#e67e22',
        ];

        $letter = strtoupper(mb_substr($name, 0, 1));
        $index = ord($letter) % count($colors);

        return $colors[$index];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public const NIVEAU_1 = 'licence';
    public const NIVEAU_2 = 'master';
    public const NIVEAU_3 = 'doctorat';

    public const NIVEAUX = [
        self::NIVEAU_1,
        self::NIVEAU_2,
        self::NIVEAU_3,
    ];
}
