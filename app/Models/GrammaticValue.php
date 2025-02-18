<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EventSourcing\Projections\Projection;

class GrammaticValue extends Projection
{
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
