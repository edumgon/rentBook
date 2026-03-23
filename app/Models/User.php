<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'provider',
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
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
        ];
    }

    /**
     * Create or update a user from social provider.
     */
    public static function createFromSocialProvider(SocialiteUser $socialiteUser, string $provider): self
    {
        return static::updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $socialiteUser->getId(),
            ],
            [
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail(),
            ]
        );
    }

    /**
     * Get the user's books.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Get the user's borrowers.
     */
    public function borrowers()
    {
        return $this->hasMany(Borrower::class);
    }

    /**
     * Get the user's loans.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
