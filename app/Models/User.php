<?php

namespace App\Models;

use App\Events\User\UserRegistered;
use App\Events\User\UserVerifiedEmail;
use DateTime;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\DocumentModel;
use Symfony\Component\Uid\Ulid;

class User extends BaseProjection implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
    use DocumentModel;
    use HasFactory, Notifiable;

    protected $keyType = 'string';

    protected $primaryKey = 'ulid';


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

    public static function register(string $username, string $visible_name, string $email, string $password_hash): ?self
    {
        $ulid = Ulid::generate();
        event(new UserRegistered($ulid, $username, $visible_name, $email, $password_hash));

        return self::getByUlid($ulid);
    }

    public function verifyEmail(): self
    {
        event(new UserVerifiedEmail($this->ulid, new Carbon));

        return $this;
    }
}
