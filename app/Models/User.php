<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use SensitiveParameter;

class User extends BaseProjection implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ulid',
        'username',
        'visible_name',
        'email',
        'password_hash',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
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

    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    public function setRememberToken($value)
    {
        if (! empty($this->getRememberTokenName())) {
            $this->writeable();
            $this->{$this->getRememberTokenName()} = $value;
        }
    }

    public static function admin(): self
    {
        return self::where('username', 'admin')->first();
    }

    /*
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function getByUlid(string $ulid): ?self
    {
        return self::where('ulid', $ulid)->first();
    }

    public static function checkUnique(string $username, string $email): bool
    {
        return ! (self::where('username', $username)->exists() || self::where('email', $email)->exists());
    }

    public function checkPassword(#[SensitiveParameter] string $password): bool
    {
        return Hash::check($password, $this->password_hash);
    }
}
