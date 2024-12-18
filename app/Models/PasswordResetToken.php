<?php

namespace App\Models;

use Illuminate\Support\Carbon;

/**
 * @property string $user_ulid
 * @property string $reset_token
 * @property Carbon $created_at
 */
class PasswordResetToken extends BaseProjection
{
    protected $keyType = 'string';

    protected $primaryKey = 'user_ulid';
}
