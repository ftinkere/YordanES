<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\CommonHelper;
use Carbon\Carbon;
use DomainException;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Hash;
use Psr\Log\InvalidArgumentException;
use SensitiveParameter;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable;
    use Authorizable;
    use HasUuids;

    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password_hash',
        'avatar',
        'preferred_theme',
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
            'uuid' => 'string',
            'email_verified_at' => 'datetime',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function isAdmin(): bool
    {
        return $this->username === 'admin';
    }


    public static function admin(): self
    {
        return self::where('username', 'admin')->first();
    }

    /*
     * A helper method to quickly retrieve an account by uuid.
     */
    public static function getByUuid(string $uuid): ?self
    {
        return self::where('uuid', $uuid)->first();
    }

    public static function checkUnique(string $username): bool
    {
        return ! self::where('username', $username)->exists();
    }

    public function checkPassword(#[SensitiveParameter] string $password): bool
    {
        return Hash::check($password, $this->password_hash);
    }

    public static function register(
        string $username,
        string $name,
        string $email,
        #[SensitiveParameter] $password,
    ): static
    {
        // Чек почты на валидность
        if (! CommonHelper::checkEmail($email)) {
            throw new Exception('Неправильный формат почты', 400);
        }

        if (! self::checkUnique($username)) {
            throw new Exception('Пользователь с таким никнеймом уже существует', 400);
        }

        $user = new self;
        $user->username = $username;
        $user->name = $name;
        $user->email = $email;
        $user->password_hash = Hash::make($password);
        $user->remember_token = CommonHelper::randomStr();

        // Send email with verification link

        return $user;
    }

    public function changeUsername(string $username): self
    {
        if (mb_strlen($username) < 3) {
            throw new Exception('Никнейм не может быть меньше 3 символов в длину');
        }

        if ($this->username === $username) {
            return $this;
        }

        if (! self::checkUnique($username)) {
            throw new Exception('Такой никнейм уже существует');
        }

        $this->username = $username;

        return $this;
    }

    public function verifyEmail(?string $token = null): self
    {
        // TODO: поменять на свой токен
        if (auth()->user()?->isAdmin() || ($token && $token === $this->uuid)) {
            $this->email_verified_at = Carbon::now();
        } else {
            throw new InvalidArgumentException('Неправильный токен для подтверждения почты');
        }


        return $this;
    }

    public function login(#[SensitiveParameter] ?string $password = null): self
    {
        if (auth()->user()?->isAdmin() || $this->checkPassword($password)) {
            auth()->login($this, true);
        } else {
            throw new InvalidArgumentException('Неправильный логин или пароль');
        }

        return $this;
    }

    public function logout(): self
    {
        if (auth()->id() === $this->id) {
            auth()->logout();
        }

        return $this;
    }

    public function createPasswordResetToken(): PasswordResetToken
    {
        $token = new PasswordResetToken();
        $token->user_uuid = $this->uuid;
        $token->reset_token = CommonHelper::randomStr();
        $token->save();

        return $token;
    }

    public function resetPassword(#[SensitiveParameter] string $password, ?string $token = null): self
    {
        $token = PasswordResetToken::where('user_uuid', $this->uuid)
            ->orderBy('created_at', 'desc')
            ->first();

        if (auth()->user()->isAdmin() ||
            ($token && ($token->reset_token == $token && $token->created_at->isLastHour()))
        ) {
            $this->password_hash = Hash::make($password);
        } else {
            throw new InvalidArgumentException('Неправильный токен для восстановления пароля');
        }

        return $this;
    }

    public function changeEmail(string $email): self
    {
        $validator = new EmailValidator();
        if (! $validator->isValid($email, new RFCValidation)) {
            throw new DomainException($validator->getError()->description());
        }

        if ($this->email === $email) {
            return $this;
        }

        $this->email = $email;
        $this->email_verified_at = null;

        // Send email with verification link

        return $this;
    }

    public function createLanguage($name): Language
    {
        return Language::create($this, $name);
    }
}
