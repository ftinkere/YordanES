<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Spatie\EventSourcing\Projections\Projection;

/**
 * @property string $user_uuid
 * @property string $reset_token
 * @property Carbon $created_at
 */
class PasswordResetToken extends Projection
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'user_uuid';

    public function getKeyName()
    {
        return $this->primaryKey;
    }
    public function getRouteKeyName()
    {
        return $this->primaryKey;
    }

}
