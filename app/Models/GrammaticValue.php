<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrammaticValue extends Model
{
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'grammatic_uuid',
        'name',
        'code',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GrammaticCategory::class, 'grammatic_uuid');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }
}
