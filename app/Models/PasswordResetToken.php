<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;
use Override;
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

    #[Override]
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    #[Override]
    public function getRouteKeyName()
    {
        return $this->primaryKey;
    }

}
