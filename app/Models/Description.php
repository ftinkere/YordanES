<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EventSourcing\Projections\Projection;

class Description extends Projection
{
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'uuid',
        'language_uuid',
        'title',
        'description',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_uuid');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }
}
