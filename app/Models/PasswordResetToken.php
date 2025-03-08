<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\PrimaryUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property string $user_uuid
 * @property string $reset_token
 * @property Carbon $created_at
 */
class PasswordResetToken extends Model
{
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

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
